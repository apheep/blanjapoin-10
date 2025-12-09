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
            return back()->withInput([
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

                return back()->withInput([
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
                // emailphone: Response should be "Verification code sent to email."
                return back()->withInput([
                    'no_hp' => $request->no_hp,
                    'otp_type' => $request->otp_type,
                ])->with('success', 'OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            } elseif ($request->otp_type === 'whatsapp') {
                // whatsapp: Response contains field 'whatsapp' with the redirect URL
                $redirectUrl = $responseData['whatsapp'] ?? $responseData['redirect_url'] ?? $responseData['redirect'] ?? $responseData['link'] ?? null;
                
                if ($redirectUrl) {
                    $request->session()->put('otp_redirect_url', $redirectUrl);

                    return back()->withInput([
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
                    return back()->withInput([
                        'no_hp' => $request->no_hp,
                        'otp_type' => $request->otp_type,
                    ])->with('success', 'OTP telah dikirim. Silakan cek aplikasi WhatsApp Anda.');
                }
            } elseif ($request->otp_type === 'telegram') {
                // telegram: Response contains field 'telegram' with the redirect URL
                $redirectUrl = $responseData['telegram'] ?? $responseData['redirect_url'] ?? $responseData['redirect'] ?? $responseData['link'] ?? null;
                
                if ($redirectUrl) {
                    $request->session()->put('otp_redirect_url', $redirectUrl);

                    return back()->withInput([
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
                    return back()->withInput([
                        'no_hp' => $request->no_hp,
                        'otp_type' => $request->otp_type,
                    ])->with('success', 'OTP telah dikirim. Silakan cek aplikasi Telegram Anda.');
                }
            }

            return back()->withInput([
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

            return back()->withInput([
                'no_hp' => $request->no_hp,
                'otp_type' => $request->otp_type,
            ])->withErrors([
                'no_hp' => 'Gagal mengirim OTP: ' . $e->getMessage(),
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

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

        // Get OTP type from session (lowercase)
        $otpType = $request->session()->get('otp_type', 'emailphone');
        
        // Map otp_type (lowercase) to API method
        $methodMap = [
            'emailphone' => 'EMAIL',
            'whatsapp' => 'WA',
            'telegram' => 'TELE',
        ];
        $apiMethod = $methodMap[$otpType] ?? 'EMAIL';

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

                Log::error('OTP Verification Failed', [
                    'status' => $verifyResponse['status'],
                    'body' => $verifyResponse['body'],
                    'phone' => $phone,
                ]);

                return back()->withInput([
                    'no_hp' => $request->no_hp,
                ])->withErrors([
                    'otp' => $errorMessage,
                ]);
            }

            // OTP is valid, get user data from API response
            $userData = json_decode($verifyResponse['body'], true);
            
            if (!$userData) {
                Log::error('Invalid user data from OTP verification', [
                    'response_body' => $verifyResponse['body'],
                ]);
                return back()->withInput([
                    'no_hp' => $request->no_hp,
                ])->withErrors([
                    'otp' => 'Gagal mendapatkan data user dari API.',
                ]);
            }
            
            Log::info('OTP Verified Successfully', [
                'user_data_keys' => array_keys($userData),
                'user_data' => $userData,
            ]);
            
            // Update user record with data from API response if available
            // Only update fields that are in the fillable array and exist in userData
            $updateData = [];
            $fillableFields = ['username', 'email', 'no_hp', 'role', 'can_approve'];
            
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

        // OTP is valid, login user (using potentially updated user object)
        Auth::login($user);
        $request->session()->regenerate();

        // Generate JWT token using Sanctum (or you can use custom JWT)
        $token = $user->createToken('auth-token', ['*'])->plainTextToken;
        
        // Store token in session (optional, for API access)
        $request->session()->put('api_token', $token);
        
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'username' => $user->username,
            'token_generated' => true,
        ]);

        // Clear OTP session data
        $request->session()->forget(['otp_redirect_url', 'otp_type', 'otp_phone', 'otp_phone_display', 'otp_requested_at']);

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

