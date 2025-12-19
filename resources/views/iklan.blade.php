@extends('layouts.app')


@include('partials.head')


@section('content')
@include('partials.navbar-admin')
<div id="iklanPage" class="min-h-screen bg-white pt-20 md:pt-32 pb-12 opacity-0 transition-opacity duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-row items-center justify-between gap-3 pl-2">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-neutral-800">Landing Page</h1>
                <p class="text-sm text-neutral-500">Atur banner yang tampil pada halaman utama pengguna.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-r from-orange-500 to-rose-500 text-white shadow-md hover:shadow-lg transition flex-shrink-0" title="Lihat Landing Page">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>

        @if (session('success'))
            <div id="successAlert" class="rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm text-green-700 shadow-sm opacity-0 translate-y-2 transition-all duration-500">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-100 px-4 py-3 text-sm text-rose-600 shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-neutral-100">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Tambah Iklan Baru</h2>
                <p class="text-sm text-neutral-500 mb-5">Unggah file gambar dengan format 5:1 aspect ratio (JPG, PNG, maksimal 2 MB). </p>
                <form id="uploadForm" action="{{ route('iklan.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Gambar</span>
                        <input id="imageInput" type="file" name="image" accept="image/*"
                               class="mt-2 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                        <span id="fileError" class="text-xs text-rose-500 mt-2 hidden">Silakan pilih gambar terlebih dahulu.</span>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">CTA Link </span>
                        <input id="linkInput" type="url" name="link_iklan" value="{{ old('link_iklan') }}"
                               placeholder="https://contoh.com/promo"
                               class="mt-2 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100">
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">Target Lokasi <span class="text-xs text-neutral-400 font-normal">(Opsional)</span></span>
                        <div class="mt-2 relative">
                            <select id="locationTypeInput" class="hidden">
                                <option value="general" {{ old('location_type') === 'general' || (!old('location_type') && !old('territorial') && !old('regional') && !old('branch') && !old('cluster') && !old('merchant_key')) ? 'selected' : '' }}>General (Tampil di semua halaman jika tidak ada banner spesifik)</option>
                                <option value="territorial" {{ old('location_type') === 'territorial' ? 'selected' : '' }}>Teritorial</option>
                                <option value="regional" {{ old('location_type') === 'regional' ? 'selected' : '' }}>Regional</option>
                                <option value="branch" {{ old('location_type') === 'branch' ? 'selected' : '' }}>Branch</option>
                                <option value="cluster" {{ old('location_type') === 'cluster' ? 'selected' : '' }}>Cluster</option>
                                <option value="merchant" {{ old('location_type') === 'merchant' ? 'selected' : '' }}>Merchant/Program</option>
                            </select>
                            <button type="button" id="locationTypeBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="locationTypeText">General (Tampil di semua halaman jika tidak ada banner spesifik)</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="locationTypeDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="locationTypeSearch" placeholder="Cari tipe lokasi..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="locationTypeOptions" class="overflow-y-auto max-h-48">
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="general">General (Tampil di semua halaman jika tidak ada banner spesifik)</div>
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="territorial">Teritorial</div>
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="regional">Regional</div>
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="branch">Branch</div>
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="cluster">Cluster</div>
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="merchant">Merchant/Program</div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-neutral-500 mt-1">Pilih General untuk banner default, atau pilih lokasi spesifik. Banner spesifik akan muncul di lokasi tersebut, banner general akan muncul jika tidak ada banner spesifik.</p>
                    </label>
                    <label class="block hidden" id="territorialLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Teritorial</span>
                        <div class="mt-2 relative">
                            <select id="territorialInput" name="territorial" class="hidden">
                                <option value="">-- Pilih Teritorial --</option>
                                @foreach($territories as $territory)
                                    <option value="{{ $territory['slug'] }}" {{ old('territorial') === $territory['slug'] ? 'selected' : '' }}>
                                        {{ $territory['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="territorialBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="territorialText">-- Pilih Teritorial --</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="territorialDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="territorialSearch" placeholder="Cari teritorial..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="territorialOptions" class="overflow-y-auto max-h-48">
                                    <div class="territorial-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="">-- Pilih Teritorial --</div>
                                    @foreach($territories as $territory)
                                        <div class="territorial-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $territory['slug'] }}">{{ $territory['name'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="block hidden" id="regionalLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Regional</span>
                        <div class="mt-2 relative">
                            <select id="regionalInput" name="regional" class="hidden">
                                <option value="">-- Pilih Regional --</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region['slug'] }}" {{ old('regional') === $region['slug'] ? 'selected' : '' }}>
                                        {{ $region['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="regionalBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="regionalText">-- Pilih Regional --</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="regionalDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="regionalSearch" placeholder="Cari regional..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="regionalOptions" class="overflow-y-auto max-h-48">
                                    <div class="regional-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="">-- Pilih Regional --</div>
                                    @foreach($regions as $region)
                                        <div class="regional-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $region['slug'] }}">{{ $region['name'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="block hidden" id="branchLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Branch</span>
                        <div class="mt-2 relative">
                            <select id="branchInput" name="branch" class="hidden">
                                <option value="">-- Pilih Branch --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch['slug'] }}" {{ old('branch') === $branch['slug'] ? 'selected' : '' }}>
                                        {{ $branch['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="branchBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="branchText">-- Pilih Branch --</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="branchDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="branchSearch" placeholder="Cari branch..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="branchOptions" class="overflow-y-auto max-h-48">
                                    <div class="branch-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="">-- Pilih Branch --</div>
                                    @foreach($branches as $branch)
                                        <div class="branch-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $branch['slug'] }}">{{ $branch['name'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="block hidden" id="clusterLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Cluster</span>
                        <div class="mt-2 relative">
                            <select id="clusterInput" name="cluster" class="hidden">
                                <option value="">-- Pilih Cluster --</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster['slug'] }}" {{ old('cluster') === $cluster['slug'] ? 'selected' : '' }}>
                                        {{ $cluster['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="clusterBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="clusterText">-- Pilih Cluster --</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="clusterDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="clusterSearch" placeholder="Cari cluster..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="clusterOptions" class="overflow-y-auto max-h-48">
                                    <div class="cluster-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="">-- Pilih Cluster --</div>
                                    @foreach($clusters as $cluster)
                                        <div class="cluster-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $cluster['slug'] }}">{{ $cluster['name'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="block hidden" id="merchantLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Merchant/Program</span>
                        <div id="selectedMerchantsDisplay" class="mt-2 mb-2 space-y-2">
                            <!-- Selected merchants will be displayed here -->
                        </div>
                        <button type="button" id="openMerchantModalBtn" class="mt-2 w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-orange-600 bg-orange-50 border border-orange-200 rounded-xl hover:bg-orange-100 transition">
                            <i class="fas fa-check-square text-xs"></i>
                            <span>Pilih Merchant/Program</span>
                        </button>
                        <!-- Hidden inputs for form submission -->
                        <div id="merchantHiddenInputs" class="hidden">
                            <!-- Hidden inputs will be added here dynamically -->
                        </div>
                    </label>
                    <button type="button" id="openConfirmModal" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-neutral-900 text-white font-semibold hover:bg-neutral-800 transition">
                        Simpan Iklan
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-neutral-100">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Preview Banner</h2>
                @php
                    $primaryBanner = $iklans->first();
                @endphp
                <div class="relative h-60 rounded-2xl overflow-hidden bg-neutral-100">
                    @if ($iklans->isNotEmpty())
                        <img src="{{ asset('storage/' . $iklans->first()->image_path) }}" alt="Preview Iklan" class="w-full h-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-neutral-500 text-sm font-medium">Belum ada iklan</div>
                    @endif
                </div>
                @if ($primaryBanner)
                    <p class="text-xs text-neutral-500 mt-3">
                        Link banner utama:
                        @if ($primaryBanner->link_iklan)
                            <a href="{{ $primaryBanner->link_iklan }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-orange-600 hover:text-orange-500 break-all">
                                {{ $primaryBanner->link_iklan }}
                            </a>
                        @else
                            <span class="text-neutral-400 font-medium">Belum ditentukan</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800">Daftar Iklan</h2>
                    <p class="text-sm text-neutral-500">
                        Total <span id="totalCount">{{ $iklans->count() }}</span> banner.
                        <span id="filteredCount" class="hidden">Menampilkan <span id="filteredNumber">0</span> banner.</span>
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <label class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-neutral-700 whitespace-nowrap">Filter Lokasi:</span>
                        <div class="relative w-full md:w-64">
                            <input id="locationFilterInput" 
                                   type="text" 
                                   autocomplete="off"
                                   placeholder="Cari atau pilih lokasi..."
                                   class="block w-full rounded-xl border border-neutral-200 px-3 py-2 pr-10 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100"
                                   readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </div>
                            <div id="locationFilterDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-auto">
                                <div class="p-2">
                                    <div class="px-3 py-2 text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Pilih Lokasi</div>
                                    <div class="location-filter-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="" data-display="Semua Lokasi">
                                        <span class="font-medium text-neutral-700">Semua Lokasi</span>
                                    </div>
                                    @if($hasGeneral)
                                    <div class="location-filter-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="general" data-display="General (Semua Lokasi)">
                                        <span class="font-medium text-neutral-700">General (Semua Lokasi)</span>
                                    </div>
                                    @endif
                                    
                                    @php
                                        $merchantLocations = $allLocations->where('type', 'merchant');
                                        $geographicLocations = $allLocations->where('type', '!=', 'merchant');
                                    @endphp
                                    
                                    @if($merchantLocations->isNotEmpty())
                                    <div class="px-3 py-2 text-xs font-semibold text-neutral-500 uppercase tracking-wide mt-2 mb-1 border-t border-neutral-100 pt-2">Merchant/Program</div>
                                    @foreach($merchantLocations as $location)
                                    <div class="location-filter-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $location['filter_value'] }}" data-display="{{ $location['display'] }} - {{ $location['name'] }}">
                                        <span class="font-medium text-neutral-700">{{ $location['display'] }}</span>
                                        <span class="text-neutral-500 ml-2">- {{ $location['name'] }}</span>
                                    </div>
                                    @endforeach
                                    @endif
                                    
                                    @if($geographicLocations->isNotEmpty())
                                    <div class="px-3 py-2 text-xs font-semibold text-neutral-500 uppercase tracking-wide mt-2 mb-1 {{ $merchantLocations->isNotEmpty() ? 'border-t border-neutral-100 pt-2' : '' }}">Lokasi Geografis</div>
                                    @foreach($geographicLocations as $location)
                                    <div class="location-filter-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $location['filter_value'] }}" data-display="{{ $location['display'] }} - {{ $location['name'] }}">
                                        <span class="font-medium text-neutral-700">{{ $location['display'] }}</span>
                                        <span class="text-neutral-500 ml-2">- {{ $location['name'] }}</span>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </label>
                    <button type="button" id="resetFilter" class="hidden px-3 py-2 text-sm font-semibold text-neutral-600 bg-neutral-100 border border-neutral-200 rounded-xl hover:bg-neutral-200 transition">
                        <i class="fas fa-times mr-1"></i>Reset
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100 text-sm">
                    <thead class="text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                        <th class="py-3 text-center w-12 md:w-12 pr-3 md:pr-0"></th>
                        <th class="py-3 px-2 md:px-0 text-left pl-3 md:pl-0">No</th>
                        <th class="py-3 px-2 md:px-0 text-center">Preview</th>
                        <th class="py-3 px-2 md:px-3 text-center">Link</th>
                        <th class="py-3 px-2 md:px-3 text-center">Lokasi</th>
                        <th class="py-3 px-2 md:px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="iklanTableBody" class="divide-y divide-neutral-100">
                        @forelse ($iklans as $iklan)
                            @php
                                $locationType = null;
                                $locationSlug = null;
                                $locationName = null;
                                $locationDisplay = null;
                                $locationRoute = null;
                                
                                if ($iklan->territorial) {
                                    $locationType = 'territorial';
                                    $locationSlug = $iklan->territorial;
                                    $locationName = territorialName($iklan->territorial);
                                    $locationDisplay = 'city/' . $iklan->territorial;
                                    $locationRoute = route('city.show', $iklan->territorial);
                                } elseif ($iklan->regional) {
                                    $locationType = 'regional';
                                    $locationSlug = $iklan->regional;
                                    $locationName = territorialNameGeneric($iklan->regional);
                                    $locationDisplay = 'reg/' . $iklan->regional;
                                    $locationRoute = route('regional.show', $iklan->regional);
                                } elseif ($iklan->branch) {
                                    $locationType = 'branch';
                                    $locationSlug = $iklan->branch;
                                    $locationName = territorialNameGeneric($iklan->branch);
                                    $locationDisplay = 'branch/' . $iklan->branch;
                                    $locationRoute = route('branch.show', $iklan->branch);
                                } elseif ($iklan->cluster) {
                                    $locationType = 'cluster';
                                    $locationSlug = $iklan->cluster;
                                    $locationName = territorialNameGeneric($iklan->cluster);
                                    $locationDisplay = 'cluster/' . $iklan->cluster;
                                    $locationRoute = route('cluster.show', $iklan->cluster);
                                } elseif ($iklan->merchant_keys || $iklan->merchant_key) {
                                    $locationType = 'merchant';
                                    // Get merchants from merchant_keys JSON or fallback to merchant_key
                                    $merchantsList = $iklan->merchants; // Use accessor
                                    
                                    if ($merchantsList->isNotEmpty()) {
                                        $merchantNames = $merchantsList->pluck('nama_merchant')->toArray();
                                        $locationName = implode(', ', $merchantNames);
                                        $merchantIds = $merchantsList->pluck('id')->toArray();
                                        $locationSlug = implode(',', $merchantIds);
                                    } else {
                                        $locationName = 'Merchant';
                                        $locationSlug = '';
                                    }
                                    $locationDisplay = 'Merchant/Program';
                                    $locationRoute = null; // Multiple merchants, no single route
                                } else {
                                    $locationType = 'general';
                                    $locationDisplay = 'General';
                                }
                                
                                $filterValue = $locationType === 'general' ? 'general' : ($locationType . ':' . $locationSlug);
                            @endphp
                            <tr data-iklan-id="{{ $iklan->id }}" 
                                data-location="{{ $filterValue }}"
                                class="cursor-move hover:bg-neutral-50 transition-all duration-300 ease-in-out draggable-row iklan-row">
                                <td class="py-3 text-center pr-3 md:pr-0">
                                    <div class="flex items-center justify-center cursor-grab active:cursor-grabbing">
                                        <i class="fas fa-grip-vertical text-neutral-400 hover:text-neutral-600 transition-colors"></i>
                                    </div>
                                </td>
                                <td class="py-3 px-2 md:px-0 text-left pl-3 md:pl-0">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3 px-2 md:px-0 text-center flex items-center justify-center">
                                    <div class="w-24 md:w-28 h-14 md:h-16 rounded-lg overflow-hidden bg-neutral-100 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $iklan->image_path) }}" alt="Iklan {{ $loop->iteration }}" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="py-3 px-2 md:px-3 text-center text-xs text-neutral-500">
                                    @if ($iklan->link_iklan)
                                        <a href="{{ $iklan->link_iklan }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2 py-1.5 md:px-0 md:py-0 font-semibold text-orange-600 hover:text-orange-500 transition rounded-lg hover:bg-orange-50 md:hover:bg-transparent">
                                            <span>Link</span>
                                            <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                    @else
                                        <span class="text-neutral-400 font-medium">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 md:px-3 text-center text-xs">
                                    @if ($locationType === 'general')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-neutral-100 text-neutral-600 font-medium">
                                            General
                                        </span>
                                    @elseif ($locationType === 'merchant')
                                        @if ($locationRoute)
                                            <a href="{{ $locationRoute }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-pink-50 text-pink-600 hover:bg-pink-100 transition font-medium max-w-[200px] md:max-w-[300px]" title="{{ $locationDisplay }} - {{ $locationName }}">
                                                <span class="truncate">{{ $locationDisplay }} - {{ $locationName }}</span>
                                                <i class="fas fa-external-link-alt text-[10px] flex-shrink-0"></i>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-pink-50 text-pink-600 font-medium max-w-[200px] md:max-w-[300px]" title="{{ $locationDisplay }} - {{ $locationName }}">
                                                <span class="truncate">{{ $locationDisplay }} - {{ $locationName }}</span>
                                            </span>
                                        @endif
                                    @else
                                        <a href="{{ $locationRoute }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg max-w-[200px] md:max-w-[300px]
                                            @if($locationType === 'territorial') bg-orange-50 text-orange-600 hover:bg-orange-100
                                            @elseif($locationType === 'regional') bg-blue-50 text-blue-600 hover:bg-blue-100
                                            @elseif($locationType === 'branch') bg-purple-50 text-purple-600 hover:bg-purple-100
                                            @elseif($locationType === 'cluster') bg-green-50 text-green-600 hover:bg-green-100
                                            @endif transition font-medium" title="{{ $locationDisplay }} - {{ $locationName }}">
                                            <span class="truncate">{{ $locationDisplay }} - {{ $locationName }}</span>
                                            <i class="fas fa-external-link-alt text-[10px] flex-shrink-0"></i>
                                        </a>
                                    @endif
                                </td>
                                <td class="py-3 px-2 md:px-3 text-center">
                                    <form id="deleteForm-{{ $iklan->id }}" action="{{ route('iklan.destroy', $iklan) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-delete-form="deleteForm-{{ $iklan->id }}" class="inline-flex items-center justify-center w-10 h-10 md:w-10 md:h-10 rounded-lg text-rose-600 font-semibold hover:bg-rose-50 transition text-xs deleteTrigger" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="6" class="py-6 text-center text-neutral-500 font-medium">
                                    Belum ada data iklan. Tambahkan gambar melalui form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="uploadConfirmationModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div id="uploadModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-4 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center">
                    <i class="fas fa-cloud-upload-alt text-orange-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Konfirmasi Upload</h3>
                    <p class="text-sm text-neutral-500">Pastikan banner yang dipilih sudah benar.</p>
                </div>
            </div>
            <button type="button" class="text-neutral-400 hover:text-neutral-600 transition" data-close-upload>
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center mb-4">
                <i class="fas fa-image text-2xl text-orange-500"></i>
            </div>
            <h4 class="text-lg font-semibold text-neutral-900 mb-2">Upload Banner Sekarang?</h4>
            <p class="text-sm text-neutral-600">Banner akan langsung tersimpan dan tampil di landing page setelah proses berhasil.</p>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-neutral-50 rounded-b-2xl">
            <button type="button" data-close-upload class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">Batal</button>
            <button type="button" id="confirmUploadBtn" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-rose-500 rounded-lg hover:shadow-lg transition">Upload</button>
        </div>
    </div>
</div>

<div id="deleteConfirmationModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div id="deleteModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-4 rounded-full bg-gradient-to-r from-rose-100 to-red-100 flex items-center justify-center">
                    <i class="fas fa-trash-alt text-rose-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Hapus Iklan</h3>
                    <p class="text-sm text-neutral-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <button type="button" class="text-neutral-400 hover:text-neutral-600 transition" data-close-delete>
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-r from-rose-100 to-red-100 flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-rose-500"></i>
            </div>
            <h4 class="text-lg font-semibold text-neutral-900 mb-2">Yakin hapus banner ini?</h4>
            <p class="text-sm text-neutral-600">Banner akan dihapus dari daftar dan tidak lagi tampil di landing page.</p>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-neutral-50 rounded-b-2xl">
            <button type="button" data-close-delete class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">Batal</button>
            <button type="button" id="confirmDeleteBtn" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-rose-500 to-red-500 rounded-lg hover:shadow-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>

<div id="merchantSelectionModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div id="merchantModalContent" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] transform transition-all duration-300 scale-95 opacity-0 flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-4 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center">
                    <i class="fas fa-check-square text-orange-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Pilih Merchant/Program</h3>
                    <p class="text-sm text-neutral-500">Pilih satu atau lebih merchant/program</p>
                </div>
            </div>
            <button type="button" class="text-neutral-400 hover:text-neutral-600 transition" data-close-merchant-modal>
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div class="mb-4">
                <input type="text" id="merchantSearchInput" placeholder="Cari merchant/program..." class="w-full px-4 py-2 text-sm border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
            </div>
            <div id="merchantChecklistContainer" class="space-y-2">
                <!-- Merchant checkboxes will be added here dynamically -->
            </div>
            <div id="merchantNoResults" class="hidden text-center py-8">
                <p class="text-sm text-neutral-500">Tidak ada merchant/program ditemukan</p>
            </div>
        </div>
        <div class="flex items-center justify-between px-6 py-4 bg-neutral-50 rounded-b-2xl border-t border-neutral-100 flex-shrink-0">
            <div class="text-sm text-neutral-600">
                <span id="selectedCount">0</span> merchant/program dipilih
            </div>
            <div class="flex items-center gap-3">
                <button type="button" data-close-merchant-modal class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">Batal</button>
                <button type="button" id="confirmMerchantSelectionBtn" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-rose-500 rounded-lg hover:shadow-lg transition">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
// Script khusus halaman iklan
// Merchant data for multiple selection
window.merchantsData = {!! json_encode($merchants) !!};

document.addEventListener('DOMContentLoaded', function () {
    const page = document.getElementById('iklanPage');
    if (page) {
        requestAnimationFrame(() => {
            page.classList.remove('opacity-0');
        });
    }

    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.remove('opacity-0', 'translate-y-2');
            setTimeout(() => {
                successAlert.classList.add('opacity-0', 'translate-y-2');
            }, 4000);
        }, 100);
    }

    const imageInput = document.getElementById('imageInput');
    const fileError = document.getElementById('fileError');
    const uploadForm = document.getElementById('uploadForm');
    const uploadModal = document.getElementById('uploadConfirmationModal');
    const uploadModalContent = document.getElementById('uploadModalContent');
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const openConfirmBtn = document.getElementById('openConfirmModal');
    const confirmUploadBtn = document.getElementById('confirmUploadBtn');
    const closeUploadButtons = document.querySelectorAll('[data-close-upload]');
    const deleteButtons = document.querySelectorAll('.deleteTrigger');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const closeDeleteButtons = document.querySelectorAll('[data-close-delete]');
    let pendingDeleteForm = null;

    // Location type dropdown handler (mutually exclusive selection)
    const locationTypeInput = document.getElementById('locationTypeInput');
    const territorialLabel = document.getElementById('territorialLabel');
    const regionalLabel = document.getElementById('regionalLabel');
    const branchLabel = document.getElementById('branchLabel');
    const clusterLabel = document.getElementById('clusterLabel');
    const merchantLabel = document.getElementById('merchantLabel');
    const territorialInput = document.getElementById('territorialInput');
    const regionalInput = document.getElementById('regionalInput');
    const branchInput = document.getElementById('branchInput');
    const clusterInput = document.getElementById('clusterInput');

    // Multiple merchant selection variables (must be declared before showLocationDropdown)
    const merchantsData = window.merchantsData || [];
    const merchantModal = document.getElementById('merchantSelectionModal');
    const merchantModalContent = document.getElementById('merchantModalContent');
    const openMerchantModalBtn = document.getElementById('openMerchantModalBtn');
    const merchantChecklistContainer = document.getElementById('merchantChecklistContainer');
    const merchantSearchInput = document.getElementById('merchantSearchInput');
    const selectedMerchantsDisplay = document.getElementById('selectedMerchantsDisplay');
    const merchantHiddenInputs = document.getElementById('merchantHiddenInputs');
    const confirmMerchantSelectionBtn = document.getElementById('confirmMerchantSelectionBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const closeMerchantModalButtons = document.querySelectorAll('[data-close-merchant-modal]');
    
    let selectedMerchants = []; // Store selected merchant IDs

    function showLocationDropdown(type) {
        // Hide all dropdowns first
        territorialLabel?.classList.add('hidden');
        regionalLabel?.classList.add('hidden');
        branchLabel?.classList.add('hidden');
        clusterLabel?.classList.add('hidden');
        merchantLabel?.classList.add('hidden');
        
        // Reset all values
        if (territorialInput) territorialInput.value = '';
        if (regionalInput) regionalInput.value = '';
        if (branchInput) branchInput.value = '';
        if (clusterInput) clusterInput.value = '';
        
        // Clear merchant selection only if switching away from merchant type
        if (type !== 'merchant') {
            selectedMerchants = [];
            updateMerchantDisplay();
            updateMerchantHiddenInputs();
        }

        // Show selected dropdown (skip if general)
        if (type === 'territorial') {
            territorialLabel?.classList.remove('hidden');
        } else if (type === 'regional') {
            regionalLabel?.classList.remove('hidden');
        } else if (type === 'branch') {
            branchLabel?.classList.remove('hidden');
        } else if (type === 'cluster') {
            clusterLabel?.classList.remove('hidden');
        } else if (type === 'merchant') {
            merchantLabel?.classList.remove('hidden');
        }
        // If type is 'general' or empty, all dropdowns stay hidden
    }

    // Initialize on page load (for old values after validation error)
    if (locationTypeInput) {
        // Check if any old value exists to determine which dropdown to show
        const hasOldTerritorial = territorialInput && territorialInput.value !== '';
        const hasOldRegional = regionalInput && regionalInput.value !== '';
        const hasOldBranch = branchInput && branchInput.value !== '';
        const hasOldCluster = clusterInput && clusterInput.value !== '';
        const hasOldMerchant = document.querySelector('input[name="merchant_keys[]"]') && document.querySelector('input[name="merchant_keys[]"]').value !== '';

        // Determine location type from old values or from locationTypeInput itself
        let locationType = locationTypeInput.value;
        
        if (hasOldTerritorial) {
            locationType = 'territorial';
            locationTypeInput.value = 'territorial';
            showLocationDropdown('territorial');
        } else if (hasOldRegional) {
            locationType = 'regional';
            locationTypeInput.value = 'regional';
            showLocationDropdown('regional');
        } else if (hasOldBranch) {
            locationType = 'branch';
            locationTypeInput.value = 'branch';
            showLocationDropdown('branch');
        } else if (hasOldCluster) {
            locationType = 'cluster';
            locationTypeInput.value = 'cluster';
            showLocationDropdown('cluster');
        } else if (hasOldMerchant) {
            locationType = 'merchant';
            locationTypeInput.value = 'merchant';
            showLocationDropdown('merchant');
        } else if (locationType) {
            // If locationTypeInput has a value but no old values, show that dropdown
            showLocationDropdown(locationType);
        }

        // Handle change event
        locationTypeInput.addEventListener('change', function() {
            showLocationDropdown(this.value);
            // Update button text
            const locationTypeText = document.getElementById('locationTypeText');
            if (locationTypeText) {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption) {
                    locationTypeText.textContent = selectedOption.textContent;
                }
            }
        });
    }

    // Searchable dropdown functions
    function initSearchableDropdown(selectId, buttonId, textId, dropdownId, searchId, optionsId, optionClass) {
        const select = document.getElementById(selectId);
        const button = document.getElementById(buttonId);
        const textSpan = document.getElementById(textId);
        const dropdown = document.getElementById(dropdownId);
        const searchInput = document.getElementById(searchId);
        const optionsContainer = document.getElementById(optionsId);

        if (!select || !button || !textSpan || !dropdown || !searchInput || !optionsContainer) {
            console.warn('Missing elements for dropdown:', { selectId, buttonId, textId, dropdownId, searchId, optionsId });
            return;
        }

        // Initialize text from selected option
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            textSpan.textContent = selectedOption.textContent;
        }

        // Toggle dropdown
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            
            const isHidden = dropdown.classList.contains('hidden');
            
            // Close all other dropdowns first
            closeAllDropdowns();
            
            // Open this dropdown if it was hidden
            if (isHidden) {
                // Set flag to prevent global handler from closing
                justOpenedDropdown = button;
                
                // Use setTimeout with delay to ensure closeAllDropdowns completes
                setTimeout(() => {
                    dropdown.classList.remove('hidden');
                    searchInput.value = '';
                    filterOptions(searchInput.value, optionsContainer, optionClass);
                    setTimeout(() => {
                        searchInput.focus();
                    }, 50);
                }, 50);
            }
        }, true); // Use capture phase to ensure this runs before global handler

        // Prevent dropdown from closing when clicking inside
        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Search functionality
        searchInput.addEventListener('input', (e) => {
            e.stopPropagation();
            filterOptions(e.target.value, optionsContainer, optionClass);
        });

        // Option selection
        optionsContainer.addEventListener('click', (e) => {
            e.stopPropagation();
            const option = e.target.closest('.' + optionClass);
            if (!option) return;
            
            const value = option.getAttribute('data-value');
            const text = option.textContent.trim();
            
            select.value = value;
            textSpan.textContent = text;
            dropdown.classList.add('hidden');
            
            // Trigger change event
            select.dispatchEvent(new Event('change', { bubbles: true }));
            
            // Trigger change event for location type
            if (selectId === 'locationTypeInput') {
                showLocationDropdown(value);
            }
        });
    }

    // Flag to prevent closing dropdown that was just opened
    let justOpenedDropdown = null;
    
    // Global click handler to close all dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        // Check if click is on any dropdown button or inside any dropdown
        const locationTypeBtn = document.getElementById('locationTypeBtn');
        const locationTypeDropdown = document.getElementById('locationTypeDropdown');
        const territorialBtn = document.getElementById('territorialBtn');
        const territorialDropdown = document.getElementById('territorialDropdown');
        const regionalBtn = document.getElementById('regionalBtn');
        const regionalDropdown = document.getElementById('regionalDropdown');
        const branchBtn = document.getElementById('branchBtn');
        const branchDropdown = document.getElementById('branchDropdown');
        const clusterBtn = document.getElementById('clusterBtn');
        const clusterDropdown = document.getElementById('clusterDropdown');
        
        let clickedInside = false;
        
        // Check location type dropdown
        if (locationTypeBtn && locationTypeBtn.contains(e.target)) {
            clickedInside = true;
        }
        if (locationTypeDropdown && locationTypeDropdown.contains(e.target)) {
            clickedInside = true;
        }
        
        // Check other location dropdowns
        if ((territorialBtn && territorialBtn.contains(e.target)) || 
            (territorialDropdown && territorialDropdown.contains(e.target)) ||
            (regionalBtn && regionalBtn.contains(e.target)) || 
            (regionalDropdown && regionalDropdown.contains(e.target)) ||
            (branchBtn && branchBtn.contains(e.target)) || 
            (branchDropdown && branchDropdown.contains(e.target)) ||
            (clusterBtn && clusterBtn.contains(e.target)) || 
            (clusterDropdown && clusterDropdown.contains(e.target))) {
            clickedInside = true;
        }
        
        // Check merchant modal
        if (merchantModal && merchantModal.contains(e.target)) {
            clickedInside = true;
        }
        
        // Only close if clicked outside all dropdowns
        // Don't close if the click was on a button that opens a dropdown
        if (!clickedInside) {
            // Check if any dropdown button was clicked (they handle their own opening)
            const allDropdownButtons = document.querySelectorAll('[id$="Btn"]');
            let isDropdownButton = false;
            allDropdownButtons.forEach(btn => {
                if (btn.contains(e.target)) {
                    isDropdownButton = true;
                }
            });
            
            // Don't close if we just opened a dropdown
            if (!isDropdownButton && justOpenedDropdown !== e.target) {
                closeAllDropdowns();
            }
        }
        
        // Reset flag after a short delay
        if (justOpenedDropdown) {
            setTimeout(() => {
                justOpenedDropdown = null;
            }, 100);
        }
    });

    function filterOptions(searchTerm, container, optionClass) {
        const term = searchTerm.toLowerCase().trim();
        const options = container.querySelectorAll('.' + optionClass);
        let visibleCount = 0;

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            // Also check data-display attribute if available
            const displayText = option.getAttribute('data-display');
            const searchText = displayText ? displayText.toLowerCase() : text;
            
            if (term === '' || text.includes(term) || searchText.includes(term)) {
                option.style.display = '';
                visibleCount++;
            } else {
                option.style.display = 'none';
            }
        });

        // Show/hide no results message
        let noResults = container.querySelector('.no-results-msg');
        if (visibleCount === 0 && term !== '') {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'no-results-msg px-3 py-2 text-sm text-neutral-500 text-center';
                noResults.textContent = 'Tidak ada hasil';
                container.appendChild(noResults);
            }
            noResults.style.display = '';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }

    function closeAllDropdowns() {
        ['locationTypeDropdown', 'territorialDropdown', 'regionalDropdown', 'branchDropdown', 'clusterDropdown'].forEach(id => {
            const dropdown = document.getElementById(id);
            if (dropdown) dropdown.classList.add('hidden');
        });
    }

    // Initialize all searchable dropdowns
    initSearchableDropdown('locationTypeInput', 'locationTypeBtn', 'locationTypeText', 'locationTypeDropdown', 'locationTypeSearch', 'locationTypeOptions', 'location-type-option');
    initSearchableDropdown('territorialInput', 'territorialBtn', 'territorialText', 'territorialDropdown', 'territorialSearch', 'territorialOptions', 'territorial-option');
    initSearchableDropdown('regionalInput', 'regionalBtn', 'regionalText', 'regionalDropdown', 'regionalSearch', 'regionalOptions', 'regional-option');
    initSearchableDropdown('branchInput', 'branchBtn', 'branchText', 'branchDropdown', 'branchSearch', 'branchOptions', 'branch-option');
    initSearchableDropdown('clusterInput', 'clusterBtn', 'clusterText', 'clusterDropdown', 'clusterSearch', 'clusterOptions', 'cluster-option');
    
    // Merchant modal functionality
    function renderMerchantChecklist(searchTerm = '') {
        if (!merchantChecklistContainer) return;
        
        const term = searchTerm.toLowerCase().trim();
        const merchantNoResults = document.getElementById('merchantNoResults');
        let visibleCount = 0;
        
        merchantChecklistContainer.innerHTML = '';
        
        merchantsData.forEach(merchant => {
            const merchantName = merchant.name.toLowerCase();
            const isVisible = term === '' || merchantName.includes(term);
            
            if (isVisible) {
                visibleCount++;
                const isChecked = selectedMerchants.includes(merchant.id);
                const checkboxItem = document.createElement('label');
                checkboxItem.className = 'flex items-center gap-3 p-3 rounded-xl border border-neutral-200 hover:bg-neutral-50 hover:border-orange-300 cursor-pointer transition';
                checkboxItem.innerHTML = `
                    <input type="checkbox" 
                           class="w-5 h-5 text-orange-600 border-neutral-300 rounded focus:ring-orange-500 focus:ring-2 cursor-pointer" 
                           value="${merchant.id}"
                           ${isChecked ? 'checked' : ''}>
                    <span class="flex-1 text-sm font-medium text-neutral-700">${merchant.name}</span>
                `;
                
                checkboxItem.querySelector('input').addEventListener('change', function() {
                    if (this.checked) {
                        if (!selectedMerchants.includes(merchant.id)) {
                            selectedMerchants.push(merchant.id);
                        }
                    } else {
                        selectedMerchants = selectedMerchants.filter(id => id !== merchant.id);
                    }
                    updateSelectedCount();
                });
                
                merchantChecklistContainer.appendChild(checkboxItem);
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0 && term !== '') {
            if (merchantNoResults) {
                merchantNoResults.classList.remove('hidden');
            }
        } else {
            if (merchantNoResults) {
                merchantNoResults.classList.add('hidden');
            }
        }
    }
    
    function updateSelectedCount() {
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedMerchants.length;
        }
    }
    
    function updateMerchantDisplay() {
        if (!selectedMerchantsDisplay) return;
        
        selectedMerchantsDisplay.innerHTML = '';
        
        if (selectedMerchants.length === 0) {
            return;
        }
        
        selectedMerchants.forEach(merchantId => {
            const merchant = merchantsData.find(m => m.id == merchantId);
            if (merchant) {
                const merchantTag = document.createElement('div');
                merchantTag.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-orange-50 border border-orange-200 text-sm text-orange-700';
                merchantTag.innerHTML = `
                    <span>${merchant.name}</span>
                    <button type="button" class="remove-merchant-tag text-orange-600 hover:text-orange-800" data-merchant-id="${merchant.id}">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                
                merchantTag.querySelector('.remove-merchant-tag').addEventListener('click', function() {
                    selectedMerchants = selectedMerchants.filter(id => id != merchantId);
                    updateMerchantDisplay();
                    updateMerchantHiddenInputs();
                    updateSelectedCount();
                });
                
                selectedMerchantsDisplay.appendChild(merchantTag);
            }
        });
    }
    
    function updateMerchantHiddenInputs() {
        if (!merchantHiddenInputs) return;
        
        merchantHiddenInputs.innerHTML = '';
        
        selectedMerchants.forEach(merchantId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'merchant_keys[]';
            input.value = merchantId;
            merchantHiddenInputs.appendChild(input);
        });
    }
    
    function openMerchantModal() {
        if (!merchantModal || !merchantModalContent) return;
        
        // Render checklist with current selections
        renderMerchantChecklist(merchantSearchInput?.value || '');
        updateSelectedCount();
        
        merchantModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            merchantModalContent.classList.remove('scale-95', 'opacity-0');
            merchantModalContent.classList.add('scale-100', 'opacity-100');
        });
    }
    
    function closeMerchantModal() {
        if (!merchantModal || !merchantModalContent) return;
        
        merchantModalContent.classList.remove('scale-100', 'opacity-100');
        merchantModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            merchantModal.classList.add('hidden');
            if (merchantSearchInput) {
                merchantSearchInput.value = '';
            }
        }, 250);
    }
    
    // Event listeners for merchant modal
    if (openMerchantModalBtn) {
        openMerchantModalBtn.addEventListener('click', openMerchantModal);
    }
    
    if (confirmMerchantSelectionBtn) {
        confirmMerchantSelectionBtn.addEventListener('click', () => {
            updateMerchantDisplay();
            updateMerchantHiddenInputs();
            closeMerchantModal();
        });
    }
    
    closeMerchantModalButtons.forEach(btn => {
        btn.addEventListener('click', closeMerchantModal);
    });
    
    if (merchantModal) {
        merchantModal.addEventListener('click', (event) => {
            if (event.target === merchantModal) {
                closeMerchantModal();
            }
        });
    }
    
    if (merchantSearchInput) {
        merchantSearchInput.addEventListener('input', (e) => {
            renderMerchantChecklist(e.target.value);
        });
    }
    
    // Initialize merchant display on page load if there are old values
    const oldMerchantInputs = document.querySelectorAll('input[name="merchant_keys[]"]');
    if (oldMerchantInputs.length > 0) {
        oldMerchantInputs.forEach(input => {
            if (input.value) {
                selectedMerchants.push(parseInt(input.value));
            }
        });
        updateMerchantDisplay();
        updateMerchantHiddenInputs();
    }

    // Update button text on page load for location-specific dropdowns
    function updateDropdownTexts() {
        const territorialSelect = document.getElementById('territorialInput');
        const territorialText = document.getElementById('territorialText');
        if (territorialSelect && territorialText && territorialSelect.value) {
            const selectedOption = territorialSelect.options[territorialSelect.selectedIndex];
            if (selectedOption) territorialText.textContent = selectedOption.textContent;
        }

        const regionalSelect = document.getElementById('regionalInput');
        const regionalText = document.getElementById('regionalText');
        if (regionalSelect && regionalText && regionalSelect.value) {
            const selectedOption = regionalSelect.options[regionalSelect.selectedIndex];
            if (selectedOption) regionalText.textContent = selectedOption.textContent;
        }

        const branchSelect = document.getElementById('branchInput');
        const branchText = document.getElementById('branchText');
        if (branchSelect && branchText && branchSelect.value) {
            const selectedOption = branchSelect.options[branchSelect.selectedIndex];
            if (selectedOption) branchText.textContent = selectedOption.textContent;
        }

        const clusterSelect = document.getElementById('clusterInput');
        const clusterText = document.getElementById('clusterText');
        if (clusterSelect && clusterText && clusterSelect.value) {
            const selectedOption = clusterSelect.options[clusterSelect.selectedIndex];
            if (selectedOption) clusterText.textContent = selectedOption.textContent;
        }

    }
    updateDropdownTexts();

    function openModal(modal, content) {
        if (!modal || !content) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal(modal, content) {
        if (!modal || !content) return;
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 250);
    }

    if (openConfirmBtn) {
        openConfirmBtn.addEventListener('click', () => {
            if (!imageInput || !imageInput.files.length) {
                fileError.classList.remove('hidden');
                return;
            }
            fileError.classList.add('hidden');
            openModal(uploadModal, uploadModalContent);
        });
    }

    closeUploadButtons.forEach(btn => {
        btn.addEventListener('click', () => closeModal(uploadModal, uploadModalContent));
    });

    if (uploadModal) {
        uploadModal.addEventListener('click', (event) => {
            if (event.target === uploadModal) {
                closeModal(uploadModal, uploadModalContent);
            }
        });
    }

    if (confirmUploadBtn) {
        confirmUploadBtn.addEventListener('click', () => {
            // Clear hidden location fields before submit to ensure only one is sent
            const locationType = locationTypeInput?.value;
            if (locationType !== 'territorial' && territorialInput) {
                territorialInput.value = '';
            }
            if (locationType !== 'regional' && regionalInput) {
                regionalInput.value = '';
            }
            if (locationType !== 'branch' && branchInput) {
                branchInput.value = '';
            }
            if (locationType !== 'cluster' && clusterInput) {
                clusterInput.value = '';
            }
            if (locationType !== 'merchant') {
                selectedMerchants = [];
                updateMerchantDisplay();
                updateMerchantHiddenInputs();
            }
            // If general or no location type selected, clear all
            if (!locationType || locationType === '' || locationType === 'general') {
                if (territorialInput) territorialInput.value = '';
                if (regionalInput) regionalInput.value = '';
                if (branchInput) branchInput.value = '';
                if (clusterInput) clusterInput.value = '';
                selectedMerchants = [];
                updateMerchantDisplay();
                updateMerchantHiddenInputs();
            }
            
            closeModal(uploadModal, uploadModalContent);
            uploadForm.submit();
        });
    }

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const formId = button.getAttribute('data-delete-form');
            pendingDeleteForm = document.getElementById(formId);
            openModal(deleteModal, deleteModalContent);
        });
    });

    confirmDeleteBtn?.addEventListener('click', () => {
        if (pendingDeleteForm) {
            closeModal(deleteModal, deleteModalContent);
            pendingDeleteForm.submit();
        }
    });

    closeDeleteButtons.forEach(btn => {
        btn.addEventListener('click', () => closeModal(deleteModal, deleteModalContent));
    });

    if (deleteModal) {
        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeModal(deleteModal, deleteModalContent);
            }
        });
    }

    // Drag and Drop functionality untuk reorder iklan
    const tableBody = document.getElementById('iklanTableBody');
    if (tableBody) {
        let draggedRow = null;
        let draggedOverRow = null;

        const rows = tableBody.querySelectorAll('.draggable-row');
        
        rows.forEach(row => {
            // Make row draggable
            row.setAttribute('draggable', 'true');
            
            row.addEventListener('dragstart', function(e) {
                draggedRow = this;
                this.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            });

            row.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                rows.forEach(r => {
                    r.classList.remove('border-t-2', 'border-orange-500');
                });
            });

            row.addEventListener('dragover', function(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                
                if (draggedRow && this !== draggedRow) {
                    rows.forEach(r => {
                        r.classList.remove('border-t-2', 'border-orange-500');
                    });
                    this.classList.add('border-t-2', 'border-orange-500');
                    draggedOverRow = this;
                }
                return false;
            });

            row.addEventListener('dragleave', function(e) {
                this.classList.remove('border-t-2', 'border-orange-500');
            });

            row.addEventListener('drop', function(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }

                if (draggedRow && this !== draggedRow) {
                    const allRows = Array.from(tableBody.querySelectorAll('.draggable-row'));
                    const draggedIndex = allRows.indexOf(draggedRow);
                    const targetIndex = allRows.indexOf(this);

                    if (draggedIndex < targetIndex) {
                        tableBody.insertBefore(draggedRow, this.nextSibling);
                    } else {
                        tableBody.insertBefore(draggedRow, this);
                    }

                    // Update nomor urut
                    updateRowNumbers();
                    
                    // Save order to server
                    saveOrder();
                }

                rows.forEach(r => {
                    r.classList.remove('border-t-2', 'border-orange-500');
                });

                return false;
            });
        });

        function updateRowNumbers() {
            const rows = Array.from(tableBody.querySelectorAll('.draggable-row')).filter(row => {
                return row.style.display !== 'none';
            });
            rows.forEach((row, index) => {
                const noCell = row.querySelector('td:nth-child(2)');
                if (noCell) {
                    noCell.textContent = index + 1;
                }
            });
        }

        function saveOrder() {
            // Get all rows in current DOM order (including hidden ones)
            // This ensures order is saved globally, not just for visible rows
            const rows = tableBody.querySelectorAll('.draggable-row');
            const orders = Array.from(rows).map(row => {
                return parseInt(row.getAttribute('data-iklan-id'));
            });

            fetch('{{ route("iklan.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optional: Show success message
                    console.log('Urutan berhasil diperbarui');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert on error
                location.reload();
            });
        }
    }

    // Filter functionality untuk lokasi (searchable dropdown)
    const locationFilterInput = document.getElementById('locationFilterInput');
    const locationFilterDropdown = document.getElementById('locationFilterDropdown');
    const resetFilterBtn = document.getElementById('resetFilter');
    const totalCount = document.getElementById('totalCount');
    const filteredCount = document.getElementById('filteredCount');
    const filteredNumber = document.getElementById('filteredNumber');
    const iklanRows = document.querySelectorAll('.iklan-row');
    let currentFilterValue = '';

    // Get all location options
    const locationOptions = Array.from(document.querySelectorAll('.location-filter-option')).map(option => ({
        element: option,
        value: option.getAttribute('data-value'),
        display: option.getAttribute('data-display') || option.textContent.trim(),
        text: option.textContent.trim()
    }));

    function renderLocationOptions(filter = '') {
        const f = filter.trim().toLowerCase();
        locationOptions.forEach(option => {
            // Search in both display text and name
            const displayText = option.display.toLowerCase();
            const optionText = option.text.toLowerCase();
            const matches = f === '' || displayText.includes(f) || optionText.includes(f);
            option.element.style.display = matches ? 'block' : 'none';
        });
        
        // Show "No results" if no matches
        const visibleOptions = locationOptions.filter(opt => opt.element.style.display !== 'none');
        let noResultsMsg = locationFilterDropdown.querySelector('.no-results-msg');
        if (visibleOptions.length === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'no-results-msg px-3 py-2 text-sm text-neutral-500';
                noResultsMsg.textContent = 'Tidak ada hasil';
                locationFilterDropdown.querySelector('.p-2').appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }

    function openLocationDropdown() {
        if (locationFilterDropdown) {
            locationFilterDropdown.classList.remove('hidden');
            renderLocationOptions(locationFilterInput.value);
        }
    }

    function closeLocationDropdown() {
        if (locationFilterDropdown) {
            locationFilterDropdown.classList.add('hidden');
        }
    }

    function setLocationFilter(value, displayText) {
        currentFilterValue = value;
        if (locationFilterInput) {
            if (value === '') {
                // Jika reset, kosongkan input
                locationFilterInput.value = '';
            } else {
                locationFilterInput.value = displayText || (value === 'general' ? 'General (Semua Lokasi)' : '');
            }
            locationFilterInput.setAttribute('readonly', 'readonly');
        }
        applyFilters();
        closeLocationDropdown();
    }

    function applyFilters() {
        let visibleCount = 0;
        let hasActiveFilter = false;
        const rowsToShow = [];
        const rowsToHide = [];
        
        // First pass: determine which rows to show/hide
        iklanRows.forEach((row) => {
            const rowLocation = row.getAttribute('data-location');
            
            if (currentFilterValue === '') {
                rowsToShow.push(row);
                visibleCount++;
            } else {
                const shouldShow = rowLocation === currentFilterValue;
                if (shouldShow) {
                    rowsToShow.push(row);
                    visibleCount++;
                } else {
                    rowsToHide.push(row);
                }
            }
        });

        // Hide rows with fade out animation
        rowsToHide.forEach((row) => {
            if (row.style.display !== 'none') {
                row.style.transition = 'opacity 0.2s ease-in-out, transform 0.2s ease-in-out';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    row.style.display = 'none';
                }, 200);
            }
        });

        // Show rows with fade in animation (staggered)
        rowsToShow.forEach((row, index) => {
            if (row.style.display === 'none') {
                row.style.display = '';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
                
                setTimeout(() => {
                    requestAnimationFrame(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    });
                }, index * 15); // Stagger animation
            } else {
                // Already visible, just ensure it's fully visible
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }
        });

        // Check if any filter is active
        hasActiveFilter = currentFilterValue !== '';

        // Update counter
        if (!hasActiveFilter) {
            totalCount.textContent = iklanRows.length;
            filteredCount.classList.add('hidden');
            resetFilterBtn.classList.add('hidden');
        } else {
            filteredNumber.textContent = visibleCount;
            filteredCount.classList.remove('hidden');
            resetFilterBtn.classList.remove('hidden');
        }

        // Update row numbers after filtering (with slight delay for animation)
        setTimeout(() => {
            if (tableBody) {
                updateRowNumbers();
            }
        }, 300);
    }

    // Location filter input events
    if (locationFilterInput) {
        locationFilterInput.addEventListener('focus', () => {
            openLocationDropdown();
        });

        locationFilterInput.addEventListener('click', () => {
            openLocationDropdown();
        });

        locationFilterInput.addEventListener('input', (e) => {
            e.preventDefault();
            e.stopPropagation();
            renderLocationOptions(e.target.value);
            openLocationDropdown();
        });

        // Prevent form submission on Enter key
        locationFilterInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // Make input editable for search
        locationFilterInput.addEventListener('mousedown', (e) => {
            e.preventDefault();
            locationFilterInput.removeAttribute('readonly');
            locationFilterInput.focus();
        });
    }

    // Location filter dropdown click events
    if (locationFilterDropdown) {
        locationFilterDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            const option = e.target.closest('.location-filter-option');
            if (!option) return;
            
            e.preventDefault();
            const value = option.getAttribute('data-value');
            const display = option.getAttribute('data-display') || option.textContent.trim();
            setLocationFilter(value, display);
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (locationFilterInput && locationFilterDropdown) {
            if (!locationFilterInput.contains(e.target) && !locationFilterDropdown.contains(e.target)) {
                closeLocationDropdown();
                // Restore readonly only if input has a value (not empty)
                if (locationFilterInput.value && locationFilterInput.value.trim() !== '') {
                    locationFilterInput.setAttribute('readonly', 'readonly');
                } else {
                    // If empty, remove readonly so user can click to filter again
                    locationFilterInput.removeAttribute('readonly');
                }
            }
        }
    });

    // Reset filter button
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            setLocationFilter('', '');
            if (locationFilterInput) {
                // Remove readonly so user can click to filter again
                locationFilterInput.removeAttribute('readonly');
            }
        });
    }

    // Initialize all rows to be visible on page load
    iklanRows.forEach(row => {
        row.style.opacity = '1';
        row.style.transform = 'translateY(0)';
        row.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
    });
});

// Dropdown user (desktop) – sama seperti di halaman admin
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const arrow = document.getElementById('userDropdownArrow');
    if (!dropdown) return;

    if (dropdown.classList.contains('opacity-0')) {
        dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.add('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(event) {
    const btn = document.getElementById('userDropdownBtn');
    const dropdown = document.getElementById('userDropdown');
    if (!btn || !dropdown) return;

    if (!btn.contains(event.target) && !dropdown.contains(event.target) && dropdown.classList.contains('opacity-100')) {
        toggleUserDropdown();
    }
});
</script>
@endsection