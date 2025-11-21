<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::with('merchant')->orderBy('id')->paginate(15);
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
                'skb'               => 'nullable|string',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'end_date'          => 'nullable|date_format:Y-m-d',
                'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'stock'             => 'nullable|integer|min:0',
                'status'            => 'nullable|in:approve,pending,reject',
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
                'cta_link'      => $request->cta_link,
                'redeem'        => $request->redeem,
                'diskon'        => $diskon,
                'skb'           => $request->skb,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'image'         => $imagePath,
                'stock'         => $request->stock,
                'status'        => $request->status ?? 'pending',
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keyword berhasil ditambahkan!',
                    'keyword' => $keyword
                ], 201);
            }

            return redirect()->route('keywords.index')->with('success', 'Keyword berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error creating keyword: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
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
                'cta_link'          => 'nullable|string|max:255',
                'redeem'            => 'nullable|string|max:255',
                'diskon_percent'    => 'nullable|numeric|min:0|max:100',
                'diskon_rupiah'     => 'nullable|numeric|min:0',
                'skb'               => 'nullable|string',
                'start_date'        => 'nullable|date_format:Y-m-d',
                'end_date'          => 'nullable|date_format:Y-m-d',
                'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'stock'             => 'nullable|integer|min:0',
                'status'            => 'nullable|in:approve,pending,reject',
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
                'cta_link'      => $request->cta_link,
                'redeem'        => $request->redeem,
                'diskon'        => $diskon,
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

            return redirect()->route('keywords.index')->with('success', 'Keyword berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error updating keyword: ' . $e->getMessage());
            
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
        $searchTerm = $request->get('q', '');
        
        $keywords = Keyword::with('merchant')
            ->where('nama_produk', 'like', "%{$searchTerm}%")
            ->orWhereHas('merchant', function ($query) use ($searchTerm) {
                $query->where('nama_merchant', 'like', "%{$searchTerm}%");
            })
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'keywords' => $keywords->items(),
                'pagination' => [
                    'current_page' => $keywords->currentPage() ?? 1,
                    'per_page' => $keywords->perPage() ?? 15,
                    'total' => $keywords->total() ?? 0,
                    'last_page' => $keywords->lastPage() ?? 1,
                    'from' => $keywords->firstItem() ?? 0,
                    'to' => $keywords->lastItem() ?? 0,
                ]
            ]);
        }

        return view('admin', compact('keywords'));
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
}
