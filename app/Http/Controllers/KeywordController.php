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
        // Auto-disable keywords that have passed their end_date
        Keyword::autoDisableExpiredKeywords();
        
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
                'kategori_keyword'  => 'nullable|string|max:255',
                'nama_produk'       => 'required|string|max:255',
                'keyword_id'        => 'required|string|max:255',
                'cta_link'          => 'required|string|max:255',
                'redeem'            => 'required|string|max:255',
                'diskon_percent'    => 'nullable|numeric|min:0|max:100',
                'diskon_rupiah'     => 'nullable|numeric|min:0',
                'diskon_free'       => 'nullable|in:0,1',
                'subsidy_enabled'   => 'nullable|in:0,1',
                'subsidy_amount'    => 'required_if:subsidy_enabled,1|string',
                'diamond_enabled'   => 'nullable|in:0,1',
                'diamond_amount'    => 'required_if:diamond_enabled,1|integer|min:0',
                'skb'               => 'required|string',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'end_date'          => 'nullable|date_format:Y-m-d',
                'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'stock'             => 'required|integer|min:0',

                'status'            => 'nullable|in:approve,pending,reject',
            ], [
                'keyword_id.required' => 'Keyword ID wajib diisi',
                'cta_link.required' => 'CTA wajib diisi',
                'redeem.required' => 'Redeem Point wajib diisi',
                'skb.required' => 'SKB wajib diisi',
                'stock.required' => 'Stock wajib diisi',
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
                
                // Validate parsed amount is numeric and >= 0
                // Remove all non-numeric characters except decimal point for validation
                $cleanAmount = preg_replace('/[^0-9.]/', '', $amount);
                $parsedAmount = (float) $cleanAmount;
                
                // Check if the parsed value is valid (not NaN, not infinite, and >= 0)
                if (!is_finite($parsedAmount) || $parsedAmount < 0) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Nominal subsidi harus berupa angka positif'
                        ], 422);
                    }
                    return back()->withErrors(['subsidy_amount' => 'Nominal subsidi harus berupa angka positif'])->withInput();
                }
                
                $subsidyAmount = $parsedAmount;
            }

            // Handle diamond amount
            if ($request->diamond_enabled == '1' && $request->diamond_amount) {
                $diamondAmount = (int) $request->diamond_amount;
            }

            // Date input sudah dalam format YYYY-MM-DD dari date picker
            // Auto-fill dari merchant jika tidak diisi
            $merchant = Merchant::find($request->merchant_key);
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            // Jika start_date tidak diisi, ambil dari merchant
            if (empty($startDate) && $merchant && $merchant->start_date) {
                $startDate = $merchant->start_date;
            }
            
            // Jika end_date tidak diisi, ambil dari merchant
            if (empty($endDate) && $merchant && $merchant->end_date) {
                $endDate = $merchant->end_date;
            }
            
            // Validasi: start_date keyword tidak boleh lebih awal dari start_date merchant
            if ($startDate && $merchant && $merchant->start_date) {
                if ($startDate < $merchant->start_date) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tanggal mulai keyword tidak boleh lebih awal dari tanggal mulai periode merchant (' . $merchant->start_date . ')'
                        ], 422);
                    }
                    return back()->withErrors(['start_date' => 'Tanggal mulai keyword tidak boleh lebih awal dari tanggal mulai periode merchant (' . $merchant->start_date . ')'])->withInput();
                }
            }
            
            // Validasi: end_date keyword tidak boleh melebihi end_date merchant
            if ($endDate && $merchant && $merchant->end_date) {
                if ($endDate > $merchant->end_date) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tanggal akhir keyword tidak boleh melebihi tanggal akhir periode merchant (' . $merchant->end_date . ')'
                        ], 422);
                    }
                    return back()->withErrors(['end_date' => 'Tanggal akhir keyword tidak boleh melebihi tanggal akhir periode merchant (' . $merchant->end_date . ')'])->withInput();
                }
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('keywords', 'public');
            }

            // Determine status: if subsidy_enabled is 0 (no), auto-approve
            $status = $request->status ?? 'pending';
            if ($request->subsidy_enabled == '0' || $request->subsidy_enabled === 0 || $subsidyAmount === null) {
                $status = 'approve';
            }

            // Ensure stock has default value if null or empty
            $stock = $request->stock !== null && $request->stock !== '' ? (int)$request->stock : 0;

            // Get kategori_keyword from request, or use merchant's kategori as default
            $kategoriKeyword = $request->kategori_keyword;
            if (empty($kategoriKeyword) && $merchant && $merchant->kategori) {
                $kategoriKeyword = $merchant->kategori;
            }

            // Create keyword
            $keyword = Keyword::create([
                'merchant_key'      => $request->merchant_key,
                'kategori_keyword' => $kategoriKeyword,
                'nama_produk'      => $request->nama_produk,
                'keyword_id'       => $request->keyword_id,
                'cta_link'         => $request->cta_link,
                'redeem'           => $request->redeem,
                'diskon'           => $diskon,
                'subsidy_amount'   => $subsidyAmount,
                'diamond_amount'   => $diamondAmount,
                'skb'              => $request->skb,
                'start_date'       => $startDate,
                'end_date'         => $endDate,
                'image'            => $imagePath,
                'stock'            => $stock,
                'status'           => $status,
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
        } catch (ValidationException $e) {
            // Handle validation errors
            $errors = $e->validator->errors();
            $missingFields = [];
            
            // Map field names to user-friendly labels
            $fieldLabels = [
                'merchant_key' => 'Nama Merchant',
                'nama_produk' => 'Nama Produk',
                'keyword_id' => 'Keyword ID',
                'cta_link' => 'CTA',
                'redeem' => 'Redeem Point',
                'diskon' => 'Diskon',
                'subsidy_amount' => 'Nominal Subsidi',
                'diamond_amount' => 'Jumlah Diamond',
                'stock' => 'Stock',
                'start_date' => 'Tanggal Mulai',
                'end_date' => 'Tanggal Berakhir',
            ];
            
            // Collect missing fields
            foreach ($errors->keys() as $field) {
                $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
                $missingFields[] = $label;
            }
            
            $errorMessage = 'Data gagal disimpan.';
            if (!empty($missingFields)) {
                $errorMessage .= ' Field yang belum diisi: ' . implode(', ', $missingFields) . '.';
            }
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => $errors->toArray(),
                    'missing_fields' => $missingFields
                ], 422);
            }
            
            return back()->withErrors(['error' => $errorMessage])->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors (like NOT NULL constraint violations)
            Log::error('Database error creating keyword: ' . $e->getMessage());
            
            // Check if it's a NOT NULL constraint violation
            if (strpos($e->getMessage(), 'cannot be null') !== false || strpos($e->getMessage(), 'Column') !== false) {
                $missingFields = [];
                
                // Map field names to user-friendly labels
                $fieldLabels = [
                    'merchant_key' => 'Nama Merchant',
                    'nama_produk' => 'Nama Produk',
                    'stock' => 'Stock',
                    'diskon' => 'Diskon',
                ];
                
                // Extract column name from error message if possible
                $columnName = null;
                if (preg_match("/Column '([^']+)' cannot be null/", $e->getMessage(), $matches)) {
                    $columnName = $matches[1];
                }
                
                // Check required fields that might be missing
                $requiredFields = ['merchant_key', 'nama_produk', 'stock'];
                foreach ($requiredFields as $field) {
                    // If we know the exact column, only check that one
                    if ($columnName && $columnName !== $field) {
                        continue;
                    }
                    
                    $value = $request->input($field);
                    if ($value === null || $value === '') {
                        $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
                        if (!in_array($label, $missingFields)) {
                            $missingFields[] = $label;
                        }
                    }
                }
                
                // If we found the exact column, add it to missing fields
                if ($columnName && !empty($fieldLabels[$columnName])) {
                    $label = $fieldLabels[$columnName];
                    if (!in_array($label, $missingFields)) {
                        $missingFields[] = $label;
                    }
                }
                
                // Also check for stock specifically (most common issue)
                if ($columnName === 'stock' || (!$columnName && ($request->stock === null || $request->stock === ''))) {
                    if (!in_array('Stock', $missingFields)) {
                        $missingFields[] = 'Stock';
                    }
                }
                
                $errorMessage = 'Data gagal disimpan.';
                if (!empty($missingFields)) {
                    $errorMessage .= ' Field yang belum diisi: ' . implode(', ', $missingFields) . '.';
                } else {
                    $errorMessage .= ' Pastikan semua field yang wajib sudah diisi.';
                }
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'missing_fields' => $missingFields
                    ], 422);
                }
                
                return back()->withErrors(['error' => $errorMessage])->withInput();
            }
            
            // For other database errors, show generic message
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan keyword: Terjadi kesalahan pada database. Pastikan semua field yang wajib sudah diisi.'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Gagal menyimpan keyword: Terjadi kesalahan pada database. Pastikan semua field yang wajib sudah diisi.'])->withInput();
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error creating keyword: ' . $e->getMessage());
            
            // Try to extract missing fields from error message
            $missingFields = [];
            $errorMessage = $e->getMessage();
            
            // Check common missing fields
            $fieldLabels = [
                'stock' => 'Stock',
                'keyword_id' => 'Keyword ID',
                'cta_link' => 'CTA',
                'redeem' => 'Redeem Point',
            ];
            
            foreach ($fieldLabels as $field => $label) {
                $value = $request->input($field);
                if ($value === null || $value === '') {
                    $missingFields[] = $label;
                }
            }
            
            $userMessage = 'Gagal menyimpan keyword.';
            if (!empty($missingFields)) {
                $userMessage .= ' Field yang belum diisi: ' . implode(', ', $missingFields) . '.';
            } else {
                $userMessage .= ' Pastikan semua field yang wajib sudah diisi.';
            }
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $userMessage,
                    'missing_fields' => $missingFields
                ], 500);
            }

            return back()->withErrors(['error' => $userMessage])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $keyword = Keyword::findOrFail($id);

            $validated = $request->validate([
                'merchant_key'      => 'required|exists:merchants,id',
                'kategori_keyword'  => 'nullable|string|max:255',
                'nama_produk'       => 'required|string|max:255',
                'keyword_id'        => 'nullable|string|max:255',
                'cta_link'          => 'nullable|string|max:255',
                'redeem'            => 'nullable|string|max:255',
                'diskon_percent'    => 'nullable|numeric|min:0|max:100',
                'diskon_rupiah'     => 'nullable|numeric|min:0',
                'diskon_free'       => 'nullable|in:0,1',
                'subsidy_enabled'   => 'nullable|in:0,1',
                'subsidy_amount'    => 'nullable|required_if:subsidy_enabled,1|string',
                'diamond_enabled'   => 'nullable|in:0,1',
                'diamond_amount'    => 'required_if:diamond_enabled,1|integer|min:0',
                'skb'               => 'nullable|string',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'end_date'          => 'nullable|date_format:Y-m-d',
                'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp',
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
                
                // Validate parsed amount is numeric and >= 0
                // Remove all non-numeric characters except decimal point for validation
                $cleanAmount = preg_replace('/[^0-9.]/', '', $amount);
                $parsedAmount = (float) $cleanAmount;
                
                // Check if the parsed value is valid (not NaN, not infinite, and >= 0)
                if (!is_finite($parsedAmount) || $parsedAmount < 0) {
                    return back()->withErrors(['subsidy_amount' => 'Nominal subsidi harus berupa angka positif'])->withInput();
                }
                
                $subsidyAmount = $parsedAmount;
            }

            // Handle diamond amount
            $diamondAmount = null;
            if ($request->diamond_enabled == '1' && $request->diamond_amount) {
                $diamondAmount = (int) $request->diamond_amount;
            }

            // Handle dates - auto-fill dari merchant jika tidak diisi
            $merchant = Merchant::find($request->merchant_key);
            $startDate = $request->start_date ?? $keyword->start_date;
            $endDate = $request->end_date ?? $keyword->end_date;
            
            // Jika start_date tidak diisi, ambil dari merchant
            if (empty($startDate) && $merchant && $merchant->start_date) {
                $startDate = $merchant->start_date;
            }
            
            // Jika end_date tidak diisi, ambil dari merchant
            if (empty($endDate) && $merchant && $merchant->end_date) {
                $endDate = $merchant->end_date;
            }
            
            // Validasi: start_date keyword tidak boleh lebih awal dari start_date merchant
            if ($startDate && $merchant && $merchant->start_date) {
                if ($startDate < $merchant->start_date) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tanggal mulai keyword tidak boleh lebih awal dari tanggal mulai periode merchant (' . $merchant->start_date . ')'
                        ], 422);
                    }
                    return back()->withErrors(['start_date' => 'Tanggal mulai keyword tidak boleh lebih awal dari tanggal mulai periode merchant (' . $merchant->start_date . ')'])->withInput();
                }
            }
            
            // Validasi: end_date keyword tidak boleh melebihi end_date merchant
            if ($endDate && $merchant && $merchant->end_date) {
                if ($endDate > $merchant->end_date) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tanggal akhir keyword tidak boleh melebihi tanggal akhir periode merchant (' . $merchant->end_date . ')'
                        ], 422);
                    }
                    return back()->withErrors(['end_date' => 'Tanggal akhir keyword tidak boleh melebihi tanggal akhir periode merchant (' . $merchant->end_date . ')'])->withInput();
                }
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

            // Determine status: if subsidy_enabled is 0 (no), auto-approve
            $status = $request->status ?? $keyword->status;
            if ($request->subsidy_enabled == '0' || $request->subsidy_enabled === 0 || $subsidyAmount === null) {
                $status = 'approve';
            }

            // Get kategori_keyword from request, or use merchant's kategori as default, or keep existing value
            $kategoriKeyword = $request->kategori_keyword;
            if (empty($kategoriKeyword) && $merchant && $merchant->kategori) {
                $kategoriKeyword = $merchant->kategori;
            } elseif (empty($kategoriKeyword)) {
                $kategoriKeyword = $keyword->kategori_keyword;
            }

            // Update keyword
            $keyword->update([
                'merchant_key'      => $request->merchant_key,
                'kategori_keyword'  => $kategoriKeyword,
                'nama_produk'       => $request->nama_produk,
                'keyword_id'        => $request->keyword_id,
                'cta_link'          => $request->cta_link,
                'redeem'            => $request->redeem,
                'diskon'            => $diskon,
                'subsidy_amount'    => $subsidyAmount,
                'diamond_amount'    => $diamondAmount,
                'skb'               => $request->skb,
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'image'             => $imagePath,
                'stock'             => $request->stock,
                'status'            => $status,
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
        // Auto-disable keywords that have passed their end_date
        Keyword::autoDisableExpiredKeywords();
        
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
        // Buat query params untuk appends, hanya tambahkan merchant_page jika ada di request
        $keywordQueryParams = $request->query();
        // Hanya tambahkan merchant_page jika benar-benar ada di request (bukan null)
        if ($request->has('merchant_page') && $request->get('merchant_page') !== null) {
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

        // Buat query params untuk appends, hanya tambahkan keyword_page jika ada di request
        $merchantQueryParams = $request->query();
        // Hanya tambahkan keyword_page jika benar-benar ada di request (bukan null)
        if ($request->has('keyword_page') && $request->get('keyword_page') !== null) {
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
        // Auto-disable keywords that have passed their end_date
        Keyword::autoDisableExpiredKeywords();
        
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
        // Query SEMUA keyword dengan status approve, termasuk yang sudah ada di database
        // Tidak ada batasan apapun kecuali status = 'approve'
        $query = Keyword::with('merchant')
            ->where('status', 'approve');
        
        // Search filter - hanya aktif jika ada search term
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
        
        // Date filter - hanya aktif jika ada parameter date
        // Jika tidak ada filter date, tampilkan SEMUA keyword dengan status approve
        $date = $request->get('date');
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'no') {
            // Sort by ID: asc = smallest first (1, 2, 3...), desc = largest first (...3, 2, 1)
            $query->reorder();
            $query->orderBy('keywords.id', $sortOrder);
        } elseif ($sortBy === 'spesial_form') {
            // Sort by is_special_promo: asc = false first, desc = true first
            $query->orderBy('is_special_promo', $sortOrder);
        } elseif ($sortBy === 'merchant') {
            $query->leftJoin('merchants', 'keywords.merchant_key', '=', 'merchants.id')
                  ->orderBy('merchants.nama_merchant', $sortOrder)
                  ->select('keywords.*')
                  ->groupBy('keywords.id');
        } elseif ($sortBy === 'nama_produk') {
            $query->orderBy('nama_produk', $sortOrder);
        } elseif ($sortBy === 'keyword_id') {
            $query->orderBy('keyword_id', $sortOrder);
        } elseif ($sortBy === 'redeem') {
            $query->orderBy('redeem', $sortOrder);
        } elseif ($sortBy === 'diskon') {
            $query->orderBy('diskon', $sortOrder);
        } elseif ($sortBy === 'stock') {
            // Arrow up (asc) = descending (paling banyak dulu), Arrow down (desc) = ascending (paling sedikit dulu)
            $actualOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
            $query->orderBy('stock', $actualOrder);
        } elseif ($sortBy === 'periode') {
            // Arrow up (asc) = descending (terbaru dulu), Arrow down (desc) = ascending (terlama dulu)
            $actualOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
            $query->orderBy('start_date', $actualOrder);
            $query->orderBy('end_date', $actualOrder);
        } else {
            // Default: order by id desc
            $query->orderBy('id', 'desc');
        }
        
        // Pagination 10 per page, tapi semua data akan muncul di halaman-halaman berikutnya
        $keywords = $query->paginate(10)->withQueryString();
        
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
            $oldStatus = $keyword->is_active;
            $keyword->is_active = $keyword->is_active ? 0 : 1;
            $keyword->save();

            Log::info('Keyword status toggled', [
                'keyword_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $keyword->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status keyword berhasil diperbarui',
                'is_active' => (bool)$keyword->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling keyword status: ' . $e->getMessage(), [
                'keyword_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByMerchant(Request $request, $merchantId)
    {
        try {
            $keywords = Keyword::where('merchant_key', $merchantId)
                ->orderBy('nama_produk', 'asc')
                ->get(['id', 'nama_produk', 'skb']);
            
            return response()->json([
                'success' => true,
                'keywords' => $keywords
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keywords: ' . $e->getMessage()
            ], 500);
        }
    }
}
