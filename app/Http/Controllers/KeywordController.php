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
        $merchants = Merchant::all();
        return view('admin', compact('keywords', 'merchants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'merchant_key'  => 'required|exists:merchants,id',
            'nama_produk'   => 'required|string|max:255',
            'cta_link'      => 'nullable|string|max:255',
            'redeem'        => 'nullable|string|max:255',
            'diskon'        => 'nullable|string|max:100',
            'skb'           => 'nullable|string',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock'         => 'required|integer|min:0',
            'status'        => 'nullable|in:approve,pending,reject',
        ]);

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
            'diskon'        => $request->diskon,
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
                'message' => 'Keyword berhasil ditambahkan!',
                'keyword' => $keyword
            ], 201);
        }

        return redirect()->route('keywords.index')->with('success', 'Keyword berhasil ditambahkan!');
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
                    'current_page' => $keywords->currentPage(),
                    'per_page' => $keywords->perPage(),
                    'total' => $keywords->total(),
                    'last_page' => $keywords->lastPage(),
                    'from' => $keywords->firstItem(),
                    'to' => $keywords->lastItem(),
                ]
            ]);
        }

        return view('admin', compact('keywords'));
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
