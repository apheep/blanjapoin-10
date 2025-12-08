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

            // Cek apakah merchant punya email_pic
            $hasEmail = !empty($merchant->email_pic) && trim($merchant->email_pic) !== '';
            
            if (!$hasEmail) {
                // Jika tidak ada email, skip auth dan lanjutkan
                Log::info('Merchant has no email, skipping auth', [
                    'merchant_id' => $merchant->id,
                    'merchant_name' => $merchant->nama_merchant,
                ]);
                return $next($request);
            }

            // Jika ada email_pic, WAJIB login (tidak peduli apakah email terdaftar di PortalUser atau tidak)
            Log::info('Merchant has email, checking auth', [
                'merchant_id' => $merchant->id,
                'merchant_name' => $merchant->nama_merchant,
                'merchant_email' => $merchant->email_pic,
                'is_authenticated' => Auth::guard('portal')->check(),
            ]);
            
            if (Auth::guard('portal')->check()) {
                $user = Auth::guard('portal')->user();
                
                // Validasi: user yang login harus sesuai dengan email merchant
                if ($user->email !== $merchant->email_pic) {
                    Log::info('User email mismatch, logging out', [
                        'user_email' => $user->email,
                        'merchant_email' => $merchant->email_pic,
                    ]);
                    
                    Auth::guard('portal')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    $request->session()->put('portal.intended', $request->fullUrl());
                    
                    return redirect()->route('portal.login', [
                        'returnTo' => $request->fullUrl(),
                    ])->withErrors([
                        'email' => 'Anda harus login dengan email: ' . $merchant->email_pic,
                    ]);
                }
                
                // Email sesuai, lanjutkan
                Log::info('User authenticated, proceeding', [
                    'user_email' => $user->email,
                ]);
                return $next($request);
            }

            // Belum login, redirect ke login
            $request->session()->put('portal.intended', $request->fullUrl());
            
            Log::info('User not authenticated, redirecting to login', [
                'merchant_email' => $merchant->email_pic,
                'intended_url' => $request->fullUrl(),
            ]);

            return redirect()->route('portal.login', [
                'returnTo' => $request->fullUrl(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in EnsureMerchantEmailAuth middleware', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

