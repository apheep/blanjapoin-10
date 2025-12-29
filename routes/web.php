
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
use App\Http\Controllers\SpesialPromoController;
use App\Models\Keyword;
use App\Models\Merchant;
use App\Models\Iklan;


// Tampilan awal untuk semua pengunjung
Route::get('/', function () {
    // Auto-disable keywords that have passed their end_date
    Keyword::autoDisableExpiredKeywords();
    
    $keywords = Keyword::with('merchant')
        ->where('is_active', 1)
        ->where('status', 'approve')
        ->whereHas('merchant', function ($query) {
            $query->where('is_active', 1);
        })
        ->get();
    // Get iklans - only show general iklans (all location fields are null) for home page
    $iklans = Iklan::whereNull('territorial')
        ->whereNull('regional')
        ->whereNull('branch')
        ->whereNull('cluster')
        ->orderBy('order', 'asc')
        ->get();
    
    // Ambil semua daerah dan ekstrak hanya kabupaten/kota (semua merchant, tidak filter is_active)
    $allDaerah = Merchant::query()
        ->whereNotNull('daerah')
        ->where('daerah', '!=', '')
        ->distinct()
        ->pluck('daerah');
    
    // Ekstrak hanya kabupaten/kota dari daerah (bukan kecamatan)
    // Format bisa: "Kecamatan, Kabupaten, Provinsi" atau "Beji, Kota Depok, Jawa Barat" atau "Kabupaten, Provinsi" atau "Kota/Kabupaten"
    $locations = $allDaerah->map(function($daerah) {
        $daerah = trim($daerah);
        
        // Jika ada koma, parse bagian-bagiannya
        if (strpos($daerah, ',') !== false) {
            $parts = array_map('trim', explode(',', $daerah));
            $partsCount = count($parts);
            
            // Jika ada 3 bagian atau lebih: format biasanya "Kecamatan, Kabupaten/Kota, Provinsi"
            // Ambil bagian kedua (index 1) yang biasanya adalah kabupaten/kota
            if ($partsCount >= 3) {
                $kabupatenKota = $parts[1]; // Ambil bagian kedua
            }
            // Jika ada 2 bagian
            else if ($partsCount == 2) {
                $firstPart = $parts[0];
                $secondPart = $parts[1];
                
                // Cek apakah bagian pertama adalah kecamatan (dengan atau tanpa kata "Kecamatan")
                // Jika bagian pertama tidak mengandung kata "Kota" atau "Kabupaten", kemungkinan besar itu kecamatan
                $isFirstPartKecamatan = preg_match('/^Kecamatan\s+/i', $firstPart) || 
                                       (!preg_match('/^(Kota|Kabupaten)\s+/i', $firstPart) && 
                                        !preg_match('/^(Kota|Kabupaten)\s+/i', $secondPart));
                
                if ($isFirstPartKecamatan) {
                    // Jika bagian pertama adalah kecamatan, ambil bagian kedua (kabupaten/kota)
                    $kabupatenKota = $secondPart;
                } else {
                    // Jika bagian pertama bukan kecamatan, ambil bagian pertama (kabupaten/kota)
                    $kabupatenKota = $firstPart;
                }
            }
            // Jika hanya 1 bagian (tidak mungkin, tapi untuk safety)
            else {
                $kabupatenKota = trim($parts[0]);
            }
            
            // Hapus kata "Kota" atau "Kabupaten" jika ada di awal
            $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($kabupatenKota));
            
            return $kabupatenKota ?: null;
        }
        
        // Jika tidak ada koma, cek apakah ada kata "Kota" atau "Kabupaten"
        if (preg_match('/^(?:Kota|Kabupaten)\s+(.+)$/i', $daerah, $matches)) {
            return trim($matches[1]);
        }
        
        // Cek apakah dimulai dengan "Kecamatan", jika ya skip (karena kita tidak mau kecamatan)
        if (preg_match('/^Kecamatan\s+/i', $daerah)) {
            return null; // Skip kecamatan
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

// Spesial Promo Public Page
Route::get('/spesial-promo', [SpesialPromoController::class, 'index'])->name('spesial-promo.index');

// ======================= TERRITORIAL FILTERS (PUBLIC) =======================
// Route untuk menampilkan merchant berdasarkan kota/kabupaten
// Format: /city/{location} (contoh: /city/surabaya)
// Route ini PUBLIC, tidak perlu login
Route::get('/city/{location}', [MerchantController::class, 'showByTerritorial'])->name('city.show');

// Route untuk menampilkan merchant berdasarkan regional
// Format: /reg/{location} (contoh: /reg/jakarta)
// Route ini PUBLIC, tidak perlu login
Route::get('/reg/{location}', [MerchantController::class, 'showByRegional'])->name('regional.show');

// Route untuk menampilkan merchant berdasarkan branch
// Format: /branch/{location} (contoh: /branch/jakarta-barat)
// Route ini PUBLIC, tidak perlu login
Route::get('/branch/{location}', [MerchantController::class, 'showByBranch'])->name('branch.show');

// Route untuk menampilkan merchant berdasarkan cluster
// Format: /cluster/{location} (contoh: /cluster/jakarta-cluster-1)
// Route ini PUBLIC, tidak perlu login
Route::get('/cluster/{location}', [MerchantController::class, 'showByCluster'])->name('cluster.show');

// Route untuk link pelanggan (public, tidak perlu login)
Route::get('/u/{code}', [MerchantController::class, 'linkPelanggan'])->name('link.pelanggan');

// Route untuk tracking click dan redirect (NO JavaScript needed!)
Route::get('/r/{merchantId}/{keywordId?}', [MerchantController::class, 'trackAndRedirect'])->name('track.redirect');

// API untuk tracking click (public, dipanggil dari JavaScript) - OPTIONAL
Route::post('/api/track-click', [\App\Http\Controllers\ClickHistoryController::class, 'trackClick'])->name('api.track.click');
Route::get('/api/resolve-gmap-url', [\App\Http\Controllers\MerchantController::class, 'resolveGmapUrl'])->name('api.resolve.gmap.url');
Route::get('/api/geocode', [\App\Http\Controllers\MerchantController::class, 'geocode'])->name('api.geocode');
Route::get('/api/place-details', [\App\Http\Controllers\MerchantController::class, 'placeDetails'])->name('api.place.details');

// Portal merchant authentication (OTP-based)
Route::middleware('guest:portal')->group(function () {
    Route::get('/merchant-login', [PortalAuthController::class, 'showLoginForm'])->name('portal.login');
    Route::post('/merchant-send-otp', [PortalAuthController::class, 'sendOtp'])->name('portal.send-otp');
    Route::post('/merchant-authenticate', [PortalAuthController::class, 'authenticate'])->name('portal.authenticate');
});
Route::post('/merchant-logout', [PortalAuthController::class, 'logout'])->name('portal.logout');

// Route untuk link dashboard (conditional auth berdasarkan wa_pic merchant)
Route::middleware('merchant.email.auth')->get('/dash/{code}', [MerchantController::class, 'linkDashboard'])->name('link.dashboard');

// Route untuk link history (public, tidak perlu login)
Route::get('/history/{code}', [MerchantController::class, 'linkHistory'])->name('link.history');

// Route untuk history page versi lengkap tanpa login
Route::get('/history-all/{code}', [MerchantController::class, 'linkHistoryAll'])->name('link.history.all');

// PENTING: Route admin keywords/search harus didefinisikan SEBELUM route /keywords/{code}
// untuk menghindari konflik pattern matching
// Route ini dipindahkan dari dalam group middleware auth ke sini, tapi tetap menggunakan middleware auth
Route::middleware(['auth'])->get('/keywords/search', [KeywordController::class, 'search'])->name('keywords.search');

// Route untuk link keywords (wajib login portal)
// Constraint: code tidak boleh "search", "export", atau path admin lainnya
Route::middleware('portal.auth')->get('/keywords/{code}', [MerchantController::class, 'linkKeywords'])
    ->where('code', '^(?!search|export|excel).*$')
    ->name('link.keywords');

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
    Route::post('/login/send-otp', [LoginController::class, 'sendOtp'])->name('login.send-otp');

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
        $keywords = Keyword::with('merchant')
            ->where('is_active', 1)
            ->where('status', 'approve')
            ->whereHas('merchant', function ($query) {
                $query->where('is_active', 1);
            })
            ->get();
        // Get iklans - only show iklans without territorial (null) for home page
    $iklans = Iklan::whereNull('territorial')
        ->orderBy('order', 'asc')
        ->get();
        
        // Ambil semua daerah dan ekstrak hanya kabupaten/kota (semua merchant, tidak filter is_active)
        $allDaerah = Merchant::query()
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->distinct()
            ->pluck('daerah');
        
        // Ekstrak hanya kabupaten/kota dari daerah (bukan kecamatan)
        // Format bisa: "Kecamatan, Kabupaten, Provinsi" atau "Beji, Kota Depok, Jawa Barat" atau "Kabupaten, Provinsi" atau "Kota/Kabupaten"
        $locations = $allDaerah->map(function($daerah) {
            $daerah = trim($daerah);
            
            // Jika ada koma, parse bagian-bagiannya
            if (strpos($daerah, ',') !== false) {
                $parts = array_map('trim', explode(',', $daerah));
                $partsCount = count($parts);
                
                // Jika ada 3 bagian atau lebih: format biasanya "Kecamatan, Kabupaten/Kota, Provinsi"
                // Ambil bagian kedua (index 1) yang biasanya adalah kabupaten/kota
                if ($partsCount >= 3) {
                    $kabupatenKota = $parts[1]; // Ambil bagian kedua
                }
                // Jika ada 2 bagian
                else if ($partsCount == 2) {
                    $firstPart = $parts[0];
                    $secondPart = $parts[1];
                    
                    // Cek apakah bagian pertama atau kedua mengandung "Kota" atau "Kabupaten"
                    $firstHasKotaKabupaten = preg_match('/^(Kota|Kabupaten)\s+/i', $firstPart);
                    $secondHasKotaKabupaten = preg_match('/^(Kota|Kabupaten)\s+/i', $secondPart);
                    
                    if ($firstHasKotaKabupaten) {
                        // Jika bagian pertama mengandung "Kota" atau "Kabupaten", ambil bagian pertama
                        $kabupatenKota = $firstPart;
                    } else if ($secondHasKotaKabupaten) {
                        // Jika bagian kedua mengandung "Kota" atau "Kabupaten", ambil bagian kedua
                        // (bagian pertama kemungkinan besar adalah kecamatan)
                        $kabupatenKota = $secondPart;
                    } else if (preg_match('/^Kecamatan\s+/i', $firstPart)) {
                        // Jika bagian pertama dimulai dengan "Kecamatan", ambil bagian kedua
                        $kabupatenKota = $secondPart;
                    } else {
                        // Jika tidak ada yang jelas, asumsikan bagian pertama adalah kecamatan
                        // Ambil bagian kedua sebagai kabupaten/kota
                        $kabupatenKota = $secondPart;
                    }
                }
                // Jika hanya 1 bagian (tidak mungkin, tapi untuk safety)
                else {
                    $kabupatenKota = trim($parts[0]);
                }
                
                // Hapus kata "Kota" atau "Kabupaten" jika ada di awal
                $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($kabupatenKota));
                
                return $kabupatenKota ?: null;
            }
            
            // Jika tidak ada koma, cek apakah ada kata "Kota" atau "Kabupaten"
            if (preg_match('/^(?:Kota|Kabupaten)\s+(.+)$/i', $daerah, $matches)) {
                return trim($matches[1]);
            }
            
            // Cek apakah dimulai dengan "Kecamatan", jika ya skip (karena kita tidak mau kecamatan)
            if (preg_match('/^Kecamatan\s+/i', $daerah)) {
                return null; // Skip kecamatan
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
    Route::patch('/api/merchants/{id}/toggle-status', [MerchantController::class, 'toggleStatus'])->name('merchants.toggle-status');
    Route::patch('/api/merchants/{id}/toggle-link-status', [MerchantController::class, 'toggleLinkStatus'])->name('merchants.toggle-link-status');

    // Keywords routes
    Route::get('/keywords', [KeywordController::class, 'index'])->name('keywords.index');
    Route::post('/keywords', [KeywordController::class, 'store'])->name('keywords.store');
    Route::put('/keywords/{id}', [KeywordController::class, 'update'])->name('keywords.update');
    Route::delete('/keywords/{id}', [KeywordController::class, 'destroy'])->name('keywords.destroy');
    Route::post('/keywords/{id}/approve', [KeywordController::class, 'approve'])->name('keywords.approve');
    Route::post('/keywords/{id}/reject', [KeywordController::class, 'reject'])->name('keywords.reject');
    Route::patch('/api/keywords/{id}/toggle-status', [KeywordController::class, 'toggleStatus'])->name('keywords.toggle-status');
    Route::patch('/api/keywords/{id}/toggle-special-promo', [KeywordController::class, 'toggleSpecialPromo'])->name('keywords.toggle-special-promo');
    Route::get('/api/keywords/by-merchant/{merchantId}', [KeywordController::class, 'getByMerchant'])->name('keywords.by-merchant');
    // Route /keywords/search sudah dipindahkan ke atas (sebelum route /keywords/{code}) untuk menghindari konflik
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

    // Click History Routes
    Route::get('/click-history', [\App\Http\Controllers\ClickHistoryController::class, 'index'])->name('click.history.index');
    Route::get('/click-history/analytics', [\App\Http\Controllers\ClickHistoryController::class, 'analytics'])->name('click.history.analytics');
    Route::get('/click-history/anonymous-redeems', [\App\Http\Controllers\ClickHistoryController::class, 'anonymousRedeems'])->name('click.history.anonymous');
    Route::get('/click-history/not-matched-detail', [\App\Http\Controllers\ClickHistoryController::class, 'notMatchedDetail'])->name('click.history.not-matched-detail');

    // Recalculate Diamond
    Route::post('/merchants/recalculate-diamond', [MerchantController::class, 'recalculateDiamond'])->name('merchants.recalculate-diamond');

    // Spesial Promo Form
    Route::get('/spesial-promo-form', [KeywordController::class, 'spesialPromoForm'])->name('spesial-promo.form');

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
    Route::post('/api/users/import-preview', [UserController::class, 'importPreview'])->name('users.import-preview');
    Route::post('/api/users/import', [UserController::class, 'import'])->name('users.import');

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


