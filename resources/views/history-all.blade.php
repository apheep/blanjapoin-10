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
                        class="tab-trigger px-4 py-2 rounded-full bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold shadow-lg shadow-orange-100">
                    History Transaksi
                </button>
                <button type="button"
                        data-tab-btn="keywords"
                        class="tab-trigger px-4 py-2 rounded-full bg-white text-gray-700 font-semibold shadow-sm hover:bg-orange-50">
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

            <section id="merchant-history" data-tab-panel="transaksi" class="mt-10 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Merchant Section</p>
                        <h2 class="text-xl font-bold text-gray-900">Transaksi Terakhir</h2>
                    </div>
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $historyPaginator->count() }} dari {{ $historyPaginator->total() }} data
                    </div>
                </div>

                <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">MSISDN</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keywords</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Poin</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant City</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
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

            <section id="keyword-history" data-tab-panel="keywords" class="mt-16 space-y-6 hidden">
                <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keyword ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA LINK</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200" id="keyword-table-body">
                                @forelse($keywordPaginator as $keyword)
                                    <tr id="keyword-row-{{ $keyword->id }}" class="hover:bg-gray-50 transition-colors keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                            {{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}
                                        <!-- </td>

                                            <td id="keyword-status-{{ $keyword->id }}" class="px-4 py-4">
                                            <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full
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
                                        </td> -->
                                    


                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $keyword->nama_produk }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <div class="font-medium">{{ $keyword->keyword_id ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            <a href="{{ $keyword->cta_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $keyword->cta_link }}</a>
                                        </td>   
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->redeem ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->diskon ? formatDiskon($keyword->diskon) : '-' }}</td>
                                        <td class="px-4 py-4 w-48">
                                            @if($keyword->skb)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-gray-500 truncate flex-1" title="{{ $keyword->skb }}">
                                                        {{ Str::limit($keyword->skb, 20, '...') }}
                                                    </span>
                                                    <button type="button"
                                                            onclick="showSKBDetail({{ json_encode($keyword->skb) }}, {{ json_encode($keyword->nama_produk) }}, {{ json_encode($keyword->merchant->nama_merchant ?? '-') }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : '-') }})"
                                                            class="px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 rounded transition-colors whitespace-nowrap flex-shrink-0"
                                                            title="Lihat Detail SKB">
                                                        Detail
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-xs text-gray-500">
                                            @if($keyword->start_date || $keyword->end_date)
                                                <div>{{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }}</div>
                                                <div>{{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}</div>
                                            @else
                                                <div>-</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($keyword->image)
                                                <img src="{{ asset('storage/' . $keyword->image) }}" 
                                                    alt="{{ $keyword->nama_produk }}" 
                                                    class="h-10 w-16 object-cover rounded">
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="px-4 py-4 text-center text-sm text-gray-500">
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
                                {{-- Previous Page Link --}}
                                @if ($keywordPaginator->onFirstPage())
                                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                @else
                                    <a href="{{ $keywordPaginator->previousPageUrl() }}" class="keyword-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($keywordPaginator->getUrlRange(1, $keywordPaginator->lastPage()) as $page => $url)
                                    @if ($page == $keywordPaginator->currentPage())
                                        <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                            {{ $page }}
                                        </button>
                                    @else
                                        <a href="{{ $url }}" class="keyword-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($keywordPaginator->hasMorePages())
                                    <a href="{{ $keywordPaginator->nextPageUrl() }}" class="keyword-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
            });
        };

        tabButtons.forEach(button => {
            button.addEventListener('click', () => showTab(button.dataset.tabBtn));
        });

        const defaultTab = document.querySelector('[data-tab-btn]')?.dataset.tabBtn || 'transaksi';
        showTab(defaultTab);
    });

    // Function to show SKB detail in modal
    function showSKBDetail(skbText, productName, merchantName, promoText) {
        // Create modal overlay
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end md:items-center justify-center p-0 md:p-4';
        overlay.id = 'skb-modal-overlay';
        overlay.onclick = function(e) {
            if (e.target === overlay) {
                closeSKBModal();
            }
        };

        // Create modal content - responsive: bottom sheet on mobile, centered on desktop
        const modal = document.createElement('div');
        modal.className = 'bg-white rounded-t-3xl md:rounded-xl shadow-2xl max-w-2xl w-full max-h-[85vh] md:max-h-[80vh] overflow-hidden flex flex-col';
        
        // Set initial state for animation
        const isMobile = window.innerWidth < 768;
        if (isMobile) {
            modal.style.transform = 'translateY(100%)';
            modal.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        } else {
            modal.style.transform = 'scale(0.95) translateY(-10px)';
            modal.style.opacity = '0';
            modal.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        }
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        
        // Drag handle for mobile (top bar)
        const dragHandle = document.createElement('div');
        dragHandle.className = 'md:hidden pt-3 pb-2 flex justify-center';
        dragHandle.innerHTML = `
            <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
        `;
        
        // Modal header
        const header = document.createElement('div');
        header.className = 'px-6 py-4 border-b border-gray-200 flex items-center justify-between';
        header.innerHTML = `
            <h3 class="text-lg font-semibold text-gray-900">Deskripsi</h3>
            <button onclick="closeSKBModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        `;

        // Modal body
        const body = document.createElement('div');
        body.className = 'px-6 py-4 overflow-y-auto flex-1';
        body.innerHTML = `
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Merchant:</p>
                    <p class="text-sm text-gray-900">${merchantName}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Produk:</p>
                    <p class="text-sm text-gray-900">${productName}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Promo:</p>
                    <p class="text-sm text-gray-900">${promoText}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-0">SKB:</p>
                    <div class="bg-gray-50 rounded-lg p-2 border border-gray-200 mt-0">
                        <p class="text-sm text-gray-700 leading-none whitespace-pre-wrap break-words" style="line-height: 1.2;">${skbText}</p>
                    </div>
                </div>
            </div>
        `;

        // Modal footer
        const footer = document.createElement('div');
        footer.className = 'px-6 py-4 border-t border-gray-200 flex justify-end gap-2';
        const copyButton = document.createElement('button');
        copyButton.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors';
        copyButton.innerHTML = '<i class="fas fa-copy mr-2"></i>Copy';
        copyButton.onclick = function() {
            copySKBToClipboard(skbText, copyButton);
        };
        
        const closeButton = document.createElement('button');
        closeButton.className = 'px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors';
        closeButton.textContent = 'Tutup';
        closeButton.onclick = closeSKBModal;
        
        footer.appendChild(copyButton);
        footer.appendChild(closeButton);

        modal.appendChild(dragHandle);
        modal.appendChild(header);
        modal.appendChild(body);
        modal.appendChild(footer);
        overlay.appendChild(modal);
        document.body.appendChild(overlay);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                if (isMobile) {
                    modal.style.transform = 'translateY(0)';
                } else {
                    modal.style.transform = 'scale(1) translateY(0)';
                    modal.style.opacity = '1';
                }
                overlay.style.opacity = '1';
            });
        });
    }

    function closeSKBModal() {
        const overlay = document.getElementById('skb-modal-overlay');
        if (overlay) {
            const modal = overlay.querySelector('div[class*="rounded"]');
            if (modal) {
                const isMobile = window.innerWidth < 768;
                
                // Ensure transitions are set before animating
                if (isMobile) {
                    modal.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    modal.style.transform = 'translateY(100%)';
                } else {
                    modal.style.transition = 'transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
                    modal.style.transform = 'scale(0.95) translateY(-10px)';
                    modal.style.opacity = '0';
                }
                
                overlay.style.transition = 'opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                overlay.style.opacity = '0';
                
                // Remove after animation completes
                setTimeout(() => {
                    overlay.remove();
                    document.body.style.overflow = '';
                }, 300);
            }
        }
    }

    function copySKBToClipboard(text, button) {
        navigator.clipboard.writeText(text).then(() => {
            // Show success message
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            alert('Gagal menyalin teks');
        });
    }
    </script>
</body>
</html>
