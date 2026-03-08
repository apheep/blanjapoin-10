<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use App\Models\Merchant;
use App\Models\Keyword;
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
        $iklans = Iklan::with('merchant')->orderBy('order', 'asc')->get();

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
            } elseif ($iklan->merchant_keys || $iklan->merchant_key) {
                // Get merchants from merchant_keys JSON or fallback to merchant_key
                $merchantIds = $iklan->merchant_keys ?? [];
                if (empty($merchantIds) && $iklan->merchant_key) {
                    $merchantIds = [$iklan->merchant_key];
                }
                
                if (!empty($merchantIds)) {
                    $merchants = Merchant::whereIn('id', $merchantIds)->get();
                    foreach ($merchants as $merchant) {
                        $allLocations->push([
                            'type' => 'merchant',
                            'type_label' => 'merchant',
                            'name' => $merchant->nama_merchant,
                            'slug' => (string) $merchant->id,
                            'display' => 'Merchant/Program',
                            'filter_value' => 'merchant:' . $merchant->id
                        ]);
                    }
                }
            }
        }
        
        // Check if there are any general iklans (all fields null)
        $hasGeneral = $iklans->whereNull('territorial')
            ->whereNull('regional')
            ->whereNull('branch')
            ->whereNull('cluster')
            ->whereNull('merchant_key')
            ->isNotEmpty();
        
        // Remove duplicates and sort
        $allLocations = $allLocations->unique('filter_value')->sortBy('name')->values();

        // Get all merchants for merchant/program selection (both active and inactive)
        $merchants = Merchant::orderBy('nama_merchant')
            ->get()
            ->map(function($merchant) {
                return [
                    'id' => $merchant->id,
                    'name' => $merchant->nama_merchant,
                ];
            })
            ->values();

        // Get all keywords with images for keyword-based ad creation
        $keywords = Keyword::with('merchant')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('nama_produk')
            ->get()
            ->map(function($keyword) {
                return [
                    'id' => $keyword->id,
                    'nama_produk' => $keyword->nama_produk,
                    'kategori_keyword' => $keyword->kategori_keyword,
                    'image' => $keyword->image,
                    'cta_link' => $keyword->cta_link,
                    'merchant_key' => $keyword->merchant_key,
                    'merchant_name' => $keyword->merchant ? $keyword->merchant->nama_merchant : null,
                ];
            })
            ->values();

        return view('iklan', compact('iklans', 'territories', 'regions', 'branches', 'clusters', 'allLocations', 'hasGeneral', 'merchants', 'keywords'));
    }

    /**
     * Store a newly created iklan image.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'upload_type' => ['required', 'in:manual,keyword'],
            'keyword_id' => ['required_if:upload_type,keyword', 'nullable', 'exists:keywords,id'],
            'image' => ['required_if:upload_type,manual', 'nullable', 'image', 'max:2048'],
            'link_iklan' => ['nullable', 'string', 'max:500'],
            'territorial' => ['nullable', 'string'],
            'regional' => ['nullable', 'string'],
            'branch' => ['nullable', 'string'],
            'cluster' => ['nullable', 'string'],
            'merchant_keys' => ['nullable', 'array'],
            'merchant_keys.*' => ['integer', 'exists:merchants,id'],
        ]);

        $uploadType = $request->input('upload_type', 'manual');
        $keywordId = null;
        $path = null;
        $link = null;
        $merchantKeys = [];

        if ($uploadType === 'keyword') {
            // Keyword-based ad
            $keywordId = $request->input('keyword_id');
            $keyword = Keyword::findOrFail($keywordId);

            // Use keyword's image (copy it to iklan directory)
            if ($keyword->image && Storage::disk('public')->exists($keyword->image)) {
                $extension = pathinfo($keyword->image, PATHINFO_EXTENSION);
                $filename = 'iklan_keyword_' . $keyword->id . '_' . time() . '.' . $extension;
                $path = 'iklan/' . $filename;
                Storage::disk('public')->copy($keyword->image, $path);
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['keyword_id' => 'Keyword tidak memiliki gambar yang valid.'])
                    ->withInput();
            }

            // Use keyword's CTA link
            $link = $keyword->cta_link;

            // If keyword doesn't have CTA link, use the one from input
            if (empty($link)) {
                $link = $request->input('link_iklan');
            }

            // Use keyword's merchant
            if ($keyword->merchant_key) {
                $merchantKeys = [$keyword->merchant_key];
            }
        } else {
            // Manual upload
            // Ensure directory exists (some servers need this)
            Storage::disk('public')->makeDirectory('iklan');

            $path = $request->file('image')->store('iklan', 'public');

            $link = $request->input('link_iklan');
            $link = is_string($link) ? trim($link) : null;
            if ($link === '') {
                $link = null;
            }

            // Get merchant keys from request for manual upload
            $merchantInputs = $request->input('merchant_keys', []);
            if (!empty($merchantInputs) && is_array($merchantInputs)) {
                $merchantKeys = array_filter(array_map('intval', $merchantInputs), function($id) {
                    return $id > 0;
                });
                $merchantKeys = array_values($merchantKeys);
            }
        }

        // Only one location type can be selected (mutually exclusive)
        $territorial = null;
        $regional = null;
        $branch = null;
        $cluster = null;

        // Check which location type is selected (priority: territorial > regional > branch > cluster > merchant)
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
        
        try {
            // Create iklan with merchant_keys as JSON array
            Iklan::create([
                'image_path' => $path,
                'link_iklan' => $link,
                'territorial' => $territorial,
                'regional' => $regional,
                'branch' => $branch,
                'cluster' => $cluster,
                'merchant_key' => !empty($merchantKeys) ? $merchantKeys[0] : null,
                'merchant_keys' => !empty($merchantKeys) ? $merchantKeys : null,
                'keyword_id' => $keywordId,
                'order' => $newOrder,
            ]);
        } catch (\Exception $e) {
            // Delete uploaded file if DB insert fails
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            \Illuminate\Support\Facades\Log::error('Iklan store error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors(['error' => 'Gagal menyimpan iklan: ' . $e->getMessage()])
                ->withInput();
        }

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
