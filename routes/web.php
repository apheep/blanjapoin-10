
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MultiUserController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\KeywordController;
use App\Models\Keyword;
use App\Models\Merchant;
use App\Models\Iklan;


// Tampilan awal untuk semua pengunjung
Route::get('/', function () {
    $keywords = Keyword::with('merchant')->get();
    $iklans = Iklan::orderBy('order', 'asc')->get();
    
    // Ambil semua daerah dan ekstrak hanya kabupaten/kota
    $allDaerah = Merchant::query()
        ->whereNotNull('daerah')
        ->where('daerah', '!=', '')
        ->distinct()
        ->pluck('daerah');
    
    // Ekstrak hanya kabupaten/kota dari daerah
    // Format biasanya: "Kota/Kabupaten, Provinsi" atau "Kota/Kabupaten" atau "Nama Kabupaten/Kota"
    $locations = $allDaerah->map(function($daerah) {
        $daerah = trim($daerah);
        
        // Jika ada koma, ambil bagian sebelum koma pertama (kabupaten/kota)
        if (strpos($daerah, ',') !== false) {
            $parts = explode(',', $daerah);
            $kabupatenKota = trim($parts[0]);
            
            // Hapus kata "Kota" atau "Kabupaten" jika ada di awal
            $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $kabupatenKota);
            
            return $kabupatenKota ?: trim($parts[0]); // Fallback jika setelah hapus jadi kosong
        }
        
        // Jika tidak ada koma, cek apakah ada kata "Kota" atau "Kabupaten"
        if (preg_match('/^(?:Kota|Kabupaten)\s+(.+)$/i', $daerah, $matches)) {
            return trim($matches[1]);
        }
        
        // Jika tidak ada format khusus, gunakan seluruhnya
        return $daerah;
    })
    ->filter(function($item) {
        // Hapus yang kosong atau terlalu pendek
        return !empty($item) && strlen($item) > 1;
    })
    ->unique() // Hapus duplikat
    ->sort()
    ->values();

    return view('welcome', [
        'keywords' => $keywords,
        'locations' => $locations,
        'iklans' => $iklans,
    ]);
})->name('home');

Route::get('/search', [KeywordController::class, 'publicSearch'])->name('merchant.search');

// Route untuk link pelanggan (public, tidak perlu login)
Route::get('/u/{code}', [MerchantController::class, 'linkPelanggan'])->name('link.pelanggan');

// Portal merchant authentication
Route::middleware('guest:portal')->group(function () {
    Route::get('/merchant-login', [PortalAuthController::class, 'showLoginForm'])->name('portal.login');
    Route::post('/merchant-login', [PortalAuthController::class, 'login'])->name('portal.login.post');
    
    // Google OAuth routes (sesuai dokumentasi Socialite)
    Route::get('/auth/redirect', [PortalAuthController::class, 'redirectToGoogle'])->name('portal.google.redirect');
    Route::get('/auth-google-callback', [PortalAuthController::class, 'handleGoogleCallback'])->name('portal.google.callback');
    
    // Debug route untuk melihat data Google OAuth (hapus di production)
    Route::get('/debug/google-callback', [PortalAuthController::class, 'debugGoogleCallback'])->name('portal.google.debug');
});
Route::post('/merchant-logout', [PortalAuthController::class, 'logout'])->name('portal.logout');

// Route untuk link dashboard (conditional auth berdasarkan email merchant)
Route::middleware('merchant.email.auth')->get('/dash/{code}', [MerchantController::class, 'linkDashboard'])->name('link.dashboard');

// Route untuk link history (public, tidak perlu login)
Route::get('/history/{code}', [MerchantController::class, 'linkHistory'])->name('link.history');

// Route untuk history page versi lengkap tanpa login
Route::get('/history-all/{code}', [MerchantController::class, 'linkHistoryAll'])->name('link.history.all');

