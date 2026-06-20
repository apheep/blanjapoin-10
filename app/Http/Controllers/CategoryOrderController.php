<?php

namespace App\Http\Controllers;

use App\Models\CategoryOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryOrderController extends Controller
{
    public function index(): View
    {
        return view('category-order', [
            'routeTypes'    => CategoryOrder::routeTypes(),
            'allCategories' => CategoryOrder::allCategories(),
        ]);
    }

    /**
     * GET /api/category-order/{routeType}?value=sector1
     */
    public function getByRouteType(Request $request, string $routeType): JsonResponse
    {
        if (!array_key_exists($routeType, CategoryOrder::routeTypes())) {
            return response()->json(['error' => 'Route type tidak valid.'], 422);
        }

        $routeValue = trim($request->query('value', ''));

        $hasConfig = CategoryOrder::where('route_type', $routeType)
            ->where('route_value', $routeValue)
            ->exists();

        $categories = CategoryOrder::getEditorCategories($routeType, $routeValue);

        return response()->json([
            'categories' => $categories,
            'inherited'  => !$hasConfig,
        ]);
    }

    /**
     * POST /category-order/save
     * Body: { route_type, route_value, categories: [{key, is_visible}] }
     */
    public function save(Request $request): JsonResponse
    {
        $request->validate([
            'route_type'              => 'required|string',
            'route_value'             => 'nullable|string|max:255',
            'categories'              => 'required|array|min:1',
            'categories.*.key'        => 'required|string',
            'categories.*.is_visible' => 'required|boolean',
        ]);

        $routeType  = $request->route_type;
        $routeValue = trim($request->input('route_value', ''));

        if (!array_key_exists($routeType, CategoryOrder::routeTypes())) {
            return response()->json(['error' => 'Route type tidak valid.'], 422);
        }

        $allDefs = CategoryOrder::allCategories();

        CategoryOrder::where('route_type', $routeType)
            ->where('route_value', $routeValue)
            ->delete();

        foreach ($request->categories as $index => $category) {
            if (!isset($allDefs[$category['key']])) {
                continue;
            }
            CategoryOrder::create([
                'route_type'   => $routeType,
                'route_value'  => $routeValue,
                'category_key' => $category['key'],
                'order_index'  => $index,
                'is_visible'   => (bool) $category['is_visible'],
            ]);
        }

        $scope = $routeValue !== ''
            ? "/{$routeType}/{$routeValue}"
            : (CategoryOrder::routeTypes()[$routeType]['label'] ?? $routeType);

        return response()->json(['success' => true, 'message' => "Urutan kategori untuk \"{$scope}\" berhasil disimpan."]);
    }

    /**
     * POST /category-order/reset
     * Body: { route_type, route_value }
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'route_type'  => 'required|string',
            'route_value' => 'nullable|string|max:255',
        ]);

        $routeType  = $request->route_type;
        $routeValue = trim($request->input('route_value', ''));

        if (!array_key_exists($routeType, CategoryOrder::routeTypes())) {
            return response()->json(['error' => 'Route type tidak valid.'], 422);
        }

        CategoryOrder::where('route_type', $routeType)
            ->where('route_value', $routeValue)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Urutan dikembalikan ke pengaturan lebih tinggi (atau default).']);
    }

    /**
     * GET /api/category-order/{routeType}/saved-values
     * Returns list of saved route_value entries for a given route type.
     */
    public function savedValues(string $routeType): JsonResponse
    {
        if (!array_key_exists($routeType, CategoryOrder::routeTypes())) {
            return response()->json(['error' => 'Route type tidak valid.'], 422);
        }

        $values = CategoryOrder::where('route_type', $routeType)
            ->select('route_value')
            ->distinct()
            ->orderBy('route_value')
            ->pluck('route_value');

        return response()->json($values);
    }

    /**
     * GET /api/category-order/{routeType}/available-categories?value=xxx
     *
     * Returns which category keys actually have active keyword data
     * for the given route type + value. Used by the admin editor to
     * show only relevant categories.
     *
     * For route_type = 'default' or empty value → check all active keywords (no location filter).
     * For route_type = 'city'  + value → filter keywords whose merchant.daerah matches the city slug.
     * For route_type = 'u'     + value → filter keywords for the merchant whose link code = value.
     * For route_type = 'reg' / 'poin-tsel' / 'cluster' → filter by DimTeritorialNational hierarchy.
     *   (Falls back to returning all active categories when filter is too complex / not resolvable.)
     */
    public function availableCategories(Request $request, string $routeType): JsonResponse
    {
        if (!array_key_exists($routeType, CategoryOrder::routeTypes())) {
            return response()->json(['error' => 'Route type tidak valid.'], 422);
        }

        $routeValue = trim($request->query('value', ''));
        $allDefs    = CategoryOrder::allCategories();

        // Base query: active & approved keywords with their merchant
        $query = \App\Models\Keyword::with('merchant')
            ->where('status', 'approve')
            ->where('is_active', 1)
            ->whereHas('merchant', fn($q) => $q->where('is_active', 1));

        // Apply location filter when a specific value is provided
        if ($routeValue !== '') {
            switch ($routeType) {
                case 'u':
                    // Link pelanggan: filter by merchant whose link code matches
                    $query->whereHas('merchant', function ($q) use ($routeValue) {
                        $q->where('link_blanjapoin', 'like', '%dash/' . $routeValue)
                          ->orWhere('link_blanjapoin', 'like', '%dash/' . $routeValue . '/%')
                          ->orWhere('link_blanjapoin', 'like', '%dash/' . $routeValue . '?%');
                    });
                    break;

                case 'city':
                    // City page: match merchants whose daerah slug matches routeValue
                    $query->whereHas('merchant', function ($q) use ($routeValue) {
                        $q->whereNotNull('daerah')->where('daerah', '!=', '');
                    });
                    // We'll filter in PHP since slug matching requires a helper function
                    $keywords = $query->get();
                    $available = $keywords->map(function ($kw) {
                        return !empty($kw->kategori_keyword)
                            ? $kw->kategori_keyword
                            : ($kw->merchant->kategori ?? null);
                    })->filter(function ($cat) use ($allDefs, $routeValue) {
                        // Also apply city slug filter in PHP
                        return $cat !== null;
                    });

                    // Re-filter: only include keyword whose merchant city slug matches
                    $available = $keywords->filter(function ($kw) use ($routeValue) {
                        $daerah = trim($kw->merchant->daerah ?? '');
                        $city   = trim(extractKabupatenKota($daerah));
                        return strtolower(territorialSlug($city)) === strtolower($routeValue);
                    })->map(function ($kw) {
                        return !empty($kw->kategori_keyword)
                            ? $kw->kategori_keyword
                            : ($kw->merchant->kategori ?? null);
                    })->filter()->unique()->values();

                    return response()->json([
                        'available' => $available->intersect(array_keys($allDefs))->values(),
                    ]);

                case 'reg':
                    // Regional: filter by merchants whose regional matches
                    $cities = \App\Models\DimTeritorialNational::where('regional', 'like', '%' . $routeValue . '%')
                        ->pluck('city')->filter()->unique()->map('strtolower')->toArray();
                    if (!empty($cities)) {
                        $query->whereHas('merchant', function ($q) use ($cities) {
                            $q->whereNotNull('daerah')->where('daerah', '!=', '');
                        });
                        $keywords = $query->get()->filter(function ($kw) use ($cities) {
                            $daerah = trim($kw->merchant->daerah ?? '');
                            $city   = strtolower(trim(extractKabupatenKota($daerah)));
                            return in_array($city, $cities);
                        });
                        $available = $keywords->map(function ($kw) {
                            return !empty($kw->kategori_keyword) ? $kw->kategori_keyword : ($kw->merchant->kategori ?? null);
                        })->filter()->unique()->values();
                        return response()->json(['available' => $available->intersect(array_keys($allDefs))->values()]);
                    }
                    break;

                case 'poin-tsel':
                    // Branch: filter by branch name
                    $cities = \App\Models\DimTeritorialNational::whereRaw("REPLACE(LOWER(branch), ' ', '-') = ?", [strtolower($routeValue)])
                        ->orWhereRaw("REPLACE(LOWER(branch), ' ', '-') LIKE ?", ['%' . strtolower($routeValue) . '%'])
                        ->pluck('city')->filter()->unique()->map('strtolower')->toArray();
                    if (!empty($cities)) {
                        $keywords = $query->get()->filter(function ($kw) use ($cities) {
                            $city = strtolower(trim(extractKabupatenKota(trim($kw->merchant->daerah ?? ''))));
                            return in_array($city, $cities);
                        });
                        $available = $keywords->map(fn($kw) => !empty($kw->kategori_keyword) ? $kw->kategori_keyword : ($kw->merchant->kategori ?? null))
                            ->filter()->unique()->values();
                        return response()->json(['available' => $available->intersect(array_keys($allDefs))->values()]);
                    }
                    break;

                case 'cluster':
                    // Cluster: filter by cluster name
                    $cities = \App\Models\DimTeritorialNational::whereRaw("REPLACE(LOWER(cluster), ' ', '-') = ?", [strtolower($routeValue)])
                        ->orWhereRaw("REPLACE(LOWER(cluster), ' ', '-') LIKE ?", ['%' . strtolower($routeValue) . '%'])
                        ->pluck('city')->filter()->unique()->map('strtolower')->toArray();
                    if (!empty($cities)) {
                        $keywords = $query->get()->filter(function ($kw) use ($cities) {
                            $city = strtolower(trim(extractKabupatenKota(trim($kw->merchant->daerah ?? ''))));
                            return in_array($city, $cities);
                        });
                        $available = $keywords->map(fn($kw) => !empty($kw->kategori_keyword) ? $kw->kategori_keyword : ($kw->merchant->kategori ?? null))
                            ->filter()->unique()->values();
                        return response()->json(['available' => $available->intersect(array_keys($allDefs))->values()]);
                    }
                    break;
            }
        }

        // Default / fallback: return all categories that have any active data
        $keywords  = $query->get();
        $available = $keywords->map(function ($kw) {
            return !empty($kw->kategori_keyword) ? $kw->kategori_keyword : ($kw->merchant->kategori ?? null);
        })->filter()->unique()->values();

        return response()->json([
            'available' => $available->intersect(array_keys($allDefs))->values(),
        ]);
    }
}
