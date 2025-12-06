<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Keyword;
use App\Models\Iklan;
use App\Models\WithdrawRequest;
use App\Exports\MerchantsExport;
use App\Exports\MerchantKeywordsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;

class MerchantController extends Controller
{
    public function index()
    {
        $merchants = Merchant::orderBy('id')->paginate(10);
        $keywords = Keyword::with('merchant')->orderBy('id')->paginate(10);
        $allMerchants = Merchant::orderBy('nama_merchant')->get();
        return view('admin', compact('merchants', 'keywords', 'allMerchants'));
    }

    public function show(Merchant $merchant)
    {
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('merchant-detail', [
            'merchant' => $merchant,
            'keywords' => $keywords,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_merchant'  => 'required|string|max:255',
            'kategori'       => 'nullable|string|max:100',
            'link_blanjapoin' => 'nullable|string|max:500',
            'link_blanjapoin_code' => 'nullable|string|max:255',
            'nama_pic'       => 'nullable|string|max:255',
            'wa_pic'         => 'nullable|string|max:20',
            'daerah'         => 'nullable|string|max:255',
            'detail_alamat'  => 'nullable|string',
            'lat'            => 'nullable|string|max:50',
            'long'           => 'nullable|string|max:50',
            'link_gmap'      => 'nullable|string|max:500',
            'logo_merchant'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    
        // =====================
        //  HANDLE LINK BLANJAPOIN
        // =====================
        $linkBlanjapoin = null;
        
        // Prioritas: link_blanjapoin (full) > link_blanjapoin_code
        if ($request->filled('link_blanjapoin') && trim($request->link_blanjapoin) !== '') {
            $linkBlanjapoin = trim($request->link_blanjapoin);
        } elseif ($request->filled('link_blanjapoin_code') && trim($request->link_blanjapoin_code) !== '') {
            $code = trim($request->link_blanjapoin_code);
            $linkBlanjapoin = 'blanjapoin.id/dash/' . $code;
        } else {
            $linkBlanjapoin = null;
        }
    
        // =====================
        //  HANDLE UPLOAD LOGO
        // =====================
        $logoPath = null;
    
        if ($request->hasFile('logo_merchant')) {
            // Simpan ke storage/app/public/merchants/
            $logoPath = $request->file('logo_merchant')->store('merchants', 'public');
        }
    
        // Helper function untuk convert empty string ke null
        $getValue = function($value) {
            if ($value === null) return null;
            if (is_string($value)) {
                $trimmed = trim($value);
                return $trimmed === '' ? null : $trimmed;
            }
            return $value;
        };
        
        // SIMPAN DATA KE DATABASE - Pastikan semua field tersimpan
        // Ambil semua field langsung dari request tanpa transformasi untuk lat/long
        $merchantData = [
            'nama_merchant'  => trim($request->input('nama_merchant', '')),
            'kategori'       => $getValue($request->input('kategori', null)),
            'link_blanjapoin' => $getValue($linkBlanjapoin),
            'nama_pic'       => $getValue($request->input('nama_pic', null)),
            'wa_pic'         => $getValue($request->input('wa_pic', null)),
            'daerah'         => $getValue($request->input('daerah', null)),
            'detail_daerah'  => $getValue($request->input('detail_alamat', null)),
            // Ambil lat dan long sebagai string untuk mempertahankan nilai asli input
            'lat'            => $request->has('lat') && $request->input('lat') !== '' && $request->input('lat') !== null
                                ? (string)$request->input('lat')
                                : null,
            'long'           => $request->has('long') && $request->input('long') !== '' && $request->input('long') !== null
                                ? (string)$request->input('long')
                                : null,
            'link_gmap'      => $getValue($request->input('link_gmap', null)),
            'logo_merchant'  => $logoPath,
        ];
        
        // Pastikan tidak ada field yang kosong string, semua harus null jika kosong
        foreach ($merchantData as $key => $value) {
            if ($value !== null && is_string($value) && trim($value) === '') {
                $merchantData[$key] = null;
            }
        }
        
        // // Log untuk debugging - log semua request data
        // Log::info('=== MERCHANT STORE REQUEST ===');
        // Log::info('Request all data:', $request->all());
        // Log::info('Link blanjapoin processed:', [
        //     'link_blanjapoin' => $request->input('link_blanjapoin'),
        //     'link_blanjapoin_code' => $request->input('link_blanjapoin_code'),
        //     'final_link' => $linkBlanjapoin
        // ]);
        // Log::info('Lat/Long processing:', [
        //     'lat_input' => $request->input('lat'),
        //     'lat_processed' => $merchantData['lat'],
        //     'long_input' => $request->input('long'),
        //     'long_processed' => $merchantData['long'],
        // ]);
        // Log::info('All individual fields:', [
        //     'nama_pic' => $request->input('nama_pic'),
        //     'wa_pic' => $request->input('wa_pic'),
        //     'detail_alamat' => $request->input('detail_alamat'),
        //     'lat' => $request->input('lat'),
        //     'long' => $request->input('long'),
        //     'link_gmap' => $request->input('link_gmap'),
        //     'daerah' => $request->input('daerah'),
        // ]);
        // Log::info('Creating merchant with processed data:', array_map(function($v) {
        //     if (is_string($v) && strlen($v) > 100) {
        //         return substr($v, 0, 100) . '...';
        //     }
        //     return $v;
        // }, $merchantData));
        
        try {
            $merchant = Merchant::create($merchantData);
            Log::info('Merchant created successfully with ID:', ['id' => $merchant->id]);
        } catch (\Exception $e) {
            Log::error('Error creating merchant:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $merchantData
            ]);
            
        //     // Jika request dari AJAX, return JSON error
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan merchant: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan merchant: ' . $e->getMessage()]);
        }
    
        // Jika request dari AJAX, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Merchant berhasil ditambahkan!',
                'merchant' => $merchant
            ], 201);
        }
    
        return redirect()->route('admin')->with('success', 'Merchant berhasil ditambahkan!');
    }
    


    
    public function create()
    {
        return view('merchant.create');
    }


    public function destroy($id)
    {
        try {
            $merchant = Merchant::findOrFail($id);
            
            // Delete logo file if exists
            if ($merchant->logo_merchant && Storage::disk('public')->exists($merchant->logo_merchant)) {
                Storage::disk('public')->delete($merchant->logo_merchant);
            }
            
            // Delete merchant record
            $merchant->delete();
            
            return response()->json(['success' => true, 'message' => 'Merchant berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus merchant'], 500);
        }
    }

    // edit, update menyusul

    public function downloadFile($path)
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function search(Request $request)
    {
        $searchTerm = trim($request->input('q', ''));
        $page = $request->input('page', 1);
        $category = $request->input('category');
        
        $merchantsQuery = Merchant::query();
        
        // Hanya filter jika search term tidak kosong
        if ($searchTerm !== '') {
            $merchantsQuery->where(function ($query) use ($searchTerm) {
                $query->where('nama_merchant', 'like', "%{$searchTerm}%")
                    ->orWhere('daerah', 'like', "%{$searchTerm}%")
                      ->orWhere('kategori', 'like', "%{$searchTerm}%");
            });
        }

        // Filter berdasarkan kategori spesifik jika dipilih
        if ($category && strtolower($category) !== 'semua') {
            $merchantsQuery->where('kategori', $category);
        }
        
        $merchants = $merchantsQuery
            ->orderBy('id')
            ->paginate(10, ['*'], 'page', $page)
            ->appends($request->query());
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('partials.table-merchant', [
                    'merchants' => $merchants
                ])->render(),
            ]);
        }
        
        // Untuk non-AJAX request (seperti pagination link), perlu semua variable yang diperlukan view admin
        $keywords = Keyword::with('merchant')->orderBy('id')->paginate(10);
        $allMerchants = Merchant::orderBy('nama_merchant')->get();
        
        return view('admin', compact('merchants', 'keywords', 'allMerchants'));
    }

    /**
     * Menampilkan halaman link pelanggan dengan voucher merchant
     * Route: /u/{code}
     */
    public function linkPelanggan($code)
    {
        // Decode URL encoded characters (e.g., h%26m -> h&m)
        $decodedCode = urldecode($code);
        
        // Cari merchant berdasarkan code dari link_blanjapoin
        // Format link_blanjapoin: "blanjapoin.id/dash/{code}"
        // Coba dengan code yang sudah di-decode dan juga dengan yang masih encoded
        $merchant = Merchant::where(function($query) use ($decodedCode, $code) {
                // Cari dengan code yang sudah di-decode
                $query->where('link_blanjapoin', 'like', '%/dash/' . $decodedCode)
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $decodedCode . '%')
                      // Juga coba dengan code yang masih encoded (jika berbeda)
                      ->orWhere('link_blanjapoin', 'like', '%/dash/' . $code)
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $code . '%');
            })
            ->whereNotNull('link_blanjapoin')
            ->first();

        if (!$merchant) {
            abort(404, 'Merchant tidak ditemukan');
        }

        // Ambil semua voucher/keyword yang approved untuk merchant ini
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->where('status', 'approve')
            ->orderBy('created_at', 'desc')
            ->get();

        $iklans = Iklan::latest()->get();

        return view('link-pelanggan', [
            'merchant' => $merchant,
            'keywords' => $keywords,
            'iklans' => $iklans,
        ]);
    }

    /**
     * Menampilkan halaman link dashboard dengan table link pelanggan, QR code, dan history
     * Route: /dash/{code}
     */
    public function linkDashboard($code)
    {
        // Decode URL encoded characters (e.g., h%26m -> h&m)
        $decodedCode = urldecode($code);
        
        // Escape special characters untuk LIKE query
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);
        
        // Cari merchant berdasarkan code dari link_blanjapoin
        // Format link_blanjapoin: "blanjapoin.id/dash/{code}" atau bisa juga "https://blanjapoin.id/dash/{code}"
        $merchant = Merchant::where(function($query) use ($escapedDecodedCode, $escapedCode) {
                // Cari dengan berbagai format yang mungkin
                $query->where('link_blanjapoin', 'like', '%/dash/' . $escapedDecodedCode)
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedDecodedCode)
                      ->orWhere('link_blanjapoin', 'like', '%/dash/' . $escapedDecodedCode . '%')
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedDecodedCode . '%')
                      // Juga coba dengan code yang masih encoded
                      ->orWhere('link_blanjapoin', 'like', '%/dash/' . $escapedCode)
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedCode)
                      ->orWhere('link_blanjapoin', 'like', '%/dash/' . $escapedCode . '%')
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $escapedCode . '%');
            })
            ->whereNotNull('link_blanjapoin')
            ->first();

        if (!$merchant) {
            // Log untuk debugging
            Log::warning('Merchant not found for code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
                'search_patterns' => [
                    '%/dash/' . $escapedDecodedCode,
                    '%dash/' . $escapedDecodedCode,
                    '%/dash/' . $escapedCode,
                    '%dash/' . $escapedCode,
                ],
                'sample_merchants' => Merchant::whereNotNull('link_blanjapoin')->take(5)->pluck('link_blanjapoin', 'id')->toArray()
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Generate link pelanggan
        $linkPelanggan = route('link.pelanggan', $decodedCode);
        $linkPelangganFull = url($linkPelanggan);

        // Ambil semua history keyword untuk merchant ini (semua status, diurutkan dari terbaru)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate link history (trx-history)
        $linkHistory = route('link.trx-history', $decodedCode);
        $linkHistoryFull = url($linkHistory);

        return view('link-dashboard', [
            'merchant' => $merchant,
            'linkPelanggan' => $linkPelangganFull,
            'linkHistory' => $linkHistoryFull,
            'keywords' => $keywords,
        ]);
    }

    /**
     * Menampilkan halaman link history dengan history keyword merchant
     * Route: /history/{code}
     */
    public function linkHistory($code)
    {
        // Decode URL encoded characters (e.g., h%26m -> h&m)
        $decodedCode = urldecode($code);
        
        // Escape special characters untuk LIKE query
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);
        
        // Cari merchant berdasarkan code dari link_blanjapoin
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
            Log::warning('Merchant not found for history code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Ambil semua history keyword untuk merchant ini (semua status, diurutkan dari terbaru)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trx-history', [
            'merchant' => $merchant,
            'histories' => $keywords,
        ]);
    }

    public function linkHistoryAll($code)
    {
        $decodedCode = urldecode($code);
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);

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
            Log::warning('Merchant not found for history-all code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        $keywordQuery = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('created_at', 'desc');

        $historyPaginator = (clone $keywordQuery)
            ->paginate(12, ['*'], 'history_page')
            ->withQueryString();

        $keywordPaginator = (clone $keywordQuery)
            ->paginate(12, ['*'], 'keyword_page')
            ->withQueryString();

        return view('history-all', [
            'merchant' => $merchant,
            'histories' => $historyPaginator,
            'keywordPaginator' => $keywordPaginator,
        ]);
    }

    /**
     * Menampilkan halaman keywords history untuk merchant
     * Route: /keywords/{code}
     */
    public function linkKeywords($code)
    {
        $decodedCode = urldecode($code);
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);

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
            Log::warning('Merchant not found for keywords code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Ambil semua keywords untuk merchant ini (semua status, diurutkan dari terbaru)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('partials_dash.keywords-history', [
            'merchant' => $merchant,
            'keywords' => $keywords,
        ]);
    }

    /**
     * Menampilkan halaman reedem untuk merchant
     * Route: /reedem/{code}
     */
    public function linkReedem($code)
    {
        $decodedCode = urldecode($code);
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);

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
            Log::warning('Merchant not found for reedem code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Ambil semua keywords untuk merchant ini yang memiliki redeem points (semua status, diurutkan dari terbaru)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->whereNotNull('redeem')
            ->where('redeem', '!=', '')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('partials_dash.reedem', [
            'merchant' => $merchant,
            'keywords' => $keywords,
        ]);
    }

    /**
     * Menampilkan halaman history withdraw untuk merchant
     * Route: /history-withdraw/{code}
     */
    public function linkHistoryWithdraw($code, Request $request)
    {
        $decodedCode = urldecode($code);
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);

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
            Log::warning('Merchant not found for history-withdraw code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Build query with date filter
        $query = WithdrawRequest::where('merchant_id', $merchant->id);
        
        // Date filter (single date)
        $date = $request->get('date');
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        // Get actual withdraw history from database
        $withdrawHistory = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('partials_dash.historywithdraw', [
            'merchant' => $merchant,
            'withdrawHistory' => $withdrawHistory,
        ]);
    }

    /**
     * Submit withdraw request
     * Route: POST /withdraw/submit
     */
    public function submitWithdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|exists:merchants,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bca,bni,bri,mandiri,linkaja,dana',
            'account_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Format account number untuk e-wallet (hapus +62 dan leading 0)
            $accountNumber = $request->account_number;
            $isEWallet = in_array($request->payment_method, ['linkaja', 'dana']);
            
            if ($isEWallet) {
                // Hapus +62 jika ada
                $accountNumber = preg_replace('/^\+62/', '', $accountNumber);
                // Hapus leading 0
                $accountNumber = ltrim($accountNumber, '0');
            }

            // Generate transaction ID
            $transactionId = 'WD' . date('YmdHis') . rand(1000, 9999);

            // Prepare data untuk insert
            $withdrawData = [
                'merchant_id' => $request->merchant_id,
                'nama' => 'Alexander', // Hardcoded untuk sekarang
                'metode_penarikan' => $request->payment_method,
                'jumlah' => $request->amount,
                'transaction_id' => $transactionId,
                'status' => 'pending', // Default status pending
            ];

            // Simpan ke kolom yang sesuai berdasarkan metode
            if ($isEWallet) {
                $withdrawData['no_ewallet'] = $accountNumber;
                $withdrawData['no_rekening'] = null;
            } else {
                $withdrawData['no_rekening'] = $accountNumber;
                $withdrawData['no_ewallet'] = null;
            }

            // Simpan ke database dengan status pending
            $withdrawRequest = WithdrawRequest::create($withdrawData);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan penarikan saldo berhasil diajukan',
                'data' => [
                    'transaction_id' => $transactionId,
                    'withdraw_id' => $withdrawRequest->id,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error submitting withdraw request', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan penarikan saldo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman trx-history untuk merchant
     * Route: /trx-history/{code}
     */
    public function linkTrxHistory($code)
    {
        $decodedCode = urldecode($code);
        $escapedDecodedCode = str_replace(['%', '_'], ['\%', '\_'], $decodedCode);
        $escapedCode = str_replace(['%', '_'], ['\%', '\_'], $code);

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
            Log::warning('Merchant not found for trx-history code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Ambil semua history keyword untuk merchant ini (semua status, diurutkan dari terbaru)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('partials_dash.trx-history', [
            'merchant' => $merchant,
            'histories' => $keywords,
        ]);
    }

    public function exportExcel()
    {
        $fileName = 'merchants_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new MerchantsExport, $fileName);
    }

    public function exportKeywordsExcel(Merchant $merchant)
    {
        $merchantName = str_replace([' ', '/', '\\'], '_', $merchant->nama_merchant);
        $fileName = 'keywords_' . $merchantName . '_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new MerchantKeywordsExport($merchant->id, $merchant->nama_merchant), $fileName);
    }

    /**
     * Menampilkan halaman withdraw approval untuk admin
     * Route: /withdraw-approval
     */
    public function withdrawApproval(Request $request)
    {
        $query = WithdrawRequest::with(['merchant', 'approver']);
        
        // Search filter (case-insensitive)
        $searchTerm = trim($request->get('q', ''));
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(metode_penarikan) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(no_rekening) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(no_ewallet) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereHas('merchant', function ($merchantQuery) use ($searchTerm) {
                      $merchantQuery->whereRaw('LOWER(nama_merchant) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                  });
            });
        }
        
        // Date filter (single date)
        $date = $request->get('date');
        
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        // Order by created_at desc and paginate
        $withdraws = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('withdraw-approval', [
            'withdraws' => $withdraws,
        ]);
    }

    /**
     * Approve withdraw request
     * Route: POST /withdraw-approval/{withdrawRequest}/approve
     */
    public function approveWithdraw(WithdrawRequest $withdrawRequest)
    {
        if ($withdrawRequest->status !== 'pending') {
            return redirect()->route('withdraw.approval')
                ->with('error', 'Withdraw request sudah diproses sebelumnya.');
        }

        $withdrawRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('withdraw.approval')
            ->with('success', 'Withdraw request berhasil disetujui.');
    }

    /**
     * Reject withdraw request
     * Route: POST /withdraw-approval/{withdrawRequest}/reject
     */
    public function rejectWithdraw(Request $request, WithdrawRequest $withdrawRequest)
    {
        if ($withdrawRequest->status !== 'pending') {
            return redirect()->route('withdraw.approval')
                ->with('error', 'Withdraw request sudah diproses sebelumnya.');
        }

        $request->validate([
            'dec_reject' => 'required|string|max:500',
        ]);

        $withdrawRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'dec_reject' => $request->dec_reject,
        ]);

        return redirect()->route('withdraw.approval')
            ->with('success', 'Withdraw request berhasil ditolak.');
    }
}