// Route untuk link keywords (wajib login portal)
Route::middleware('portal.auth')->get('/keywords/{code}', [MerchantController::class, 'linkKeywords'])->name('link.keywords');

// Route untuk link reedem (wajib login portal)
Route::middleware('portal.auth')->get('/reedem/{code}', [MerchantController::class, 'linkReedem'])->name('link.reedem');

// Route untuk link history-withdraw (wajib login portal)
Route::middleware('portal.auth')->get('/history-withdraw/{code}', [MerchantController::class, 'linkHistoryWithdraw'])->name('link.history-withdraw');

// Route untuk submit withdraw request (wajib login portal)
Route::middleware('portal.auth')->post('/withdraw/submit', [MerchantController::class, 'submitWithdraw'])->name('withdraw.submit');

// Route untuk link trx-history (wajib login portal)
Route::middleware('portal.auth')->get('/trx-history/{code}', [MerchantController::class, 'linkTrxHistory'])->name('link.trx-history');

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
        $iklans = Iklan::orderBy('order', 'asc')->get();
        
        // Ambil semua daerah dan ekstrak hanya kabupaten/kota
        $allDaerah = Merchant::query()
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->distinct()
            ->pluck('daerah');
        
        // Ekstrak hanya kabupaten/kota dari daerah
        // Format biasanya: "Kota/Kabupaten, Provinsi" atau "Kota/Kabupaten" atau "Nama Kabupaten/Kota"
        $locations = $allDaerah->map(function($daerah) {
            $daerah = trim($daerah);
            
            // Jika ada koma, ambil bagian sebelum koma pertama (kabupaten/kota)
            if (strpos($daerah, ',') !== false) {
                $parts = explode(',', $daerah);
                $kabupatenKota = trim($parts[0]);
                
                // Hapus kata "Kota" atau "Kabupaten" jika ada di awal
                $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $kabupatenKota);
                
                return $kabupatenKota ?: trim($parts[0]); // Fallback jika setelah hapus jadi kosong
            }
            
            // Jika tidak ada koma, cek apakah ada kata "Kota" atau "Kabupaten"
            if (preg_match('/^(?:Kota|Kabupaten)\s+(.+)$/i', $daerah, $matches)) {
                return trim($matches[1]);
            }
            
            // Jika tidak ada format khusus, gunakan seluruhnya
            return $daerah;
        })
        ->filter(function($item) {
            // Hapus yang kosong atau terlalu pendek
            return !empty($item) && strlen($item) > 1;
        })
        ->unique() // Hapus duplikat
        ->sort()
        ->values();

        return view('welcome', [
            'keywords' => $keywords,
            'locations' => $locations,
            'iklans' => $iklans,
        ]);
    })->name('welcome');

    // ======================= ADMIN / DASHBOARD =======================
    // Sekarang admin & dashboard pakai MerchantController@index
    Route::get('/admin', [MerchantController::class, 'index'])->name('admin');
    Route::get('/dashboard', [MerchantController::class, 'index'])->name('dashboard');
    Route::get('/merchants/search', [MerchantController::class, 'search'])->name('merchants.search');
    Route::get('/merchants/export/excel', [MerchantController::class, 'exportExcel'])->name('merchants.export.excel');
    Route::get('/merchants/{merchant}/keywords/export/excel', [MerchantController::class, 'exportKeywordsExcel'])->name('merchants.keywords.export.excel');

    // Resource CRUD merchant (index sudah dipakai di atas)
    Route::resource('merchants', MerchantController::class)->except(['index', 'show']);
    Route::get('/merchants/{merchant}', [MerchantController::class, 'show'])->name('merchants.show');

    // Keywords routes
    Route::get('/keywords', [KeywordController::class, 'index'])->name('keywords.index');
    Route::post('/keywords', [KeywordController::class, 'store'])->name('keywords.store');
    Route::put('/keywords/{id}', [KeywordController::class, 'update'])->name('keywords.update');
    Route::delete('/keywords/{id}', [KeywordController::class, 'destroy'])->name('keywords.destroy');
    Route::post('/keywords/{id}/approve', [KeywordController::class, 'approve'])->name('keywords.approve');
    Route::post('/keywords/{id}/reject', [KeywordController::class, 'reject'])->name('keywords.reject');
    Route::get('/keywords/search', [KeywordController::class, 'search'])->name('keywords.search');
    Route::get('/keywords/export/excel', [KeywordController::class, 'exportExcel'])->name('keywords.export.excel');

    // Iklan management
    Route::get('/iklan', [IklanController::class, 'index'])->name('iklan.index');
    Route::post('/iklan', [IklanController::class, 'store'])->name('iklan.store');
    Route::post('/iklan/reorder', [IklanController::class, 'updateOrder'])->name('iklan.reorder');
    Route::delete('/iklan/{iklan}', [IklanController::class, 'destroy'])->name('iklan.destroy');

    // Withdraw Approval
    Route::get('/withdraw-approval', [MerchantController::class, 'withdrawApproval'])->name('withdraw.approval');
    Route::post('/withdraw-approval/{withdrawRequest}/approve', [MerchantController::class, 'approveWithdraw'])->name('withdraw.approve');
    Route::post('/withdraw-approval/{withdrawRequest}/reject', [MerchantController::class, 'rejectWithdraw'])->name('withdraw.reject');

    // History All (requires login)
    Route::get('/history-all/{code}', [MerchantController::class, 'linkHistoryAll'])->name('link.history.all');

    // Manajemen user
    Route::get('/user-management', [UserController::class, 'index'])->name('user.management');
    Route::get('/api/users', [UserController::class, 'getUsers'])->name('users.get');
    Route::post('/api/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/api/users/{user}/toggle-approval', [UserController::class, 'toggleApproval'])->name('users.toggle-approval');
    Route::delete('/api/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/api/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('/api/users/bulk-toggle-approval', [UserController::class, 'bulkToggleApproval'])->name('users.bulk-toggle-approval');

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

// API Proxy untuk wilayah Indonesia (menghindari CORS)
// OJOK DISENGGOL CAK!!

Route::get('/api/wilayah/provinces', function () {
    try {
        $response = Http::timeout(10)->get('https://wilayah.id/api/provinces.json');
        if ($response->successful()) {
            return response()->json($response->json());
        }
        Log::error('Failed to fetch provinces. Status: ' . $response->status());
        return response()->json(['error' => 'Failed to fetch provinces'], 500);
    } catch (\Exception $e) {
        Log::error('Error fetching provinces: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch provinces', 'message' => $e->getMessage()], 500);
    }
})->name('api.wilayah.provinces');

