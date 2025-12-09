<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Merchant;
use App\Exports\KeywordsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::with('merchant')->orderBy('id')->paginate(10);
        $merchants = Merchant::orderBy('id')->paginate(10);
        $allMerchants = Merchant::orderBy('nama_merchant')->get();
        return view('admin', compact('keywords', 'merchants', 'allMerchants'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'merchant_key'      => 'required|exists:merchants,id',
                'nama_produk'       => 'required|string|max:255',
                'cta_link'          => 'nullable|string|max:255',
                'redeem'            => 'nullable|string|max:255',
                'diskon_percent'    => 'nullable|numeric|min:0|max:100',
                'diskon_rupiah'     => 'nullable|numeric|min:0',
                'subsidy_enabled'   => 'nullable|in:0,1',
                'subsidy_amount'    => 'required_if:subsidy_enabled,1|numeric|min:0',
                'diamond_enabled'   => 'nullable|in:0,1',
                'diamond_amount'    => 'required_if:diamond_enabled,1|integer|min:0',
                'skb'               => 'nullable|string',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'end_date'          => 'nullable|date_format:Y-m-d',
                'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'stock'             => 'nullable|integer|min:0',

                'status'            => 'nullable|in:approve,pending,reject',
            ], [
                'subsidy_amount.required_if' => 'Nominal subsidi wajib diisi jika Subsidi Diskon dipilih Yes',
                'diamond_amount.required_if' => 'Jumlah diamond wajib diisi jika Diamond dipilih Yes',
            ]);

            // Validasi bahwa salah satu dari diskon harus diisi
            if (empty($request->diskon_percent) && empty($request->diskon_rupiah) && empty($request->diskon_free)) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan isi salah satu dari diskon (persen, rupiah, atau free)'
                    ], 422);
                }
                return back()->withErrors(['diskon' => 'Silakan isi salah satu dari diskon (persen, rupiah, atau free)'])->withInput();
            }

            // Validasi start date tidak boleh melebihi end date
            if ($request->start_date && $request->end_date) {
                if ($request->start_date > $request->end_date) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tanggal mulai tidak boleh melebihi tanggal berakhir'
                        ], 422);
                    }
                    return back()->withErrors(['start_date' => 'Tanggal mulai tidak boleh melebihi tanggal berakhir'])->withInput();
                }
            }

            // Format diskon
            $diskon = '';
            if (!empty($request->diskon_free)) {
                $diskon = 'FREE';
            } elseif ($request->diskon_percent) {
                // Jika diskon 100%, tampilkan sebagai "FREE"
                if ($request->diskon_percent == 100 || $request->diskon_percent == '100') {
                    $diskon = 'FREE';
                } else {
                    $diskon = $request->diskon_percent . '%';
                }
            } elseif ($request->diskon_rupiah) {
                $diskon = 'Rp ' . number_format($request->diskon_rupiah, 0, ',', '.');
            }

            // Initialize subsidy and diamond amounts to null to ensure they're always defined
            $subsidyAmount = null;
            $diamondAmount = null;

            // Handle subsidy amount
            if ($request->subsidy_enabled == '1' && $request->subsidy_amount !== null && $request->subsidy_amount !== '') {
                // Handle format rupiah: hapus thousands separator (titik) tanpa merusak decimal separator
                $amount = trim($request->subsidy_amount);
                
                // Jika menggunakan format Indonesia (koma sebagai decimal separator)
                if (strpos($amount, ',') !== false) {
                    // Ganti koma dengan titik untuk decimal separator
                    $amount = str_replace(',', '.', $amount);
                }
                
                // Hapus thousands separator (titik) tanpa merusak decimal separator
                $dotCount = substr_count($amount, '.');
                
                if ($dotCount > 1) {
                    // Multiple titik = ada thousands separator
                    // Hapus semua titik kecuali yang terakhir (decimal separator)
                    $parts = explode('.', $amount);
                    $lastPart = array_pop($parts);
                    $amount = implode('', $parts) . '.' . $lastPart;
                } elseif ($dotCount == 1) {
                    // Hanya 1 titik - cek apakah decimal atau thousands separator
                    $parts = explode('.', $amount);
                    if (count($parts) == 2) {
                        $afterDot = $parts[1];
                        // Jika bagian setelah titik adalah 3 digit, kemungkinan thousands separator
                        // Jika 1-2 digit, kemungkinan decimal separator
                        if (strlen($afterDot) == 3 && is_numeric($afterDot)) {
                            // 3 digit = thousands separator, hapus titik
                            $amount = implode('', $parts);
                        }
                        // Jika 1-2 digit, biarkan sebagai decimal separator
                    }
                } else {
                    // Tidak ada titik, hapus semua titik (jika ada dari format lain)
                    $amount = str_replace('.', '', $amount);
                }
                
                $subsidyAmount = (float) $amount;
            }

            // Handle diamond amount
            if ($request->diamond_enabled == '1' && $request->diamond_amount) {
                $diamondAmount = (int) $request->diamond_amount;
            }

            // Date input sudah dalam format YYYY-MM-DD dari date picker
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('keywords', 'public');
            }

            // Create keyword
            $keyword = Keyword::create([
                'merchant_key'  => $request->merchant_key,
                'nama_produk'   => $request->nama_produk,
                'keyword_id'    => $request->keyword_id,
                'cta_link'      => $request->cta_link,
                'redeem'        => $request->redeem,
                'diskon'        => $diskon,
                'subsidy_amount'=> $subsidyAmount,
                'diamond_amount'=> $diamondAmount,
                'skb'           => $request->skb,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'image'         => $imagePath,
                'stock'         => $request->stock,
                'status'        => $request->status ?? 'pending',
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keyword berhasil ditambahkan!',
                    'keyword' => $keyword
                ], 201);
            }

            $redirect = $request->input('redirect_to');
            if ($redirect) {
                return redirect()->to($redirect)->with('success', 'Keyword berhasil ditambahkan!');
            }
            if ($request->boolean('stay_on_detail')) {
                return redirect()->back()->with('success', 'Keyword berhasil ditambahkan!');
            }

            return redirect()->route('keywords.index')->with('success', 'Keyword berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error creating keyword: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan keyword: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Gagal menyimpan keyword: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $keyword = Keyword::findOrFail($id);

            $validated = $request->validate([
                'merchant_key'      => 'required|exists:merchants,id',
                'nama_produk'       => 'required|string|max:255',
                'keyword_id'        => 'nullable|string|max:255',
                'cta_link'          => 'nullable|string|max:255',
                'redeem'            => 'nullable|string|max:255',
                'diskon_percent'    => 'nullable|numeric|min:0|max:100',
                'diskon_rupiah'     => 'nullable|numeric|min:0',
                'subsidy_enabled'   => 'nullable|in:0,1',
                'subsidy_amount'    => 'required_if:subsidy_enabled,1|numeric|min:0',
                'diamond_enabled'   => 'nullable|in:0,1',
                'diamond_amount'    => 'required_if:diamond_enabled,1|integer|min:0',
                'skb'               => 'nullable|string',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'end_date'          => 'nullable|date_format:Y-m-d',
                'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'stock'             => 'nullable|integer|min:0',
                'status'            => 'nullable|in:approve,pending,reject',
            ], [
                'subsidy_amount.required_if' => 'Nominal subsidi wajib diisi jika Subsidi Diskon dipilih Yes',
                'diamond_amount.required_if' => 'Jumlah diamond wajib diisi jika Diamond dipilih Yes',
            ]);

            // Validasi bahwa salah satu dari diskon harus diisi
            if (empty($request->diskon_percent) && empty($request->diskon_rupiah) && empty($request->diskon_free)) {
                return back()->withErrors(['diskon' => 'Silakan isi salah satu dari diskon (persen, rupiah, atau free)'])->withInput();
            }

            // Validasi start date tidak boleh melebihi end date
            if ($request->start_date && $request->end_date) {
                if ($request->start_date > $request->end_date) {
                    return back()->withErrors(['start_date' => 'Tanggal mulai tidak boleh melebihi tanggal berakhir'])->withInput();
                }
            }

            // Format diskon
            $diskon = '';
            if (!empty($request->diskon_free)) {
                $diskon = 'FREE';
            } elseif ($request->diskon_percent) {
                // Jika diskon 100%, tampilkan sebagai "FREE"
                if ($request->diskon_percent == 100 || $request->diskon_percent == '100') {
                    $diskon = 'FREE';
                } else {
                    $diskon = $request->diskon_percent . '%';
                }
            } elseif ($request->diskon_rupiah) {
                $diskon = 'Rp ' . number_format($request->diskon_rupiah, 0, ',', '.');
            }

            // Handle subsidy amount
            $subsidyAmount = null;
            if ($request->subsidy_enabled == '1' && $request->subsidy_amount !== null && $request->subsidy_amount !== '') {
                // Handle format rupiah: hapus thousands separator (titik) tanpa merusak decimal separator
                $amount = trim($request->subsidy_amount);
                
                // Jika menggunakan format Indonesia (koma sebagai decimal separator)
                if (strpos($amount, ',') !== false) {
                    // Ganti koma dengan titik untuk decimal separator
                    $amount = str_replace(',', '.', $amount);
                }
                
                // Hapus thousands separator (titik) tanpa merusak decimal separator
                $dotCount = substr_count($amount, '.');
                
                if ($dotCount > 1) {
                    // Multiple titik = ada thousands separator
                    // Hapus semua titik kecuali yang terakhir (decimal separator)
                    $parts = explode('.', $amount);
                    $lastPart = array_pop($parts);
                    $amount = implode('', $parts) . '.' . $lastPart;
                } elseif ($dotCount == 1) {
                    // Hanya 1 titik - cek apakah decimal atau thousands separator
                    $parts = explode('.', $amount);
                    if (count($parts) == 2) {
                        $afterDot = $parts[1];
                        // Jika bagian setelah titik adalah 3 digit, kemungkinan thousands separator
                        // Jika 1-2 digit, kemungkinan decimal separator
                        if (strlen($afterDot) == 3 && is_numeric($afterDot)) {
                            // 3 digit = thousands separator, hapus titik
                            $amount = implode('', $parts);
                        }
                        // Jika 1-2 digit, biarkan sebagai decimal separator
                    }
                } else {
                    // Tidak ada titik, hapus semua titik (jika ada dari format lain)
                    $amount = str_replace('.', '', $amount);
                }
                
                $subsidyAmount = (float) $amount;
            }

            // Handle diamond amount
            $diamondAmount = null;
            if ($request->diamond_enabled == '1' && $request->diamond_amount) {
                $diamondAmount = (int) $request->diamond_amount;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($keyword->image && Storage::disk('public')->exists($keyword->image)) {
                    Storage::disk('public')->delete($keyword->image);
                }
                $imagePath = $request->file('image')->store('keywords', 'public');
            } else {
                $imagePath = $keyword->image;
            }

            // Update keyword
            $keyword->update([
                'merchant_key'  => $request->merchant_key,
                'nama_produk'   => $request->nama_produk,
                'keyword_id'    => $request->keyword_id,
                'cta_link'      => $request->cta_link,
                'redeem'        => $request->redeem,
                'diskon'        => $diskon,
                'subsidy_amount'=> $subsidyAmount,
                'diamond_amount'=> $diamondAmount,
                'skb'           => $request->skb,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'image'         => $imagePath,
                'stock'         => $request->stock,
                'status'        => $request->status ?? 'pending',
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keyword berhasil diperbarui!',
                    'keyword' => $keyword
                ], 200);
            }

            $redirect = $request->input('redirect_to');
            if ($redirect) {
                return redirect()->to($redirect)->with('success', 'Keyword berhasil diperbarui!');
            }
            if ($request->boolean('stay_on_detail')) {
                return redirect()->back()->with('success', 'Keyword berhasil diperbarui!');
            }

            return redirect()->back()->with('success', 'Keyword berhasil diperbarui!');
        } catch (ValidationException $e) {
            // Tangani error validasi agar modal menampilkan pesan yang lebih spesifik
            $message = $e->validator ? $e->validator->errors()->first() : 'Data tidak valid.';
            return back()->withErrors(['error' => $message])->withInput();
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error updating keyword: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui keyword: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Gagal memperbarui keyword: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $keyword = Keyword::findOrFail($id);

            // Delete image file if exists
            if ($keyword->image && Storage::disk('public')->exists($keyword->image)) {
                Storage::disk('public')->delete($keyword->image);
            }

            // Delete keyword record
            $keyword->delete();

            return response()->json(['success' => true, 'message' => 'Keyword berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus keyword'], 500);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = trim($request->get('q', ''));
        $status = $request->get('status');
        $merchantId = $request->get('merchant_id');

        $keywordsQuery = Keyword::with('merchant')
            ->when($merchantId, function ($query) use ($merchantId) {
                $query->where('merchant_key', $merchantId);
            })
            ->when(in_array($status, ['approve', 'pending', 'reject']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_produk', 'like', "%{$searchTerm}%")
                        ->orWhere('keyword_id', 'like', "%{$searchTerm}%")
                        ->orWhere('cta_link', 'like', "%{$searchTerm}%")
                        ->orWhere('redeem', 'like', "%{$searchTerm}%")
                        ->orWhere('diskon', 'like', "%{$searchTerm}%")
                        ->orWhereHas('merchant', function ($merchantQuery) use ($searchTerm) {
                            $merchantQuery->where('nama_merchant', 'like', "%{$searchTerm}%")
                                ->orWhere('kategori', 'like', "%{$searchTerm}%")
                                ->orWhere('daerah', 'like', "%{$searchTerm}%");
                        });
                });
            })
            ->orderBy('id');

        // Paginate dengan parameter keyword_page yang terpisah
        // Let Laravel automatically read the page number from the request using the page name
        // Buat query params untuk appends, pastikan merchant_page tetap ada
        $keywordQueryParams = $request->query();
        // Pastikan merchant_page tetap ada jika sebelumnya ada di request
        if ($request->has('merchant_page')) {
            $keywordQueryParams['merchant_page'] = $request->get('merchant_page');
        }
        
        $keywords = $keywordsQuery
            ->paginate(10, ['*'], 'keyword_page')
            ->appends($keywordQueryParams);

        if ($request->ajax()) {
            try {
                $html = view('partials.table-keyword', ['keywords' => $keywords])->render();
                return response()->json([
                    'html' => $html,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error rendering keyword table: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'html' => '<div class="p-4 text-center text-red-600">Error loading keywords: ' . $e->getMessage() . '</div>',
                    'error' => true
                ], 500);
            }
        }

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
        $allMerchants = Merchant::orderBy('nama_merchant')->get();

        return view('admin', compact('keywords', 'merchants', 'allMerchants'));
    }

    public function publicSearch(Request $request)
    {
        $searchTerm = trim($request->get('q', ''));

        $searchResults = Keyword::with('merchant')
            ->where('status', 'approve')
            ->where('is_active', 1)
            ->whereHas('merchant', function ($query) {
                $query->where('is_active', 1);
            })
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_produk', 'like', "%{$searchTerm}%")
                        ->orWhere('skb', 'like', "%{$searchTerm}%")
                        ->orWhereHas('merchant', function ($merchantQuery) use ($searchTerm) {
                            $merchantQuery->where('nama_merchant', 'like', "%{$searchTerm}%")
                                ->orWhere('kategori', 'like', "%{$searchTerm}%")
                                ->orWhere('daerah', 'like', "%{$searchTerm}%");
                        });
                });
            }, function ($query) {
                $query->latest();
            })
            ->orderByDesc('created_at')
            ->get();

        $totalPoint = optional($request->user())->point ?? 0;

        return view('merchant.search', [
            'searchResults' => $searchResults,
            'searchTerm' => $searchTerm,
            'totalPoint' => $totalPoint,
        ]);
    }

    public function approve($id)
    {
        try {
            $keyword = Keyword::findOrFail($id);
            $keyword->update(['status' => 'approve']);

            return response()->json([
                'success' => true,
                'message' => 'Keyword berhasil disetujui',
                'keyword' => $keyword
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui keyword'
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $keyword = Keyword::findOrFail($id);
            $keyword->update(['status' => 'reject']);

            return response()->json([
                'success' => true,
                'message' => 'Keyword berhasil ditolak',
                'keyword' => $keyword
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error rejecting keyword: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak keyword: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel()
    {
        $fileName = 'keywords_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new KeywordsExport, $fileName);
    }

    public function spesialPromoForm(Request $request)
    {
        $query = Keyword::with('merchant')
            ->where('status', 'approve');
        
        // Search filter (sama seperti di keyword search)
        $searchTerm = trim($request->get('q', ''));
        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'like', "%{$searchTerm}%")
                  ->orWhere('keyword_id', 'like', "%{$searchTerm}%")
                  ->orWhere('cta_link', 'like', "%{$searchTerm}%")
                  ->orWhere('redeem', 'like', "%{$searchTerm}%")
                  ->orWhere('diskon', 'like', "%{$searchTerm}%")
                  ->orWhereHas('merchant', function ($merchantQuery) use ($searchTerm) {
                      $merchantQuery->where('nama_merchant', 'like', "%{$searchTerm}%")
                          ->orWhere('kategori', 'like', "%{$searchTerm}%")
                          ->orWhere('daerah', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Date filter (filter by start_date dan end_date keyword, sama seperti di keyword)
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        if ($startDate && $endDate) {
            // Filter keyword yang periodenya berada dalam range yang dipilih
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($subQuery) use ($startDate, $endDate) {
                    // Keyword yang start_date dan end_date berada dalam range
                    $subQuery->whereNotNull('start_date')
                             ->whereNotNull('end_date')
                             ->where('start_date', '>=', $startDate)
                             ->where('end_date', '<=', $endDate);
                })->orWhere(function ($subQuery) use ($startDate, $endDate) {
                    // Keyword yang hanya punya start_date
                    $subQuery->whereNotNull('start_date')
                             ->whereNull('end_date')
                             ->where('start_date', '>=', $startDate)
                             ->where('start_date', '<=', $endDate);
                })->orWhere(function ($subQuery) use ($startDate, $endDate) {
                    // Keyword yang hanya punya end_date
                    $subQuery->whereNull('start_date')
                             ->whereNotNull('end_date')
                             ->where('end_date', '>=', $startDate)
                             ->where('end_date', '<=', $endDate);
                });
            });
        } elseif ($startDate) {
            // Hanya start date filter
            $query->where(function ($q) use ($startDate) {
                $q->where(function ($subQuery) use ($startDate) {
                    $subQuery->whereNotNull('start_date')
                             ->where('start_date', '>=', $startDate);
                })->orWhere(function ($subQuery) use ($startDate) {
                    $subQuery->whereNotNull('end_date')
                             ->where('end_date', '>=', $startDate);
                });
            });
        } elseif ($endDate) {
            // Hanya end date filter
            $query->where(function ($q) use ($endDate) {
                $q->where(function ($subQuery) use ($endDate) {
                    $subQuery->whereNotNull('start_date')
                             ->where('start_date', '<=', $endDate);
                })->orWhere(function ($subQuery) use ($endDate) {
                    $subQuery->whereNotNull('end_date')
                             ->where('end_date', '<=', $endDate);
                });
            });
        }
        
        $keywords = $query->orderBy('id', 'desc')->paginate(10);
        
        // Hitung jumlah keyword yang sudah aktif sebagai spesial promo
        $activeSpecialPromoCount = Keyword::where('is_special_promo', 1)
            ->where('status', 'approve')
            ->count();
        
        return view('spesial-promo-form', compact('keywords', 'activeSpecialPromoCount'));
    }

    /**
     * Toggle special promo status (is_special_promo)
     */
    public function toggleSpecialPromo(Request $request, $id)
    {
        // Only admin with can_approve = 1 can access
        if (!Auth::check() || !Auth::user()->can_approve) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized access'
            ], 403);
        }

        try {
            $keyword = Keyword::findOrFail($id);
            
            // Jika akan mengaktifkan, cek apakah sudah ada 4 yang aktif
            if (!$keyword->is_special_promo) {
                $activeCount = Keyword::where('is_special_promo', 1)
                    ->where('status', 'approve')
                    ->count();
                
                if ($activeCount >= 4) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Maksimal 4 keyword yang bisa diaktifkan sebagai spesial promo. Silakan nonaktifkan salah satu terlebih dahulu.',
                        'active_count' => $activeCount
                    ], 400);
                }
            }
            
            $keyword->is_special_promo = $keyword->is_special_promo ? 0 : 1;
            $keyword->save();
            
            // Hitung ulang jumlah aktif setelah update
            $activeCount = Keyword::where('is_special_promo', 1)
                ->where('status', 'approve')
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Status spesial promo berhasil diperbarui',
                'is_special_promo' => $keyword->is_special_promo,
                'active_count' => $activeCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling special promo status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal memperbarui status spesial promo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle keyword status (is_active)
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
            $keyword = Keyword::findOrFail($id);
            $keyword->is_active = $keyword->is_active ? 0 : 1;
            $keyword->save();

            return response()->json([
                'success' => true,
                'message' => 'Status keyword berhasil diperbarui',
                'is_active' => $keyword->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling keyword status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
