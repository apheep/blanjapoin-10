<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History Redeem | BlanjaPoin Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
    <style>
        .search-input {
            width: 100%;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: 0.5rem 2.5rem;
            font-size: 0.875rem;
        }
        .search-input::placeholder {
            color: #9ca3af;
        }
        .search-input:focus {
            border-color: #fb923c;
            outline: none;
            box-shadow: 0 0 0 2px rgba(251, 146, 60, 0.1);
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body class="min-h-screen bg-white font-poppins">
    @include('partials.navbar-admin')

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
            <!-- Header -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin Panel</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">History Redeem</h1>
                    <p class="text-sm text-gray-600 mt-2">Track dan analisa klik sebelum redeem untuk mendeteksi potensi cheating</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('click.history.blocked') }}" class="px-4 py-2 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-lock mr-2"></i>Blocked IPs
                    </a>
                    <a href="{{ route('click.history.anonymous') }}" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Anonymous Redeems
                    </a>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="flex flex-wrap gap-3 mb-6">
                <button type="button" data-ch-tab="click-log"
                        class="ch-tab-btn px-4 py-2 rounded-full bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold shadow-lg shadow-orange-100 transition-all duration-300">
                    <i class="fas fa-mouse-pointer mr-1"></i> Click Log
                </button>
                <button type="button" data-ch-tab="rekap-merchant"
                        class="ch-tab-btn px-4 py-2 rounded-full bg-white text-gray-700 font-semibold shadow-sm hover:bg-orange-50 transition-all duration-300">
                    <i class="fas fa-store mr-1"></i> Rekap per Merchant
                </button>
                <button type="button" data-ch-tab="rekap-keyword"
                        class="ch-tab-btn px-4 py-2 rounded-full bg-white text-gray-700 font-semibold shadow-sm hover:bg-orange-50 transition-all duration-300">
                    <i class="fas fa-key mr-1"></i> Rekap per Keyword
                </button>
            </div>

            {{-- =============== TAB 1: CLICK LOG (existing content) =============== --}}
            <div data-ch-panel="click-log" class="ch-tab-panel transition-all duration-300">

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('click.history.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                                   placeholder="IP, Device ID, Keyword ID, MSISDN..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>

                        <!-- Merchant Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Merchant</label>
                            <div class="relative" id="merchant-dropdown-container">
                                <input type="hidden" name="merchant_id" id="merchant_id_input" value="{{ $filters['merchant_id'] ?? '' }}">
                                <input type="text" 
                                       id="merchant_search_input"
                                       value="{{ $merchants->where('id', $filters['merchant_id'] ?? '')->first()->nama_merchant ?? 'Semua Merchant' }}"
                                       placeholder="Cari merchant..."
                                       readonly
                                       autocomplete="off"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm cursor-pointer bg-white">
                                <div id="merchant_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden hidden">
                                    <div class="p-2 border-b border-gray-200 bg-white sticky top-0 z-10">
                                        <input type="text" 
                                               id="merchant_search_inner"
                                               placeholder="Ketik untuk mencari merchant..."
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50"
                                               autocomplete="off">
                                    </div>
                                    <div class="py-1 overflow-y-auto max-h-52" id="merchant_options_list">
                                        <div class="merchant-option px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm transition-colors" data-value="">Semua Merchant</div>
                                        @foreach($merchants as $merchant)
                                            <div class="merchant-option px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm transition-colors" data-value="{{ $merchant->id }}" data-name="{{ strtolower($merchant->nama_merchant) }}">{{ $merchant->nama_merchant }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.store('merchants', [
                                    { id: '', name: 'Semua Merchant' },
                                    @foreach($merchants as $merchant)
                                    { id: '{{ $merchant->id }}', name: '{{ addslashes($merchant->nama_merchant) }}' },
                                    @endforeach
                                ]);
                            });
                        </script>

                        <!-- Date Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('click.history.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Klik</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $clickHistories->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mouse-pointer text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Matched</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">
                                {{ $totalMatched ?? 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Unmatched</p>
                            <p class="text-2xl font-bold text-gray-600 mt-1">
                                {{ $totalUnmatched ?? 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Not Matched</p>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-2xl font-bold text-red-600">
                                    {{ $totalNotMatched ?? 0 }}
                                </p>
                                @if(($totalNotMatched ?? 0) > 0)
                                    <a href="{{ route('click.history.not-matched-detail') }}" 
                                       class="text-xs text-red-600 hover:text-red-700 font-medium underline flex items-center gap-1">
                                        <i class="fas fa-eye"></i> View Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <a href="{{ route('click.history.index', array_merge(request()->query(), ['sort' => 'merchant', 'dir' => (request('sort') == 'merchant' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                        Merchant
                                        @if(request('sort') == 'merchant')
                                            @if(request('dir') == 'asc')
                                                <i class="fas fa-sort-up text-orange-500"></i>
                                            @else
                                                <i class="fas fa-sort-down text-orange-500"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keyword ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Device ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <a href="{{ route('click.history.index', array_merge(request()->query(), ['sort' => 'clicked_at', 'dir' => (request('sort') == 'clicked_at' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                        Waktu Klik
                                        @if(request('sort') == 'clicked_at')
                                            @if(request('dir') == 'asc')
                                                <i class="fas fa-sort-up text-orange-500"></i>
                                            @else
                                                <i class="fas fa-sort-down text-orange-500"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <a href="{{ route('click.history.index', array_merge(request()->query(), ['sort' => 'status', 'dir' => (request('sort') == 'status' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                        Status
                                        @if(request('sort') == 'status')
                                            @if(request('dir') == 'asc')
                                                <i class="fas fa-sort-up text-orange-500"></i>
                                            @else
                                                <i class="fas fa-sort-down text-orange-500"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail Match</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($clickHistories as $click)
                                {{-- Baris untuk Matched --}}
                                @if($click->matched_redeem)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $click->merchant->nama_merchant ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $click->merchant_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-mono text-gray-900">{{ $click->keyword_id ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-mono text-gray-700">{{ $click->ip_address ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($click->device_id)
                                            <div class="flex items-center gap-2 group">
                                                <span class="text-xs font-mono text-gray-600 truncate flex-1 min-w-0" title="{{ $click->device_id }}">
                                                    {{ $click->device_id }}
                                                </span>
                                                <button onclick="copyDeviceId('{{ addslashes($click->device_id) }}', this)" class="opacity-0 group-hover:opacity-100 transition-opacity p-1.5 text-gray-500 hover:text-orange-500 hover:bg-orange-50 rounded flex-shrink-0" title="Copy Device ID">
                                                    <i class="fas fa-copy text-xs"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($click->latitude && $click->longitude)
                                            <a href="https://www.google.com/maps?q={{ $click->latitude }},{{ $click->longitude }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                                <i class="fas fa-map-marker-alt"></i> Maps
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $click->clicked_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $click->clicked_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>Matched
                                            </span>
                                    </td>
                                    <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">MSISDN:</span> 
                                                    <span class="text-gray-600">{{ $click->matched_redeem->msisdn }}</span>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Time Diff:</span> 
                                                    <span class="text-gray-600">{{ $click->matched_redeem->time_diff_human ?? 'N/A' }}</span>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Confidence:</span> 
                                                    @if(($click->matched_redeem->confidence ?? 'low') === 'high')
                                                        <span class="text-green-600 font-semibold">● High</span> <span class="text-gray-500">(≤5 menit)</span>
                                                    @elseif(($click->matched_redeem->confidence ?? 'low') === 'medium')
                                                        <span class="text-yellow-600 font-semibold">● Medium</span> <span class="text-gray-500">(≤15 menit)</span>
                                                    @else
                                                        <span class="text-red-600 font-semibold">● Low</span> <span class="text-gray-500">(>15 menit)</span>
                                                    @endif
                                                </div>
                                                 <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Poin:</span> 
                                                    <span class="text-orange-600 font-semibold">{{ number_format($click->matched_redeem->poin_redeem ?? 0) }}</span>
                                                </div>
                                                <div class="text-xs mt-1 pt-1 border-t border-gray-200">
                                                    <span class="font-semibold text-green-700">✓ Merchant dari Click:</span> 
                                                    <span class="text-gray-900 font-medium">{{ $click->merchant->nama_merchant ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Baris untuk Not Matched (jika ada) --}}
                                @if($click->not_matched_redeem)
                                    <tr class="hover:bg-red-50 transition-colors bg-red-50/30">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $click->merchant->nama_merchant ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $click->merchant_id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-gray-900">{{ $click->keyword_id ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-gray-700">{{ $click->ip_address ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($click->device_id)
                                                <div class="flex items-center gap-2 group">
                                                    <span class="text-xs font-mono text-gray-600 truncate flex-1 min-w-0" title="{{ $click->device_id }}">
                                                        {{ $click->device_id }}
                                                    </span>
                                                    <button onclick="copyDeviceId('{{ addslashes($click->device_id) }}', this)" class="opacity-0 group-hover:opacity-100 transition-opacity p-1.5 text-gray-500 hover:text-orange-500 hover:bg-orange-50 rounded flex-shrink-0" title="Copy Device ID">
                                                        <i class="fas fa-copy text-xs"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($click->latitude && $click->longitude)
                                                <a href="https://www.google.com/maps?q={{ $click->latitude }},{{ $click->longitude }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                                    <i class="fas fa-map-marker-alt"></i> Maps
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $click->clicked_at->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $click->clicked_at->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="badge badge-danger">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Not Matched
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">MSISDN:</span> 
                                                    <span class="text-gray-600">{{ $click->not_matched_redeem->msisdn }}</span>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Time Diff:</span> 
                                                    <span class="text-red-600 font-semibold">{{ $click->not_matched_redeem->time_diff_human ?? 'N/A' }}</span>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Confidence:</span> 
                                                    @if(($click->not_matched_redeem->confidence ?? 'low') === 'high')
                                                        <span class="text-green-600 font-semibold">● High</span> <span class="text-gray-500">(≤5 menit)</span>
                                                    @elseif(($click->not_matched_redeem->confidence ?? 'low') === 'medium')
                                                        <span class="text-yellow-600 font-semibold">● Medium</span> <span class="text-gray-500">(≤15 menit)</span>
                                                    @else
                                                        <span class="text-red-600 font-semibold">● Low</span> <span class="text-gray-500">(>15 menit)</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Poin:</span> 
                                                    <span class="text-orange-600 font-semibold">{{ number_format($click->not_matched_redeem->poin_redeem ?? 0) }}</span>
                                                </div>
                                                @if(isset($click->not_matched_redeem->matched_merchant))
                                                    <div class="text-xs mt-1 pt-1 border-t border-red-200">
                                                        <span class="font-semibold text-red-700">✗ Merchant dari Click:</span> 
                                                        <span class="text-gray-900 font-medium">{{ $click->not_matched_redeem->matched_merchant->nama_merchant ?? 'N/A' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Baris untuk No Redeem --}}
                                @if(!$click->matched_redeem && !$click->not_matched_redeem)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $click->merchant->nama_merchant ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $click->merchant_id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-gray-900">{{ $click->keyword_id ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-gray-700">{{ $click->ip_address ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($click->device_id)
                                                <div class="flex items-center gap-2 group">
                                                    <span class="text-xs font-mono text-gray-600 truncate flex-1 min-w-0" title="{{ $click->device_id }}">
                                                        {{ $click->device_id }}
                                                    </span>
                                                    <button onclick="copyDeviceId('{{ addslashes($click->device_id) }}', this)" class="opacity-0 group-hover:opacity-100 transition-opacity p-1.5 text-gray-500 hover:text-orange-500 hover:bg-orange-50 rounded flex-shrink-0" title="Copy Device ID">
                                                        <i class="fas fa-copy text-xs"></i>
                                                    </button>
                                            </div>
                                        @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($click->latitude && $click->longitude)
                                                <a href="https://www.google.com/maps?q={{ $click->latitude }},{{ $click->longitude }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                                    <i class="fas fa-map-marker-alt"></i> Maps
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $click->clicked_at->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $click->clicked_at->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="badge badge-info">
                                                <i class="fas fa-clock mr-1"></i>No Redeem
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs text-gray-400">Belum ada redeem setelah klik ini</span>
                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-500 text-lg font-medium">Tidak ada data click history</p>
                                            <p class="text-gray-400 text-sm mt-2">Data akan muncul ketika ada user yang klik merchant/keyword</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($clickHistories->hasPages())
                <div class="mt-6 bg-white rounded-xl shadow-sm px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Info -->
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $clickHistories->firstItem() ?? 0 }}</span> hingga <span class="font-semibold">{{ $clickHistories->lastItem() ?? 0 }}</span> dari <span class="font-semibold">{{ $clickHistories->total() }}</span> data
                        </div>
                        
                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-2">
                            {{-- Previous Page Link --}}
                            @if ($clickHistories->onFirstPage())
                                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            @else
                                <a href="{{ $clickHistories->previousPageUrl() }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $current = $clickHistories->currentPage();
                                $last = $clickHistories->lastPage();
                                $range = 2; // kiri/kanan dari current
                                $start = max(1, $current - $range);
                                $end = min($last, $current + $range);
                            @endphp

                            {{-- First Page --}}
                            @if ($start > 1)
                                <a href="{{ $clickHistories->url(1) }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">1</a>
                                @if ($start > 2)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                            @endif

                            {{-- Page Numbers --}}
                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $current)
                                    <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $clickHistories->url($page) }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                                <a href="{{ $clickHistories->url($last) }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $last }}</a>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($clickHistories->hasMorePages())
                                <a href="{{ $clickHistories->nextPageUrl() }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="mt-3"><strong>Confidence Level</strong> (dengan memperhitungkan processing time):</p>
                            <ul class="list-disc ml-5 space-y-1">
                                <li><span class="text-green-600 font-semibold">● High</span> - Time diff ≤5 menit (termasuk processing, sangat mungkin dari klik langsung)</li>
                                <li><span class="text-yellow-600 font-semibold">● Medium</span> - Time diff ≤15 menit (kemungkinan besar dari klik)</li>
                                <li><span class="text-red-600 font-semibold">● Low</span> - Time diff >15 menit (perlu dicek manual, kemungkinan sharelink)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            </div>{{-- End Tab 1: Click Log --}}

            {{-- =============== TAB 2: REKAP PER MERCHANT =============== --}}
            <div data-ch-panel="rekap-merchant" class="ch-tab-panel transition-all duration-300 hidden opacity-0">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Rekap TRX per Merchant</h2>
                        <p class="text-sm text-gray-500 mt-1">Total TRX matched yang masuk ke setiap merchant</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Total: {{ $rekapMerchant->count() }} merchant
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-600">Total TRX (Semua Merchant)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($rekapMerchant->sum('total_redeem'), 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-600">Total Merchant</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($rekapMerchant->count(), 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kota</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Keyword</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total TRX</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rekapMerchant as $index => $rekap)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $rekap->nama_merchant }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $rekap->merchant_id }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $rekap->merchant_city ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-center">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ $rekap->total_keyword }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center font-semibold text-gray-900">{{ number_format($rekap->total_redeem, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-sm font-medium text-gray-500">Belum ada data rekap merchant</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>{{-- End Tab 2: Rekap per Merchant --}}

            {{-- =============== TAB 3: REKAP PER KEYWORD =============== --}}
            <div data-ch-panel="rekap-keyword" class="ch-tab-panel transition-all duration-300 hidden opacity-0">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Rekap TRX per Keyword ID</h2>
                        <p class="text-sm text-gray-500 mt-1">Total redeem dan stock per keyword ID dari tokodigi_tselpoin_redeem</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Total: {{ $rekapKeyword->count() }} keyword
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-600">Total Redeem (Semua Keyword)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($rekapKeyword->sum('total_redeem'), 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-600">Total Keyword</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($rekapKeyword->count(), 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keyword ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Sisa Stock</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Redeem</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rekapKeyword as $index => $rekap)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4">
                                            <span class="text-sm font-mono font-medium text-gray-900">{{ $rekap->keyword_id }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $rekap->nama_produk ?? '-' }}</td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $rekap->nama_merchant ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ $rekap->stock ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center">
                                            @php
                                                $sisaStock = $rekap->sisa_stock ?? null;
                                                $stockClass = 'bg-orange-100 text-orange-800';
                                                if ($sisaStock !== null && $sisaStock <= 0) $stockClass = 'bg-red-100 text-red-800';
                                                elseif ($sisaStock !== null && $sisaStock <= 5) $stockClass = 'bg-yellow-100 text-yellow-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stockClass }}">{{ $sisaStock ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center font-semibold text-gray-900">{{ number_format($rekap->total_redeem, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-sm font-medium text-gray-500">Belum ada data rekap keyword</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>{{-- End Tab 3: Rekap per Keyword --}}
        </main>

    <script>
        // Tab switching for Click History page
        (function() {
            const tabBtns = document.querySelectorAll('.ch-tab-btn');
            const tabPanels = document.querySelectorAll('.ch-tab-panel');
            const activeClasses = ['bg-gradient-to-r', 'from-[#F81611]', 'to-[#F0B100]', 'text-white', 'shadow-lg', 'shadow-orange-100'];
            const inactiveClasses = ['bg-white', 'text-gray-700', 'shadow-sm'];
            function showTab(targetTab) {
                tabBtns.forEach(btn => {
                    const isActive = btn.dataset.chTab === targetTab;
                    activeClasses.forEach(cls => btn.classList.toggle(cls, isActive));
                    inactiveClasses.forEach(cls => btn.classList.toggle(cls, !isActive));
                });
                tabPanels.forEach(panel => {
                    if (panel.dataset.chPanel === targetTab) {
                        panel.classList.remove('hidden', 'opacity-0');
                        panel.classList.add('opacity-100');
                    } else {
                        panel.classList.add('hidden', 'opacity-0');
                        panel.classList.remove('opacity-100');
                    }
                });
            }
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => showTab(btn.dataset.chTab));
            });
            showTab('click-log');
        })();

        // Merchant searchable dropdown
        (function() {
            const container = document.getElementById('merchant-dropdown-container');
            const input = document.getElementById('merchant_search_input');
            const hiddenInput = document.getElementById('merchant_id_input');
            const dropdown = document.getElementById('merchant_dropdown');
            const searchInner = document.getElementById('merchant_search_inner');
            const optionsList = document.getElementById('merchant_options_list');
            
            let selectedValue = hiddenInput.value;
            let selectedName = input.value || 'Semua Merchant';
            
            // Show dropdown on input focus/click
            input.addEventListener('click', function(e) {
                e.preventDefault();
                dropdown.classList.remove('hidden');
                searchInner.value = ''; // Clear search when opening
                filterMerchants();
                setTimeout(() => {
                    searchInner.focus();
                }, 50);
            });
            
            input.addEventListener('focus', function(e) {
                e.preventDefault();
                input.blur(); // Prevent keyboard from opening on mobile
                dropdown.classList.remove('hidden');
                searchInner.value = '';
                filterMerchants();
                setTimeout(() => {
                    searchInner.focus();
                }, 50);
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    searchInner.value = ''; // Clear search when closing
                }
            });
            
            // Prevent dropdown from closing when clicking inside
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Filter merchants based on search
            function filterMerchants() {
                const searchTerm = searchInner.value.toLowerCase().trim();
                const options = optionsList.querySelectorAll('.merchant-option');
                
                options.forEach(option => {
                    const merchantName = option.getAttribute('data-name') || option.textContent.toLowerCase();
                    if (searchTerm === '' || merchantName.includes(searchTerm)) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
            
            // Search in inner input (real-time filtering)
            searchInner.addEventListener('input', function() {
                filterMerchants();
            });
            
            // Allow Enter key to select first visible option
            searchInner.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const visibleOptions = Array.from(optionsList.querySelectorAll('.merchant-option')).filter(
                        opt => opt.style.display !== 'none'
                    );
                    if (visibleOptions.length > 0) {
                        visibleOptions[0].click();
                    }
                }
            });
            
            // Handle option click
            optionsList.addEventListener('click', function(e) {
                const option = e.target.closest('.merchant-option');
                if (option) {
                    selectedValue = option.getAttribute('data-value') || '';
                    selectedName = option.textContent.trim();
                    hiddenInput.value = selectedValue;
                    input.value = selectedName;
                    dropdown.classList.add('hidden');
                    searchInner.value = '';
                    
                    // Highlight selected option
                    optionsList.querySelectorAll('.merchant-option').forEach(opt => {
                        opt.classList.remove('bg-orange-50', 'font-semibold');
                        if (opt.getAttribute('data-value') === selectedValue) {
                            opt.classList.add('bg-orange-50', 'font-semibold');
                        }
                    });
                }
            });
            
            // Highlight selected option on load
            setTimeout(() => {
                optionsList.querySelectorAll('.merchant-option').forEach(opt => {
                    opt.classList.remove('bg-orange-50', 'font-semibold');
                    if (opt.getAttribute('data-value') === selectedValue) {
                        opt.classList.add('bg-orange-50', 'font-semibold');
                    }
                });
            }, 100);
        })();
        
        function copyDeviceId(deviceId, buttonElement) {
            // Copy to clipboard
            navigator.clipboard.writeText(deviceId).then(function() {
                // Change icon to checkmark
                const icon = buttonElement.querySelector('i');
                icon.classList.remove('fa-copy');
                icon.classList.add('fa-check');
                buttonElement.classList.remove('text-gray-500', 'hover:text-orange-500');
                buttonElement.classList.add('text-green-500');
                buttonElement.title = 'Copied!';
                
                // Reset after 2 seconds
                setTimeout(function() {
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-copy');
                    buttonElement.classList.remove('text-green-500');
                    buttonElement.classList.add('text-gray-500', 'hover:text-orange-500');
                    buttonElement.title = 'Copy Device ID';
                }, 2000);
            }).catch(function(err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = deviceId;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    // Change icon to checkmark
                    const icon = buttonElement.querySelector('i');
                    icon.classList.remove('fa-copy');
                    icon.classList.add('fa-check');
                    buttonElement.classList.remove('text-gray-500', 'hover:text-orange-500');
                    buttonElement.classList.add('text-green-500');
                    buttonElement.title = 'Copied!';
                    
                    // Reset after 2 seconds
                    setTimeout(function() {
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-copy');
                        buttonElement.classList.remove('text-green-500');
                        buttonElement.classList.add('text-gray-500', 'hover:text-orange-500');
                        buttonElement.title = 'Copy Device ID';
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy Device ID');
                }
                document.body.removeChild(textArea);
            });
        }
        
        // Auto-refresh table every 30 seconds (lazy load)
        let autoRefreshInterval;
        let isRefreshing = false;
        
        function refreshTable() {
            if (isRefreshing) return;
            
            isRefreshing = true;
            
            // Get current URL with all query parameters
            const currentUrl = window.location.href;
            
            // Make AJAX request
            fetch(currentUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the response HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update table container
                const tableContainer = document.querySelector('.bg-white.rounded-xl.shadow-sm.overflow-hidden');
                const newTableContainer = doc.querySelector('.bg-white.rounded-xl.shadow-sm.overflow-hidden');
                if (tableContainer && newTableContainer) {
                    tableContainer.innerHTML = newTableContainer.innerHTML;
                }
                
                // Update pagination
                const paginationContainer = document.querySelector('.mt-6.bg-white.rounded-xl.shadow-sm.px-6.py-4');
                const newPaginationContainer = doc.querySelector('.mt-6.bg-white.rounded-xl.shadow-sm.px-6.py-4');
                if (paginationContainer && newPaginationContainer) {
                    paginationContainer.innerHTML = newPaginationContainer.innerHTML;
                } else if (!newPaginationContainer && paginationContainer) {
                    // If no pagination in new response, remove it
                    paginationContainer.remove();
                } else if (newPaginationContainer && !paginationContainer) {
                    // If pagination exists in new response but not in current DOM, add it
                    const tableWrapper = document.querySelector('.bg-white.rounded-xl.shadow-sm.overflow-hidden')?.parentElement;
                    if (tableWrapper) {
                        tableWrapper.insertAdjacentElement('afterend', newPaginationContainer);
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing table:', error);
            })
            .finally(() => {
                isRefreshing = false;
            });
        }
        
        // Start auto-refresh on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh every 30 seconds
            autoRefreshInterval = setInterval(refreshTable, 30000);
            
            // Stop auto-refresh when user leaves page
            window.addEventListener('beforeunload', function() {
                if (autoRefreshInterval) {
                    clearInterval(autoRefreshInterval);
                }
            });
        });
    </script>

    <script>
        // Merchant searchable dropdown
        (function() {
</body>
</html>