Route::get('/api/wilayah/regencies/{code}', function ($code) {
    try {
        $url = "https://wilayah.id/api/regencies/{$code}.json";
        $response = Http::timeout(10)->get($url);
        if ($response->successful()) {
            return response()->json($response->json());
        }
        Log::error('Failed to fetch regencies. Status: ' . $response->status());
        return response()->json(['error' => 'Failed to fetch regencies'], 500);
    } catch (\Exception $e) {
        Log::error('Error fetching regencies: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch regencies', 'message' => $e->getMessage()], 500);
    }
})->where('code', '[0-9.]+')->name('api.wilayah.regencies');

// Route untuk districts - menggunakan query parameter untuk menghindari masalah titik
Route::get('/api/wilayah/districts/{code}', function (Request $request, $code) {
    // Jika code tidak ada atau kosong, coba ambil dari query
    if (empty($code) || $code === 'null' || $code === 'undefined') {
        $code = $request->query('code', '');
    }
    // Log request untuk debugging
    Log::info('Districts route hit', [
        'code' => $code,
        'url' => $request->fullUrl(),
        'path' => $request->path()
    ]);
    
    try {
        // Log untuk debugging
        Log::info('Fetching districts for code: ' . $code);
        
        // Bersihkan kode dari karakter yang tidak perlu
        $cleanCode = preg_replace('/[^0-9.]/', '', $code);
        
        // Jika kode mengandung titik, coba format tanpa titik juga
        $codesToTry = [$cleanCode];
        if (strpos($cleanCode, '.') !== false) {
            $codesToTry[] = str_replace('.', '', $cleanCode);
        }
        
        $url = null;
        $response = null;
        $lastError = null;
        
        foreach ($codesToTry as $tryCode) {
            $url = "https://wilayah.id/api/districts/{$tryCode}.json";
            Log::info('Trying API URL: ' . $url);
            
            try {
                $response = Http::timeout(10)->get($url);
                
                if ($response->successful()) {
                    Log::info('Success with code: ' . $tryCode);
                    return response()->json($response->json());
                } else {
                    $lastError = [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ];
                    Log::warning('Failed with code ' . $tryCode . ': Status ' . $response->status());
                }
            } catch (\Exception $e) {
                $lastError = ['message' => $e->getMessage()];
                Log::warning('Exception with code ' . $tryCode . ': ' . $e->getMessage());
            }
        }
        
        // Log error response
        Log::error('Failed to fetch districts. Last error: ' . json_encode($lastError));
        return response()->json([
            'error' => 'Failed to fetch districts',
            'status' => $lastError['status'] ?? 404,
            'message' => 'District data not found for code: ' . $code,
            'tried_codes' => $codesToTry,
            'last_error' => $lastError
        ], $lastError['status'] ?? 404);
    } catch (\Exception $e) {
        Log::error('Error fetching districts: ' . $e->getMessage());
        return response()->json([
            'error' => 'Failed to fetch districts',
            'message' => $e->getMessage()
        ], 500);
    }
})->where('code', '[^/]+')->name('api.wilayah.districts');

