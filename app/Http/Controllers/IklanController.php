<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use App\Models\Merchant;
use App\Models\DimTeritorialNational;
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

        // Get all available territories from DimTeritorialNational (all cities)
        $territories = DimTeritorialNational::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->filter(function($city) {
                return !empty(trim($city));
            })
            ->map(function($city) {
                // Normalize city name (remove prefix Kota/Kabupaten if exists)
                $cityName = trim($city);
                $cityName = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $cityName);
                return [
                    'name' => trim($cityName),
                    'slug' => territorialSlug(trim($cityName))
                ];
            })
            ->filter(function($item) {
                return !empty($item['name']) && !empty($item['slug']);
            })
            ->unique('slug')
            ->sortBy('name')
            ->values();

        // Get all available regions, branches, and clusters from DimTeritorialNational
        $regions = DimTeritorialNational::whereNotNull('regional')
            ->where('regional', '!=', '')
            ->distinct()
            ->orderBy('regional')
            ->pluck('regional')
            ->filter(function($regional) {
                return !empty(trim($regional));
            })
            ->map(function($regional) {
                return [
                    'name' => trim($regional),
                    'slug' => territorialSlugGeneric(trim($regional))
                ];
            })
            ->unique('slug')
            ->values();

        $branches = DimTeritorialNational::whereNotNull('branch')
            ->where('branch', '!=', '')
            ->distinct()
            ->orderBy('branch')
            ->pluck('branch')
            ->filter(function($branch) {
                return !empty(trim($branch));
            })
            ->map(function($branch) {
                return [
                    'name' => trim($branch),
                    'slug' => territorialSlugGeneric(trim($branch))
                ];
            })
            ->unique('slug')
            ->values();

        $clusters = DimTeritorialNational::whereNotNull('cluster')
            ->where('cluster', '!=', '')
            ->distinct()
            ->orderBy('cluster')
            ->pluck('cluster')
            ->filter(function($cluster) {
                return !empty(trim($cluster));
            })
            ->map(function($cluster) {
                return [
                    'name' => trim($cluster),
                    'slug' => territorialSlugGeneric(trim($cluster))
                ];
            })
            ->unique('slug')
            ->values();

        // Combine all locations for unified filter - only locations that have iklans
        $allLocations = collect();
        
        // Get unique locations from existing iklans
        foreach ($iklans as $iklan) {
            if ($iklan->territorial) {
                $territory = $territories->firstWhere('slug', $iklan->territorial);
                if ($territory) {
                    $allLocations->push([
                        'type' => 'territorial',
                        'type_label' => 'city',
                        'name' => $territory['name'],
                        'slug' => $territory['slug'],
                        'display' => 'city/' . $territory['slug'],
                        'filter_value' => 'territorial:' . $territory['slug']
                    ]);
                }
            } elseif ($iklan->regional) {
                $region = $regions->firstWhere('slug', $iklan->regional);
                if ($region) {
                    $allLocations->push([
                        'type' => 'regional',
                        'type_label' => 'reg',
                        'name' => $region['name'],
                        'slug' => $region['slug'],
                        'display' => 'reg/' . $region['slug'],
                        'filter_value' => 'regional:' . $region['slug']
                    ]);
                }
            } elseif ($iklan->branch) {
                $branch = $branches->firstWhere('slug', $iklan->branch);
                if ($branch) {
                    $allLocations->push([
                        'type' => 'branch',
                        'type_label' => 'branch',
                        'name' => $branch['name'],
                        'slug' => $branch['slug'],
                        'display' => 'branch/' . $branch['slug'],
                        'filter_value' => 'branch:' . $branch['slug']
                    ]);
                }
            } elseif ($iklan->cluster) {
                $cluster = $clusters->firstWhere('slug', $iklan->cluster);
                if ($cluster) {
                    $allLocations->push([
                        'type' => 'cluster',
                        'type_label' => 'cluster',
                        'name' => $cluster['name'],
                        'slug' => $cluster['slug'],
                        'display' => 'cluster/' . $cluster['slug'],
                        'filter_value' => 'cluster:' . $cluster['slug']
                    ]);
                }
            }
        }
        
        // Check if there are any general iklans (all fields null)
        $hasGeneral = $iklans->whereNull('territorial')
            ->whereNull('regional')
            ->whereNull('branch')
            ->whereNull('cluster')
            ->isNotEmpty();
        
        // Remove duplicates and sort
        $allLocations = $allLocations->unique('filter_value')->sortBy('name')->values();

        return view('iklan', compact('iklans', 'territories', 'regions', 'branches', 'clusters', 'allLocations', 'hasGeneral'));
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
            'regional' => ['nullable', 'string'],
            'branch' => ['nullable', 'string'],
            'cluster' => ['nullable', 'string'],
        ]);

        $path = $request->file('image')->store('iklan', 'public');

        $link = $request->input('link_iklan');
        $link = is_string($link) ? trim($link) : null;
        if ($link === '') {
            $link = null;
        }

        // Only one location type can be selected (mutually exclusive)
        $territorial = null;
        $regional = null;
        $branch = null;
        $cluster = null;

        // Check which location type is selected (priority: territorial > regional > branch > cluster)
        $territorialInput = $request->input('territorial');
        $regionalInput = $request->input('regional');
        $branchInput = $request->input('branch');
        $clusterInput = $request->input('cluster');

        if (!empty($territorialInput) && trim($territorialInput) !== '') {
            $territorial = territorialSlug(trim($territorialInput));
        } elseif (!empty($regionalInput) && trim($regionalInput) !== '') {
            $regional = territorialSlugGeneric(trim($regionalInput));
        } elseif (!empty($branchInput) && trim($branchInput) !== '') {
            $branch = territorialSlugGeneric(trim($branchInput));
        } elseif (!empty($clusterInput) && trim($clusterInput) !== '') {
            $cluster = territorialSlugGeneric(trim($clusterInput));
        }

        // Get the minimum order value and subtract 1 to place new items at the top
        // System uses 0-based ordering where lower values appear first (orderBy('order', 'asc'))
        // New items should get order = minOrder - 1 to appear before all existing items
        $minOrder = Iklan::min('order') ?? 0;
        
        // New items get minOrder - 1 to appear before all existing items
        // This allows negative values which will appear first when sorted ascending
        $newOrder = $minOrder - 1;
        
        Iklan::create([
            'image_path' => $path,
            'link_iklan' => $link,
            'territorial' => $territorial,
            'regional' => $regional,
            'branch' => $branch,
            'cluster' => $cluster,
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
        // Frontend sends 0-based array indices which should be used directly as order values
        // Lower order values appear first when sorted ascending (orderBy('order', 'asc'))
        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $id) {
                Iklan::where('id', $id)->update(['order' => $order]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Urutan iklan berhasil diperbarui.',
        ]);
    }
}
