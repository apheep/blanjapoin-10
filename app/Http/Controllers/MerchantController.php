<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Keyword;
use App\Models\Iklan;
use App\Models\PortalUser;
use App\Models\WithdrawRequest;
use App\Models\DimTeritorialNational;
use App\Models\User;
use App\Exports\MerchantsExport;
use App\Exports\MerchantKeywordsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        // Buat query params untuk appends, pastikan keyword_page tetap ada
        $merchantQueryParams = $request->query();
        // Pastikan keyword_page tetap ada jika sebelumnya ada di request
        if ($request->has('keyword_page')) {
            $merchantQueryParams['keyword_page'] = $request->get('keyword_page');
        }
        
        // Let Laravel automatically read the page number from the request using the page name
        $merchants = Merchant::orderBy('id')
            ->paginate(10, ['*'], 'merchant_page')
            ->appends($merchantQueryParams);
            
        // Buat query params untuk appends, pastikan merchant_page tetap ada
        $keywordQueryParams = $request->query();
        // Pastikan merchant_page tetap ada jika sebelumnya ada di request
        if ($request->has('merchant_page')) {
            $keywordQueryParams['merchant_page'] = $request->get('merchant_page');
        }
            
        // Let Laravel automatically read the page number from the request using the page name
        $keywords = Keyword::with('merchant')
            ->orderBy('id')
            ->paginate(10, ['*'], 'keyword_page')
            ->appends($keywordQueryParams);
            
        $allMerchants = Merchant::orderBy('nama_merchant')->get();
        
        // Get filtered cities based on user_level
        $cities = $this->getFilteredCities();
        
        return view('admin', compact('merchants', 'keywords', 'allMerchants', 'cities'));
    }
    
    /**
     * Get filtered cities based on authenticated user's user_level
     */
    private function getFilteredCities()
    {
        if (!Auth::check()) {
            return [];
        }
        
        $user = Auth::user();
        $userLevel = strtoupper($user->user_level ?? '');
        
        $query = DimTeritorialNational::select('city')
            ->distinct()
            ->orderBy('city');
        
        switch ($userLevel) {
            case 'NATIONAL':
                // Show all cities, no filter
                break;
                
            case 'AREA':
                // Filter by id_area from area_level (e.g., "AREA 3" -> id_area = 3)
                if ($user->area_level) {
                    // Extract number from "AREA 3" format
                    preg_match('/AREA\s*(\d+)/i', $user->area_level, $matches);
                    if (isset($matches[1])) {
                        $idArea = (int)$matches[1];
                        $query->where('id_area', $idArea);
                    }
                }
                break;
                
            case 'REGIONAL':
                // Filter by regional
                if ($user->regional) {
                    $query->where('regional', $user->regional);
                }
                break;
                
            case 'BRANCH':
                // Filter by branch
                if ($user->branch) {
                    $query->where('branch', $user->branch);
                }
                break;
                
            default:
                // If user_level is not recognized, return empty array
                return [];
        }
        
        return $query->pluck('city')->unique()->sort()->values()->toArray();
    }

    public function show(Merchant $merchant)
    {
        // Auto-disable keywords that have passed their end_date
        Keyword::autoDisableExpiredKeywords();
        
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            // Removed is_active filter to show all keywords (both active and inactive) in merchant-detail page
            // ->where('status', 'approve')
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
            'wa_pic'         => ['nullable', 'string', 'max:20', 'regex:/^\+62[0-9]{9,12}$/'],
            'email_pic'      => 'nullable|email|max:255',
            'daerah'         => 'nullable|string|max:255',
            'detail_alamat'  => 'nullable|string',
            'link_gmap'      => 'nullable|string|max:500',
            'logo_merchant'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ktp_pic'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'wa_pic.regex' => 'Nomor WhatsApp harus dimulai dengan +62 dan diikuti 9-12 digit angka (format: +6281234567890)',
            'email_pic.email' => 'Email PIC harus dalam format email yang valid',
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
        
        // =====================
        //  HANDLE UPLOAD KTP
        // =====================
        $ktpPath = null;
    
        if ($request->hasFile('ktp_pic')) {
            // Simpan ke storage/app/public/merchants/
            $ktpPath = $request->file('ktp_pic')->store('merchants', 'public');
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
        // Handle is_active: ambil langsung dari request, default 1 jika tidak ada
        $isActive = $request->input('is_active', 1);
        
        $merchantData = [
            'nama_merchant'  => trim($request->input('nama_merchant', '')),
            'kategori'       => $getValue($request->input('kategori', null)),
            'link_blanjapoin' => $getValue($linkBlanjapoin),
            'nama_pic'       => $getValue($request->input('nama_pic', null)),
            'wa_pic'         => $getValue($request->input('wa_pic', null)),
            'email_pic'      => $getValue($request->input('email_pic', null)),
            'daerah'         => $getValue($request->input('daerah', null)),
            'detail_daerah'  => $getValue($request->input('detail_alamat', null)),
            // Ambil lat dan long sebagai string untuk mempertahankan nilai asli input
            // 'lat'            => $request->has('lat') && $request->input('lat') !== '' && $request->input('lat') !== null
            //                     ? (string)$request->input('lat')
            //                     : null,
            // 'long'           => $request->has('long') && $request->input('long') !== '' && $request->input('long') !== null
            //                     ? (string)$request->input('long')
            //                     : null,
            'link_gmap'      => $getValue($request->input('link_gmap', null)),
            'logo_merchant'  => $logoPath,
            'ktp_pic'        => $ktpPath,
            'is_active'      => (int)$isActive,
            'start_date'     => $request->input('start_date') ?: null,
            'end_date'       => $request->input('end_date') ?: null,
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
            
            // Delete KTP file if exists
            if ($merchant->ktp_pic && Storage::disk('public')->exists($merchant->ktp_pic)) {
                Storage::disk('public')->delete($merchant->ktp_pic);
            }
            
            // Delete merchant record
            $merchant->delete();
            
            return response()->json(['success' => true, 'message' => 'Merchant berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus merchant'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_merchant'  => 'required|string|max:255',
            'kategori'       => 'nullable|string|max:100',
            'link_blanjapoin' => 'nullable|string|max:500',
            'link_blanjapoin_code' => 'nullable|string|max:255',
            'nama_pic'       => 'nullable|string|max:255',
            'wa_pic'         => ['nullable', 'string', 'max:20', 'regex:/^\+62[0-9]{9,12}$/'],
            'email_pic'      => 'nullable|email|max:255',
            'daerah'         => 'nullable|string|max:255',
            'detail_alamat'  => 'nullable|string',
            'link_gmap'      => 'nullable|string|max:500',
            'logo_merchant'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ktp_pic'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'start_date'     => 'nullable|date_format:Y-m-d',
            'end_date'       => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ], [
            'wa_pic.regex' => 'Nomor WhatsApp harus dimulai dengan +62 dan diikuti 9-12 digit angka (format: +6281234567890)',
            'email_pic.email' => 'Email PIC harus dalam format email yang valid',
            'end_date.after_or_equal' => 'Tanggal akhir periode tidak boleh sebelum tanggal mulai periode',
        ]);
    
        try {
            $merchant = Merchant::findOrFail($id);
            
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
                $linkBlanjapoin = $merchant->link_blanjapoin; // Keep existing if not provided
            }
        
            // =====================
            //  HANDLE UPLOAD LOGO
            // =====================
            $logoPath = $merchant->logo_merchant; // Keep existing logo by default
        
            if ($request->hasFile('logo_merchant')) {
                // Delete old logo if exists
                if ($merchant->logo_merchant && Storage::disk('public')->exists($merchant->logo_merchant)) {
                    Storage::disk('public')->delete($merchant->logo_merchant);
                }
                // Simpan ke storage/app/public/merchants/
                $logoPath = $request->file('logo_merchant')->store('merchants', 'public');
            }
            
            // =====================
            //  HANDLE UPLOAD KTP
            // =====================
            $ktpPath = $merchant->ktp_pic; // Keep existing KTP by default
        
            if ($request->hasFile('ktp_pic')) {
                // Delete old KTP if exists
                if ($merchant->ktp_pic && Storage::disk('public')->exists($merchant->ktp_pic)) {
                    Storage::disk('public')->delete($merchant->ktp_pic);
                }
                // Simpan ke storage/app/public/merchants/
                $ktpPath = $request->file('ktp_pic')->store('merchants', 'public');
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
            
            // UPDATE DATA KE DATABASE
            $merchantData = [
                'nama_merchant'  => trim($request->input('nama_merchant', '')),
                'kategori'       => $getValue($request->input('kategori', null)),
                'link_blanjapoin' => $getValue($linkBlanjapoin),
                'nama_pic'       => $getValue($request->input('nama_pic', null)),
                'wa_pic'         => $getValue($request->input('wa_pic', null)),
                'email_pic'      => $getValue($request->input('email_pic', null)),
                'daerah'         => $getValue($request->input('daerah', null)),
                'detail_daerah'  => $getValue($request->input('detail_alamat', null)),
                'link_gmap'      => $getValue($request->input('link_gmap', null)),
                'logo_merchant'  => $logoPath,
                'ktp_pic'        => $ktpPath,
                'start_date'     => $request->input('start_date') ?: null,
                'end_date'       => $request->input('end_date') ?: null,
            ];
            
            // Pastikan tidak ada field yang kosong string, semua harus null jika kosong
            foreach ($merchantData as $key => $value) {
                if ($value !== null && is_string($value) && trim($value) === '') {
                    $merchantData[$key] = null;
                }
            }
            
            // Update merchant
            $oldStartDate = $merchant->start_date;
            $oldEndDate = $merchant->end_date;
            $merchant->update($merchantData);
            
            // Refresh merchant untuk mendapatkan nilai baru
            $merchant->refresh();
            $newStartDate = $merchant->start_date;
            $newEndDate = $merchant->end_date;
            
            Log::info('Merchant updated successfully', ['id' => $merchant->id]);
            
            // Update semua keyword yang terkait dengan merchant ini
            // Jika start_date atau end_date merchant berubah, paksa semua keyword mengikuti merchant period
            if ($oldStartDate != $newStartDate || $oldEndDate != $newEndDate) {
                // Update semua keyword yang terkait - paksa semua start/end date mengikuti merchant
                $keywords = Keyword::where('merchant_key', $merchant->id)->get();
                $updatedCount = 0;
                
                foreach ($keywords as $keyword) {
                    $originalStartDate = $keyword->start_date;
                    $originalEndDate = $keyword->end_date;
                    
                    // Paksa semua keyword mengikuti merchant start_date dan end_date
                    $keywordStartDate = $newStartDate;
                    $keywordEndDate = $newEndDate;
                    
                    // Update keyword dengan start/end date merchant yang baru
                    $keyword->update([
                        'start_date' => $keywordStartDate,
                        'end_date' => $keywordEndDate,
                    ]);
                    
                    $updatedCount++;
                    Log::info('Keyword updated due to merchant period change', [
                        'keyword_id' => $keyword->id,
                        'merchant_id' => $merchant->id,
                        'old_start_date' => $originalStartDate,
                        'new_start_date' => $keywordStartDate,
                        'old_end_date' => $originalEndDate,
                        'new_end_date' => $keywordEndDate,
                    ]);
                }
                
                if ($updatedCount > 0) {
                    Log::info('Keywords updated due to merchant period change', [
                        'merchant_id' => $merchant->id,
                        'updated_count' => $updatedCount,
                        'merchant_start_date' => $newStartDate,
                        'merchant_end_date' => $newEndDate,
                    ]);
                }
            }
            
            // Jika request dari AJAX, return JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Merchant berhasil diupdate!',
                    'merchant' => $merchant
                ], 200);
            }
        
            return redirect()->route('admin')->with('success', 'Merchant berhasil diupdate!');
            
        } catch (\Exception $e) {
            Log::error('Error updating merchant:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'merchant_id' => $id
            ]);
            
            // Jika request dari AJAX, return JSON error
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate merchant: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mengupdate merchant: ' . $e->getMessage()]);
        }
    }

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
        
        // Buat query params untuk appends, pastikan keyword_page tetap ada
        $merchantQueryParams = $request->query();
        // Pastikan keyword_page tetap ada jika sebelumnya ada di request
        if ($request->has('keyword_page')) {
            $merchantQueryParams['keyword_page'] = $request->get('keyword_page');
        }
        
        // Let Laravel automatically read the page number from the request using the page name
        $merchants = $merchantsQuery
            ->orderBy('id')
            ->paginate(10, ['*'], 'merchant_page')
            ->appends($merchantQueryParams);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('partials.table-merchant', [
                    'merchants' => $merchants
                ])->render(),
            ]);
        }
        
        // Untuk non-AJAX request (seperti pagination link), perlu semua variable yang diperlukan view admin
        // Buat query params untuk appends, pastikan merchant_page tetap ada
        $keywordQueryParams = $request->query();
        // Pastikan merchant_page tetap ada jika sebelumnya ada di request
        if ($request->has('merchant_page')) {
            $keywordQueryParams['merchant_page'] = $request->get('merchant_page');
        }
        
        // Let Laravel automatically read the page number from the request using the page name
        $keywords = Keyword::with('merchant')
            ->orderBy('id')
            ->paginate(10, ['*'], 'keyword_page')
            ->appends($keywordQueryParams);
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
        // Note: Tidak ada validasi is_active merchant, semua merchant bisa diakses
        $merchant = Merchant::where(function($query) use ($decodedCode, $code) {
                // Cari dengan code yang sudah di-decode
                $query->where('link_blanjapoin', 'like', '%/dash/' . $decodedCode)
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $decodedCode . '%')
                      // Juga coba dengan code yang masih encoded (jika berbeda)
                      ->orWhere('link_blanjapoin', 'like', '%/dash/' . $code)
                      ->orWhere('link_blanjapoin', 'like', '%dash/' . $code . '%');
            })
            ->whereNotNull('link_blanjapoin')
            // Tidak ada filter is_active merchant - semua merchant bisa diakses
            ->first();

        if (!$merchant) {
            abort(404, 'Merchant tidak ditemukan');
        }

        // Ambil semua voucher/keyword yang approved untuk merchant ini
        // Validasi hanya pada is_active keyword (bukan merchant)
        $merchantKeywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->where('status', 'approve')
            ->where('is_active', 1) // Validasi is_active keyword
            ->orderBy('created_at', 'desc')
            ->get();

        // Get iklans - prioritize merchant-specific banner, fallback to general
        // Ambil keywords dari merchant ini untuk section kategori (filter berdasarkan kategori keyword)
        // Validasi hanya pada is_active keyword (bukan merchant)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->where('status', 'approve')
            ->where('is_active', 1) // Validasi is_active keyword
            ->get();

        // Get iklans - only show general iklans (all location fields are null) for link pelanggan page
        // Use orderBy('order', 'asc') to respect admin-configured order
        // Check both merchant_key (legacy) and merchant_keys JSON array
        $specificIklans = Iklan::where(function($query) use ($merchant) {
                $query->where('merchant_key', $merchant->id)
                      ->orWhereJsonContains('merchant_keys', $merchant->id);
            })
            ->whereNull('territorial')
            ->whereNull('regional')
            ->whereNull('branch')
            ->whereNull('cluster')
            ->orderBy('order', 'asc')
            ->get();
        
        // If specific merchant iklans found, use them. Otherwise, use general iklans
        if ($specificIklans->isNotEmpty()) {
            $iklans = $specificIklans;
        } else {
            // Get general iklans (all location fields are null and merchant_key/merchant_keys are null or empty)
            $iklans = Iklan::whereNull('territorial')
                ->whereNull('regional')
                ->whereNull('branch')
                ->whereNull('cluster')
                ->whereNull('merchant_key')
                ->where(function($query) {
                    $query->whereNull('merchant_keys')
                          ->orWhere('merchant_keys', '[]')
                          ->orWhere('merchant_keys', '');
                })
                ->orderBy('order', 'asc')
                ->get();
        }

        return view('link-pelanggan', [
            'merchant' => $merchant,
            'keywords' => $keywords,
            'merchantKeywords' => $merchantKeywords,
            'iklans' => $iklans,
            'isLinkPelanggan' => true, // Flag untuk skip validasi merchant->is_active di view
        ]);
    }

    /**
     * Menampilkan halaman link dashboard dengan table link pelanggan, QR code, dan history
     * Route: /dash/{code}
     */
    public function linkDashboard($code, Request $request)
    {
        // Decode URL encoded characters (e.g., h%26m -> h&m)
        $decodedCode = urldecode($code);
        
        // Ambil merchant dari request (sudah di-set oleh middleware EnsureMerchantEmailAuth)
        $merchant = $request->attributes->get('merchant');
        
        if (!$merchant) {
            // Fallback: jika merchant tidak ada di request, cari manual
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
        }

        if (!$merchant) {
            Log::warning('Merchant not found for code', [
                'code' => $code,
                'decoded_code' => $decodedCode,
            ]);
            abort(404, 'Merchant tidak ditemukan untuk code: ' . $code);
        }

        // Cek apakah merchant punya email_pic dan email terdaftar di PortalUser
        $hasEmail = !empty($merchant->email_pic) && trim($merchant->email_pic) !== '';
        $showDiamond = false;
        
        if ($hasEmail) {
            // Cek apakah email terdaftar di PortalUser
            $portalUser = PortalUser::where('email', $merchant->email_pic)->first();
            $showDiamond = $portalUser !== null;
        }

        // Generate link pelanggan
        $linkPelanggan = route('link.pelanggan', $decodedCode);
        $linkPelangganFull = url($linkPelanggan);

        // Ambil semua history keyword untuk merchant ini (semua status, diurutkan dari terbaru)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total diamond dari history transaksi
        // Logic: Setiap transaksi (trx) pada keyword dengan subsidy_amount menghasilkan diamond
        // Total diamond = sum(trx * subsidy_amount) untuk semua keywords
        // 1 rupiah = 1 diamond
        $totalDiamond = 0;
        foreach ($keywords as $keyword) {
            if ($keyword->subsidy_amount && $keyword->trx) {
                // Parse trx menjadi integer (jika string, ambil nilai numeriknya)
                $trxCount = is_numeric($keyword->trx) ? (int)$keyword->trx : 0;
                // Hitung diamond = jumlah transaksi * nilai subsidi (rupiah)
                $diamondFromKeyword = $trxCount * (float)$keyword->subsidy_amount;
                $totalDiamond += $diamondFromKeyword;
            }
        }

        // Generate link history (trx-history)
        $linkHistory = route('link.trx-history', $decodedCode);
        $linkHistoryFull = url($linkHistory);

        return view('link-dashboard', [
            'merchant' => $merchant,
            'linkPelanggan' => $linkPelangganFull,
            'linkHistory' => $linkHistoryFull,
            'keywords' => $keywords,
            'showDiamond' => $showDiamond,
            'totalDiamond' => $totalDiamond,
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

        // Ambil semua keywords untuk merchant ini yang memiliki redeem points (hanya yang aktif dan approved)
        $keywords = Keyword::with('merchant')
            ->where('merchant_key', $merchant->id)
            ->where('is_active', 1)
            ->where('status', 'approve')
            ->whereNotNull('redeem')
            ->where('redeem', '!=', '')
            ->whereHas('merchant', function ($query) {
                $query->where('is_active', 1);
            })
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
        
        // Sorting
        $sortBy = $request->get('sort_by');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'no') {
            // Sort by ID: asc = smallest first (1, 2, 3...), desc = largest first (...3, 2, 1)
            // Clear any default ordering first
            $query->reorder();
            $query->orderBy('withdraw_requests.id', $sortOrder);
        } elseif ($sortBy === 'status') {
            // Custom sorting dengan 3 state: approved first, pending first, rejected first (sama dengan withdraw approval)
            if ($sortOrder === 'asc') {
                // Klik 1: Approve di atas
                $query->orderByRaw("CASE 
                    WHEN status = 'approved' THEN 1 
                    WHEN status = 'rejected' THEN 2 
                    WHEN status = 'pending' THEN 3 
                    ELSE 4 
                END");
            } elseif ($sortOrder === 'desc') {
                // Klik 2: Waiting di atas
                $query->orderByRaw("CASE 
                    WHEN status = 'pending' THEN 1 
                    WHEN status = 'approved' THEN 2 
                    WHEN status = 'rejected' THEN 3 
                    ELSE 4 
                END");
            } else {
                // Klik 3: Reject di atas (sort_order = 'reject')
                $query->orderByRaw("CASE 
                    WHEN status = 'rejected' THEN 1 
                    WHEN status = 'approved' THEN 2 
                    WHEN status = 'pending' THEN 3 
                    ELSE 4 
                END");
            }
        } elseif ($sortBy === 'nama') {
            $query->orderBy('nama', $sortOrder);
        } elseif ($sortBy === 'metode') {
            // Custom sorting: Bank first (bca, bni, bri, mandiri) then E-Wallet (linkaja, dana)
            if ($sortOrder === 'asc') {
                $query->orderByRaw("CASE 
                    WHEN metode_penarikan IN ('bca', 'bni', 'bri', 'mandiri') THEN 1 
                    WHEN metode_penarikan IN ('linkaja', 'dana') THEN 2 
                    ELSE 3 
                END")
                ->orderBy('metode_penarikan', 'asc');
            } else {
                // E-Wallet first
                $query->orderByRaw("CASE 
                    WHEN metode_penarikan IN ('linkaja', 'dana') THEN 1 
                    WHEN metode_penarikan IN ('bca', 'bni', 'bri', 'mandiri') THEN 2 
                    ELSE 3 
                END")
                ->orderBy('metode_penarikan', 'asc');
            }
        } elseif ($sortBy === 'tanggal') {
            $query->orderBy('created_at', $sortOrder);
        } else {
            // Default: order by created_at desc
            $query->orderBy('created_at', 'desc');
        }
        
        // Get actual withdraw history from database
        $withdrawHistory = $query->paginate(10)->withQueryString();

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
            // SECURITY: Verify authorization BEFORE fetching merchant data
            // This prevents unauthorized access by checking permissions first
            
            $isPortalUser = Auth::guard('portal')->check();
            $isAdmin = Auth::check() && Auth::user()->can_approve;
            
            // If neither portal user nor admin, reject immediately
            if (!$isPortalUser && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk mengajukan penarikan.',
                ], 401);
            }
            
            // Fetch merchant - this will throw 404 if merchant doesn't exist
            $merchant = Merchant::findOrFail($request->merchant_id);
            
            // SECURITY: Verify that the authenticated user is authorized to submit withdrawals for this merchant
            if ($isPortalUser) {
                $portalUser = Auth::guard('portal')->user();
                
                // Portal users can ONLY submit for merchants where email_pic matches their email
                // This is the critical security check - portal users cannot submit for other merchants
                if ($merchant->email_pic) {
                    // Merchant has email_pic - must match portal user's email exactly
                    if ($merchant->email_pic !== $portalUser->email) {
                        Log::warning('Unauthorized withdraw attempt by portal user', [
                            'portal_user_id' => $portalUser->id,
                            'portal_user_email' => $portalUser->email,
                            'merchant_id' => $merchant->id,
                            'merchant_email_pic' => $merchant->email_pic,
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda tidak memiliki izin untuk mengajukan penarikan untuk merchant ini.',
                        ], 403);
                    }
                } else {
                    // Merchant has no email_pic - portal users cannot submit for these merchants
                    // Only admins can submit for merchants without email_pic
                    Log::warning('Portal user attempted withdraw for merchant without email_pic', [
                        'portal_user_id' => $portalUser->id,
                        'portal_user_email' => $portalUser->email,
                        'merchant_id' => $merchant->id,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk mengajukan penarikan untuk merchant ini.',
                    ], 403);
                }
            } else {
                // Admin users can submit for any merchant
                // This is allowed as admins have can_approve = 1
                if (!$isAdmin) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk mengajukan penarikan.',
                    ], 403);
                }
            }
            
            // Tentukan nama: prioritaskan PortalUser name, kemudian nama_pic, terakhir nama_merchant
            $nama = $merchant->nama_merchant; // Default fallback
            if ($merchant->nama_pic) {
                $nama = $merchant->nama_pic;
            }
            // Jika ada user yang login via portal, gunakan nama dari PortalUser
            if (Auth::guard('portal')->check()) {
                $portalUser = Auth::guard('portal')->user();
                if ($portalUser && $portalUser->name) {
                    $nama = $portalUser->name;
                }
            }
            
            // Format account number untuk e-wallet
            // Standardize format: store with +62 prefix to match merchant wa_pic format
            $accountNumber = $request->account_number;
            $isEWallet = in_array($request->payment_method, ['linkaja', 'dana']);
            
            if ($isEWallet) {
                // Normalize: ensure +62 prefix for consistency with merchant wa_pic format
                // Remove existing +62 if present, then add it back to ensure consistency
                $accountNumber = preg_replace('/^\+62/', '', $accountNumber);
                // Remove leading 0 if present
                $accountNumber = preg_replace('/^0/', '', $accountNumber);
                // Add +62 prefix for consistent storage format (matches merchant wa_pic)
                $accountNumber = '+62' . $accountNumber;
            }

            // Generate transaction ID
            $transactionId = 'WD' . date('YmdHis') . rand(1000, 9999);

            // Prepare data untuk insert
            $withdrawData = [
                'merchant_id' => $request->merchant_id,
                'nama' => $nama,
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
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

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
        
        // Sorting
        $sortBy = $request->get('sort_by');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'no') {
            // Sort by ID: asc = smallest first (1, 2, 3...), desc = largest first (...3, 2, 1)
            // Clear any default ordering first
            $query->reorder();
            $query->orderBy('withdraw_requests.id', $sortOrder);
        } elseif ($sortBy === 'status') {
            // Custom sorting dengan 3 state: approved first, pending first, rejected first
            if ($sortOrder === 'asc') {
                // Klik 1: Approve di atas
                $query->orderByRaw("CASE 
                    WHEN status = 'approved' THEN 1 
                    WHEN status = 'rejected' THEN 2 
                    WHEN status = 'pending' THEN 3 
                    ELSE 4 
                END");
            } elseif ($sortOrder === 'desc') {
                // Klik 2: Waiting di atas
                $query->orderByRaw("CASE 
                    WHEN status = 'pending' THEN 1 
                    WHEN status = 'approved' THEN 2 
                    WHEN status = 'rejected' THEN 3 
                    ELSE 4 
                END");
            } else {
                // Klik 3: Reject di atas (sort_order = 'reject')
                $query->orderByRaw("CASE 
                    WHEN status = 'rejected' THEN 1 
                    WHEN status = 'approved' THEN 2 
                    WHEN status = 'pending' THEN 3 
                    ELSE 4 
                END");
            }
        } elseif ($sortBy === 'nama') {
            $query->orderBy('nama', $sortOrder);
        } elseif ($sortBy === 'merchant') {
            $query->leftJoin('merchants', 'withdraw_requests.merchant_id', '=', 'merchants.id')
                  ->orderBy('merchants.nama_merchant', $sortOrder)
                  ->select('withdraw_requests.*')
                  ->groupBy('withdraw_requests.id');
        } elseif ($sortBy === 'jumlah') {
            $query->orderBy('jumlah', $sortOrder);
        } elseif ($sortBy === 'tanggal') {
            $query->orderBy('created_at', $sortOrder);
        } elseif ($sortBy === 'metode') {
            // Custom sorting: Bank first (bca, bni, bri, mandiri) then E-Wallet (linkaja, dana)
            if ($sortOrder === 'asc') {
                $query->orderByRaw("CASE 
                    WHEN metode_penarikan IN ('bca', 'bni', 'bri', 'mandiri') THEN 1 
                    WHEN metode_penarikan IN ('linkaja', 'dana') THEN 2 
                    ELSE 3 
                END")
                ->orderBy('metode_penarikan', 'asc');
            } else {
                // E-Wallet first
                $query->orderByRaw("CASE 
                    WHEN metode_penarikan IN ('linkaja', 'dana') THEN 1 
                    WHEN metode_penarikan IN ('bca', 'bni', 'bri', 'mandiri') THEN 2 
                    ELSE 3 
                END")
                ->orderBy('metode_penarikan', 'asc');
            }
        } else {
            // Default: order by created_at asc (terbaru di bawah/terakhir)
            $query->orderBy('created_at', 'asc');
        }
        
        $withdraws = $query->paginate(10)->withQueryString();

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
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

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
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

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

    /**
     * Toggle merchant status (is_active)
     */
    public function toggleStatus(Request $request, $id)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized access'
            ], 403);
        }

        try {
            $merchant = Merchant::findOrFail($id);
            $merchant->is_active = $merchant->is_active ? 0 : 1;
            $merchant->save();

            return response()->json([
                'success' => true,
                'message' => 'Status merchant berhasil diperbarui',
                'is_active' => $merchant->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling merchant status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show merchants by territorial (kota/kabupaten)
     * Route: GET /territorial/{location}
     */
    public function showByTerritorial($location)
    {
        // Convert slug back to readable name
        $locationName = territorialName($location);
        
        // Get all active merchants
        $allMerchants = Merchant::query()
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->get();
        
        // Filter merchants by territorial (compare slug with slug)
        $merchants = $allMerchants->filter(function($merchant) use ($location) {
            $merchantTerritorial = extractKabupatenKota($merchant->daerah);
            $merchantSlug = territorialSlug($merchantTerritorial);
            // Compare slug with slug (case-insensitive)
            return strtolower($merchantSlug) === strtolower($location);
        })->values();
        
        // Auto-disable keywords that have passed their end_date
        Keyword::autoDisableExpiredKeywords();
        
        // Get keywords for these merchants
        $merchantIds = $merchants->pluck('id');
        $keywords = Keyword::with('merchant')
            ->whereIn('merchant_key', $merchantIds)
            ->where('status', 'approve')
            ->where('is_active', 1)
            ->whereHas('merchant', function($query) {
                $query->where('is_active', 1);
            })
            ->get();
        
        // Get iklans - prioritize specific territorial banner, fallback to general
        // Normalize comparison by converting both to slug format for consistency
        $locationSlug = territorialSlug($location);
        
        // First, try to get iklans that match this territorial
        $specificIklans = Iklan::whereNotNull('territorial')
            ->whereNull('regional')
            ->whereNull('branch')
            ->whereNull('cluster')
            ->orderBy('order', 'asc')
            ->get()
            ->filter(function($iklan) use ($locationSlug) {
                $storedSlug = territorialSlug($iklan->territorial);
                return strtolower($storedSlug) === strtolower($locationSlug);
            });
        
        // If specific iklans found, use them. Otherwise, use general iklans
        if ($specificIklans->isNotEmpty()) {
            $iklans = $specificIklans->values();
        } else {
            // Get general iklans (all location fields are null)
            $iklans = Iklan::whereNull('territorial')
                ->whereNull('regional')
                ->whereNull('branch')
                ->whereNull('cluster')
                ->orderBy('order', 'asc')
                ->get();
        }
        
        // Get all available territories for filter
        $allDaerah = Merchant::query()
            ->where('is_active', 1)
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->distinct()
            ->pluck('daerah');
        
        $territories = $allDaerah->map(function($daerah) {
            $territorial = extractKabupatenKota($daerah);
            return [
                'name' => $territorial,
                'slug' => territorialSlug($territorial)
            ];
        })
        ->filter(function($item) {
            return !empty($item['name']) && !empty($item['slug']);
        })
        ->unique('slug')
        ->sortBy('name')
        ->values();
        
        return view('territorial', [
            'location' => $location,
            'locationName' => $locationName,
            'merchants' => $merchants,
            'keywords' => $keywords,
            'iklans' => $iklans,
            'territories' => $territories,
        ]);
    }

    /**
     * Show merchants by regional
     * Route: GET /reg/{location}
     * Logic: 
     *   - URL menggunakan nama regional langsung (contoh: /reg/jatim)
     *   - Semua data regional, branch, cluster, city diambil dari DimTeritorialNational
     *   - Cari semua kota di DimTeritorialNational yang regional-nya = 'Jatim'
     *   - Tampilkan semua merchant dari semua kota tersebut
     *   - Contoh: Blitar (city) ada di cluster Tulungagung, branch Malang, regional Jatim
     *     sehingga card Blitar akan tampil di:
     *     - /city/blitar
     *     - /cluster/tulungagung
     *     - /branch/malang
     *     - /reg/jatim
     */
    public function showByRegional($location)
    {
        // Convert alias atau slug ke nama regional yang sebenarnya
        // Support alias seperti: balnus, bali-nusra -> Bali Nusra
        //                      jatengdiy, jateng-diy -> Jateng DIY
        //                      jatim -> Jatim
        $locationName = getRegionalNameFromAlias($location);
        
        // Get all cities that belong to this regional from DimTeritorialNational
        // Query: SELECT DISTINCT city FROM dim_teritorial_national WHERE LOWER(regional) = LOWER('Jatim')
        // Semua data regional, branch, cluster, city diambil dari DimTeritorialNational
        $territorialData = DimTeritorialNational::whereRaw('LOWER(TRIM(regional)) = ?', [strtolower(trim($locationName))])
            ->distinct()
            ->pluck('city')
            ->filter(function($city) {
                return !empty(trim($city));
            })
            ->map(function($city) {
                return trim($city);
            })
            ->unique()
            ->values()
            ->toArray();
        
        if (empty($territorialData)) {
            // Try with original location (slug) as fallback
            $territorialData = DimTeritorialNational::whereRaw('LOWER(TRIM(regional)) = ?', [strtolower(trim($location))])
                ->distinct()
                ->pluck('city')
                ->filter(function($city) {
                    return !empty(trim($city));
                })
                ->map(function($city) {
                    return trim($city);
                })
                ->unique()
                ->values()
                ->toArray();
        }
        
        // Get the actual regional name from database for display
        // Cari berdasarkan locationName yang sudah di-convert dari alias
        $regionalData = DimTeritorialNational::whereRaw('LOWER(TRIM(regional)) = ?', [strtolower(trim($locationName))])
            ->first();
        
        // Jika tidak ditemukan, coba dengan original location
        if (!$regionalData) {
            $regionalData = DimTeritorialNational::whereRaw('LOWER(TRIM(regional)) = ?', [strtolower(trim($location))])
                ->first();
        }
        
        $displayName = $regionalData ? $regionalData->regional : $locationName;
        
        // Get all active merchants
        $allMerchants = Merchant::query()
            ->where('is_active', 1)
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->get();
        
        // Filter merchants by matching cities from DimTeritorialNational
        // Match merchant city dengan city yang ada di territorialData (case-insensitive, toleransi prefix Kota/Kabupaten)
        // Normalisasi semua city name untuk matching yang lebih toleran
        $territorialDataNormalized = array_map(function($city) {
            return strtolower(trim(normalizeCityName($city)));
        }, $territorialData);
        
        $merchants = $allMerchants->filter(function($merchant) use ($territorialData, $territorialDataNormalized) {
            $merchantCity = trim(extractKabupatenKota($merchant->daerah));
            if (empty($merchantCity)) {
                return false;
            }
            
            // Normalisasi merchant city (hapus prefix Kota/Kabupaten)
            $merchantCityNormalized = strtolower(normalizeCityName($merchantCity));
            
            // Try exact match first (dengan normalisasi)
            if (in_array($merchantCityNormalized, $territorialDataNormalized)) {
                return true;
            }
            
            // Try exact match dengan original (tanpa normalisasi) untuk backward compatibility
            if (in_array($merchantCity, $territorialData)) {
                return true;
            }
            
            // Try case-insensitive match dengan original
            return in_array(strtolower($merchantCity), array_map('strtolower', $territorialData));
        })->values();
        
        // Get keywords for these merchants
        $merchantIds = $merchants->pluck('id');
        $keywords = Keyword::with('merchant')
            ->whereIn('merchant_key', $merchantIds)
            ->where('status', 'approve')
            ->where('is_active', 1)
            ->whereHas('merchant', function($query) {
                $query->where('is_active', 1);
            })
            ->get();
        
        // Get iklans - prioritize specific regional banner, fallback to general
        $locationSlug = territorialSlugGeneric($location);
        
        // First, try to get iklans that match this regional
        $specificIklans = Iklan::whereNotNull('regional')
            ->whereNull('territorial')
            ->whereNull('branch')
            ->whereNull('cluster')
            ->orderBy('order', 'asc')
            ->get()
            ->filter(function($iklan) use ($locationSlug) {
                $storedSlug = territorialSlugGeneric($iklan->regional);
                return strtolower($storedSlug) === strtolower($locationSlug);
            });
        
        // If specific iklans found, use them. Otherwise, use general iklans
        if ($specificIklans->isNotEmpty()) {
            $iklans = $specificIklans->values();
        } else {
            // Get general iklans (all location fields are null)
            $iklans = Iklan::whereNull('territorial')
                ->whereNull('regional')
                ->whereNull('branch')
                ->whereNull('cluster')
                ->orderBy('order', 'asc')
                ->get();
        }
        
        return view('regional', [
            'location' => $location,
            'locationName' => $displayName,
            'merchants' => $merchants,
            'keywords' => $keywords,
            'iklans' => $iklans,
        ]);
    }

    /**
     * Show merchants by branch
     * Route: GET /branch/{location}
     * Logic:
     *   - URL menggunakan nama branch langsung (contoh: /branch/malang)
     *   - Semua data regional, branch, cluster, city diambil dari DimTeritorialNational
     *   - Cari semua kota di DimTeritorialNational yang branch-nya = 'Malang'
     *   - Tampilkan semua merchant dari semua kota tersebut
     *   - Contoh: Blitar (city) ada di cluster Tulungagung, branch Malang, regional Jatim
     *     sehingga card Blitar akan tampil di:
     *     - /city/blitar
     *     - /cluster/tulungagung
     *     - /branch/malang
     *     - /reg/jatim
     */
    public function showByBranch($location)
    {
        // Convert slug back to readable name (location adalah nama branch langsung)
        $locationName = territorialNameGeneric($location);
        
        // Get all cities that belong to this branch from DimTeritorialNational
        // Query: SELECT DISTINCT city FROM dim_teritorial_national WHERE LOWER(branch) = LOWER('Malang')
        // Semua data regional, branch, cluster, city diambil dari DimTeritorialNational
        $territorialData = DimTeritorialNational::whereRaw('LOWER(TRIM(branch)) = ?', [strtolower(trim($locationName))])
            ->distinct()
            ->pluck('city')
            ->filter(function($city) {
                return !empty(trim($city));
            })
            ->map(function($city) {
                return trim($city);
            })
            ->unique()
            ->values()
            ->toArray();
        
        if (empty($territorialData)) {
            // Try with original location (slug) as fallback
            $territorialData = DimTeritorialNational::whereRaw('LOWER(TRIM(branch)) = ?', [strtolower(trim($location))])
                ->distinct()
                ->pluck('city')
                ->filter(function($city) {
                    return !empty(trim($city));
                })
                ->map(function($city) {
                    return trim($city);
                })
                ->unique()
                ->values()
                ->toArray();
        }
        
        // Get the actual branch name from database for display
        $branchData = DimTeritorialNational::where(function($query) use ($locationName, $location) {
            $query->whereRaw('LOWER(branch) = ?', [strtolower($locationName)])
                  ->orWhereRaw('LOWER(branch) = ?', [strtolower($location)]);
        })->first();
        
        $displayName = $branchData ? $branchData->branch : $locationName;
        
        // Get all active merchants
        $allMerchants = Merchant::query()
            ->where('is_active', 1)
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->get();
        
        // Filter merchants by matching cities from DimTeritorialNational
        // Match merchant city dengan city yang ada di territorialData (case-insensitive, toleransi prefix Kota/Kabupaten)
        // Normalisasi semua city name untuk matching yang lebih toleran
        $territorialDataNormalized = array_map(function($city) {
            return strtolower(trim(normalizeCityName($city)));
        }, $territorialData);
        
        $merchants = $allMerchants->filter(function($merchant) use ($territorialData, $territorialDataNormalized) {
            $merchantCity = trim(extractKabupatenKota($merchant->daerah));
            if (empty($merchantCity)) {
                return false;
            }
            
            // Normalisasi merchant city (hapus prefix Kota/Kabupaten)
            $merchantCityNormalized = strtolower(normalizeCityName($merchantCity));
            
            // Try exact match first (dengan normalisasi)
            if (in_array($merchantCityNormalized, $territorialDataNormalized)) {
                return true;
            }
            
            // Try exact match dengan original (tanpa normalisasi) untuk backward compatibility
            if (in_array($merchantCity, $territorialData)) {
                return true;
            }
            
            // Try case-insensitive match dengan original
            return in_array(strtolower($merchantCity), array_map('strtolower', $territorialData));
        })->values();
        
        // Get keywords for these merchants
        $merchantIds = $merchants->pluck('id');
        $keywords = Keyword::with('merchant')
            ->whereIn('merchant_key', $merchantIds)
            ->where('status', 'approve')
            ->where('is_active', 1)
            ->whereHas('merchant', function($query) {
                $query->where('is_active', 1);
            })
            ->get();
        
        // Get iklans - prioritize specific branch banner, fallback to general
        $locationSlug = territorialSlugGeneric($location);
        
        // First, try to get iklans that match this branch
        $specificIklans = Iklan::whereNotNull('branch')
            ->whereNull('territorial')
            ->whereNull('regional')
            ->whereNull('cluster')
            ->orderBy('order', 'asc')
            ->get()
            ->filter(function($iklan) use ($locationSlug) {
                $storedSlug = territorialSlugGeneric($iklan->branch);
                return strtolower($storedSlug) === strtolower($locationSlug);
            });
        
        // If specific iklans found, use them. Otherwise, use general iklans
        if ($specificIklans->isNotEmpty()) {
            $iklans = $specificIklans->values();
        } else {
            // Get general iklans (all location fields are null)
            $iklans = Iklan::whereNull('territorial')
                ->whereNull('regional')
                ->whereNull('branch')
                ->whereNull('cluster')
                ->orderBy('order', 'asc')
                ->get();
        }
        
        return view('branch', [
            'location' => $location,
            'locationName' => $displayName,
            'merchants' => $merchants,
            'keywords' => $keywords,
            'iklans' => $iklans,
        ]);
    }

    /**
     * Show merchants by cluster
     * Route: GET /cluster/{location}
     * Logic:
     *   - URL menggunakan nama cluster langsung (contoh: /cluster/tulungagung)
     *   - Semua data regional, branch, cluster, city diambil dari DimTeritorialNational
     *   - Cari semua kota di DimTeritorialNational yang cluster-nya = 'Tulungagung'
     *   - Tampilkan semua merchant dari semua kota tersebut
     *   - Contoh: Blitar (city) ada di cluster Tulungagung, branch Malang, regional Jatim
     *     sehingga card Blitar akan tampil di:
     *     - /city/blitar
     *     - /cluster/tulungagung
     *     - /branch/malang
     *     - /reg/jatim
     */
    public function showByCluster($location)
    {
        // Convert slug back to readable name (location adalah nama cluster langsung)
        $locationName = territorialNameGeneric($location);
        
        // Get all cities that belong to this cluster from DimTeritorialNational
        // Query: SELECT DISTINCT city FROM dim_teritorial_national WHERE LOWER(cluster) = LOWER('Tulungagung')
        // Semua data regional, branch, cluster, city diambil dari DimTeritorialNational
        $territorialData = DimTeritorialNational::whereRaw('LOWER(TRIM(cluster)) = ?', [strtolower(trim($locationName))])
            ->distinct()
            ->pluck('city')
            ->filter(function($city) {
                return !empty(trim($city));
            })
            ->map(function($city) {
                return trim($city);
            })
            ->unique()
            ->values()
            ->toArray();
        
        if (empty($territorialData)) {
            // Try with original location (slug) as fallback
            $territorialData = DimTeritorialNational::whereRaw('LOWER(TRIM(cluster)) = ?', [strtolower(trim($location))])
                ->distinct()
                ->pluck('city')
                ->filter(function($city) {
                    return !empty(trim($city));
                })
                ->map(function($city) {
                    return trim($city);
                })
                ->unique()
                ->values()
                ->toArray();
        }
        
        // Get the actual cluster name from database for display
        $clusterData = DimTeritorialNational::where(function($query) use ($locationName, $location) {
            $query->whereRaw('LOWER(cluster) = ?', [strtolower($locationName)])
                  ->orWhereRaw('LOWER(cluster) = ?', [strtolower($location)]);
        })->first();
        
        $displayName = $clusterData ? $clusterData->cluster : $locationName;
        
        // Get all active merchants
        $allMerchants = Merchant::query()
            ->where('is_active', 1)
            ->whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->get();
        
        // Filter merchants by matching cities from DimTeritorialNational
        // Match merchant city dengan city yang ada di territorialData (case-insensitive, toleransi prefix Kota/Kabupaten)
        // Normalisasi semua city name untuk matching yang lebih toleran
        $territorialDataNormalized = array_map(function($city) {
            return strtolower(trim(normalizeCityName($city)));
        }, $territorialData);
        
        $merchants = $allMerchants->filter(function($merchant) use ($territorialData, $territorialDataNormalized) {
            $merchantCity = trim(extractKabupatenKota($merchant->daerah));
            if (empty($merchantCity)) {
                return false;
            }
            
            // Normalisasi merchant city (hapus prefix Kota/Kabupaten)
            $merchantCityNormalized = strtolower(normalizeCityName($merchantCity));
            
            // Try exact match first (dengan normalisasi)
            if (in_array($merchantCityNormalized, $territorialDataNormalized)) {
                return true;
            }
            
            // Try exact match dengan original (tanpa normalisasi) untuk backward compatibility
            if (in_array($merchantCity, $territorialData)) {
                return true;
            }
            
            // Try case-insensitive match dengan original
            return in_array(strtolower($merchantCity), array_map('strtolower', $territorialData));
        })->values();
        
        // Get keywords for these merchants
        $merchantIds = $merchants->pluck('id');
        $keywords = Keyword::with('merchant')
            ->whereIn('merchant_key', $merchantIds)
            ->where('status', 'approve')
            ->where('is_active', 1)
            ->whereHas('merchant', function($query) {
                $query->where('is_active', 1);
            })
            ->get();
        
        // Get iklans - prioritize specific cluster banner, fallback to general
        $locationSlug = territorialSlugGeneric($location);
        
        // First, try to get iklans that match this cluster
        $specificIklans = Iklan::whereNotNull('cluster')
            ->whereNull('territorial')
            ->whereNull('regional')
            ->whereNull('branch')
            ->orderBy('order', 'asc')
            ->get()
            ->filter(function($iklan) use ($locationSlug) {
                $storedSlug = territorialSlugGeneric($iklan->cluster);
                return strtolower($storedSlug) === strtolower($locationSlug);
            });
        
        // If specific iklans found, use them. Otherwise, use general iklans
        if ($specificIklans->isNotEmpty()) {
            $iklans = $specificIklans->values();
        } else {
            // Get general iklans (all location fields are null)
            $iklans = Iklan::whereNull('territorial')
                ->whereNull('regional')
                ->whereNull('branch')
                ->whereNull('cluster')
                ->orderBy('order', 'asc')
                ->get();
        }
        
        return view('cluster', [
            'location' => $location,
            'locationName' => $displayName,
            'merchants' => $merchants,
            'keywords' => $keywords,
            'iklans' => $iklans,
        ]);
    }
}
