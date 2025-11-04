<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MultiUserController;

// Routes for guests only (not logged in users)
    Route::middleware(['guest'])->group(function () {
    // Login page as homepage
    Route::get('/', [LoginController::class, 'index'])->name('home');
    
    // Login routes
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    // Minimal forgot-password route to satisfy login view link
    Route::get('/forgot-password', function () {
        return response('Fitur lupa password belum tersedia.', 200);
    })->name('password.request');
});
// Routes for authenticated users
Route::middleware(['auth'])->group(function () {
    // Post-login landing for regular users
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    // Post-login landing for admins
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');

    // Dashboard alias to support existing links
    Route::get('/dashboard', function () {
        return view('admin');
    })->name('dashboard');


    // Root redirect for authenticated users based on role
    Route::get('/', function () {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin');
        }
        return redirect()->route('welcome');
    });

    // Fallback for frameworks that redirect authenticated users to /home
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