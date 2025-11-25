<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use Illuminate\Http\RedirectResponse;
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
        $iklans = Iklan::latest()->get();

        return view('iklan', compact('iklans'));
    }

    /**
     * Store a newly created iklan image.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('image')->store('iklan', 'public');

        Iklan::create([
            'image_path' => $path,
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
}
