<?php

namespace App\Http\Controllers;

use App\Models\PortalUser;
use App\Models\Merchant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PortalAuthController extends Controller
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
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $allHeaders,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_ENCODING => '',
            CURLOPT_COOKIEFILE => '',
            CURLOPT_COOKIEJAR => '',
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

    public function showLoginForm(Request $request)
    {
        $returnTo = $request->query('returnTo', $request->session()->get('portal.intended'));
        $waPic = $request->query('wa_pic');
        
        // Log untuk debugging
        Log::info('Portal Login Form Displayed', [
            'wa_pic_from_query' => $waPic,
            'returnTo' => $returnTo,
            'session_portal_otp_phone_display' => $request->session()->get('portal_otp_phone_display'),
        ]);

        return view('portal-login', [
            'returnTo' => $returnTo,
            'waPic' => $waPic, // Pass wa_pic untuk auto-fill (dari query parameter)
        ]);
    }

    public function sendOtp(Request $request)
    {
        Log::info('Portal Send OTP Request Received', [
            'all_input' => $request->all(),
            'wa_pic' => $request->wa_pic,
            'otp_type' => $request->otp_type,
        ]);

        try {
            $request->validate([
                'wa_pic' => ['required', 'string'],
                'otp_type' => ['required', 'in:emailphone,whatsapp,telegram'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Portal OTP Validation Failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            throw $e;
        }

        // Normalize input phone untuk pencarian
        $inputPhone = trim($request->wa_pic);
        
        // Helper function untuk normalize nomor ke format dasar (hanya angka, tanpa prefix)
        $normalizeToBase = function($phone) {
            if (empty($phone)) return null;
            $phone = trim($phone);
            // Remove semua karakter non-digit
            $phone = preg_replace('/\D/', '', $phone);
            // Remove prefix 62 jika ada
            if (substr($phone, 0, 2) === '62' && strlen($phone) > 2) {
                $phone = substr($phone, 2);
            }
            // Remove leading 0 jika ada
            if (substr($phone, 0, 1) === '0' && strlen($phone) > 1) {
                $phone = substr($phone, 1);
            }
            return $phone;
        };
        
        $basePhone = $normalizeToBase($inputPhone);
        
        // Cari merchant dengan berbagai format nomor
        // Gunakan base phone (hanya angka) untuk pencarian yang lebih fleksibel
        $merchant = Merchant::where(function($query) use ($inputPhone, $basePhone) {
            // Cari dengan format asli (exact match)
            $query->where('wa_pic', $inputPhone)
                  // Cari dengan format +62 + base
                  ->orWhere('wa_pic', '+62' . $basePhone)
                  // Cari dengan format 62 + base
                  ->orWhere('wa_pic', '62' . $basePhone)
                  // Cari dengan format 0 + base
                  ->orWhere('wa_pic', '0' . $basePhone)
                  // Cari dengan format base saja (tanpa prefix)
                  ->orWhere('wa_pic', $basePhone)
                  // Cari dengan LIKE untuk handle variasi format (jika ada spasi atau karakter lain)
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(wa_pic, '+', ''), ' ', ''), '-', '') LIKE ?", ['%' . $basePhone . '%']);
        })->first();
        
        Log::info('Merchant Lookup', [
            'input_wa_pic' => $inputPhone,
            'base_phone' => $basePhone,
            'merchant_found' => $merchant ? true : false,
            'merchant_id' => $merchant ? $merchant->id : null,
            'merchant_name' => $merchant ? $merchant->nama_merchant : null,
            'merchant_wa_pic' => $merchant ? $merchant->wa_pic : null,
        ]);

        if (!$merchant) {
            return back()->withInput([
                'wa_pic' => $request->wa_pic,
                'otp_type' => $request->otp_type,
            ])->withErrors([
                'wa_pic' => 'Nomor WhatsApp tidak terdaftar sebagai PIC merchant.',
            ]);
        }

        // Normalize nomor untuk API (format: 62xxx)
        $phone = '62' . $basePhone;

        try {
            // Build API URL
            $otpTypeLower = strtolower($request->otp_type);
            $apiUrl = "{$this->apiBaseUrl}/{$otpTypeLower}";
            
            $requestData = [
                'phone' => $phone,
                'name' => 'BLANJAPOIN MERCHANT',
            ];
            
            Log::info('Preparing Portal API Call (cURL)', [
                'url' => $apiUrl,
                'phone' => $phone,
                'type' => $request->otp_type,
                'request_data' => $requestData,
            ]);

            // Make API call using cURL
            $response = $this->makeCurlRequest($apiUrl, $requestData);
            
            Log::info('Portal API Response', [
                'status' => $response['status'],
                'body' => $response['body'],
                'success' => $response['success'],
            ]);

            if (!$response['success']) {
                $errorMessage = 'Gagal mengirim OTP.';
                
                if ($response['status'] == 404) {
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

                Log::error('Portal OTP API Error', [
                    'status' => $response['status'],
                    'body' => $response['body'],
                    'phone' => $phone,
                    'type' => $request->otp_type,
                    'url' => $apiUrl,
                ]);

                return back()->withInput([
                    'wa_pic' => $request->wa_pic,
                    'otp_type' => $request->otp_type,
                ])->withErrors([
                    'wa_pic' => $errorMessage,
                ]);
            }

            // Parse response
            $responseData = json_decode($response['body'], true);
            if (!$responseData) {
                $responseText = trim($response['body']);
                $responseData = ['message' => $responseText];
            }
            
            // Store phone and type in session for OTP verification
            $request->session()->put('portal_otp_phone', $phone);
            $request->session()->put('portal_otp_phone_display', $request->wa_pic);
            $request->session()->put('portal_otp_type', $request->otp_type);
            $request->session()->put('portal_otp_requested_at', Carbon::now()->toDateTimeString());

            // Handle response based on type
            if ($request->otp_type === 'emailphone') {
                return back()->withInput([
                    'wa_pic' => $request->wa_pic,
                    'otp_type' => $request->otp_type,
                ])->with('success', 'OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            } elseif ($request->otp_type === 'whatsapp') {
                $rawRedirectUrl = $responseData['whatsapp'] ?? $responseData['redirect_url'] ?? $responseData['redirect'] ?? $responseData['link'] ?? null;
                $redirectUrl = $this->buildAppDeepLink($rawRedirectUrl, 'whatsapp');
                
                if ($redirectUrl) {
                    $request->session()->put('portal_otp_redirect_url', $redirectUrl);

                    Log::info('Portal OTP WhatsApp redirect transformed', [
                        'raw_url' => $rawRedirectUrl,
                        'app_url' => $redirectUrl,
                    ]);

                    return back()->withInput([
                        'wa_pic' => $request->wa_pic,
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
                    return back()->withInput([
                        'wa_pic' => $request->wa_pic,
                        'otp_type' => $request->otp_type,
                    ])->with('success', 'OTP telah dikirim. Silakan cek aplikasi WhatsApp Anda.');
                }
            } elseif ($request->otp_type === 'telegram') {
                $rawRedirectUrl = $responseData['telegram'] ?? $responseData['redirect_url'] ?? $responseData['redirect'] ?? $responseData['link'] ?? null;
                $redirectUrl = $this->buildAppDeepLink($rawRedirectUrl, 'telegram');
                
                if ($redirectUrl) {
                    $request->session()->put('portal_otp_redirect_url', $redirectUrl);

                    Log::info('Portal OTP Telegram redirect transformed', [
                        'raw_url' => $rawRedirectUrl,
                        'app_url' => $redirectUrl,
                    ]);

                    return back()->withInput([
                        'wa_pic' => $request->wa_pic,
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
                    return back()->withInput([
                        'wa_pic' => $request->wa_pic,
                        'otp_type' => $request->otp_type,
                    ])->with('success', 'OTP telah dikirim. Silakan cek aplikasi Telegram Anda.');
                }
            }

            return back()->withInput([
                'wa_pic' => $request->wa_pic,
                'otp_type' => $request->otp_type,
            ])->with('success', 'OTP telah dikirim.');

        } catch (\Exception $e) {
            Log::error('Portal OTP API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $phone,
                'type' => $request->otp_type,
            ]);

            return back()->withInput([
                'wa_pic' => $request->wa_pic,
                'otp_type' => $request->otp_type,
            ])->withErrors([
                'wa_pic' => 'Gagal mengirim OTP: ' . $e->getMessage(),
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'wa_pic' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        // Normalize input phone untuk pencarian
        $inputPhone = trim($request->wa_pic);
        
        // Helper function untuk normalize nomor ke format dasar (hanya angka, tanpa prefix)
        $normalizeToBase = function($phone) {
            if (empty($phone)) return null;
            $phone = trim($phone);
            // Remove semua karakter non-digit
            $phone = preg_replace('/\D/', '', $phone);
            // Remove prefix 62 jika ada
            if (substr($phone, 0, 2) === '62' && strlen($phone) > 2) {
                $phone = substr($phone, 2);
            }
            // Remove leading 0 jika ada
            if (substr($phone, 0, 1) === '0' && strlen($phone) > 1) {
                $phone = substr($phone, 1);
            }
            return $phone;
        };
        
        $basePhone = $normalizeToBase($inputPhone);
        
        // Cari merchant dengan berbagai format nomor
        $merchant = Merchant::where(function($query) use ($inputPhone, $basePhone) {
            // Cari dengan format asli (exact match)
            $query->where('wa_pic', $inputPhone)
                  // Cari dengan format +62 + base
                  ->orWhere('wa_pic', '+62' . $basePhone)
                  // Cari dengan format 62 + base
                  ->orWhere('wa_pic', '62' . $basePhone)
                  // Cari dengan format 0 + base
                  ->orWhere('wa_pic', '0' . $basePhone)
                  // Cari dengan format base saja (tanpa prefix)
                  ->orWhere('wa_pic', $basePhone)
                  // Cari dengan LIKE untuk handle variasi format (jika ada spasi atau karakter lain)
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(wa_pic, '+', ''), ' ', ''), '-', '') LIKE ?", ['%' . $basePhone . '%']);
        })->first();
        
        Log::info('Merchant Lookup (Authenticate)', [
            'input_wa_pic' => $inputPhone,
            'base_phone' => $basePhone,
            'merchant_found' => $merchant ? true : false,
            'merchant_id' => $merchant ? $merchant->id : null,
            'merchant_wa_pic' => $merchant ? $merchant->wa_pic : null,
        ]);

        if (!$merchant) {
            $otpType = $request->session()->get('portal_otp_type');
            
            return back()->withInput([
                'wa_pic' => $request->wa_pic,
                'otp_type' => $otpType,
            ])->withErrors([
                'wa_pic' => 'Nomor WhatsApp tidak terdaftar sebagai PIC merchant.',
            ]);
        }

        // Normalize nomor untuk API (format: 62xxx)
        $phone = '62' . $basePhone;

        // Check if OTP was requested (from session)
        $otpRequestedAt = $request->session()->get('portal_otp_requested_at');
        if (!$otpRequestedAt) {
            return back()->withInput([
                'wa_pic' => $request->wa_pic,
            ])->withErrors([
                'otp' => 'Silakan request OTP terlebih dahulu.',
            ]);
        }

        // Check if OTP is expired (10 minutes)
        $requestedTime = Carbon::parse($otpRequestedAt);
        if (Carbon::now()->diffInMinutes($requestedTime) > 10) {
            $request->session()->forget(['portal_otp_requested_at', 'portal_otp_phone', 'portal_otp_type', 'portal_otp_phone_display']);
            return back()->withInput([
                'wa_pic' => $request->wa_pic,
            ])->withErrors([
                'otp' => 'OTP telah kadaluarsa. Silakan request OTP baru.',
            ]);
        }

        // Verify OTP format (6 digits)
        if (!preg_match('/^\d{6}$/', $request->otp)) {
            return back()->withInput([
                'wa_pic' => $request->wa_pic,
            ])->withErrors([
                'otp' => 'Format OTP tidak valid. Harus 6 digit angka.',
            ]);
        }

        // Get OTP type from session
        $otpType = $request->session()->get('portal_otp_type', 'emailphone');
        
        // Map otp_type to API method
        $methodMap = [
            'emailphone' => 'EMAIL',
            'whatsapp' => 'WA',
            'telegram' => 'TELE',
        ];
        $apiMethod = $methodMap[$otpType] ?? 'EMAIL';

        // Verify OTP with API
        try {
            $verifyData = [
                'method' => $apiMethod,
                'phone' => $phone,
                'otp' => $request->otp,
            ];
            
            Log::info('Portal Verifying OTP with API', [
                'url' => $this->apiVerifyUrl,
                'phone' => $phone,
                'method' => $apiMethod,
                'otp' => $request->otp,
            ]);

            $verifyResponse = $this->makeCurlRequest($this->apiVerifyUrl, $verifyData);

            Log::info('Portal OTP Verification Response', [
                'status' => $verifyResponse['status'],
                'body_preview' => substr($verifyResponse['body'], 0, 500),
                'success' => $verifyResponse['success'],
            ]);

            if (!$verifyResponse['success']) {
                $errorMessage = 'OTP tidak valid.';
                
                try {
                    $errorData = json_decode($verifyResponse['body'], true);
                    if (isset($errorData['message'])) {
                        $errorMessage = $errorData['message'];
                    } elseif (isset($errorData['error'])) {
                        $errorMessage = $errorData['error'];
                    }
                } catch (\Exception $e) {
                    // Use default error message
                }

                Log::error('Portal OTP Verification Failed', [
                    'status' => $verifyResponse['status'],
                    'body' => $verifyResponse['body'],
                    'phone' => $phone,
                ]);

                return back()->withInput([
                    'wa_pic' => $request->wa_pic,
                ])->withErrors([
                    'otp' => $errorMessage,
                ]);
            }

            // OTP is valid, create or update PortalUser
            $userData = json_decode($verifyResponse['body'], true);
            
            // Normalize wa_pic untuk konsistensi (format: 62xxx)
            $normalizedWaPic = $phone; // $phone sudah dinormalisasi ke format 62xxx
            
            Log::info('Portal OTP Verified Successfully', [
                'merchant_id' => $merchant->id,
                'merchant_name' => $merchant->nama_merchant,
                'merchant_wa_pic' => $merchant->wa_pic,
                'normalized_wa_pic' => $normalizedWaPic,
            ]);
            
            // Create or update PortalUser dengan wa_pic yang sudah dinormalisasi
            $portalUser = PortalUser::updateOrCreate(
                ['wa_pic' => $normalizedWaPic],
                [
                    'name' => $merchant->nama_merchant,
                    'merchant_id' => $merchant->id,
                    'wa_pic' => $normalizedWaPic, // Pastikan wa_pic disimpan dalam format konsisten
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            Log::info('PortalUser created/updated', [
                'portal_user_id' => $portalUser->id,
                'merchant_id' => $merchant->id,
                'wa_pic' => $portalUser->wa_pic,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Portal OTP Verification Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $phone,
            ]);

            return back()->withInput([
                'wa_pic' => $request->wa_pic,
            ])->withErrors([
                'otp' => 'Gagal memverifikasi OTP. Silakan coba lagi.',
            ]);
        }

        // OTP is valid, login user with portal guard
        // JANGAN regenerate session karena akan mempengaruhi guard lain (admin)
        // Guard portal sudah terpisah, jadi tidak perlu regenerate session
        Auth::guard('portal')->login($portalUser, true);

        Log::info('Portal User logged in successfully', [
            'portal_user_id' => $portalUser->id,
            'merchant_id' => $merchant->id,
            'wa_pic' => $portalUser->wa_pic,
            'admin_session_active' => Auth::guard('web')->check(), // Log status admin session
        ]);

        // Clear OTP session data (hanya data portal, tidak mempengaruhi session admin)
        $request->session()->forget(['portal_otp_redirect_url', 'portal_otp_type', 'portal_otp_phone', 'portal_otp_phone_display', 'portal_otp_requested_at']);

        // Redirect to intended URL or home
        $redirectTo = $request->session()->pull('portal.intended', route('home'));
        return redirect()->to($redirectTo);
    }

    public function logout(Request $request): RedirectResponse
    {
        // Hanya logout guard portal, jangan invalidate semua session
        // Ini akan menjaga session admin tetap aktif
        Auth::guard('portal')->logout();
        
        // Hanya clear session data portal, tidak invalidate semua session
        $request->session()->forget([
            'portal_otp_redirect_url',
            'portal_otp_type',
            'portal_otp_phone',
            'portal_otp_phone_display',
            'portal_otp_requested_at',
            'portal.intended'
        ]);
        
        // JANGAN regenerate token karena akan mempengaruhi guard lain
        // $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}

