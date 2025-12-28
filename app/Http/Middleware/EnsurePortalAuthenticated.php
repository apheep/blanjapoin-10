<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePortalAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (Auth::guard('portal')->check()) {
            return $next($request);
        }

        // Simpan URL yang dituju untuk redirect setelah login
        $request->session()->put('portal.intended', $request->fullUrl());

        // Cek apakah ini karena session expired
        $errorMessage = 'Silakan login untuk melanjutkan.';
        if ($request->session()->has('_token')) {
            // Session masih ada tapi user tidak terautentikasi = session expired
            $errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
        }

        // Redirect ke halaman login biasa (bukan portal.login)
        return redirect()->route('login')
            ->with('error', $errorMessage);
    }
}

