
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MultiUserController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\KeywordController;
use App\Models\Keyword;

// Tampilan awal untuk semua pengunjung
Route::get('/', function () {
    $keywords = Keyword::with('merchant')->get();
    return view('welcome', compact('keywords'));
})->name('home');

// Routes untuk tamu (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    Route::get('/forgot-password', function () {
        return response('Fitur lupa password belum tersedia.', 200);
    })->name('password.request');
});

// Route untuk download/view storage files
Route::get('/storage/{path}', [MerchantController::class, 'downloadFile'])->name('storage.download')->where('path', '.*');

// Routes untuk user yang sudah login
Route::middleware(['auth'])->group(function () {
    // Halaman utama setelah login user biasa
    Route::get('/welcome', function () {
        $keywords = Keyword::with('merchant')->get();
        return view('welcome', compact('keywords'));
    })->name('welcome');

    // ======================= ADMIN / DASHBOARD =======================
    // Sekarang admin & dashboard pakai MerchantController@index
    Route::get('/admin', [MerchantController::class, 'index'])->name('admin');
    Route::get('/dashboard', [MerchantController::class, 'index'])->name('dashboard');
    Route::get('/merchants/search', [MerchantController::class, 'search'])->name('merchants.search');

    // Resource CRUD merchant (index sudah dipakai di atas)
    Route::resource('merchants', MerchantController::class)->except(['index', 'show']);

    // Keywords routes
    Route::get('/keywords', [KeywordController::class, 'index'])->name('keywords.index');
    Route::post('/keywords', [KeywordController::class, 'store'])->name('keywords.store');
    Route::put('/keywords/{id}', [KeywordController::class, 'update'])->name('keywords.update');
    Route::delete('/keywords/{id}', [KeywordController::class, 'destroy'])->name('keywords.destroy');
    Route::post('/keywords/{id}/approve', [KeywordController::class, 'approve'])->name('keywords.approve');
    Route::get('/keywords/search', [KeywordController::class, 'search'])->name('keywords.search');

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
