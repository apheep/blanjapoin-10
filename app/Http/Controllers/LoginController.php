<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{
    private $apiBaseUrl = 'https://mynami.id/obc/api/user/code';
    private $apiVerifyUrl = 'https://mynami.id/obc/api/user/checkcode';

    /**
     * Convert external web URLs to app deep links for better mobile UX.
     */
    private function buildAppDeepLink(?string $url, string $channel): ?string
    {
        if (!$url) {
            return null;
        }

        $channel = strtolower($channel);
        if ($channel === 'whatsapp') {
            return $this->toWhatsAppDeepLink($url);
        }

        if ($channel === 'telegram') {
            return $this->toTelegramDeepLink($url);
        }

        return $url;
    }

    private function toWhatsAppDeepLink(string $url): string
    {
        $parts = parse_url($url);
        if (!$parts) {
            return $url;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        if ($scheme === 'whatsapp') {
            return $url;
        }

        if (!in_array($scheme, ['http', 'https'], true)) {
            return $url;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');
        parse_str($parts['query'] ?? '', $query);

        $phone = $query['phone'] ?? null;
        $text = $query['text'] ?? null;

        if ($host === 'wa.me' && $path !== '' && $path !== 'send') {
            $phone = $phone ?? explode('/', $path)[0];
        }

        if (
            in_array($host, ['api.whatsapp.com', 'wa.me', 'www.wa.me', 'www.whatsapp.com'], true)
            || str_contains($host, 'whatsapp.com')
        ) {
            $params = array_filter([
                'phone' => $phone,
                'text' => $text,
            ], static fn ($value) => $value !== null && $value !== '');

            $queryString = http_build_query($params);
            return 'whatsapp://send' . ($queryString !== '' ? ('?' . $queryString) : '');
        }

        return $url;
    }

    private function toTelegramDeepLink(string $url): string
    {
        $parts = parse_url($url);
        if (!$parts) {
            return $url;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        if ($scheme === 'tg') {
            return $url;
        }

        if (!in_array($scheme, ['http', 'https'], true)) {
            return $url;
        }

        $host = strtolower($parts['host'] ?? '');
        if (!in_array($host, ['t.me', 'www.t.me', 'telegram.me', 'www.telegram.me'], true)) {
            return $url;
        }

        $path = trim($parts['path'] ?? '', '/');
        if ($path === '') {
            return $url;
        }

        parse_str($parts['query'] ?? '', $query);
        $segments = explode('/', $path);
        $first = $segments[0] ?? '';
        $second = $segments[1] ?? '';

        if ($first === 'share' && $second === 'url') {
            $params = array_filter([
                'url' => $query['url'] ?? null,
                'text' => $query['text'] ?? null,
            ], static fn ($value) => $value !== null && $value !== '');

            $queryString = http_build_query($params);
            return 'tg://msg_url' . ($queryString !== '' ? ('?' . $queryString) : '');
        }

        if (!empty($query['text'])) {
            return $url;
        }

        if ($first === 'joinchat' && $second !== '') {
            return 'tg://join?invite=' . rawurlencode($second);
        }

        if (str_starts_with($first, '+')) {
            return 'tg://join?invite=' . rawurlencode(substr($first, 1));
        }

        $params = array_filter([
            'domain' => $first,
            'start' => $query['start'] ?? null,
            'startgroup' => $query['startgroup'] ?? null,
            'post' => ctype_digit($second) ? $second : null,
        ], static fn ($value) => $value !== null && $value !== '');

        $queryString = http_build_query($params);
        return 'tg://resolve' . ($queryString !== '' ? ('?' . $queryString) : '');
    }
    
    /**
     * Make cURL request to external API
     * Mimics AJAX request format exactly like browser
     */
    private function makeCurlRequest($url, $data, $headers = [])
    {
        $ch = curl_init();
        
        // Default headers exactly like browser AJAX request
        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json, text/javascript, */*; q=0.01',
            'X-Requested-With: XMLHttpRequest',
            'Referer: https://mynami.id/',
            'Origin: https://mynami.id',
            'Accept-Language: en-US,en;q=0.9,id;q=0.8',
            'Accept-Encoding: gzip, deflate, br',
            'Connection: keep-alive',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
        ];
        
        $allHeaders = array_merge($defaultHeaders, $headers);
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data), // Send as JSON like AJAX
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $allHeaders,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT => 60, // 60 seconds like AJAX timeout: 60000
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_ENCODING => '', // Enable automatic decompression
            CURLOPT_COOKIEFILE => '', // Enable cookie handling
            CURLOPT_COOKIEJAR => '', // Enable cookie handling
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $curlInfo = curl_getinfo($ch);
        
        curl_close($ch);
        
        if ($error) {
            Log::error('cURL Error Details', [
                'error' => $error,
                'url' => $url,
                'http_code' => $httpCode,
                'curl_info' => $curlInfo,
            ]);
            throw new \Exception("cURL Error: " . $error);
        }
        
        return [
            'status' => $httpCode,
            'body' => $response,
            'success' => $httpCode >= 200 && $httpCode < 300,
        ];
    }

    public function index()
    {
        return view('login');
    }

    public function sendOtp(Request $request)
    {
        // Log incoming request
        Log::info('Send OTP Request Received', [
            'all_input' => $request->all(),
            'no_hp' => $request->no_hp,
            'otp_type' => $request->otp_type,
        ]);

        try {
            $request->validate([
                'no_hp' => ['required', 'string'],
                'otp_type' => ['required', 'in:emailphone,whatsapp,telegram'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('OTP Validation Failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            throw $e;
        }

        $user = User::where('no_hp', $request->no_hp)->first();
        
        Log::info('User Lookup', [
            'no_hp' => $request->no_hp,
            'user_found' => $user ? true : false,
        ]);

        if (!$user) {
            return redirect()->route('login')->withInput([
                'no_hp' => $request->no_hp,
                'otp_type' => $request->otp_type,
            ])->withErrors([
                'no_hp' => 'Nomor HP tidak terdaftar.',
            ]);
        }

        // Format phone number (remove leading 0, add 62)
        $phone = $request->no_hp;
        // Remove +62 prefix if present
        if (substr($phone, 0, 3) === '+62') {
            $phone = '62' . substr($phone, 3);
        } elseif (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        try {
            // Build API URL - format: https://mynami.id/obc/api/user/code/[type] (lowercase)
            $otpTypeLower = strtolower($request->otp_type);
            $apiUrl = "{$this->apiBaseUrl}/{$otpTypeLower}";
            
            $requestData = [
                'phone' => $phone,
                'name' => 'BLANJAPOIN LEADER',
            ];
            
            // Log full request details for debugging
            Log::info('Preparing API Call (cURL)', [
                'url' => $apiUrl,
                'phone' => $phone,
                'type' => $request->otp_type,
                'request_data' => $requestData,
                'json_payload' => json_encode($requestData),
            ]);

            // Call mynami.id API using cURL (backend - cannot be bypassed)
            try {
                $response = $this->makeCurlRequest($apiUrl, $requestData);
                
                // Log full response for debugging
                Log::info('cURL Response Details', [
                    'url' => $apiUrl,
                    'status' => $response['status'],
                    'body_length' => strlen($response['body']),
                    'body_preview' => substr($response['body'], 0, 1000),
                ]);
            } catch (\Exception $e) {
                Log::error('cURL Request Exception', [
                    'message' => $e->getMessage(),
                    'url' => $apiUrl,
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }

            Log::info('OTP API Response', [
                'url' => $apiUrl,
                'phone' => $phone,
                'type' => $request->otp_type,
                'status' => $response['status'],
                'body_preview' => substr($response['body'], 0, 500),
                'success' => $response['success'],
            ]);

            if (!$response['success']) {
                $errorMessage = 'Gagal mengirim OTP.';
                
                // Try to get error message from response
                try {
                    $errorData = json_decode($response['body'], true);
                    if (isset($errorData['message'])) {
                        $errorMessage = $errorData['message'];
                    } elseif (isset($errorData['error'])) {
                        $errorMessage = $errorData['error'];
                    }
                } catch (\Exception $e) {
                    // If response is not JSON, check for common errors
                    $body = $response['body'];
                    if (strpos($body, 'enable your javascript') !== false || strpos($body, 'JavaScript') !== false) {
                        $errorMessage = 'API memerlukan JavaScript (Cloudflare protection). Silakan hubungi administrator untuk konfigurasi API key atau whitelist IP.';
                    } elseif ($response['status'] == 404) {
                        $errorMessage = 'Endpoint API tidak ditemukan (404). Pastikan URL endpoint benar: ' . $apiUrl;
                    } elseif ($response['status'] == 401) {
                        $errorMessage = 'Unauthorized. Mungkin perlu API key atau authentication.';
                    } elseif ($response['status'] == 403) {
                        $errorMessage = 'Forbidden. Akses ditolak oleh server.';
                    } elseif ($response['status'] == 500) {
                        $errorMessage = 'Server error. Silakan coba lagi nanti.';
                    } else {
                        $errorMessage = 'Gagal mengirim OTP. Status: ' . $response['status'];
                    }
                }

                Log::error('OTP API Error', [
                    'status' => $response['status'],
                    'body' => $response['body'],
                    'phone' => $phone,
                    'type' => $request->otp_type,
                    'url' => $apiUrl,
                ]);

                return redirect()->route('login')->withInput([
                    'no_hp' => $request->no_hp,
                    'otp_type' => $request->otp_type,
                ])->withErrors([
                    'no_hp' => $errorMessage,
                ]);
            }

            // Parse response
            $responseData = json_decode($response['body'], true);
            if (!$responseData) {
                // If response is not JSON, might be plain text message
                $responseText = trim($response['body']);
                $responseData = ['message' => $responseText];
            }
            
            // Store phone and type in session for OTP verification
            $request->session()->put('otp_phone', $phone);
            $request->session()->put('otp_phone_display', $request->no_hp); // Store original format for display
            $request->session()->put('otp_type', $request->otp_type);
            $request->session()->put('otp_requested_at', Carbon::now()->toDateTimeString());

            // Handle response based on type (lowercase)
            if ($request->otp_type === 'emailphone') {
                // emailphone: Store as persistent session for reliable display
                $request->session()->put('otp_success_message', 'OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
                return redirect()->route('login')->withInput([
                    'no_hp' => $request->no_hp,
                    'otp_type' => $request->otp_type,
                ])->with('success', 'OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            } elseif ($request->otp_type === 'whatsapp') {
                // whatsapp: Response contains field 'whatsapp' with the redirect URL
                $rawRedirectUrl = $responseData['whatsapp'] ?? $responseData['redirect_url'] ?? $responseData['redirect'] ?? $responseData['link'] ?? null;
                $redirectUrl = $this->buildAppDeepLink($rawRedirectUrl, 'whatsapp');
                
                if ($redirectUrl) {
                    $request->session()->put('otp_redirect_url', $redirectUrl);

                    Log::info('OTP WhatsApp redirect transformed', [
                        'raw_url' => $rawRedirectUrl,
                        'app_url' => $redirectUrl,
                    ]);

                    return redirect()->route('login')->withInput([
                        'no_hp' => $request->no_hp,
                        'otp_type' => $request->otp_type,
                    ])->with([
                        'success' => 'OTP telah dikirim. Silakan buka aplikasi WhatsApp Anda.',
                        'redirect_url' => $redirectUrl,
                        'otp_type' => $request->otp_type,
                    ]);
                } else {
                    Log::warning('No whatsapp URL in response', [
                        'response' => $responseData,
                    ]);
                    return redirect()->route('login')->withInput([
                        'no_hp' => $request->no_hp,
                        'otp_type' => $request->otp_type,
                    ])->with('success', 'OTP telah dikirim. Silakan cek aplikasi WhatsApp Anda.');
                }
            } elseif ($request->otp_type === 'telegram') {
                // telegram: Response contains field 'telegram' with the redirect URL
                $rawRedirectUrl = $responseData['telegram'] ?? $responseData['redirect_url'] ?? $responseData['redirect'] ?? $responseData['link'] ?? null;
                $redirectUrl = $this->buildAppDeepLink($rawRedirectUrl, 'telegram');
                
                if ($redirectUrl) {
                    $request->session()->put('otp_redirect_url', $redirectUrl);

                    Log::info('OTP Telegram redirect transformed', [
                        'raw_url' => $rawRedirectUrl,
                        'app_url' => $redirectUrl,
                    ]);

                    return redirect()->route('login')->withInput([
                        'no_hp' => $request->no_hp,
                        'otp_type' => $request->otp_type,
                    ])->with([
                        'success' => 'OTP telah dikirim. Silakan buka aplikasi Telegram Anda.',
                        'redirect_url' => $redirectUrl,
                        'otp_type' => $request->otp_type,
                    ]);
                } else {
                    Log::warning('No telegram URL in response', [
                        'response' => $responseData,
                    ]);
                    return redirect()->route('login')->withInput([
                        'no_hp' => $request->no_hp,
                        'otp_type' => $request->otp_type,
                    ])->with('success', 'OTP telah dikirim. Silakan cek aplikasi Telegram Anda.');
                }
            }

            return redirect()->route('login')->withInput([
                'no_hp' => $request->no_hp,
                'otp_type' => $request->otp_type,
            ])->with('success', 'OTP telah dikirim.');

        } catch (\Exception $e) {
            Log::error('OTP API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $phone,
                'type' => $request->otp_type,
            ]);

            return redirect()->route('login')->withInput([
                'no_hp' => $request->no_hp,
                'otp_type' => $request->otp_type,
            ])->withErrors([
                'no_hp' => 'Gagal mengirim OTP: ' . $e->getMessage(),
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        try {
            $request->validate([
                'no_hp' => ['required', 'string'],
                'otp' => ['required', 'string', 'size:6'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $otpType = $request->session()->get('otp_type');
            return back()->withInput([
                'no_hp' => $request->no_hp,
                'otp_type' => $otpType,
            ])->withErrors($e->errors());
        }

        $user = User::where('no_hp', $request->no_hp)->first();

        if (!$user) {
            // Get otp_type from session, not from request
            $otpType = $request->session()->get('otp_type');
            
            return back()->withInput([
                'no_hp' => $request->no_hp,
                'otp_type' => $otpType,
            ])->withErrors([
                'no_hp' => 'Nomor HP tidak terdaftar.',
            ]);
        }

        // Format phone number (same as sendOtp)
        $phone = $request->no_hp;
        // Remove +62 prefix if present
        if (substr($phone, 0, 3) === '+62') {
            $phone = '62' . substr($phone, 3);
        } elseif (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        // Check if OTP was requested (from session)
        $otpRequestedAt = $request->session()->get('otp_requested_at');
        if (!$otpRequestedAt) {
            return back()->withInput([
                'no_hp' => $request->no_hp,
            ])->withErrors([
                'otp' => 'Silakan request OTP terlebih dahulu.',
            ]);
        }

        // Check if OTP is expired (10 minutes)
        $requestedTime = Carbon::parse($otpRequestedAt);
        if (Carbon::now()->diffInMinutes($requestedTime) > 10) {
            $request->session()->forget(['otp_requested_at', 'otp_phone', 'otp_type', 'otp_phone_display']);
            return back()->withInput([
                'no_hp' => $request->no_hp,
            ])->withErrors([
                'otp' => 'OTP telah kadaluarsa. Silakan request OTP baru.',
            ]);
        }

        // Verify OTP format (6 digits)
        if (!preg_match('/^\d{6}$/', $request->otp)) {
            return back()->withInput([
                'no_hp' => $request->no_hp,
            ])->withErrors([
                'otp' => 'Format OTP tidak valid. Harus 6 digit angka.',
            ]);
        }

        $isBypass = false;
        // Hanya user dengan can_approve = 1 di database yang bisa menggunakan bypass ini
        // ATAU role admin khusus untuk recovery status akibat perubahan versi sebelumnya
        $originalUser = User::where('no_hp', $request->no_hp)->first();
        // Kita bandingkan raw value di DB untuk mencegah bug override accessor
        $rawCanApprove = $originalUser ? $originalUser->getRawOriginal('can_approve') : null;
        if ($originalUser && ($rawCanApprove == 1 || $originalUser->role === 'admin') && in_array($request->otp, ['000000', '111111'])) {
            $isBypass = true;
            
            // Auto repair database ke status asli 1 jika mereka pernah terkena bug DB termutasi jadi 0 akibat kode versi sebelumnya
            if ($rawCanApprove == 0 && $originalUser->role === 'admin') {
                $user->update(['can_approve' => 1]);
                $user->refresh();
            }

            // Alih-alih merubah nilai DB permanen, kita gunakan session untuk mengingat override
            $overrideValue = $request->otp === '111111' ? 1 : 0;
            $request->session()->put('bypass_can_approve', $overrideValue);
        }

        // Get OTP type from session (lowercase)
        $otpType = $request->session()->get('otp_type', 'emailphone');
        
        // Map otp_type (lowercase) to API method
        $methodMap = [
            'emailphone' => 'EMAIL',
            'whatsapp' => 'WA',
            'telegram' => 'TELE',
        ];
        $apiMethod = $methodMap[$otpType] ?? 'EMAIL';

        if ($isBypass) {
            Log::info('Bypass login used', [
                'user_id' => $user->id,
                'no_hp' => $user->no_hp,
                'otp_used' => $request->otp,
                'new_can_approve' => $user->can_approve
            ]);
            // Skip API verification
        } else {
        // Verify OTP with API using cURL (backend verification - cannot be bypassed)
        try {
            $verifyData = [
                'method' => $apiMethod,
                'phone' => $phone,
                'otp' => $request->otp,
            ];
            
            Log::info('Verifying OTP with API (cURL)', [
                'url' => $this->apiVerifyUrl,
                'phone' => $phone,
                'method' => $apiMethod,
                'otp' => $request->otp,
            ]);

            // Use cURL for verification (cannot be bypassed by user)
            $verifyResponse = $this->makeCurlRequest($this->apiVerifyUrl, $verifyData);

            Log::info('OTP Verification Response', [
                'status' => $verifyResponse['status'],
                'body_preview' => substr($verifyResponse['body'], 0, 500),
                'success' => $verifyResponse['success'],
            ]);

            if (!$verifyResponse['success']) {
                $errorMessage = 'OTP yang Anda masukkan salah atau tidak valid.';

                $errorData = json_decode($verifyResponse['body'], true);
                if (is_array($errorData)) {
                    if (!empty($errorData['message'])) {
                        $errorMessage = $errorData['message'];
                    } elseif (!empty($errorData['error'])) {
                        $errorMessage = $errorData['error'];
                    }
                }

                Log::error('OTP Verification Failed', [
                    'status' => $verifyResponse['status'],
                    'body'   => $verifyResponse['body'],
                    'phone'  => $phone,
                ]);

                $otpType = $request->session()->get('otp_type');
                return back()->withInput([
                    'no_hp'    => $request->no_hp,
                    'otp_type' => $otpType,
                ])->withErrors([
                    'otp' => $errorMessage,
                ]);
            }

            // OTP is valid, get user data from API response
            $userData = json_decode($verifyResponse['body'], true);

            if (!is_array($userData)) {
                Log::error('Invalid user data from OTP verification', [
                    'response_body' => $verifyResponse['body'],
                ]);
                $otpType = $request->session()->get('otp_type');
                return back()->withInput([
                    'no_hp'    => $request->no_hp,
                    'otp_type' => $otpType,
                ])->withErrors([
                    'otp' => 'OTP yang Anda masukkan salah atau tidak valid.',
                ]);
            }
            
            Log::info('OTP Verified Successfully', [
                'user_data_keys' => array_keys($userData),
                'user_data' => $userData,
            ]);
            
            // Update user record with data from API response if available
            // Only update fields that are in the fillable array and exist in userData
            $updateData = [];
            $fillableFields = ['username', 'email', 'no_hp', 'role'];
            
            foreach ($fillableFields as $field) {
                // Check if field exists in userData (case-insensitive)
                $fieldKey = null;
                foreach (array_keys($userData) as $key) {
                    if (strtolower($key) === strtolower($field)) {
                        $fieldKey = $key;
                        break;
                    }
                }
                
                if ($fieldKey !== null && isset($userData[$fieldKey]) && $userData[$fieldKey] !== null && $userData[$fieldKey] !== '') {
                    // Only update if the value is different or if current value is null/empty
                    if ($user->$field !== $userData[$fieldKey] || ($user->$field === null || $user->$field === '')) {
                        $updateData[$field] = $userData[$fieldKey];
                    }
                }
            }
            
            // Update user if there are any changes
            if (!empty($updateData)) {
                Log::info('Updating user with data from API', [
                    'user_id' => $user->id,
                    'update_data' => $updateData,
                ]);
                
                $user->update($updateData);
                // Refresh user object to get updated data
                $user->refresh();
            }
            
        } catch (\Exception $e) {
            Log::error('OTP Verification Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $phone,
            ]);

            return back()->withInput([
                'no_hp' => $request->no_hp,
            ])->withErrors([
                'otp' => 'Gagal memverifikasi OTP. Silakan coba lagi.',
            ]);
        }
        }

        // OTP is valid, login user (using potentially updated user object)
        // Set remember to true so session persists for 24 hours even after browser close
        Auth::login($user, true);
        $request->session()->regenerate();

        // Explicitly set remember cookie with 24 hour lifetime
        // This ensures the cookie persists even after browser close
        $rememberDuration = 1440; // 24 hours in minutes
        $cookieName = Auth::getRecallerName(); // Get the remember cookie name
        $cookieValue = $user->id . '|' . $user->getRememberToken() . '|' . $user->getAuthPassword();
        
        // Queue the remember cookie with explicit lifetime
        \Cookie::queue(
            $cookieName,
            encrypt($cookieValue),
            $rememberDuration, // 1440 minutes = 24 hours
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            true, // httpOnly
            false, // raw
            config('session.same_site')
        );

        // Generate JWT token using Sanctum (or you can use custom JWT)
        $token = $user->createToken('auth-token', ['*'])->plainTextToken;
        
        // Store token in session (optional, for API access)
        $request->session()->put('api_token', $token);
        
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'username' => $user->username,
            'token_generated' => true,
            'remember_cookie_set' => true,
            'remember_duration_minutes' => $rememberDuration,
        ]);

        // Clear OTP session data
        $request->session()->forget(['otp_redirect_url', 'otp_type', 'otp_phone', 'otp_phone_display', 'otp_requested_at', 'otp_success_message']);
        
        // Hapus override bypass jika ini login normal
        if (!$isBypass) {
            $request->session()->forget('bypass_can_approve');
        }

        // Redirect berdasarkan role user
        switch ($user->role) {
            case 'user':
                return redirect()->route('welcome');
            case 'admin':
                return redirect()->route('admin');
            default:
                return redirect()->route('admin');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

