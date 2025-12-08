<?php

namespace App\Http\Controllers;

use App\Models\PortalUser;
use App\Services\UserGoogle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PortalAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $returnTo = $request->query('returnTo', $request->session()->get('portal.intended'));

        return view('portal-login', [
            'returnTo' => $returnTo,
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');
        $returnTo = $request->input('returnTo') ?: $request->session()->pull('portal.intended', route('home'));

        if (Auth::guard('portal')->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $remember)) {
            $request->session()->regenerate();

            return redirect()->to($returnTo ?? route('home'));
        }

        throw ValidationException::withMessages([
            'email' => __('Email atau password tidak sesuai.'),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('portal')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }

    public function redirectToGoogle(Request $request)
    {
        $returnTo = $request->query('returnTo');

        if ($returnTo) {
            $request->session()->put('portal.intended', $returnTo);
        }

        try {
            $google = new UserGoogle();
            $authUrl = $google->getAuthUrl();
            
            Log::info('Google OAuth redirect', [
                'auth_url' => $authUrl,
                'redirect_uri' => url('/auth-google-callback'),
            ]);
            
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('portal.login')->withErrors([
                'google' => 'Konfigurasi Google OAuth tidak valid. Silakan hubungi administrator.',
            ]);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->query('code');
        
        if (!$code) {
            Log::error('Google OAuth callback: No code received', [
                'request_params' => $request->all(),
            ]);
            
            return redirect()->route('portal.login')->withErrors([
                'google' => __('Gagal login menggunakan Google. Kode autentikasi tidak ditemukan.'),
            ]);
        }

        try {
            $google = new UserGoogle();
            $userInfo = $google->getProfile($code);
            
            if (!$userInfo) {
                Log::error('Google OAuth callback: Failed to get profile', [
                    'code' => $code,
                ]);
                
                return redirect()->route('portal.login')->withErrors([
                    'google' => __('Gagal login menggunakan Google. Tidak dapat mengambil data profil.'),
                ]);
            }
            
            // Log semua data yang diterima dari Google
            Log::info('=== Google OAuth Callback Data ===', [
                'email' => $userInfo->getEmail(),
                'name' => $userInfo->getName(),
                'id' => $userInfo->getId(),
                'picture' => $userInfo->getPicture(),
                'verified_email' => $userInfo->getVerifiedEmail(),
            ]);
            
            $user = PortalUser::updateOrCreate(
                ['email' => $userInfo->getEmail()],
                [
                    'name' => $userInfo->getName(),
                    'google_id' => $userInfo->getId(),
                    'avatar' => $userInfo->getPicture(),
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            Log::info('PortalUser created/updated', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'google_id' => $user->google_id,
            ]);

            Auth::guard('portal')->login($user, true);

            $redirectTo = $request->session()->pull('portal.intended', route('home'));

            Log::info('Redirecting after Google login', [
                'redirect_to' => $redirectTo,
                'user_email' => $user->email,
            ]);

            return redirect()->to($redirectTo);
            
        } catch (\Exception $exception) {
            Log::error('Google OAuth callback error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'code' => $code ?? 'not set',
            ]);
            
            return redirect()->route('portal.login')->withErrors([
                'google' => __('Gagal login menggunakan Google. Silakan coba lagi.'),
            ]);
        }
    }

    /**
     * Debug method untuk melihat data yang diterima dari Google OAuth
     * Hapus method ini di production atau protect dengan middleware
     */
    public function debugGoogleCallback(Request $request)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $code = $request->query('code');
        
        if (!$code) {
            return response()->json([
                'status' => 'error',
                'message' => 'No code received',
                'request_params' => $request->all(),
            ], 400, [], JSON_PRETTY_PRINT);
        }

        try {
            $google = new UserGoogle();
            $userInfo = $google->getProfile($code);
            
            if (!$userInfo) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get profile from Google',
                ], 500, [], JSON_PRETTY_PRINT);
            }
            
            $data = [
                'email' => $userInfo->getEmail(),
                'name' => $userInfo->getName(),
                'id' => $userInfo->getId(),
                'picture' => $userInfo->getPicture(),
                'verified_email' => $userInfo->getVerifiedEmail(),
            ];
            
            // Log juga ke file
            Log::info('=== DEBUG: Google OAuth Callback Data ===', $data);
            
            // Return JSON untuk mudah dibaca
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diterima dari Google',
                'data' => $data,
                'note' => 'Cek juga di storage/logs/laravel.log untuk detail lengkap'
            ], 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $exception) {
            Log::error('DEBUG: Google OAuth callback error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data dari Google',
                'error' => $exception->getMessage(),
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }
}

