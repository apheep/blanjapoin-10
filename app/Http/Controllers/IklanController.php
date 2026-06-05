<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use App\Models\Merchant;
use App\Models\Keyword;
use App\Models\DimTeritorialNational;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IklanController extends Controller
{
    /**
     * Determine whether the current user is a "maha" admin (can_approve = 1).
     */
    private function isUserMaha(): bool
    {
        return Auth::check() && Auth::user()->can_approve == 1;
    }

    /**
     * Get the territorial scope of the current user.
     * Returns an array: type, value (raw DB value), slug.
     * type: national | area | regional | branch | city | none
     */
    private function getUserTerritorialScope(): array
    {
        if (!Auth::check()) {
            return ['type' => 'none', 'value' => null, 'slug' => null];
        }

        $user = Auth::user();

        // Maha admin: full access
        if ($user->can_approve == 1) {
            return ['type' => 'national', 'value' => null, 'slug' => null];
        }

        $userLevel = strtoupper($user->user_level ?? '');

        switch ($userLevel) {
            case 'NATIONAL':
                return ['type' => 'national', 'value' => null, 'slug' => null];

            case 'AREA':
                if ($user->area_level) {
                    preg_match('/AREA\s*(\d+)/i', $user->area_level, $matches);
                    if (isset($matches[1])) {
                        return ['type' => 'area', 'value' => (int)$matches[1], 'slug' => null];
                    }
                }
                return ['type' => 'none', 'value' => null, 'slug' => null];

            case 'REGIONAL':
                if ($user->regional) {
                    return [
                        'type'  => 'regional',
                        'value' => $user->regional,
                        'slug'  => territorialSlugGeneric(trim($user->regional)),
                    ];
                }
                return ['type' => 'none', 'value' => null, 'slug' => null];

            case 'BRANCH':
                if ($user->branch) {
                    return [
                        'type'  => 'branch',
                        'value' => $user->branch,
                        'slug'  => territorialSlugGeneric(trim($user->branch)),
                    ];
                }
                return ['type' => 'none', 'value' => null, 'slug' => null];

            default:
                // Fallback: city-level if city is set
                if ($user->city) {
                    return [
                        'type'  => 'city',
                        'value' => $user->city,
                        'slug'  => territorialSlug(trim($user->city)),
                    ];
                }
                return ['type' => 'none', 'value' => null, 'slug' => null];
        }
    }

    /**
     * Pre-compute sets of allowed slugs for each iklan location type based on user scope.
     * null means "all allowed" (national/maha), empty array means "none allowed".
     *
     * Hierarchy (each level includes all levels below it):
     *   NATIONAL  → territorial + regional + branch + cluster (all, no general)
     *   AREA      → territorial + regional + branch + cluster within area
     *   REGIONAL  → territorial + branch + cluster within regional, + regional itself
     *   BRANCH    → territorial + cluster within branch, + branch itself
     *   city      → only that city (territorial)
     *
     * @return array{territorial:array|null, regional:array|null, branch:array|null, cluster:array|null}
     */
    private function getAllowedSlugsForScope(): array
    {
        if ($this->isUserMaha()) {
            return ['territorial' => null, 'regional' => null, 'branch' => null, 'cluster' => null];
        }

        $scope = $this->getUserTerritorialScope();

        // Maha-equivalent national scope
        if ($scope['type'] === 'national') {
            return ['territorial' => null, 'regional' => null, 'branch' => null, 'cluster' => null];
        }

        if ($scope['type'] === 'none') {
            return ['territorial' => [], 'regional' => [], 'branch' => [], 'cluster' => []];
        }

        if ($scope['type'] === 'city') {
            return [
                'territorial' => [$scope['slug']],
                'regional'    => [],
                'branch'      => [],
                'cluster'     => [],
            ];
        }

        // Helper: map a collection of DB row values to slugs
        $toTerritorialSlugs = fn($rows) => $rows
            ->map(fn($r) => territorialSlug(trim(preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($r->city ?? '')))))
            ->filter(fn($s) => !empty($s))
            ->unique()->values()->toArray();

        $toGenericSlugs = fn($rows, $field) => $rows
            ->filter(fn($r) => !empty($r->$field))
            ->map(fn($r) => territorialSlugGeneric(trim($r->$field)))
            ->filter(fn($s) => !empty($s))
            ->unique()->values()->toArray();

        if ($scope['type'] === 'area') {
            $rows = DimTeritorialNational::where('id_area', $scope['value'])
                ->get(['city', 'regional', 'branch', 'cluster']);
            return [
                'territorial' => $toTerritorialSlugs($rows),
                'regional'    => $toGenericSlugs($rows, 'regional'),
                'branch'      => $toGenericSlugs($rows, 'branch'),
                'cluster'     => $toGenericSlugs($rows, 'cluster'),
            ];
        }

        if ($scope['type'] === 'regional') {
            $rows = DimTeritorialNational::where('regional', $scope['value'])
                ->get(['city', 'branch', 'cluster']);
            return [
                'territorial' => $toTerritorialSlugs($rows),
                'regional'    => [$scope['slug']],            // only own regional
                'branch'      => $toGenericSlugs($rows, 'branch'),
                'cluster'     => $toGenericSlugs($rows, 'cluster'),
            ];
        }

        if ($scope['type'] === 'branch') {
            $rows = DimTeritorialNational::where('branch', $scope['value'])
                ->get(['city', 'cluster']);
            return [
                'territorial' => $toTerritorialSlugs($rows),
                'regional'    => [],                          // branch user cannot manage regional iklan
                'branch'      => [$scope['slug']],
                'cluster'     => $toGenericSlugs($rows, 'cluster'),
            ];
        }

        return ['territorial' => [], 'regional' => [], 'branch' => [], 'cluster' => []];
    }

    /**
     * Which location types can this user choose when creating/editing iklan?
     * Returns array of allowed type strings: 'general','territorial','regional','branch','cluster','merchant'
     */
    private function getAllowedLocationTypes(): array
    {
        if ($this->isUserMaha()) {
            return ['general', 'territorial', 'regional', 'branch', 'cluster', 'merchant'];
        }

        $scope = $this->getUserTerritorialScope();

        switch ($scope['type']) {
            case 'national':
                return ['territorial', 'regional', 'branch', 'cluster', 'merchant'];
            case 'area':
            case 'regional':
                return ['territorial', 'regional', 'branch', 'cluster'];
            case 'branch':
                return ['territorial', 'branch', 'cluster'];
            case 'city':
                return ['territorial'];
            default:
                return [];
        }
    }

    /**
     * Check whether the current user is allowed to manage a given iklan.
     * - can_approve = 1 (maha): always true
     * - General iklan (no location): only maha
     * - Otherwise: check if iklan's location slug is within user's allowed slugs
     */
    private function canManageIklan(Iklan $iklan): bool
    {
        if ($this->isUserMaha()) {
            return true;
        }

        if ($this->isGeneralIklan($iklan)) {
            return false; // only maha can manage general
        }

        $allowed = $this->getAllowedSlugsForScope();

        if ($iklan->territorial) {
            return $allowed['territorial'] === null
                || in_array($iklan->territorial, $allowed['territorial']);
        }
        if ($iklan->regional) {
            return $allowed['regional'] === null
                || in_array($iklan->regional, $allowed['regional']);
        }
        if ($iklan->branch) {
            return $allowed['branch'] === null
                || in_array($iklan->branch, $allowed['branch']);
        }
        if ($iklan->cluster) {
            return $allowed['cluster'] === null
                || in_array($iklan->cluster, $allowed['cluster']);
        }
        if ($iklan->merchant_key || !empty($iklan->merchant_keys)) {
            // Merchant iklan: allowed for national scope (can_approve=0) and above
            $scope = $this->getUserTerritorialScope();
            return in_array($scope['type'], ['national']);
        }

        return false;
    }

    /**
     * Check if an iklan is a "general" iklan (no location specified).
     */
    private function isGeneralIklan(Iklan $iklan): bool
    {
        return is_null($iklan->territorial)
            && is_null($iklan->regional)
            && is_null($iklan->branch)
            && is_null($iklan->cluster)
            && is_null($iklan->merchant_key)
            && empty($iklan->merchant_keys);
    }

    /**
     * Validate that the territorial value submitted in the form is within the user's scope.
     */
    private function isSubmittedTerritorialAllowed(Request $request): bool
    {
        if ($this->isUserMaha()) {
            return true;
        }

        $territorialInput = $request->input('territorial');
        $regionalInput    = $request->input('regional');
        $branchInput      = $request->input('branch');
        $clusterInput     = $request->input('cluster');
        $merchantKeys     = $request->input('merchant_keys', []);

        // Detect general iklan attempt
        $wouldBeGeneral = empty($territorialInput) && empty($regionalInput)
            && empty($branchInput) && empty($clusterInput) && empty($merchantKeys);

        if ($wouldBeGeneral) {
            return false; // Only maha can create general
        }

        $allowed     = $this->getAllowedSlugsForScope();
        $scope       = $this->getUserTerritorialScope();
        $allowedTypes = $this->getAllowedLocationTypes();

        if (!empty($territorialInput)) {
            if (!in_array('territorial', $allowedTypes)) return false;
            $slug = territorialSlug(trim($territorialInput));
            return $allowed['territorial'] === null || in_array($slug, $allowed['territorial']);
        }
        if (!empty($regionalInput)) {
            if (!in_array('regional', $allowedTypes)) return false;
            $slug = territorialSlugGeneric(trim($regionalInput));
            return $allowed['regional'] === null || in_array($slug, $allowed['regional']);
        }
        if (!empty($branchInput)) {
            if (!in_array('branch', $allowedTypes)) return false;
            $slug = territorialSlugGeneric(trim($branchInput));
            return $allowed['branch'] === null || in_array($slug, $allowed['branch']);
        }
        if (!empty($clusterInput)) {
            if (!in_array('cluster', $allowedTypes)) return false;
            $slug = territorialSlugGeneric(trim($clusterInput));
            return $allowed['cluster'] === null || in_array($slug, $allowed['cluster']);
        }
        if (!empty($merchantKeys)) {
            return in_array('merchant', $allowedTypes);
        }

        return false;
    }

    /**
     * Build filtered territory/region/branch/cluster lists based on user scope for the form dropdowns.
     */
    private function buildFilteredLists(array $scope, bool $isUserMaha): array
    {
        // Base queries
        $citiesQ    = DimTeritorialNational::whereNotNull('city')->where('city', '!=', '');
        $regionsQ   = DimTeritorialNational::whereNotNull('regional')->where('regional', '!=', '');
        $branchesQ  = DimTeritorialNational::whereNotNull('branch')->where('branch', '!=', '');
        $clustersQ  = DimTeritorialNational::whereNotNull('cluster')->where('cluster', '!=', '');

        if (!$isUserMaha) {
            switch ($scope['type']) {
                case 'area':
                    foreach ([$citiesQ, $regionsQ, $branchesQ, $clustersQ] as $q) {
                        $q->where('id_area', $scope['value']);
                    }
                    break;
                case 'regional':
                    foreach ([$citiesQ, $branchesQ, $clustersQ] as $q) {
                        $q->where('regional', $scope['value']);
                    }
                    // regional dropdown: only own regional
                    $regionsQ = null;
                    break;
                case 'branch':
                    foreach ([$citiesQ, $clustersQ] as $q) {
                        $q->where('branch', $scope['value']);
                    }
                    // branch/regional dropdowns: only own branch, no regional
                    $regionsQ  = null;
                    $branchesQ = null;
                    break;
                case 'city':
                    // Only own city
                    $citiesQ   = null;
                    $regionsQ  = null;
                    $branchesQ = null;
                    $clustersQ = null;
                    break;
                case 'national':
                    // All — no filter
                    break;
                default:
                    $citiesQ = $regionsQ = $branchesQ = $clustersQ = null;
                    break;
            }
        }

        $mapCities = fn($q) => ($q ? $q->distinct()->orderBy('city')->pluck('city') : collect())
            ->filter(fn($c) => !empty(trim($c)))
            ->map(function ($city) {
                $name = trim(preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($city)));
                return ['name' => $name, 'slug' => territorialSlug($name)];
            })
            ->filter(fn($i) => !empty($i['name']) && !empty($i['slug']))
            ->unique('slug')->sortBy('name')->values();

        $mapGeneric = fn($q, $field) => ($q ? $q->distinct()->orderBy($field)->pluck($field) : collect())
            ->filter(fn($v) => !empty(trim($v)))
            ->map(fn($v) => ['name' => trim($v), 'slug' => territorialSlugGeneric(trim($v))])
            ->unique('slug')->sortBy('name')->values();

        $territories = $mapCities($citiesQ);
        $regions     = $mapGeneric($regionsQ, 'regional');
        $branches    = $mapGeneric($branchesQ, 'branch');
        $clusters    = $mapGeneric($clustersQ, 'cluster');

        // For city-scoped: inject only own city
        if (!$isUserMaha && $scope['type'] === 'city') {
            $cityName = trim(preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($scope['value'])));
            $territories = collect([['name' => $cityName, 'slug' => $scope['slug']]]);
        }

        // For regional-scoped: inject own regional into regions list
        if (!$isUserMaha && $scope['type'] === 'regional') {
            $regions = collect([['name' => $scope['value'], 'slug' => $scope['slug']]]);
        }

        // For branch-scoped: inject own branch into branches list
        if (!$isUserMaha && $scope['type'] === 'branch') {
            $branches = collect([['name' => $scope['value'], 'slug' => $scope['slug']]]);
        }

        return compact('territories', 'regions', 'branches', 'clusters');
    }

    /**
     * Display the iklan management page.
     */
    public function index(): View
    {
        $isUserMaha       = $this->isUserMaha();
        $userScope        = $this->getUserTerritorialScope();
        $allowedLocTypes  = $this->getAllowedLocationTypes();

        // All iklans — always show everything (read access)
        $iklans = Iklan::with('merchant')->orderBy('order', 'asc')->get();

        // Build filtered form dropdowns
        $lists = $this->buildFilteredLists($userScope, $isUserMaha);
        ['territories' => $territories, 'regions' => $regions,
         'branches'    => $branches,    'clusters' => $clusters] = $lists;

        // Build location filter options for the list (based on all existing iklans, not scope)
        // Use the full unfiltered lists for the filter sidebar
        $allTerritories = DimTeritorialNational::whereNotNull('city')->where('city', '!=', '')
            ->distinct()->orderBy('city')->pluck('city')
            ->filter(fn($c) => !empty(trim($c)))
            ->map(function ($city) {
                $name = trim(preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($city)));
                return ['name' => $name, 'slug' => territorialSlug($name)];
            })
            ->filter(fn($i) => !empty($i['name']) && !empty($i['slug']))
            ->unique('slug')->sortBy('name')->values();

        $allRegions = DimTeritorialNational::whereNotNull('regional')->where('regional', '!=', '')
            ->distinct()->orderBy('regional')->pluck('regional')
            ->filter(fn($r) => !empty(trim($r)))
            ->map(fn($r) => ['name' => trim($r), 'slug' => territorialSlugGeneric(trim($r))])
            ->unique('slug')->values();

        $allBranches = DimTeritorialNational::whereNotNull('branch')->where('branch', '!=', '')
            ->distinct()->orderBy('branch')->pluck('branch')
            ->filter(fn($b) => !empty(trim($b)))
            ->map(fn($b) => ['name' => trim($b), 'slug' => territorialSlugGeneric(trim($b))])
            ->unique('slug')->values();

        $allClusters = DimTeritorialNational::whereNotNull('cluster')->where('cluster', '!=', '')
            ->distinct()->orderBy('cluster')->pluck('cluster')
            ->filter(fn($c) => !empty(trim($c)))
            ->map(fn($c) => ['name' => trim($c), 'slug' => territorialSlugGeneric(trim($c))])
            ->unique('slug')->values();

        // Build allLocations from existing iklans using full lists
        $allLocations = collect();
        foreach ($iklans as $iklan) {
            if ($iklan->territorial) {
                $territory = $allTerritories->firstWhere('slug', $iklan->territorial);
                if ($territory) {
                    $allLocations->push([
                        'type' => 'territorial', 'type_label' => 'city',
                        'name' => $territory['name'], 'slug' => $territory['slug'],
                        'display' => 'city/' . $territory['slug'],
                        'filter_value' => 'territorial:' . $territory['slug'],
                    ]);
                }
            } elseif ($iklan->regional) {
                $region = $allRegions->firstWhere('slug', $iklan->regional);
                if ($region) {
                    $allLocations->push([
                        'type' => 'regional', 'type_label' => 'reg',
                        'name' => $region['name'], 'slug' => $region['slug'],
                        'display' => 'reg/' . $region['slug'],
                        'filter_value' => 'regional:' . $region['slug'],
                    ]);
                }
            } elseif ($iklan->branch) {
                $branch = $allBranches->firstWhere('slug', $iklan->branch);
                if ($branch) {
                    $allLocations->push([
                        'type' => 'branch', 'type_label' => 'branch',
                        'name' => $branch['name'], 'slug' => $branch['slug'],
                        'display' => 'branch/' . $branch['slug'],
                        'filter_value' => 'branch:' . $branch['slug'],
                    ]);
                }
            } elseif ($iklan->cluster) {
                $cluster = $allClusters->firstWhere('slug', $iklan->cluster);
                if ($cluster) {
                    $allLocations->push([
                        'type' => 'cluster', 'type_label' => 'cluster',
                        'name' => $cluster['name'], 'slug' => $cluster['slug'],
                        'display' => 'cluster/' . $cluster['slug'],
                        'filter_value' => 'cluster:' . $cluster['slug'],
                    ]);
                }
            } elseif ($iklan->merchant_keys || $iklan->merchant_key) {
                $merchantIds = $iklan->merchant_keys ?? [];
                if (empty($merchantIds) && $iklan->merchant_key) {
                    $merchantIds = [$iklan->merchant_key];
                }
                if (!empty($merchantIds)) {
                    $merchantObjs = Merchant::whereIn('id', $merchantIds)->get();
                    foreach ($merchantObjs as $merchant) {
                        $allLocations->push([
                            'type' => 'merchant', 'type_label' => 'merchant',
                            'name' => $merchant->nama_merchant,
                            'slug' => (string) $merchant->id,
                            'display' => 'Merchant/Program',
                            'filter_value' => 'merchant:' . $merchant->id,
                        ]);
                    }
                }
            }
        }

        $hasGeneral = $iklans->whereNull('territorial')->whereNull('regional')
            ->whereNull('branch')->whereNull('cluster')->whereNull('merchant_key')->isNotEmpty();

        $allLocations = $allLocations->unique('filter_value')->sortBy('name')->values();

        $merchants = Merchant::orderBy('nama_merchant')->get()
            ->map(fn($m) => ['id' => $m->id, 'name' => $m->nama_merchant])->values();

        $keywords = Keyword::with('merchant')->whereNotNull('image')->where('image', '!=', '')
            ->orderBy('nama_produk')->get()
            ->map(fn($k) => [
                'id'               => $k->id,
                'nama_produk'      => $k->nama_produk,
                'kategori_keyword' => $k->kategori_keyword,
                'image'            => $k->image,
                'cta_link'         => $k->cta_link,
                'merchant_key'     => $k->merchant_key,
                'merchant_name'    => $k->merchant ? $k->merchant->nama_merchant : null,
            ])->values();

        return view('iklan', compact(
            'iklans', 'territories', 'regions', 'branches', 'clusters',
            'allLocations', 'hasGeneral', 'merchants', 'keywords',
            'isUserMaha', 'userScope', 'allowedLocTypes'
        ));
    }

    /**
     * Store a newly created iklan image.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$this->isSubmittedTerritorialAllowed($request)) {
            return redirect()
                ->back()
                ->withErrors(['territorial' => 'Anda tidak memiliki izin untuk membuat iklan di lokasi ini.'])
                ->withInput();
        }

        $request->validate([
            'upload_type'     => ['required', 'in:manual,keyword'],
            'keyword_id'      => ['required_if:upload_type,keyword', 'nullable', 'exists:keywords,id'],
            'image'           => ['required_if:upload_type,manual', 'nullable', 'image', 'max:2048'],
            'link_iklan'      => ['nullable', 'string', 'max:500'],
            'is_active'       => ['nullable', 'boolean'],
            'territorial'     => ['nullable', 'string'],
            'regional'        => ['nullable', 'string'],
            'branch'          => ['nullable', 'string'],
            'cluster'         => ['nullable', 'string'],
            'merchant_keys'   => ['nullable', 'array'],
            'merchant_keys.*' => ['integer', 'exists:merchants,id'],
        ]);

        $uploadType   = $request->input('upload_type', 'manual');
        $keywordId    = null;
        $path         = null;
        $link         = null;
        $merchantKeys = [];
        $isActive     = $request->boolean('is_active', true);

        if ($uploadType === 'keyword') {
            $keywordId = $request->input('keyword_id');
            $keyword   = Keyword::findOrFail($keywordId);

            if ($keyword->image && Storage::disk('public')->exists($keyword->image)) {
                $extension = pathinfo($keyword->image, PATHINFO_EXTENSION);
                $filename  = 'iklan_keyword_' . $keyword->id . '_' . time() . '.' . $extension;
                $path      = 'iklan/' . $filename;
                Storage::disk('public')->copy($keyword->image, $path);
            } else {
                return redirect()->back()
                    ->withErrors(['keyword_id' => 'Keyword tidak memiliki gambar yang valid.'])
                    ->withInput();
            }

            $link = $keyword->cta_link ?: $request->input('link_iklan');
            if ($keyword->merchant_key) {
                $merchantKeys = [$keyword->merchant_key];
            }
        } else {
            Storage::disk('public')->makeDirectory('iklan');
            $path = $request->file('image')->store('iklan', 'public');
            $link = $request->input('link_iklan');
            $link = is_string($link) ? trim($link) : null;
            if ($link === '') $link = null;

            $merchantInputs = $request->input('merchant_keys', []);
            if (!empty($merchantInputs) && is_array($merchantInputs)) {
                $merchantKeys = array_values(array_filter(array_map('intval', $merchantInputs), fn($id) => $id > 0));
            }
        }

        $territorial = null;
        $regional    = null;
        $branch      = null;
        $cluster     = null;

        $tInput = $request->input('territorial');
        $rInput = $request->input('regional');
        $bInput = $request->input('branch');
        $cInput = $request->input('cluster');

        if (!empty($tInput) && trim($tInput) !== '')      $territorial = territorialSlug(trim($tInput));
        elseif (!empty($rInput) && trim($rInput) !== '')  $regional    = territorialSlugGeneric(trim($rInput));
        elseif (!empty($bInput) && trim($bInput) !== '')  $branch      = territorialSlugGeneric(trim($bInput));
        elseif (!empty($cInput) && trim($cInput) !== '')  $cluster     = territorialSlugGeneric(trim($cInput));

        $newOrder = (Iklan::min('order') ?? 0) - 1;

        try {
            Iklan::create([
                'image_path'    => $path,
                'link_iklan'    => $link,
                'is_active'     => $isActive,
                'territorial'   => $territorial,
                'regional'      => $regional,
                'branch'        => $branch,
                'cluster'       => $cluster,
                'merchant_key'  => !empty($merchantKeys) ? $merchantKeys[0] : null,
                'merchant_keys' => !empty($merchantKeys) ? $merchantKeys : null,
                'keyword_id'    => $keywordId,
                'order'         => $newOrder,
            ]);
        } catch (\Exception $e) {
            if ($path) Storage::disk('public')->delete($path);
            \Illuminate\Support\Facades\Log::error('Iklan store error: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan iklan: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('iklan.index')->with('success', 'Iklan berhasil ditambahkan.');
    }

    /**
     * Update the specified iklan.
     */
    public function update(Request $request, Iklan $iklan): RedirectResponse
    {
        if (!$this->canManageIklan($iklan)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit iklan ini.');
        }

        $request->validate([
            'image'      => ['nullable', 'image', 'max:2048'],
            'link_iklan' => ['nullable', 'string', 'max:500'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $newImagePath = $iklan->image_path;
        if ($request->hasFile('image')) {
            Storage::disk('public')->makeDirectory('iklan');
            $uploadedPath = $request->file('image')->store('iklan', 'public');
            if ($iklan->image_path) Storage::disk('public')->delete($iklan->image_path);
            $newImagePath = $uploadedPath;
        }

        $link = $request->input('link_iklan');
        $link = is_string($link) ? trim($link) : null;
        if ($link === '') $link = null;

        $iklan->update([
            'image_path' => $newImagePath,
            'link_iklan' => $link,
            'is_active'  => $request->boolean('is_active', false),
        ]);

        return redirect()->route('iklan.index')->with('success', 'Iklan berhasil diperbarui.');
    }

    /**
     * Remove the specified iklan — allowed if user can manage it (within territorial scope).
     */
    public function destroy(Iklan $iklan): RedirectResponse
    {
        if (!$this->canManageIklan($iklan)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus iklan ini.');
        }

        if ($iklan->image_path) Storage::disk('public')->delete($iklan->image_path);
        $iklan->delete();

        return redirect()->route('iklan.index')->with('success', 'Iklan berhasil dihapus.');
    }

    /**
     * Update the order of iklans — maha only.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        if (!$this->isUserMaha()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'orders'   => ['required', 'array'],
            'orders.*' => ['required', 'integer', 'exists:iklans,id'],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $order => $id) {
                Iklan::where('id', $id)->update(['order' => $order]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Urutan iklan berhasil diperbarui.']);
    }
}
