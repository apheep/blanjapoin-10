<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MerchantController extends Controller
{
    public function index()
    {
        $merchants = Merchant::orderBy('id')->paginate(15);
        return view('admin', compact('merchants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'daerah'         => 'required|string|max:100',
            'nama_merchant'  => 'required|string|max:255',
            'logo_merchant'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kategori'       => 'nullable|string|max:100',
        ]);
    
        // =====================
        //  HANDLE UPLOAD LOGO
        // =====================
        $logoPath = null;
    
        if ($request->hasFile('logo_merchant')) {
            // Simpan ke storage/app/public/merchants/
            $logoPath = $request->file('logo_merchant')->store('merchants', 'public');
        }
    
        // SIMPAN DATA KE DATABASE
        $merchant = Merchant::create([
            'daerah'        => $request->daerah,
            'nama_merchant' => $request->nama_merchant,
            'logo_merchant' => $logoPath, // path-nya disimpan ke DB
            'kategori'      => $request->kategori,
        ]);
    
        // Jika request dari AJAX, return JSON
        if ($request->wantsJson()) {
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

    // edit, update menyusul seperti yang sudah aku kirim tadi

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
        $searchTerm = $request->input('q', '');
        $page = $request->input('page', 1);
        
        $merchants = Merchant::where('nama_merchant', 'like', "%{$searchTerm}%")
                    ->orWhere('daerah', 'like', "%{$searchTerm}%")
                    ->orWhere('kategori', 'like', "%{$searchTerm}%")
                    ->orderBy('id')
                    ->paginate(15, ['*'], 'page', $page);
        
        if ($request->wantsJson()) {
            return response()->json([
                'merchants' => $merchants->items(),
                'pagination' => [
                    'current_page' => $merchants->currentPage(),
                    'last_page' => $merchants->lastPage(),
                    'per_page' => $merchants->perPage(),
                    'total' => $merchants->total(),
                    'from' => $merchants->firstItem(),
                    'to' => $merchants->lastItem(),
                    'has_more_pages' => $merchants->hasMorePages(),
                    'next_page_url' => $merchants->nextPageUrl(),
                    'prev_page_url' => $merchants->previousPageUrl(),
                ]
            ]);
        }
        
        return view('admin', compact('merchants'));
    }
}
