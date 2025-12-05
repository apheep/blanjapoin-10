<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IklanController extends Controller
{
    /**
     * Display the iklan management page.
     */
    public function index(): View
    {
        $iklans = Iklan::orderBy('order', 'asc')->get();

        return view('iklan', compact('iklans'));
    }

    /**
     * Store a newly created iklan image.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
            'link_iklan' => ['nullable', 'url'],
        ]);

        $path = $request->file('image')->store('iklan', 'public');

        $link = $request->input('link_iklan');
        $link = is_string($link) ? trim($link) : null;
        if ($link === '') {
            $link = null;
        }

        // Get the highest order value and add 1
        $maxOrder = Iklan::max('order') ?? 0;
        
        Iklan::create([
            'image_path' => $path,
            'link_iklan' => $link,
            'order' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('iklan.index')
            ->with('success', 'Iklan berhasil ditambahkan.');
    }

    /**
     * Remove the specified iklan image from storage.
     */
    public function destroy(Iklan $iklan): RedirectResponse
    {
        if ($iklan->image_path) {
            Storage::disk('public')->delete($iklan->image_path);
        }

        $iklan->delete();

        return redirect()
            ->route('iklan.index')
            ->with('success', 'Iklan berhasil dihapus.');
    }

    /**
     * Update the order of iklans.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*' => ['required', 'integer', 'exists:iklans,id'],
        ]);

        foreach ($request->orders as $order => $id) {
            Iklan::where('id', $id)->update(['order' => $order + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan iklan berhasil diperbarui.',
        ]);
    }
}