// Route alternatif untuk districts dengan query parameter (untuk kode dengan titik)
Route::get('/api/wilayah/districts-by-code', function (Request $request) {
    $code = $request->query('code', '');
    
    if (empty($code)) {
        return response()->json(['error' => 'Code parameter is required'], 400);
    }
    
    // Log request untuk debugging
    Log::info('Districts route (query) hit', [
        'code' => $code,
        'url' => $request->fullUrl()
    ]);
    
    try {
        // Bersihkan kode dari karakter yang tidak perlu
        $cleanCode = preg_replace('/[^0-9.]/', '', $code);
        
        // Jika kode mengandung titik, coba format tanpa titik juga
        $codesToTry = [$cleanCode];
        if (strpos($cleanCode, '.') !== false) {
            $codesToTry[] = str_replace('.', '', $cleanCode);
        }
        
        $url = null;
        $response = null;
        $lastError = null;
        
        foreach ($codesToTry as $tryCode) {
            $url = "https://wilayah.id/api/districts/{$tryCode}.json";
            Log::info('Trying API URL: ' . $url);
            
            try {
                $response = Http::timeout(10)->get($url);
                
                if ($response->successful()) {
                    Log::info('Success with code: ' . $tryCode);
                    return response()->json($response->json());
                } else {
                    $lastError = [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ];
                    Log::warning('Failed with code ' . $tryCode . ': Status ' . $response->status());
                }
            } catch (\Exception $e) {
                $lastError = ['message' => $e->getMessage()];
                Log::warning('Exception with code ' . $tryCode . ': ' . $e->getMessage());
            }
        }
        
        // Log error response
        Log::error('Failed to fetch districts. Last error: ' . json_encode($lastError));
        return response()->json([
            'error' => 'Failed to fetch districts',
            'status' => $lastError['status'] ?? 404,
            'message' => 'District data not found for code: ' . $code,
            'tried_codes' => $codesToTry
        ], $lastError['status'] ?? 404);
    } catch (\Exception $e) {
        Log::error('Error fetching districts: ' . $e->getMessage());
        return response()->json([
            'error' => 'Failed to fetch districts',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('api.wilayah.districts.query');


