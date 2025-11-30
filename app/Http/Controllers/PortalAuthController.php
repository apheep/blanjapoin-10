<?php

namespace App\Http\Controllers;

use App\Models\PortalUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

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

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $exception) {
            return redirect()->route('portal.login')->withErrors([
                'google' => __('Gagal login menggunakan Google. Silakan coba lagi.'),
            ]);
        }

        $user = PortalUser::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)),
            ]
        );

        Auth::guard('portal')->login($user, true);

        $redirectTo = $request->session()->pull('portal.intended', route('home'));

        return redirect()->to($redirectTo);
    }
}

