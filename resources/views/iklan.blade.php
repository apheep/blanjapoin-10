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

        @php
            $canAddIklan = $isUserMaha || ($userScope['type'] !== 'none');
        @endphp

        @if(!$isUserMaha)
        <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 flex items-start gap-3">
            <i class="fas fa-shield-alt text-amber-500 mt-0.5 flex-shrink-0"></i>
            <div class="text-sm text-amber-800">
                <span class="font-semibold">Akses Terbatas.</span>
                Anda hanya dapat mengelola iklan sesuai teritorial Anda
                @if($userScope['type'] === 'regional') <span class="font-semibold">(Regional: {{ $userScope['value'] }})</span>
                @elseif($userScope['type'] === 'branch') <span class="font-semibold">(Branch: {{ $userScope['value'] }})</span>
                @elseif($userScope['type'] === 'city') <span class="font-semibold">(Kota: {{ $userScope['value'] }})</span>
                @elseif($userScope['type'] === 'area') <span class="font-semibold">(Area)</span>
                @elseif($userScope['type'] === 'national') <span class="font-semibold">(Semua wilayah – non-general)</span>
                @elseif($userScope['type'] === 'none') <span class="font-semibold text-rose-700">(Wilayah tidak diatur, hubungi admin)</span>
                @endif
            </div>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-neutral-100">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Tambah Iklan Baru</h2>
                <p class="text-sm text-neutral-500 mb-5">Unggah file gambar dengan format 5:1 aspect ratio (JPG, PNG, maksimal 2 MB). </p>
                @if(!$canAddIklan)
                <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-4 flex items-center gap-3">
                    <i class="fas fa-lock text-rose-400"></i>
                    <p class="text-sm text-rose-700 font-medium">Wilayah Anda belum diatur. Hubungi Admin Pusat untuk mengatur teritorial akun Anda.</p>
                </div>
                @else
                <form id="uploadForm" action="{{ route('iklan.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    {{-- ── LANGKAH 1: Target Lokasi ─────────────────────────────── --}}
                    <div class="flex items-center gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">1</span>
                        <span class="text-sm font-semibold text-neutral-700">Target Lokasi
                            @if(!$isUserMaha)
                                <span class="text-xs font-normal text-amber-600 ml-1"><i class="fas fa-lock text-xs mr-0.5"></i>Dibatasi sesuai teritorial Anda</span>
                            @else
                                <span class="text-xs text-neutral-400 font-normal ml-1">(Opsional — pilih General untuk semua halaman)</span>
                            @endif
                        </span>
                    </div>

                    <div class="relative">
                        <select id="locationTypeInput" class="hidden">
                            @foreach(['general'=>'General (Tampil di semua halaman jika tidak ada banner spesifik)','territorial'=>'Teritorial','regional'=>'Regional','branch'=>'Branch','cluster'=>'Cluster','merchant'=>'Merchant/Program'] as $locVal => $locLabel)
                                @if(in_array($locVal, $allowedLocTypes))
                                    <option value="{{ $locVal }}"
                                        {{ old('location_type') === $locVal ? 'selected'
                                           : ($locVal === 'general' && $isUserMaha && !old('location_type') && !old('territorial') && !old('regional') && !old('branch') && !old('cluster') && !old('merchant_key') ? 'selected' : '') }}>
                                        {{ $locLabel }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" id="locationTypeBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                            <span id="locationTypeText">
                                @if($isUserMaha)
                                    General (Tampil di semua halaman jika tidak ada banner spesifik)
                                @elseif(in_array('territorial', $allowedLocTypes) && count($allowedLocTypes) === 1)
                                    Teritorial
                                @elseif($allowedLocTypes[0] ?? false)
                                    {{ ['general'=>'General','territorial'=>'Teritorial','regional'=>'Regional','branch'=>'Branch','cluster'=>'Cluster','merchant'=>'Merchant/Program'][$allowedLocTypes[0]] ?? '-- Pilih Tipe Lokasi --' }}
                                @else
                                    -- Pilih Tipe Lokasi --
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                        </button>
                        <div id="locationTypeDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                            <div class="p-2 border-b border-neutral-100">
                                <input type="text" id="locationTypeSearch" placeholder="Cari tipe lokasi..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                            </div>
                            <div id="locationTypeOptions" class="overflow-y-auto max-h-48">
                                @foreach(['general'=>'General (Tampil di semua halaman jika tidak ada banner spesifik)','territorial'=>'Teritorial','regional'=>'Regional','branch'=>'Branch','cluster'=>'Cluster','merchant'=>'Merchant/Program'] as $locVal => $locLabel)
                                    @if(in_array($locVal, $allowedLocTypes))
                                    <div class="location-type-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $locVal }}">{{ $locLabel }}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Spesifik: Territorial --}}
                    <label class="block hidden" id="territorialLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Teritorial (Kota/Kabupaten)</span>
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

                    {{-- Spesifik: Regional --}}
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
                    {{-- Apply Scope: All Regional --}}
                    <div id="applyScopeRegionalWrap" class="hidden">
                        <label class="inline-flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 w-full cursor-pointer hover:bg-blue-100 transition">
                            <input type="checkbox" id="applyScopeAllRegional" name="apply_scope" value="all_regional"
                                   class="mt-0.5 h-4 w-4 rounded border-neutral-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-semibold text-blue-800">Terapkan ke semua link di Regional ini</span>
                                <p class="text-xs text-blue-600 mt-0.5">Banner akan tampil di semua branch, city, dan program dalam regional ini yang belum punya banner spesifik.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Spesifik: Branch --}}
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
                    {{-- Apply Scope: All Branch --}}
                    <div id="applyScopeBranchWrap" class="hidden">
                        <label class="inline-flex items-start gap-3 rounded-xl border border-purple-200 bg-purple-50 px-4 py-3 w-full cursor-pointer hover:bg-purple-100 transition">
                            <input type="checkbox" id="applyScopeAllBranch" name="apply_scope" value="all_branch"
                                   class="mt-0.5 h-4 w-4 rounded border-neutral-300 text-purple-600 focus:ring-purple-500">
                            <div>
                                <span class="text-sm font-semibold text-purple-800">Terapkan ke semua link di Branch ini</span>
                                <p class="text-xs text-purple-600 mt-0.5">Banner akan tampil di semua city dan program dalam branch ini yang belum punya banner spesifik.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Spesifik: Cluster --}}
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

                    {{-- Spesifik: Merchant/Program --}}
                    <label class="block hidden" id="merchantLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Merchant/Program</span>
                        <div id="selectedMerchantsDisplay" class="mt-2 mb-2 space-y-2"></div>
                        <button type="button" id="openMerchantModalBtn" class="mt-2 w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-orange-600 bg-orange-50 border border-orange-200 rounded-xl hover:bg-orange-100 transition">
                            <i class="fas fa-check-square text-xs"></i>
                            <span>Pilih Merchant/Program</span>
                        </button>
                        <div id="merchantHiddenInputs" class="hidden"></div>
                    </label>

                    <hr class="border-neutral-100">

                    {{-- ── LANGKAH 2: Sumber Banner ─────────────────────────────── --}}
                    <div class="flex items-center gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">2</span>
                        <span class="text-sm font-semibold text-neutral-700">Sumber Banner</span>
                    </div>

                    <input type="hidden" name="upload_type" id="uploadTypeInput" value="{{ old('upload_type', 'manual') }}">
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" id="manualUploadBtn" class="upload-type-btn px-4 py-3 rounded-xl border-2 border-orange-500 bg-orange-50 text-orange-700 font-semibold text-sm hover:bg-orange-100 transition flex items-center justify-center gap-2">
                            <i class="fas fa-upload"></i>
                            <span>Upload Manual</span>
                        </button>
                        <button type="button" id="keywordUploadBtn" class="upload-type-btn px-4 py-3 rounded-xl border-2 border-neutral-200 bg-white text-neutral-600 font-semibold text-sm hover:bg-neutral-50 transition flex items-center justify-center gap-2">
                            <i class="fas fa-tag"></i>
                            <span>Dari Keyword</span>
                        </button>
                    </div>

                    {{-- Program (muncul saat "Dari Keyword", difilter berdasarkan lokasi) --}}
                    <label class="block hidden" id="keywordMerchantLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Program <span class="text-xs font-normal text-neutral-400 ml-1">(sesuai lokasi yang dipilih)</span></span>
                        <div class="mt-2 relative">
                            <select id="keywordMerchantInput" class="hidden">
                                <option value="">-- Pilih Program --</option>
                                @foreach($merchants as $merchant)
                                    <option value="{{ $merchant['id'] }}">{{ $merchant['name'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="keywordMerchantBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="keywordMerchantText">-- Pilih Program --</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="keywordMerchantDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="keywordMerchantSearch" placeholder="Cari program..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="keywordMerchantOptions" class="overflow-y-auto max-h-48">
                                    <div class="keyword-merchant-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="">-- Pilih Program --</div>
                                    @foreach($merchants as $merchant)
                                        <div class="keyword-merchant-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="{{ $merchant['id'] }}">{{ $merchant['name'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-neutral-500 mt-1">Hanya program di lokasi yang dipilih yang ditampilkan.</p>
                    </label>

                    {{-- Keyword (muncul setelah program dipilih) --}}
                    <label class="block hidden" id="keywordSelectionLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Keyword</span>
                        <div class="mt-2 relative">
                            <select id="keywordInput" name="keyword_id" class="hidden">
                                <option value="">-- Pilih Keyword --</option>
                            </select>
                            <button type="button" id="keywordBtn" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 text-left flex items-center justify-between bg-white hover:border-neutral-300 transition">
                                <span id="keywordText">-- Pilih Program Terlebih Dahulu --</span>
                                <i class="fas fa-chevron-down text-neutral-400 text-xs"></i>
                            </button>
                            <div id="keywordDropdown" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-neutral-100">
                                    <input type="text" id="keywordSearch" placeholder="Cari keyword..." class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400">
                                </div>
                                <div id="keywordOptions" class="overflow-y-auto max-h-48">
                                    <div class="keyword-option px-3 py-2 text-sm text-neutral-400 text-center">Pilih program terlebih dahulu</div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-neutral-500 mt-1">Pilih keyword yang memiliki gambar. CTA akan otomatis terisi.</p>
                    </label>

                    {{-- Preview keyword --}}
                    <div id="keywordPreview" class="hidden">
                        <div class="rounded-xl border border-neutral-200 p-4 bg-neutral-50">
                            <p class="text-xs font-semibold text-neutral-600 mb-2">Preview Keyword:</p>
                            <div class="flex items-center gap-3">
                                <div class="w-20 h-16 rounded-lg overflow-hidden bg-neutral-100 flex-shrink-0">
                                    <img id="keywordPreviewImage" src="" alt="Keyword Preview" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p id="keywordPreviewName" class="text-sm font-semibold text-neutral-800 truncate"></p>
                                    <p id="keywordPreviewMerchant" class="text-xs text-neutral-500 truncate"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Gambar manual --}}
                    <label class="block" id="manualImageLabel">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Gambar</span>
                        <input id="imageInput" type="file" name="image" accept="image/*"
                               class="mt-2 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                        <span id="fileError" class="text-xs text-rose-500 mt-2 hidden">Silakan pilih gambar terlebih dahulu.</span>
                    </label>

                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">CTA Link <span id="ctaAutoLabel" class="text-xs text-neutral-400 font-normal hidden">(Otomatis dari keyword)</span> <span class="text-rose-500">*</span></span>
                        <input id="linkInput" type="url" name="link_iklan" value="{{ old('link_iklan') }}"
                               placeholder="https://contoh.com/promo"
                               class="mt-2 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100"
                               required>
                    </label>
                    <label class="inline-flex items-center gap-3 rounded-xl border border-neutral-200 px-4 py-3 bg-neutral-50">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-neutral-300 text-orange-600 focus:ring-orange-500">
                        <span class="text-sm font-semibold text-neutral-700">Iklan aktif</span>
                        <span class="text-xs text-neutral-500">Banner hanya tampil jika status ini aktif.</span>
                    </label>
                    <button type="button" id="openConfirmModal" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-neutral-900 text-white font-semibold hover:bg-neutral-800 transition">
                        Simpan Iklan
                    </button>
                </form>
                @endif
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

        {{-- ============================================================ --}}
        {{-- DAFTAR LINK YANG SUDAH DIKONFIGURASI TOP BANNER-NYA --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800">Daftar Link Top Banner</h2>
                    <p class="text-sm text-neutral-500">
                        {{ $locationGroups->count() }} lokasi dikonfigurasi,
                        {{ $iklans->count() }} total banner.
                    </p>
                </div>
                <div class="relative w-full md:w-64">
                    <input id="linkSearchInput" type="text" placeholder="Cari lokasi..."
                           class="block w-full rounded-xl border border-neutral-200 px-3 py-2 pl-9 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            @if($locationGroups->isEmpty())
            <div class="py-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-neutral-100 flex items-center justify-center">
                    <i class="fas fa-images text-neutral-400 text-2xl"></i>
                </div>
                <p class="text-neutral-500 font-medium">Belum ada top banner yang dikonfigurasi.</p>
                <p class="text-sm text-neutral-400 mt-1">Tambahkan banner melalui form di atas.</p>
            </div>
            @else
            <div id="locationGroupsList" class="space-y-3">
                @foreach($locationGroups as $group)
                @php
                    $typeColors = [
                        'general'     => 'bg-neutral-100 text-neutral-600',
                        'territorial' => 'bg-orange-50 text-orange-600',
                        'regional'    => 'bg-blue-50 text-blue-600',
                        'branch'      => 'bg-purple-50 text-purple-600',
                        'cluster'     => 'bg-green-50 text-green-600',
                        'merchant'    => 'bg-pink-50 text-pink-600',
                    ];
                    $typeLabels = [
                        'general'     => 'General',
                        'territorial' => 'Territorial',
                        'regional'    => 'Regional',
                        'branch'      => 'Branch',
                        'cluster'     => 'Cluster',
                        'merchant'    => 'Program',
                    ];
                    $badgeClass = $typeColors[$group['type']] ?? 'bg-neutral-100 text-neutral-600';
                    $typeLabel  = $typeLabels[$group['type']] ?? $group['type'];
                    $firstIklan = $group['iklans']->first();
                @endphp
                <div class="location-group-row rounded-xl border border-neutral-100 hover:border-neutral-200 hover:shadow-sm transition-all"
                     data-search="{{ strtolower($group['name']) }} {{ strtolower($group['display']) }}">
                    <div class="flex items-center gap-4 p-4">
                        {{-- Thumbnail --}}
                        <div class="w-20 h-12 rounded-lg overflow-hidden bg-neutral-100 flex-shrink-0">
                            @if($firstIklan)
                            <img src="{{ asset('storage/' . $firstIklan->image_path) }}" alt="{{ $group['name'] }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-300">
                                <i class="fas fa-image"></i>
                            </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-semibold text-neutral-800 truncate">{{ $group['name'] }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeClass }}">{{ $typeLabel }}</span>
                                @if($group['count'] > 1)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-500 font-medium">{{ $group['count'] }} banner</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-500 font-medium">1 banner</span>
                                @endif
                            </div>
                            <p class="text-xs text-neutral-400 mt-0.5 font-mono truncate">{{ $group['display'] }}</p>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($group['url'])
                            <a href="{{ $group['url'] }}" target="_blank"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 transition"
                               title="Lihat halaman">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                            @endif
                            <button type="button"
                                    class="kelola-btn inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-neutral-900 text-white text-xs font-semibold hover:bg-neutral-700 transition"
                                    data-group-key="{{ $group['key'] }}"
                                    data-group-name="{{ $group['name'] }}"
                                    data-group-type="{{ $group['type'] }}"
                                    data-group-display="{{ $group['display'] }}"
                                    data-group-url="{{ $group['url'] ?? '' }}"
                                    data-is-maha="{{ $isUserMaha ? '1' : '0' }}">
                                <i class="fas fa-sliders-h"></i>
                                Kelola
                            </button>
                        </div>
                    </div>
                    {{-- Mini preview strip banner-banner dalam grup --}}
                    @if($group['count'] > 1)
                    <div class="flex gap-2 px-4 pb-3 overflow-x-auto">
                        @foreach($group['iklans']->skip(1)->take(4) as $extraIklan)
                        <div class="w-16 h-10 rounded-md overflow-hidden bg-neutral-100 flex-shrink-0 opacity-60">
                            <img src="{{ asset('storage/' . $extraIklan->image_path) }}" alt="" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                        @if($group['count'] > 5)
                        <div class="w-16 h-10 rounded-md bg-neutral-100 flex-shrink-0 flex items-center justify-center">
                            <span class="text-xs text-neutral-400 font-semibold">+{{ $group['count'] - 5 }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
{{-- Hidden delete forms untuk semua iklan (dipakai oleh modal kelola) --}}
@foreach($iklans as $iklan)
<form id="deleteForm-{{ $iklan->id }}" action="{{ route('iklan.destroy', $iklan) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endforeach

{{-- ============================================================ --}}
{{-- MODAL KELOLA BANNER PER LOKASI --}}
{{-- ============================================================ --}}
<div id="kelolaModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[70] flex items-center justify-center p-4">
    <div id="kelolaModalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[92vh] transform transition-all duration-300 scale-95 opacity-0 flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 flex-shrink-0">
            <div>
                <h3 class="text-lg font-semibold text-neutral-900" id="kelolaModalTitle">Banner Lokasi</h3>
                <p class="text-xs text-neutral-400 mt-0.5 font-mono" id="kelolaModalDisplay"></p>
            </div>
            <div class="flex items-center gap-2">
                <a id="kelolaModalViewLink" href="#" target="_blank"
                   class="hidden inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-neutral-600 bg-neutral-100 rounded-lg hover:bg-neutral-200 transition">
                    <i class="fas fa-external-link-alt"></i> Lihat
                </a>
                <button type="button" id="kelolaModalClose" class="text-neutral-400 hover:text-neutral-600 transition w-8 h-8 flex items-center justify-center rounded-lg hover:bg-neutral-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Body: daftar banner dengan drag-reorder --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div class="flex items-center justify-between mb-3">
                <p id="kelolaModalCount" class="text-sm text-neutral-500"></p>
                @if($isUserMaha)
                <p class="text-xs text-neutral-400"><i class="fas fa-grip-vertical mr-1"></i>Drag untuk ubah urutan</p>
                @endif
            </div>
            <div id="kelolaModalBannerList" class="space-y-3">
                {{-- Diisi via JavaScript --}}
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-neutral-100 flex items-center justify-between flex-shrink-0">
            <button type="button" id="kelolaModalClose2" class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">
                Tutup
            </button>
            @if($isUserMaha)
            <button type="button" id="saveKelolaOrderBtn"
                    class="hidden px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-rose-500 rounded-lg hover:shadow-lg transition">
                <i class="fas fa-save mr-1.5"></i>Simpan Urutan
            </button>
            @endif
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

<div id="editConfirmationModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[80] flex items-center justify-center p-4">
    <div id="editModalContent" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] transform transition-all duration-300 scale-95 opacity-0 flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-4 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center">
                    <i class="fas fa-pen text-orange-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Edit Iklan</h3>
                    <p class="text-sm text-neutral-500" id="editLocationText">Perbarui detail banner dan status aktif.</p>
                </div>
            </div>
            <button type="button" class="text-neutral-400 hover:text-neutral-600 transition" data-close-edit-modal>
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1">
            @csrf
            @method('PATCH')
            <div class="p-6 overflow-y-auto space-y-4 flex-1">
                <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-4">
                    <p class="text-xs font-semibold text-neutral-600 mb-2">Preview saat ini</p>
                    <div class="flex items-start gap-4">
                        <div class="w-28 h-16 rounded-lg overflow-hidden bg-neutral-200 flex-shrink-0">
                            <img id="editImagePreview" src="" alt="Preview Iklan" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1 space-y-1">
                            <p class="text-sm font-semibold text-neutral-800 truncate" id="editLocationValue">-</p>
                            <p class="text-xs text-neutral-500">Gambar baru opsional. Jika tidak diubah, banner lama tetap dipakai.</p>
                        </div>
                    </div>
                </div>

                <label class="block">
                    <span class="text-sm font-semibold text-neutral-700">Ganti Gambar <span class="text-xs text-neutral-400 font-normal">(Opsional)</span></span>
                    <input id="editImageInput" type="file" name="image" accept="image/*"
                           class="mt-2 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-neutral-700">CTA Link</span>
                    <input id="editLinkInput" type="url" name="link_iklan" placeholder="https://contoh.com/promo"
                           class="mt-2 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100">
                </label>

                <label class="inline-flex items-center gap-3 rounded-xl border border-neutral-200 px-4 py-3 bg-neutral-50 w-full">
                    <input id="editIsActiveInput" type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-neutral-300 text-orange-600 focus:ring-orange-500">
                    <span class="text-sm font-semibold text-neutral-700">Iklan aktif</span>
                </label>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-neutral-50 rounded-b-2xl border-t border-neutral-100 flex-shrink-0">
                <button type="button" data-close-edit-modal class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-rose-500 rounded-lg hover:shadow-lg transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
// Script khusus halaman iklan
// Merchant data for multiple selection
window.merchantsData = {!! json_encode($merchants) !!};
// Keyword data for keyword-based ads
window.keywordsData = {!! json_encode($keywords) !!};

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
    const editModal = document.getElementById('editConfirmationModal');
    const editModalContent = document.getElementById('editModalContent');
    const editForm = document.getElementById('editForm');
    const editImagePreview = document.getElementById('editImagePreview');
    const editImageInput = document.getElementById('editImageInput');
    const editLinkInput = document.getElementById('editLinkInput');
    const editIsActiveInput = document.getElementById('editIsActiveInput');
    const editLocationText = document.getElementById('editLocationText');
    const editLocationValue = document.getElementById('editLocationValue');
    const openConfirmBtn = document.getElementById('openConfirmModal');
    const confirmUploadBtn = document.getElementById('confirmUploadBtn');
    const closeUploadButtons = document.querySelectorAll('[data-close-upload]');
    const editButtons = document.querySelectorAll('.editTrigger');
    const deleteButtons = document.querySelectorAll('.deleteTrigger');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const closeDeleteButtons = document.querySelectorAll('[data-close-delete]');
    const closeEditButtons = document.querySelectorAll('[data-close-edit-modal]');
    let pendingDeleteForm = null;

    // Upload type handling
    const uploadTypeInput = document.getElementById('uploadTypeInput');
    const manualUploadBtn = document.getElementById('manualUploadBtn');
    const keywordUploadBtn = document.getElementById('keywordUploadBtn');
    const keywordMerchantLabel = document.getElementById('keywordMerchantLabel');
    const keywordSelectionLabel = document.getElementById('keywordSelectionLabel');
    const keywordPreview = document.getElementById('keywordPreview');
    const manualImageLabel = document.getElementById('manualImageLabel');
    const linkInput = document.getElementById('linkInput');
    const ctaAutoLabel = document.getElementById('ctaAutoLabel');
    
    let currentUploadType = uploadTypeInput.value || 'manual';
    let selectedMerchantId = null;
    
    function setUploadType(type) {
        currentUploadType = type;
        uploadTypeInput.value = type;
        
        // Update button styles
        if (type === 'manual') {
            manualUploadBtn.classList.remove('border-neutral-200', 'bg-white', 'text-neutral-600');
            manualUploadBtn.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-700');
            keywordUploadBtn.classList.remove('border-orange-500', 'bg-orange-50', 'text-orange-700');
            keywordUploadBtn.classList.add('border-neutral-200', 'bg-white', 'text-neutral-600');
            
            // Show manual upload, hide keyword selection (tanpa hapus state)
            manualImageLabel.classList.remove('hidden');
            keywordMerchantLabel.classList.add('hidden');
            keywordSelectionLabel.classList.add('hidden');
            keywordPreview.classList.add('hidden');
            ctaAutoLabel.classList.add('hidden');
            linkInput.removeAttribute('readonly');
            linkInput.classList.remove('bg-neutral-50');
            
            // JANGAN hapus keywordMerchantInput / keywordMerchantText
            // Hanya reset selectedMerchantId lokal (dipakai mode manual)
            selectedMerchantId = null;
        } else {
            keywordUploadBtn.classList.remove('border-neutral-200', 'bg-white', 'text-neutral-600');
            keywordUploadBtn.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-700');
            manualUploadBtn.classList.remove('border-orange-500', 'bg-orange-50', 'text-orange-700');
            manualUploadBtn.classList.add('border-neutral-200', 'bg-white', 'text-neutral-600');
            
            // Hide manual upload, show merchant selection first
            manualImageLabel.classList.add('hidden');
            keywordMerchantLabel.classList.remove('hidden');
            ctaAutoLabel.classList.remove('hidden');
            
            // Clear manual image
            if (imageInput) imageInput.value = '';

            // Restore state: jika sudah ada 1 merchant terpilih, re-apply auto-set
            // (fungsi autoSetKeywordMerchantIfSingle didefinisikan lebih bawah,
            //  dipanggil via setTimeout agar definisi sudah tersedia)
            setTimeout(() => { if (typeof autoSetKeywordMerchantIfSingle === 'function') autoSetKeywordMerchantIfSingle(); }, 0);
        }
    }
    
    if (manualUploadBtn) {
        manualUploadBtn.addEventListener('click', () => setUploadType('manual'));
    }
    
    if (keywordUploadBtn) {
        keywordUploadBtn.addEventListener('click', () => setUploadType('keyword'));
    }
    
    // Initialize upload type on page load
    setUploadType(currentUploadType);
    
    // Merchant selection for keyword upload
    const keywordMerchantInput = document.getElementById('keywordMerchantInput');
    const keywordMerchantBtn = document.getElementById('keywordMerchantBtn');
    const keywordMerchantText = document.getElementById('keywordMerchantText');
    const keywordMerchantDropdown = document.getElementById('keywordMerchantDropdown');
    const keywordMerchantSearch = document.getElementById('keywordMerchantSearch');
    const keywordMerchantOptions = document.getElementById('keywordMerchantOptions');
    
    // Initialize merchant dropdown
    initSearchableDropdown('keywordMerchantInput', 'keywordMerchantBtn', 'keywordMerchantText', 'keywordMerchantDropdown', 'keywordMerchantSearch', 'keywordMerchantOptions', 'keyword-merchant-option');
    
    // Handle merchant selection
    if (keywordMerchantOptions) {
        keywordMerchantOptions.addEventListener('click', (e) => {
            const option = e.target.closest('.keyword-merchant-option');
            if (!option) return;
            
            const merchantId = option.getAttribute('data-value');
            const merchantName = option.textContent.trim();
            
            if (keywordMerchantInput) keywordMerchantInput.value = merchantId;
            if (keywordMerchantText) keywordMerchantText.textContent = merchantName;
            
            selectedMerchantId = merchantId ? parseInt(merchantId) : null;
            
            // Show keyword selection and populate keywords for this merchant
            if (selectedMerchantId) {
                keywordSelectionLabel.classList.remove('hidden');
                populateKeywordsForMerchant(selectedMerchantId);
            } else {
                keywordSelectionLabel.classList.add('hidden');
                keywordPreview.classList.add('hidden');
                // Clear keyword selection
                const keywordInput = document.getElementById('keywordInput');
                if (keywordInput) keywordInput.value = '';
                const keywordText = document.getElementById('keywordText');
                if (keywordText) keywordText.textContent = '-- Pilih Merchant Terlebih Dahulu --';
            }
            
            // Close dropdown
            if (keywordMerchantDropdown) keywordMerchantDropdown.classList.add('hidden');
        });
    }
    
    // Function to populate keywords based on selected merchant
    function populateKeywordsForMerchant(merchantId) {
        const keywordInput = document.getElementById('keywordInput');
        const keywordOptions = document.getElementById('keywordOptions');
        const keywordText = document.getElementById('keywordText');
        
        if (!keywordInput || !keywordOptions) return;
        
        // Filter keywords by merchant
        const filteredKeywords = window.keywordsData.filter(k => k.merchant_key == merchantId);
        
        // Clear existing options
        keywordInput.innerHTML = '<option value="">-- Pilih Keyword --</option>';
        keywordOptions.innerHTML = '';
        
        if (filteredKeywords.length === 0) {
            keywordOptions.innerHTML = '<div class="keyword-option px-3 py-2 text-sm text-neutral-400 text-center">Tidak ada keyword dengan gambar untuk merchant ini</div>';
            if (keywordText) keywordText.textContent = '-- Tidak Ada Keyword --';
            return;
        }
        
        // Add filtered keywords
        filteredKeywords.forEach(keyword => {
            // Add to select
            const option = document.createElement('option');
            option.value = keyword.id;
            option.setAttribute('data-image', keyword.image);
            option.setAttribute('data-cta', keyword.cta_link || '');
            option.setAttribute('data-merchant', keyword.merchant_key);
            option.setAttribute('data-merchant-name', keyword.merchant_name || '');
            option.textContent = keyword.nama_produk + (keyword.kategori_keyword ? ' (' + keyword.kategori_keyword + ')' : '');
            keywordInput.appendChild(option);
            
            // Add to dropdown
            const div = document.createElement('div');
            div.className = 'keyword-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg';
            div.setAttribute('data-value', keyword.id);
            div.setAttribute('data-image', keyword.image);
            div.setAttribute('data-cta', keyword.cta_link || '');
            div.setAttribute('data-merchant', keyword.merchant_key);
            div.setAttribute('data-merchant-name', keyword.merchant_name || '');
            div.innerHTML = keyword.nama_produk + (keyword.kategori_keyword ? ' <span class="text-neutral-400">(' + keyword.kategori_keyword + ')</span>' : '');
            keywordOptions.appendChild(div);
        });
        
        if (keywordText) keywordText.textContent = '-- Pilih Keyword --';
    }
    
    // Keyword selection handling
    const keywordInput = document.getElementById('keywordInput');
    const keywordBtn = document.getElementById('keywordBtn');
    const keywordText = document.getElementById('keywordText');
    const keywordDropdown = document.getElementById('keywordDropdown');
    const keywordSearch = document.getElementById('keywordSearch');
    const keywordOptions = document.getElementById('keywordOptions');
    
    function selectKeyword(keywordId, keywordName, keywordImage, keywordCta, keywordMerchant, keywordMerchantName) {
        if (keywordInput) keywordInput.value = keywordId;
        if (keywordText) keywordText.textContent = keywordName || '-- Pilih Keyword --';
        
        if (keywordId) {
            // Show preview
            keywordPreview.classList.remove('hidden');
            const previewImage = document.getElementById('keywordPreviewImage');
            const previewName = document.getElementById('keywordPreviewName');
            const previewMerchant = document.getElementById('keywordPreviewMerchant');
            
            if (previewImage && keywordImage) {
                previewImage.src = '/storage/' + keywordImage;
            }
            if (previewName) previewName.textContent = keywordName;
            if (previewMerchant) previewMerchant.textContent = keywordMerchantName || 'Merchant tidak diketahui';
            
            // Auto-populate CTA
            if (linkInput) {
                if (keywordCta) {
                    linkInput.value = keywordCta;
                    linkInput.setAttribute('readonly', 'readonly');
                    linkInput.classList.add('bg-neutral-50');
                    if (ctaAutoLabel) ctaAutoLabel.classList.remove('hidden');
                } else {
                    linkInput.value = '';
                    linkInput.removeAttribute('readonly');
                    linkInput.classList.remove('bg-neutral-50');
                    if (ctaAutoLabel) ctaAutoLabel.classList.add('hidden');
                }
            }
        } else {
            // Hide preview
            keywordPreview.classList.add('hidden');
            if (linkInput) {
                linkInput.value = '';
                linkInput.removeAttribute('readonly');
                linkInput.classList.remove('bg-neutral-50');
            }
        }
    }
    
    // Initialize keyword dropdown
    initSearchableDropdown('keywordInput', 'keywordBtn', 'keywordText', 'keywordDropdown', 'keywordSearch', 'keywordOptions', 'keyword-option');
    
    // Handle keyword selection with auto-population
    if (keywordOptions) {
        keywordOptions.addEventListener('click', (e) => {
            const option = e.target.closest('.keyword-option');
            if (!option) return;
            
            const keywordId = option.getAttribute('data-value');
            const keywordName = option.textContent.trim();
            const keywordImage = option.getAttribute('data-image');
            const keywordCta = option.getAttribute('data-cta');
            const keywordMerchant = option.getAttribute('data-merchant');
            const keywordMerchantName = option.getAttribute('data-merchant-name');
            
            selectKeyword(keywordId, keywordName, keywordImage, keywordCta, keywordMerchant, keywordMerchantName);
        });
    }

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
    window.selectedMerchantsRef = selectedMerchants; // expose untuk filterKeywordMerchantsByLocationType

    const applyScopeRegionalWrap = document.getElementById('applyScopeRegionalWrap');
    const applyScopeBranchWrap   = document.getElementById('applyScopeBranchWrap');
    const applyScopeAllRegional  = document.getElementById('applyScopeAllRegional');
    const applyScopeAllBranch    = document.getElementById('applyScopeAllBranch');

    function showLocationDropdown(type) {
        // Hide all dropdowns first
        territorialLabel?.classList.add('hidden');
        regionalLabel?.classList.add('hidden');
        branchLabel?.classList.add('hidden');
        clusterLabel?.classList.add('hidden');
        merchantLabel?.classList.add('hidden');
        applyScopeRegionalWrap?.classList.add('hidden');
        applyScopeBranchWrap?.classList.add('hidden');
        if (applyScopeAllRegional) applyScopeAllRegional.checked = false;
        if (applyScopeAllBranch)   applyScopeAllBranch.checked   = false;

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
            applyScopeRegionalWrap?.classList.remove('hidden');
        } else if (type === 'branch') {
            branchLabel?.classList.remove('hidden');
            applyScopeBranchWrap?.classList.remove('hidden');
        } else if (type === 'cluster') {
            clusterLabel?.classList.remove('hidden');
        } else if (type === 'merchant') {
            merchantLabel?.classList.remove('hidden');
        }
        // If type is 'general' or empty, all dropdowns stay hidden

        // Filter keyword merchant options berdasarkan lokasi yang dipilih
        filterKeywordMerchantsByLocationType(type);
    }

    // Filter dropdown program di "Dari Keyword" berdasarkan location type & value yang dipilih
    function filterKeywordMerchantsByLocationType(type) {
        const allMerchants = window.merchantsData || [];
        const regionVal   = regionalInput    ? regionalInput.value    : '';
        const branchVal   = branchInput      ? branchInput.value      : '';
        const cityVal     = territorialInput ? territorialInput.value : '';

        let filtered;
        if (type === 'territorial' && cityVal) {
            filtered = allMerchants.filter(m => m.city === cityVal);
        } else if (type === 'regional' && regionVal) {
            filtered = allMerchants.filter(m => m.regional === regionVal);
        } else if (type === 'branch' && branchVal) {
            filtered = allMerchants.filter(m => m.branch === branchVal);
        } else if (type === 'merchant') {
            // Tampilkan hanya merchant yang sudah dipilih di Step 1
            const selIds = (window.selectedMerchantsRef || []).map(m => String(m.id));
            filtered = selIds.length > 0 ? allMerchants.filter(m => selIds.includes(String(m.id))) : allMerchants;
        } else {
            filtered = allMerchants; // general/cluster/empty: tampilkan semua
        }

        const container = document.getElementById('keywordMerchantOptions');
        if (!container) return;

        // Tunjukkan info konteks lokasi jika ada filter aktif
        let headerHtml = '<div class="keyword-merchant-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg" data-value="">-- Pilih Program --</div>';
        if (filtered.length === 0 && (cityVal || branchVal || regionVal)) {
            headerHtml += '<div class="px-3 py-2 text-xs text-neutral-400 text-center">Tidak ada program di lokasi ini</div>';
        }
        container.innerHTML = headerHtml;

        filtered.forEach(m => {
            const div = document.createElement('div');
            div.className = 'keyword-merchant-option px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer rounded-lg';
            div.setAttribute('data-value', m.id);
            div.textContent = m.name;
            container.appendChild(div);
        });
        // Re-wire click handlers untuk opsi baru
        rewireKeywordMerchantOptions();
    }

    function rewireKeywordMerchantOptions() {
        document.querySelectorAll('.keyword-merchant-option').forEach(opt => {
            opt.onclick = function() {
                const val = this.getAttribute('data-value');
                const text = this.textContent;
                const keywordMerchantInput = document.getElementById('keywordMerchantInput');
                const keywordMerchantText  = document.getElementById('keywordMerchantText');
                const keywordMerchantDropdown = document.getElementById('keywordMerchantDropdown');
                if (keywordMerchantInput) keywordMerchantInput.value = val;
                if (keywordMerchantText)  keywordMerchantText.textContent = text;
                if (keywordMerchantDropdown) keywordMerchantDropdown.classList.add('hidden');
                if (val) {
                    populateKeywordsForMerchant(val);
                    const keywordSelectionLabel = document.getElementById('keywordSelectionLabel');
                    keywordSelectionLabel?.classList.remove('hidden');
                }
            };
        });
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
        
        // Keyword upload dropdowns
        const keywordMerchantBtn = document.getElementById('keywordMerchantBtn');
        const keywordMerchantDropdown = document.getElementById('keywordMerchantDropdown');
        const keywordBtn = document.getElementById('keywordBtn');
        const keywordDropdown = document.getElementById('keywordDropdown');
        
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
        
        // Check keyword upload dropdowns
        if ((keywordMerchantBtn && keywordMerchantBtn.contains(e.target)) ||
            (keywordMerchantDropdown && keywordMerchantDropdown.contains(e.target)) ||
            (keywordBtn && keywordBtn.contains(e.target)) ||
            (keywordDropdown && keywordDropdown.contains(e.target))) {
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
        ['locationTypeDropdown', 'territorialDropdown', 'regionalDropdown', 'branchDropdown', 'clusterDropdown', 'keywordMerchantDropdown', 'keywordDropdown'].forEach(id => {
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
        window.selectedMerchantsRef = selectedMerchants; // sync reference
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
                    // Re-evaluate lock state setelah hapus tag
                    autoSetKeywordMerchantIfSingle();
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

    /**
     * Jika hanya 1 merchant dipilih:
     *   - Otomatis isi dropdown "Pilih Program" di Dari Keyword dengan merchant tsb.
     *   - Kunci button dropdown (disabled styling) agar tidak bisa diganti.
     *   - Langsung tampilkan keyword section.
     * Jika >1 atau 0 merchant:
     *   - Unlock kembali dropdown "Pilih Program".
     */
    function autoSetKeywordMerchantIfSingle() {
        const kmInput    = document.getElementById('keywordMerchantInput');
        const kmBtn      = document.getElementById('keywordMerchantBtn');
        const kmText     = document.getElementById('keywordMerchantText');
        const kmLabel    = document.getElementById('keywordMerchantLabel');
        const kwSelLabel = document.getElementById('keywordSelectionLabel');

        if (!kmInput || !kmBtn) return;

        if (selectedMerchants.length === 1) {
            const merchantId = selectedMerchants[0];
            const merchant   = merchantsData.find(m => m.id == merchantId);
            if (!merchant) return;

            // Isi dan kunci dropdown Pilih Program
            kmInput.value       = merchantId;
            kmText.textContent  = merchant.name;

            // Visual: disabled
            kmBtn.disabled = true;
            kmBtn.classList.add('opacity-60', 'cursor-not-allowed', 'pointer-events-none');
            kmBtn.setAttribute('title', 'Program dikunci sesuai merchant yang dipilih');

            // Tampilkan label keywordMerchant jika mode keyword aktif
            if (currentUploadType === 'keyword') {
                kmLabel?.classList.remove('hidden');
            }

            // Langsung populate keywords (hanya jika mode keyword aktif)
            if (currentUploadType === 'keyword') {
                populateKeywordsForMerchant(merchantId);
                kwSelLabel?.classList.remove('hidden');
            }

        } else {
            // Reset: unlock dropdown Pilih Program
            kmInput.value      = '';
            kmText.textContent = '-- Pilih Program --';
            kmBtn.disabled     = false;
            kmBtn.classList.remove('opacity-60', 'cursor-not-allowed', 'pointer-events-none');
            kmBtn.removeAttribute('title');

            // Sembunyikan keyword section jika tidak ada merchant yang dipilih
            if (selectedMerchants.length === 0) {
                kwSelLabel?.classList.add('hidden');
                const keywordPreviewEl = document.getElementById('keywordPreview');
                keywordPreviewEl?.classList.add('hidden');
                const kInput = document.getElementById('keywordInput');
                if (kInput) kInput.value = '';
                const kText = document.getElementById('keywordText');
                if (kText) kText.textContent = '-- Pilih Merchant Terlebih Dahulu --';
            }
        }
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
            // Auto-set Pilih Program jika hanya 1 merchant dipilih
            autoSetKeywordMerchantIfSingle();
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

    // Re-filter keyword merchants setiap kali value lokasi berubah
    ['regionalInput', 'branchInput', 'territorialInput'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function() {
            const locType = document.getElementById('locationTypeInput')?.value || '';
            filterKeywordMerchantsByLocationType(locType);
        });
    });

    // Pastikan filter juga terpanggil langsung saat opsi di-klik (backup, menghindari race condition)
    ['branchOptions', 'regionalOptions', 'territorialOptions'].forEach(optId => {
        const container = document.getElementById(optId);
        if (!container) return;
        container.addEventListener('click', function(e) {
            const option = e.target.closest('[data-value]');
            if (!option) return;
            // Tunggu sebentar agar select.value sudah diset oleh initSearchableDropdown
            setTimeout(() => {
                const locType = document.getElementById('locationTypeInput')?.value || '';
                filterKeywordMerchantsByLocationType(locType);
            }, 10);
        });
    });

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
            // Check validation based on upload type
            if (currentUploadType === 'manual') {
                // Manual upload requires image file
                if (!imageInput || !imageInput.files.length) {
                    fileError.classList.remove('hidden');
                    return;
                }
                fileError.classList.add('hidden');
            } else if (currentUploadType === 'keyword') {
                // Keyword upload requires keyword selection
                const keywordInput = document.getElementById('keywordInput');
                if (!keywordInput || !keywordInput.value) {
                    alert('Silakan pilih keyword terlebih dahulu.');
                    return;
                }
            }
            
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

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const updateUrl = button.getAttribute('data-update-url') || '';
            const imageUrl = button.getAttribute('data-image-url') || '';
            const linkValue = button.getAttribute('data-link') || '';
            const activeValue = button.getAttribute('data-active') === '1';
            const locationValue = button.getAttribute('data-location') || '-';

            if (editForm) {
                editForm.setAttribute('action', updateUrl);
            }
            if (editImagePreview && imageUrl) {
                editImagePreview.src = imageUrl;
            }
            if (editLinkInput) {
                editLinkInput.value = linkValue;
            }
            if (editIsActiveInput) {
                editIsActiveInput.checked = activeValue;
            }
            if (editLocationText) {
                editLocationText.textContent = 'Edit iklan untuk ' + locationValue;
            }
            if (editLocationValue) {
                editLocationValue.textContent = locationValue;
            }
            if (editImageInput) {
                editImageInput.value = '';
            }

            openModal(editModal, editModalContent);
        });
    });

    closeEditButtons.forEach(btn => {
        btn.addEventListener('click', () => closeModal(editModal, editModalContent));
    });

    if (editModal) {
        editModal.addEventListener('click', (event) => {
            if (event.target === editModal) {
                closeModal(editModal, editModalContent);
            }
        });
    }

    if (editImageInput && editImagePreview) {
        editImageInput.addEventListener('change', () => {
            const file = editImageInput.files && editImageInput.files[0];
            if (!file) {
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            editImagePreview.src = objectUrl;
            editImagePreview.onload = () => URL.revokeObjectURL(objectUrl);
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

    // ============================================================
    // SEARCH LOKASI DI LIST LINK
    // ============================================================
    const linkSearchInput = document.getElementById('linkSearchInput');
    const locationGroupRows = document.querySelectorAll('.location-group-row');
    if (linkSearchInput) {
        linkSearchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            locationGroupRows.forEach(row => {
                const searchText = (row.getAttribute('data-search') || '').toLowerCase();
                row.style.display = q === '' || searchText.includes(q) ? '' : 'none';
            });
        });
    }

    // ============================================================
    // MODAL KELOLA BANNER PER LOKASI
    // ============================================================
    const kelolaModal        = document.getElementById('kelolaModal');
    const kelolaModalContent = document.getElementById('kelolaModalContent');
    const kelolaModalTitle   = document.getElementById('kelolaModalTitle');
    const kelolaModalDisplay = document.getElementById('kelolaModalDisplay');
    const kelolaModalCount   = document.getElementById('kelolaModalCount');
    const kelolaModalViewLink = document.getElementById('kelolaModalViewLink');
    const kelolaModalBannerList = document.getElementById('kelolaModalBannerList');
    const kelolaModalClose   = document.getElementById('kelolaModalClose');
    const kelolaModalClose2  = document.getElementById('kelolaModalClose2');
    const saveKelolaOrderBtn = document.getElementById('saveKelolaOrderBtn');

    // Data banner per grup (dikirim dari PHP)
    window.locationGroupsData = {!! json_encode($locationGroups->map(function($g) {
        return [
            'key'     => $g['key'],
            'type'    => $g['type'],
            'name'    => $g['name'],
            'display' => $g['display'],
            'url'     => $g['url'],
            'count'   => $g['count'],
            'iklans'  => $g['iklans']->map(function($i) {
                return [
                    'id'         => $i->id,
                    'image_path' => $i->image_path,
                    'link_iklan' => $i->link_iklan,
                    'is_active'  => $i->is_active,
                    'is_keyword' => $i->is_keyword_based,
                    'update_url' => route('iklan.update', $i->id),
                    'delete_url' => route('iklan.destroy', $i->id),
                ];
            })->values()->toArray(),
        ];
    })->values()->toArray()) !!};

    function openKelolaModal(btn) {
        const groupKey  = btn.getAttribute('data-group-key');
        const groupName = btn.getAttribute('data-group-name');
        const groupDisplay = btn.getAttribute('data-group-display');
        const groupUrl  = btn.getAttribute('data-group-url');
        const isMaha    = btn.getAttribute('data-is-maha') === '1';

        const group = window.locationGroupsData.find(g => g.key === groupKey);
        if (!group) return;

        kelolaModalTitle.textContent  = groupName;
        kelolaModalDisplay.textContent = groupDisplay;
        kelolaModalCount.textContent   = group.count + ' banner';

        if (groupUrl) {
            kelolaModalViewLink.href = groupUrl;
            kelolaModalViewLink.classList.remove('hidden');
        } else {
            kelolaModalViewLink.classList.add('hidden');
        }

        // Render banner list
        kelolaModalBannerList.innerHTML = '';
        group.iklans.forEach((iklan, idx) => {
            const isActive = iklan.is_active;
            const div = document.createElement('div');
            div.className = 'kelola-banner-item flex items-center gap-3 p-3 rounded-xl border border-neutral-100 hover:border-neutral-200 bg-white' + (isMaha ? ' cursor-move' : '');
            div.setAttribute('draggable', isMaha ? 'true' : 'false');
            div.setAttribute('data-iklan-id', iklan.id);
            div.setAttribute('data-update-url', iklan.update_url);
            div.setAttribute('data-delete-url', iklan.delete_url);
            div.setAttribute('data-image-url', '/storage/' + iklan.image_path);
            div.setAttribute('data-link', iklan.link_iklan || '');
            div.setAttribute('data-active', isActive ? '1' : '0');

            div.innerHTML = `
                ${isMaha ? '<div class="flex-shrink-0 cursor-grab text-neutral-300 hover:text-neutral-500"><i class="fas fa-grip-vertical"></i></div>' : ''}
                <div class="w-20 h-12 rounded-lg overflow-hidden bg-neutral-100 flex-shrink-0">
                    <img src="/storage/${iklan.image_path}" alt="Banner ${idx+1}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-semibold text-neutral-700">Banner ${idx+1}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium ${isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-neutral-100 text-neutral-400'}">
                            ${isActive ? 'Aktif' : 'Nonaktif'}
                        </span>
                        ${iklan.is_keyword ? '<span class="text-xs px-2 py-0.5 rounded-full bg-orange-50 text-orange-600 font-medium">Keyword</span>' : ''}
                    </div>
                    ${iklan.link_iklan ? `<p class="text-xs text-neutral-400 truncate mt-0.5">${iklan.link_iklan}</p>` : ''}
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button type="button" class="kelola-edit-btn w-8 h-8 rounded-lg text-orange-600 hover:bg-orange-50 flex items-center justify-center transition" title="Edit">
                        <i class="fas fa-pen text-xs"></i>
                    </button>
                    <button type="button" class="kelola-delete-btn w-8 h-8 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition" title="Hapus">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>
            `;
            kelolaModalBannerList.appendChild(div);
        });

        // Wire edit buttons
        kelolaModalBannerList.querySelectorAll('.kelola-edit-btn').forEach(editBtn => {
            editBtn.addEventListener('click', function () {
                const item = this.closest('.kelola-banner-item');
                const fakeBtn = document.createElement('button');
                fakeBtn.setAttribute('data-update-url', item.getAttribute('data-update-url'));
                fakeBtn.setAttribute('data-image-url', item.getAttribute('data-image-url'));
                fakeBtn.setAttribute('data-link', item.getAttribute('data-link'));
                fakeBtn.setAttribute('data-active', item.getAttribute('data-active'));
                fakeBtn.setAttribute('data-location', groupName);
                fakeBtn.classList.add('editTrigger');
                fakeBtn.click();
                // Dispatch click on the editTrigger logic
                triggerEditModal(fakeBtn);
            });
        });

        // Wire delete buttons
        kelolaModalBannerList.querySelectorAll('.kelola-delete-btn').forEach(delBtn => {
            delBtn.addEventListener('click', function () {
                const item = this.closest('.kelola-banner-item');
                const iklanId = item.getAttribute('data-iklan-id');
                // Find the existing hidden delete form
                const realForm = document.getElementById('deleteForm-' + iklanId);
                if (realForm) {
                    pendingDeleteForm = realForm;
                    openModal(deleteModal, deleteModalContent);
                }
            });
        });

        // Drag-reorder in modal
        if (isMaha && saveKelolaOrderBtn) {
            saveKelolaOrderBtn.classList.remove('hidden');
            initKelolaModalDrag();
        }

        // Show modal
        openModal(kelolaModal, kelolaModalContent);
    }

    function initKelolaModalDrag() {
        let dragItem = null;
        const list = kelolaModalBannerList;
        list.querySelectorAll('.kelola-banner-item').forEach(item => {
            item.addEventListener('dragstart', e => {
                dragItem = item;
                item.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
            });
            item.addEventListener('dragend', () => {
                item.style.opacity = '';
                list.querySelectorAll('.kelola-banner-item').forEach(i => i.classList.remove('border-t-2', 'border-orange-500'));
            });
            item.addEventListener('dragover', e => {
                e.preventDefault();
                if (dragItem && item !== dragItem) {
                    list.querySelectorAll('.kelola-banner-item').forEach(i => i.classList.remove('border-t-2', 'border-orange-500'));
                    item.classList.add('border-t-2', 'border-orange-500');
                }
            });
            item.addEventListener('dragleave', () => item.classList.remove('border-t-2', 'border-orange-500'));
            item.addEventListener('drop', e => {
                e.stopPropagation();
                if (dragItem && item !== dragItem) {
                    const items = Array.from(list.querySelectorAll('.kelola-banner-item'));
                    const di = items.indexOf(dragItem);
                    const ti = items.indexOf(item);
                    di < ti ? list.insertBefore(dragItem, item.nextSibling) : list.insertBefore(dragItem, item);
                    // Renumber
                    list.querySelectorAll('.kelola-banner-item').forEach((it, i) => {
                        const label = it.querySelector('.text-xs.font-semibold.text-neutral-700');
                        if (label) label.textContent = 'Banner ' + (i+1);
                    });
                }
                list.querySelectorAll('.kelola-banner-item').forEach(i => i.classList.remove('border-t-2', 'border-orange-500'));
            });
        });
    }

    // Save order dari modal kelola
    if (saveKelolaOrderBtn) {
        saveKelolaOrderBtn.addEventListener('click', function () {
            const items = kelolaModalBannerList.querySelectorAll('.kelola-banner-item');
            const orders = Array.from(items).map(i => parseInt(i.getAttribute('data-iklan-id')));
            fetch('{{ route("iklan.reorder") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ orders })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    saveKelolaOrderBtn.textContent = 'Tersimpan!';
                    setTimeout(() => { saveKelolaOrderBtn.innerHTML = '<i class="fas fa-save mr-1.5"></i>Simpan Urutan'; }, 2000);
                }
            });
        });
    }

    // Helper: trigger edit modal logic re-used from editTrigger handler
    function triggerEditModal(btn) {
        const updateUrl = btn.getAttribute('data-update-url');
        const imageUrl  = btn.getAttribute('data-image-url');
        const link      = btn.getAttribute('data-link');
        const active    = btn.getAttribute('data-active');
        const location  = btn.getAttribute('data-location');
        if (editForm)           editForm.action = updateUrl;
        if (editImagePreview)   editImagePreview.src = imageUrl;
        if (editLinkInput)      editLinkInput.value = link || '';
        if (editIsActiveInput)  editIsActiveInput.checked = active === '1';
        const editLocationValue = document.getElementById('editLocationValue');
        const editLocationText  = document.getElementById('editLocationText');
        if (editLocationValue) editLocationValue.textContent = location || 'General';
        if (editLocationText)  editLocationText.textContent  = location || 'General';
        openModal(editModal, editModalContent);
    }

    // Wire kelola buttons
    document.querySelectorAll('.kelola-btn').forEach(btn => {
        btn.addEventListener('click', function () { openKelolaModal(this); });
    });

    // Close kelola modal
    [kelolaModalClose, kelolaModalClose2].forEach(btn => {
        if (btn) btn.addEventListener('click', () => closeModal(kelolaModal, kelolaModalContent));
    });
    if (kelolaModal) {
        kelolaModal.addEventListener('click', function (e) {
            if (e.target === kelolaModal) closeModal(kelolaModal, kelolaModalContent);
        });
    }
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