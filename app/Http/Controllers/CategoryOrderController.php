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
}
