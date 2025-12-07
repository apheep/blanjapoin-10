<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Merchant;
use App\Exports\KeywordsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
                'subsidy_amount'    => 'required_if:subsidy_enabled,1|nullable|numeric|min:0',
                'diamond_enabled'   => 'nullable|in:0,1',
                'diamond_amount'    => 'required_if:diamond_enabled,1|nullable|integer|min:0',
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
            if (empty($request->diskon_percent) && empty($request->diskon_rupiah)) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan isi salah satu dari diskon (persen atau rupiah)'
                    ], 422);
                }
                return back()->withErrors(['diskon' => 'Silakan isi salah satu dari diskon (persen atau rupiah)'])->withInput();
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
            if ($request->diskon_percent) {
                $diskon = $request->diskon_percent . '%';
            } elseif ($request->diskon_rupiah) {
                $diskon = 'Rp ' . number_format($request->diskon_rupiah, 0, ',', '.');
            }

            // Handle subsidy amount
            $subsidyAmount = null;
            if ($request->subsidy_enabled == '1' && $request->subsidy_amount) {
                // Hapus format rupiah (titik sebagai pemisah ribuan)
                $subsidyAmount = str_replace('.', '', $request->subsidy_amount);
                $subsidyAmount = (float) $subsidyAmount;
            }

            // Handle diamond amount
            $diamondAmount = null;
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
                'subsidy_amount'    => 'required_if:subsidy_enabled,1|nullable|numeric|min:0',
                'diamond_enabled'   => 'nullable|in:0,1',
                'diamond_amount'    => 'required_if:diamond_enabled,1|nullable|integer|min:0',
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
            if (empty($request->diskon_percent) && empty($request->diskon_rupiah)) {
                return back()->withErrors(['diskon' => 'Silakan isi salah satu dari diskon (persen atau rupiah)'])->withInput();
            }

            // Validasi start date tidak boleh melebihi end date
            if ($request->start_date && $request->end_date) {
                if ($request->start_date > $request->end_date) {
                    return back()->withErrors(['start_date' => 'Tanggal mulai tidak boleh melebihi tanggal berakhir'])->withInput();
                }
            }

            // Format diskon
            $diskon = '';
            if ($request->diskon_percent) {
                $diskon = $request->diskon_percent . '%';
            } elseif ($request->diskon_rupiah) {
                $diskon = 'Rp ' . number_format($request->diskon_rupiah, 0, ',', '.');
            }

            // Handle subsidy amount
            $subsidyAmount = null;
            if ($request->subsidy_enabled == '1' && $request->subsidy_amount) {
                // Hapus format rupiah (titik sebagai pemisah ribuan)
                $subsidyAmount = str_replace('.', '', $request->subsidy_amount);
                $subsidyAmount = (float) $subsidyAmount;
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

        // Paginate standar 10 data per halaman (baik dengan atau tanpa filter status)
        $keywords = $keywordsQuery->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.table-keyword', ['keywords' => $keywords])->render(),
            ]);
        }

        $merchants = Merchant::orderBy('id')->paginate(10);
        $allMerchants = Merchant::orderBy('nama_merchant')->get();

        return view('admin', compact('keywords', 'merchants', 'allMerchants'));
    }

    public function publicSearch(Request $request)
    {
        $searchTerm = trim($request->get('q', ''));

        $searchResults = Keyword::with('merchant')
            ->where('status', 'approve')
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
}
