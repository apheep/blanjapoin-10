<?php

namespace App\Http\Middleware;

use App\Models\Merchant;
use App\Models\PortalUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureMerchantEmailAuth
{
    /**
     * Format wa_pic untuk display di form (hapus semua prefix)
     */
    private function formatWaPicForDisplay($waPic)
    {
        if (empty($waPic)) {
            return null;
        }
        
        $waPic = trim($waPic);
        
        // Remove +62 prefix if present
        if (strpos($waPic, '+62') === 0) {
            $waPic = substr($waPic, 3);
        } 
        // Remove 62 prefix if present (pastikan bukan hanya "62" saja)
        elseif (strpos($waPic, '62') === 0 && strlen($waPic) > 2) {
            $waPic = substr($waPic, 2);
        } 
        // Remove 0 prefix if present (pastikan bukan hanya "0" saja)
        elseif (strpos($waPic, '0') === 0 && strlen($waPic) > 1) {
            $waPic = substr($waPic, 1);
        }
        
        return $waPic;
    }
    
    public function handle(Request $request, Closure $next)
    {
        try {
            $code = $request->route('code');
            
            if (!$code) {
                abort(404, 'Code tidak ditemukan');
            }

            // Decode URL encoded characters
            $decodedCode = urldecode($code);
            
            // Escape special characters untuk LIKE query
            $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
            $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);
            
            // Cari merchant berdasarkan code
            $merchant = Merchant::where(function($query) use ($escapedDecodedCode, $escapedCode) {
                    $query->where('link_blanjapoin', 'like', '%/dash/' . $escapedDecodedCode)
                          ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedDecodedCode)
                          ->orWhere('link_blanjapoin', 'like', '%/dash/' . $escapedDecodedCode . '%')
                          ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedDecodedCode . '%')
                          ->orWhere('link_blanjapoin', 'like', '%/dash/' . $escapedCode)
                          ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedCode)
                          ->orWhere('link_blanjapoin', 'like', '%/dash/' . $escapedCode . '%')
                          ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedCode . '%');
                })
                ->whereNotNull('link_blanjapoin')
                ->first();

            if (!$merchant) {
                abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
            }

            // Simpan merchant ke request untuk digunakan di controller
            $request->attributes->set('merchant', $merchant);

            // Cek apakah user yang login adalah admin (superadmin/admin yang bisa approve)
            // Admin bisa mengakses tanpa perlu login portal
            if (Auth::check()) {
                $adminUser = Auth::user();
                if ($adminUser->role === 'admin' && $adminUser->can_approve == 1) {
                    Log::info('Admin user accessing link-dashboard, skipping portal auth', [
                        'admin_username' => $adminUser->username,
                        'merchant_id' => $merchant->id,
                        'merchant_name' => $merchant->nama_merchant,
                    ]);
                    return $next($request);
                }
            }

            // Cek apakah merchant punya wa_pic
            $hasWaPic = !empty($merchant->wa_pic) && trim($merchant->wa_pic) !== '';
            
            if (!$hasWaPic) {
                // Jika tidak ada wa_pic, skip auth dan lanjutkan
                Log::info('Merchant has no wa_pic, skipping auth', [
                    'merchant_id' => $merchant->id,
                    'merchant_name' => $merchant->nama_merchant,
                ]);
                return $next($request);
            }

            // Jika ada wa_pic, WAJIB login dengan OTP
            Log::info('Merchant has wa_pic, checking auth', [
                'merchant_id' => $merchant->id,
                'merchant_name' => $merchant->nama_merchant,
                'merchant_wa_pic' => $merchant->wa_pic,
                'is_authenticated' => Auth::guard('portal')->check(),
            ]);
            
            if (Auth::guard('portal')->check()) {
                $user = Auth::guard('portal')->user();
                
                // Normalize phone numbers untuk perbandingan (format: 62xxx tanpa + atau 0)
                $normalizePhone = function($phone) {
                    if (empty($phone)) return null;
                    $phone = trim($phone);
                    // Remove +62 prefix if present
                    if (strpos($phone, '+62') === 0) {
                        $phone = '62' . substr($phone, 3);
                    } elseif (strpos($phone, '0') === 0) {
                        $phone = '62' . substr($phone, 1);
                    } elseif (strpos($phone, '62') !== 0) {
                        $phone = '62' . $phone;
                    }
                    return $phone;
                };
                
                $userWaPic = $normalizePhone($user->wa_pic);
                $merchantWaPic = $normalizePhone($merchant->wa_pic);
                
                // Validasi: user yang login harus sesuai dengan wa_pic merchant
                if ($userWaPic !== $merchantWaPic) {
                    Log::info('User wa_pic mismatch, logging out portal only', [
                        'user_wa_pic' => $user->wa_pic,
                        'user_wa_pic_normalized' => $userWaPic,
                        'merchant_wa_pic' => $merchant->wa_pic,
                        'merchant_wa_pic_normalized' => $merchantWaPic,
                        'admin_session_active' => Auth::guard('web')->check(), // Log status admin
                    ]);
                    
                    // Hanya logout guard portal, jangan invalidate semua session
                    // Ini akan menjaga session admin tetap aktif
                    Auth::guard('portal')->logout();
                    
                    // Hanya clear session data portal, tidak invalidate semua session
                    $request->session()->forget([
                        'portal_otp_redirect_url',
                        'portal_otp_type',
                        'portal_otp_phone',
                        'portal_otp_phone_display',
                        'portal_otp_requested_at'
                    ]);
                    
                    // JANGAN regenerate token karena akan mempengaruhi guard lain
                    // $request->session()->regenerateToken();
                    
                    $request->session()->put('portal.intended', $request->fullUrl());
                    
                    // Clear session OTP yang lama
                    $request->session()->forget([
                        'portal_otp_phone',
                        'portal_otp_phone_display',
                        'portal_otp_type',
                        'portal_otp_requested_at',
                        'portal_otp_redirect_url'
                    ]);
                    
                    // Pass wa_pic merchant untuk auto-fill
                    $loginParams = [
                        'returnTo' => $request->fullUrl(),
                    ];
                    
                    // Format wa_pic untuk display menggunakan helper function
                    if ($merchant->wa_pic) {
                        $waPicDisplay = $this->formatWaPicForDisplay($merchant->wa_pic);
                        
                        // Hanya tambahkan jika wa_pic tidak kosong setelah normalisasi
                        if (!empty($waPicDisplay)) {
                            $loginParams['wa_pic'] = $waPicDisplay;
                            
                            Log::info('Formatting wa_pic for display (mismatch)', [
                                'merchant_id' => $merchant->id,
                                'merchant_name' => $merchant->nama_merchant,
                                'original_wa_pic' => $merchant->wa_pic,
                                'formatted_wa_pic' => $waPicDisplay,
                            ]);
                        }
                    }
                    
                    return redirect()->route('portal.login', $loginParams)->withErrors([
                        'wa_pic' => 'Anda harus login dengan nomor WhatsApp sesuai wa_pic merchant',
                    ]);
                }
                
                // wa_pic sesuai, lanjutkan
                Log::info('User authenticated, proceeding', [
                    'user_wa_pic' => $user->wa_pic,
                    'user_wa_pic_normalized' => $userWaPic,
                    'merchant_wa_pic_normalized' => $merchantWaPic,
                ]);
                return $next($request);
            }

            // Belum login, redirect ke login
            // Clear session OTP yang lama untuk memastikan tidak ada konflik
            $request->session()->forget([
                'portal_otp_phone',
                'portal_otp_phone_display',
                'portal_otp_type',
                'portal_otp_requested_at',
                'portal_otp_redirect_url'
            ]);
            
            $request->session()->put('portal.intended', $request->fullUrl());
            
            Log::info('User not authenticated, redirecting to login', [
                'merchant_id' => $merchant->id,
                'merchant_name' => $merchant->nama_merchant,
                'merchant_wa_pic_raw' => $merchant->wa_pic,
                'intended_url' => $request->fullUrl(),
            ]);

            // Pass wa_pic merchant sebagai parameter untuk auto-fill
            $loginParams = [
                'returnTo' => $request->fullUrl(),
            ];
            
            // Format wa_pic untuk display menggunakan helper function
            if ($merchant->wa_pic) {
                $waPicDisplay = $this->formatWaPicForDisplay($merchant->wa_pic);
                
                // Hanya tambahkan jika wa_pic tidak kosong setelah normalisasi
                if (!empty($waPicDisplay)) {
                    $loginParams['wa_pic'] = $waPicDisplay;
                    
                    Log::info('Formatting wa_pic for display', [
                        'merchant_id' => $merchant->id,
                        'merchant_name' => $merchant->nama_merchant,
                        'original_wa_pic' => $merchant->wa_pic,
                        'formatted_wa_pic' => $waPicDisplay,
                    ]);
                }
            }

            return redirect()->route('portal.login', $loginParams);
        } catch (\Exception $e) {
            Log::error('Error in EnsureMerchantEmailAuth middleware', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

