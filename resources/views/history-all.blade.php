<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Lengkap | BlanjaPoin</title>
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
    </style>
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full shadow-sm">
        <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6">
            <div class="flex items-center">
                <a href="{{ route('home') }}">
                    <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                </a>
            </div>
        </div>
    </nav>

    <div class="mx-auto max-w-[1120px]">
        <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mt-4">History</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Riwayat Merchant</h1>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mt-6">
                <button type="button"
                        data-tab-btn="transaksi"
                        class="tab-trigger px-4 py-2 rounded-full bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold shadow-lg shadow-orange-100 transition-all duration-300">
                    History Transaksi
                </button>
                <button type="button"
                        data-tab-btn="keywords"
                        class="tab-trigger px-4 py-2 rounded-full bg-white text-gray-700 font-semibold shadow-sm hover:bg-orange-50 transition-all duration-300">
                    History Keywords
                </button>
            </div>

            @php
            $historySource = $histories ?? collect();

            if ($historySource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $historyPaginator = $historySource;
            } else {
                $collection = $historySource instanceof \Illuminate\Support\Collection
                    ? $historySource
                    : collect($historySource);

                $perPage = 12;
                $currentPage = request()->integer('page', 1);
                $items = $collection->forPage($currentPage, $perPage)->values();

                $historyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $items,
                    $collection->count(),
                    $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
            @endphp

            <section id="merchant-history" data-tab-panel="transaksi" class="mt-10 space-y-6 opacity-0 transition-all duration-500 ease-in-out">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Merchant Section</p>
                        <h2 class="text-xl font-bold text-gray-900">Transaksi Terakhir</h2>
                    </div>
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $historyPaginator->count() }} dari {{ $historyPaginator->total() }} data
                    </div>
                </div>

                <!-- Search and Date Filter for Transaksi -->
                <form method="GET" action="{{ url()->current() }}" id="transaksiSearchForm" class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <input type="hidden" name="tab" value="transaksi">
                    <div class="relative w-full max-w-[280px] sm:max-w-[240px]">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text"
                               name="search_transaksi"
                               id="transaksiSearchInput"
                               value="{{ request('search_transaksi', '') }}"
                               class="search-input"
                               placeholder="Cari MSISDN, Product, Keyword..."
                               onkeydown="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('transaksiSearchForm').submit(); }" />
                        @if(request()->has('search_transaksi'))
                            <button type="button" onclick="clearTransaksiSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        @include('partials.date-withdraw', ['filterId' => 'transaksiDateFilter'])
                    </div>
                    <input type="hidden" name="start_date" id="transaksiStartDate" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" id="transaksiEndDate" value="{{ request('end_date') }}">
                    @if(request()->has('search_transaksi') || request()->has('start_date') || request()->has('end_date'))
                        <a href="{{ url()->current() }}?tab=transaksi" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    @endif
                </form>

                <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="transaksi-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 1, 'date')">
                                        <div class="flex items-center gap-1">
                                            <span>Tanggal</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 2, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>MSISDN</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 3, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Merchant Name</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 4, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Product</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 5, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Keywords</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 6, 'number')">
                                        <div class="flex items-center justify-end gap-1">
                                            <span>Total Poin</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 7, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Merchant City</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('transaksi-table', 8, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Status</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($historyPaginator as $history)
                                    @php
                                        // Tanggal dari created_date tokodigi_tselpoin_redeem
                                        $displayDate = $history->tanggal ?? null;
                                        $formattedDate = $displayDate ? \Carbon\Carbon::parse($displayDate)->format('d/m/Y H:i') : '-';
                                        $msisdn = $history->msisdn ?? '-';
                                        $merchantName = $history->merchant_name ?? '-';
                                        $product = $history->product ?? '-';
                                        $keywords = $history->keywords ?? '-';
                                        $totalPoin = $history->total_poin ?? 0;
                                        $merchantCity = $history->merchant_city ?? '-';
                                        $status = $history->status ?? '-';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                            {{ ($historyPaginator->currentPage() - 1) * $historyPaginator->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $formattedDate }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $msisdn }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $merchantName }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $product }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $keywords }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-right">
                                            {{ is_numeric($totalPoin) ? number_format($totalPoin, 0, ',', '.') : $totalPoin }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $merchantCity }}
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if(strtolower($status) === 'approve')
                                                    bg-green-100 text-green-800
                                                @elseif(strtolower($status) === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif(strtolower($status) === 'reject')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Belum ada data transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($historyPaginator->hasPages())
                        <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-semibold">{{ $historyPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $historyPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $historyPaginator->total() }}</span> data
                            </div>
                            <div class="flex items-center space-x-2">
                                @if ($historyPaginator->onFirstPage())
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                @else
                                    <a href="{{ $historyPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach ($historyPaginator->getUrlRange(1, $historyPaginator->lastPage()) as $page => $url)
                                    @if ($page == $historyPaginator->currentPage())
                                        <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                            {{ $page }}
                                        </button>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if ($historyPaginator->hasMorePages())
                                    <a href="{{ $historyPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="md:hidden space-y-4 mt-6">
                    @forelse($historyPaginator as $history)
                        @php
                            // Tanggal dari created_date tokodigi_tselpoin_redeem
                            $displayDate = $history->tanggal ?? null;
                            $formattedDate = $displayDate ? \Carbon\Carbon::parse($displayDate)->format('d/m/Y H:i') : '-';
                            $msisdn = $history->msisdn ?? '-';
                            $merchantName = $history->merchant_name ?? '-';
                            $product = $history->product ?? '-';
                            $keywords = $history->keywords ?? '-';
                            $pointsValue = $history->total_poin ?? 0;
                            $merchantCity = $history->merchant_city ?? '-';
                            $statusLabel = $history->status ?? '-';
                        @endphp
                        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ ($historyPaginator->currentPage() - 1) * $historyPaginator->perPage() + $loop->iteration }}.
                                </span>
                                <span class="text-xs font-semibold text-gray-500">
                                    {{ $formattedDate }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">MSISDN</p>
                                    <p class="font-semibold text-gray-900">{{ $msisdn }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Merchant</p>
                                    <p class="font-semibold text-gray-900">{{ $merchantName }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Points</p>
                                    <p class="font-semibold text-gray-900">{{ is_numeric($pointsValue) ? number_format($pointsValue, 0, ',', '.') : $pointsValue }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Status</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($statusLabel) }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Product</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $product }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Keywords</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $keywords }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Merchant City</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $merchantCity }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-center text-gray-500">Belum ada data transaksi.</p>
                    @endforelse

                    @if($historyPaginator->hasPages())
                        <div class="bg-white px-4 py-4 border border-gray-200 rounded-2xl text-center space-y-3">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-semibold">{{ $historyPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $historyPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $historyPaginator->total() }}</span> data
                            </div>
                            <div class="flex items-center justify-center space-x-2">
                                @if ($historyPaginator->onFirstPage())
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                @else
                                    <a href="{{ $historyPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif
                                @foreach ($historyPaginator->getUrlRange(1, $historyPaginator->lastPage()) as $page => $url)
                                    @if ($page == $historyPaginator->currentPage())
                                        <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                            {{ $page }}
                                        </button>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                                @if ($historyPaginator->hasMorePages())
                                    <a href="{{ $historyPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <section id="keyword-history" data-tab-panel="keywords" class="mt-16 space-y-6 hidden opacity-0 transition-all duration-500 ease-in-out">
                <!-- Search for Keywords -->
                <form method="GET" action="{{ url()->current() }}" id="keywordSearchForm" class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <input type="hidden" name="tab" value="keywords">
                    <div class="relative w-full max-w-[280px] sm:max-w-[240px]">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text"
                               name="search_keyword"
                               id="keywordSearchInput"
                               value="{{ request('search_keyword', '') }}"
                               class="search-input"
                               placeholder="Cari Keyword ID, Produk..."
                               onkeydown="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('keywordSearchForm').submit(); }" />
                        @if(request()->has('search_keyword'))
                            <button type="button" onclick="clearKeywordSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                    @if(request()->has('search_keyword'))
                        <a href="{{ url()->current() }}?tab=keywords" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    @endif
                </form>

                <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="keyword-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table', 1, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Merchant</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table', 2, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Nama Produk</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table', 3, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Keyword ID</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table', 4, 'number')">
                                        <div class="flex items-center gap-1">
                                            <span>TRX</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table', 5, 'number')">
                                        <div class="flex items-center gap-1">
                                            <span>Stock</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table', 6, 'text')">
                                        <div class="flex items-center gap-1">
                                            <span>Status</span>
                                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($keywordPaginator as $keyword)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                            {{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $keyword->nama_produk }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $keyword->keyword_id ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->trx ?? 0 }}</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ $keyword->stock }}</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($keyword->status === 'approve')
                                                    bg-green-100 text-green-800
                                                @elseif($keyword->status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($keyword->status === 'reject')
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ ucfirst($keyword->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Belum ada data keyword.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($keywordPaginator->hasPages())
                        <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Menampilkan <span class="font-semibold">{{ $keywordPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywordPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $keywordPaginator->total() }}</span> data
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if ($keywordPaginator->onFirstPage())
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                @else
                                    <a href="{{ $keywordPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach ($keywordPaginator->getUrlRange(1, $keywordPaginator->lastPage()) as $page => $url)
                                    @if ($page == $keywordPaginator->currentPage())
                                        <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                            {{ $page }}
                                        </button>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if ($keywordPaginator->hasMorePages())
                                    <a href="{{ $keywordPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Riwayat Lengkap BlanjaPoin</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>
    <script>
    // Clear search functions
    function clearTransaksiSearch() {
        window.location.href = '{{ url()->current() }}?tab=transaksi';
    }

    function clearKeywordSearch() {
        window.location.href = '{{ url()->current() }}?tab=keywords';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('[data-tab-btn]');
        const tabPanels = document.querySelectorAll('[data-tab-panel]');
        if (!tabButtons.length || !tabPanels.length) {
            return;
        }

        const activeClasses = [
            'bg-gradient-to-r',
            'from-[#F81611]',
            'to-[#F0B100]',
            'text-white',
            'shadow-lg',
            'shadow-orange-100',
            'border-transparent'
        ];
        const inactiveClasses = [
            'bg-white',
            'text-gray-700',
            'border',
            'border-orange-200',
            'shadow-sm'
        ];

        const toggleButtonState = (button, isActive) => {
            activeClasses.forEach(cls => button.classList.toggle(cls, isActive));
            inactiveClasses.forEach(cls => button.classList.toggle(cls, !isActive));
        };

        const showTab = (target) => {
            tabButtons.forEach(button => {
                const isActive = button.dataset.tabBtn === target;
                toggleButtonState(button, isActive);
            });

            tabPanels.forEach(panel => {
                if (panel.dataset.tabPanel !== target) {
                    // Hide with fade out
                    panel.style.opacity = '0';
                    setTimeout(() => {
                        panel.classList.add('hidden');
                    }, 300);
                } else {
                    // Show with fade in
                    panel.classList.remove('hidden');
                    setTimeout(() => {
                        panel.style.opacity = '1';
                    }, 10);
                }
            });
        };

        tabButtons.forEach(button => {
            button.addEventListener('click', () => showTab(button.dataset.tabBtn));
        });

        // Check if there's a tab parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        const defaultTab = tabParam || 'transaksi';
        showTab(defaultTab);
    });

    // Sort table function
    function sortTable(tableId, columnIndex, type) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const header = table.querySelector(`thead th:nth-child(${columnIndex + 1})`);
        const allHeaders = table.querySelectorAll('thead th');
        
        // Remove sort icons from other headers
        allHeaders.forEach((th, idx) => {
            if (idx !== columnIndex) {
                const icon = th.querySelector('.sort-icon i');
                if (icon) {
                    icon.className = 'fas fa-sort';
                }
            }
        });

        // Get current sort direction
        const currentIcon = header.querySelector('.sort-icon i');
        let isAscending = true;
        
        if (currentIcon.classList.contains('fa-sort-up')) {
            isAscending = false;
            currentIcon.className = 'fas fa-sort-down';
        } else {
            currentIcon.className = 'fas fa-sort-up';
        }

        rows.sort((a, b) => {
            let aValue = a.cells[columnIndex].textContent.trim();
            let bValue = b.cells[columnIndex].textContent.trim();

            if (type === 'number') {
                // Remove thousand separators and parse as number
                aValue = parseFloat(aValue.replace(/\./g, '').replace(/,/g, '.')) || 0;
                bValue = parseFloat(bValue.replace(/\./g, '').replace(/,/g, '.')) || 0;
                return isAscending ? aValue - bValue : bValue - aValue;
            } else if (type === 'date') {
                // Parse date in format dd/mm/yyyy hh:mm
                aValue = parseDateString(aValue);
                bValue = parseDateString(bValue);
                return isAscending ? aValue - bValue : bValue - aValue;
            } else {
                // Text comparison
                if (isAscending) {
                    return aValue.localeCompare(bValue);
                } else {
                    return bValue.localeCompare(aValue);
                }
            }
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    function parseDateString(dateStr) {
        if (!dateStr || dateStr === '-') return 0;
        // Format: dd/mm/yyyy hh:mm
        const parts = dateStr.split(' ');
        if (parts.length !== 2) return 0;
        
        const dateParts = parts[0].split('/');
        const timeParts = parts[1].split(':');
        
        if (dateParts.length !== 3 || timeParts.length !== 2) return 0;
        
        return new Date(
            parseInt(dateParts[2]), // year
            parseInt(dateParts[1]) - 1, // month (0-indexed)
            parseInt(dateParts[0]), // day
            parseInt(timeParts[0]), // hour
            parseInt(timeParts[1]) // minute
        ).getTime();
    }
    </script>
</body>
</html>
