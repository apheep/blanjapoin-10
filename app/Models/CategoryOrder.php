<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOrder extends Model
{
    protected $fillable = ['route_type', 'route_value', 'category_key', 'order_index', 'is_visible', 'item_sort'];

    protected $casts = ['is_visible' => 'boolean'];

    /**
     * All known categories: key → [label, emoji, view partial, section]
     * `section` matches the data-voucher-section attribute rendered on each
     * category's card container, used to target the right cards when applying
     * a per-category point sort on the client side.
     */
    public static function allCategories(): array
    {
        return [
            'belanja'        => ['label' => 'Belanja',        'emoji' => '🛍️', 'view' => 'merchant.shop',         'section' => 'shop'],
            'kuliner'        => ['label' => 'Kuliner',        'emoji' => '🍔', 'view' => 'merchant.food',          'section' => 'food'],
            'telkomsel'      => ['label' => 'Telkomsel',      'emoji' => '📱', 'view' => 'merchant.telkomsel',     'section' => 'telkomsel'],
            'hiburan'        => ['label' => 'Hiburan',        'emoji' => '🎬', 'view' => 'merchant.entertain',     'section' => 'entertain'],
            'liburan'        => ['label' => 'Liburan',        'emoji' => '✈️', 'view' => 'merchant.vacation',      'section' => 'vacation'],
            'kecantikan'     => ['label' => 'Kecantikan',     'emoji' => '💄', 'view' => 'merchant.beautyncare',   'section' => 'beauty'],
            'merchandise'    => ['label' => 'Merchandise',    'emoji' => '🎽', 'view' => 'merchant.merchandise',   'section' => 'merchandise'],
            'paket_video'    => ['label' => 'Paket Video',    'emoji' => '🎥', 'view' => 'merchant.paketvideo',    'section' => 'paketvideo'],
            'paket_games'    => ['label' => 'Paket Games',    'emoji' => '🎮', 'view' => 'merchant.paketgames',    'section' => 'paketgames'],
            'paket_internet' => ['label' => 'Paket Internet', 'emoji' => '🌐', 'view' => 'merchant.paketinternet', 'section' => 'paketinternet'],
        ];
    }

    /**
     * Valid values for item_sort: how vouchers within each category are ordered by redeem point.
     */
    public static function itemSortOptions(): array
    {
        return [
            'none'        => 'Tidak diurutkan (default)',
            'redeem_desc' => 'Poin Tertinggi ke Terendah',
            'redeem_asc'  => 'Poin Terendah ke Tertinggi',
        ];
    }

    /**
     * Available route type prefixes with their display labels.
     */
    public static function routeTypes(): array
    {
        return [
            'default'   => ['label' => 'Halaman Utama (/)',       'placeholder' => ''],
            'u'         => ['label' => 'Link Pelanggan (/u/...)',  'placeholder' => 'Contoh: sector1'],
            'city'      => ['label' => 'Kota (/city/...)',         'placeholder' => 'Contoh: surabaya'],
            'reg'       => ['label' => 'Regional (/reg/...)',      'placeholder' => 'Contoh: jateng-diy'],
            'poin-tsel' => ['label' => 'Branch (/poin-tsel/...)', 'placeholder' => 'Contoh: jakarta-barat'],
            'cluster'   => ['label' => 'Cluster (/cluster/...)',   'placeholder' => 'Contoh: jakarta-cluster-1'],
        ];
    }

    /**
     * Return ordered visible categories for the given route type + specific value.
     *
     * Fallback chain:
     *   1. Specific: route_type=$routeType, route_value=$routeValue
     *   2. Generic:  route_type=$routeType, route_value=''
     *   3. Default:  route_type='default',  route_value=''
     *   4. Hardcoded order
     */
    public static function getOrderedCategories(string $routeType, string $routeValue = ''): array
    {
        $allDefs = static::allCategories();

        $rows = static::fetchRows($routeType, $routeValue);

        if ($rows->isEmpty()) {
            return static::hardcodedDefaults($allDefs);
        }

        return $rows
            ->filter(fn($r) => $r->is_visible && isset($allDefs[$r->category_key]))
            ->map(fn($r) => array_merge(
                ['key' => $r->category_key],
                $allDefs[$r->category_key],
                ['is_visible' => true, 'item_sort' => static::normalizeItemSort($r->item_sort)]
            ))
            ->values()
            ->all();
    }

    /**
     * Return full list (visible + hidden) for the admin editor.
     * Only looks at the EXACT (route_type, route_value) — no fallback chain.
     * Returns hardcoded defaults when no config is saved yet.
     */
    public static function getEditorCategories(string $routeType, string $routeValue = ''): array
    {
        $allDefs = static::allCategories();

        $rows = static::where('route_type', $routeType)
            ->where('route_value', $routeValue)
            ->orderBy('order_index')
            ->get();

        if ($rows->isEmpty()) {
            return static::hardcodedDefaults($allDefs);
        }

        $saved = $rows->keyBy('category_key');

        $result = $rows->map(fn($r) => array_merge(
            ['key' => $r->category_key],
            $allDefs[$r->category_key] ?? ['label' => $r->category_key, 'emoji' => '📦', 'view' => '', 'section' => $r->category_key],
            ['is_visible' => (bool) $r->is_visible, 'item_sort' => static::normalizeItemSort($r->item_sort)]
        ))->all();

        // Append any categories not in saved order
        foreach ($allDefs as $key => $def) {
            if (!$saved->has($key)) {
                $result[] = array_merge(['key' => $key], $def, ['is_visible' => true, 'item_sort' => 'none']);
            }
        }

        return $result;
    }

    /**
     * Ensure only known item_sort values are ever surfaced, defaulting to 'none'.
     */
    private static function normalizeItemSort(?string $itemSort): string
    {
        return $itemSort !== null && array_key_exists($itemSort, static::itemSortOptions()) ? $itemSort : 'none';
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private static function fetchRows(string $routeType, string $routeValue): \Illuminate\Support\Collection
    {
        // 1. Try exact match (specific)
        if ($routeValue !== '') {
            $rows = static::where('route_type', $routeType)
                ->where('route_value', $routeValue)
                ->orderBy('order_index')
                ->get();
            if ($rows->isNotEmpty()) {
                return $rows;
            }
        }

        // 2. Try generic for this route type (route_value = '')
        if ($routeType !== 'default') {
            $rows = static::where('route_type', $routeType)
                ->where('route_value', '')
                ->orderBy('order_index')
                ->get();
            if ($rows->isNotEmpty()) {
                return $rows;
            }
        }

        // 3. Try global default
        return static::where('route_type', 'default')
            ->where('route_value', '')
            ->orderBy('order_index')
            ->get();
    }

    private static function hardcodedDefaults(array $allDefs): array
    {
        return collect($allDefs)
            ->map(fn($def, $key) => array_merge(['key' => $key], $def, ['is_visible' => true, 'item_sort' => 'none']))
            ->values()
            ->all();
    }
}
