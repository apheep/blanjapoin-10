<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MultiUserController;
use App\Models\Keyword;

// Tampilan awal untuk semua pengunjung
Route::get('/', function () {
    $keywords = Keyword::with('merchant')->get();
    return view('welcome', compact('keywords'));
})->name('home');

// Routes untuk tamu (belum login)
Route::middleware(['guest'])->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    // Minimal forgot-password route
    Route::get('/forgot-password', function () {
        return response('Fitur lupa password belum tersedia.', 200);
    })->name('password.request');
});

// Routes untuk user yang sudah login
Route::middleware(['auth'])->group(function () {
    // Halaman utama setelah login user biasa
    Route::get('/welcome', function () {
        $keywords = Keyword::with('merchant')->get();
        return view('welcome', compact('keywords'));
    })->name('welcome');

    // Halaman admin
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');

    // Dashboard (alias admin)
    Route::get('/dashboard', function () {
        return view('admin');
    })->name('dashboard');

    // Halaman approval
    Route::get('/approval', function () {
        return view('approval');
    })->name('approval');

    // Manajemen user
    Route::get('/user-management', function () {
        return view('usermng');
    })->name('user.management');

    // Halaman profil
    Route::get('/profile', function () {
        return response('Halaman profil belum tersedia.', 200);
    })->name('profile');

    // Redirect setelah login berdasarkan role
    Route::get('/home', function () {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin');
        }
        return redirect()->route('welcome');
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
