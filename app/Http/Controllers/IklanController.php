<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use App\Models\Merchant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IklanController extends Controller
{
    /**
     * Display the iklan management page.
     */
    public function index(): View
    {
        // Order by ascending to show newest items first (matching migration pattern where newest=lowest order)
        $iklans = Iklan::orderBy('order', 'asc')->get();

        // Get all available territories
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

        return view('iklan', compact('iklans', 'territories'));
    }

    /**
     * Store a newly created iklan image.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
            'link_iklan' => ['nullable', 'url'],
            'territorial' => ['nullable', 'string'],
        ]);

        $path = $request->file('image')->store('iklan', 'public');

        $link = $request->input('link_iklan');
        $link = is_string($link) ? trim($link) : null;
        if ($link === '') {
            $link = null;
        }

        $territorial = $request->input('territorial');
        $territorial = is_string($territorial) ? trim($territorial) : null;
        if ($territorial === '') {
            $territorial = null;
        } elseif ($territorial !== null) {
            // Normalize territorial to slug format to ensure consistency
            // If it's already a slug, territorialSlug will return it as-is
            // If it's a display name, it will be converted to slug
            $territorial = territorialSlug($territorial);
        }

        // Get the minimum order value and subtract 1 to place new items at the top
        // This matches the migration pattern where newest items have order=1 (descending created_at)
        // Ensure we don't create negative values
        $minOrder = Iklan::min('order') ?? 1;
        
        // If minOrder is 1 or less, new items get order 0 (will appear first when sorted ascending)
        // If minOrder > 1, new items get minOrder - 1 (will appear before current minimum)
        $newOrder = $minOrder > 1 ? $minOrder - 1 : 0;
        
        Iklan::create([
            'image_path' => $path,
            'link_iklan' => $link,
            'territorial' => $territorial,
            'order' => $newOrder,
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

        // Use database transaction to ensure atomic updates
        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $id) {
                Iklan::where('id', $id)->update(['order' => $order + 1]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Urutan iklan berhasil diperbarui.',
        ]);
    }
}
