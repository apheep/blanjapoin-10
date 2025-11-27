@extends('layouts.app')
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History • {{ $merchant->nama_merchant }} | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.head')
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full shadow-sm">
        <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6">
            <div class="flex items-center">
                <a href="{{ route('home') }}">
                    <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="mx-auto max-w-[1120px]">
        <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">

            @php
            // Pastikan $keywords adalah paginator agar kompatibel dengan partial table-keyword
            if ($keywords instanceof \Illuminate\Support\Collection) {
                $perPage = 10;
                $currentPage = request()->integer('page', 1);
                $items = $keywords->forPage($currentPage, $perPage)->values();
                $keywords = new \Illuminate\Pagination\LengthAwarePaginator(
                    $items,
                    $keywords->count(),
                    $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
            @endphp

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mt-4">Overview</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-4">
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                            {{ $merchant->daerah ?? '-' }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-tags text-gray-400"></i>
                            {{ $merchant->kategori ?? '-' }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-key text-gray-400"></i>
                            {{ $keywords->total() }} Keyword
                        </span>
                    </div>
                </div>
            </div>

                @php
    // gabungkan query yg sudah ada (misal search/filter) + paksa tab=keyword
    $keywordPaginator = $keywords->appends(array_merge(request()->query(), ['tab' => 'keyword']));
@endphp

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
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->diskon ?? '-' }}</td>
                        <td class="px-4 py-4 text-xs text-gray-500">{{ $keyword->skb ?? '-' }}</td>
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

{{-- MOBILE VERSION --}}
<div class="md:hidden space-y-4" id="keyword-cards-container">
    @forelse($keywordPaginator as $keyword)
        <div id="keyword-card-{{ $keyword->id }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border-l-4 p-4 pl-5 keyword-row
            @if($keyword->status === 'approve')
                border-l-green-500
            @elseif($keyword->status === 'pending')
                border-l-yellow-500
            @elseif($keyword->status === 'reject')
                border-l-red-500
            @else
                border-l-gray-400
            @endif
        " data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
            {{-- Header dengan No, Status, dan Actions --}}
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <span class="text-sm font-bold text-gray-900">{{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}</span>
                    <!-- <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                        @if($keyword->status === 'approve')
                            bg-green-100 text-green-800
                        @elseif($keyword->status === 'pending')
                            bg-yellow-100 text-yellow-800
                        @elseif($keyword->status === 'reject')
                            bg-red-100 text-red-800
                        @endif
                    ">
                        {{ ucfirst($keyword->status) }}
                    </span> -->
                </div>
 
            </div>

            {{-- Grid Layout untuk informasi utama --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium mb-1 uppercase tracking-wide">Merchant</p>
                    <p class="text-xs font-bold text-gray-900 truncate" title="{{ $keyword->merchant->nama_merchant ?? '-' }}">{{ $keyword->merchant->nama_merchant ?? '-' }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-2.5 border border-blue-100">
                    <p class="text-[11px] text-blue-600 font-medium mb-1 uppercase tracking-wide">Stock</p>
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded-full bg-blue-600 text-white">{{ $keyword->stock }}</span>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-[11px] text-gray-500 font-medium mb-1.5 uppercase tracking-wide">Produk</p>
                <p class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $keyword->nama_produk }}</p>
            </div>

            @if($keyword->keyword_id)
            <div class="mb-4 bg-orange-50 rounded-lg p-2.5 border border-orange-100">
                <p class="text-[11px] text-orange-600 font-medium mb-1 uppercase tracking-wide">Keyword ID</p>
                <p class="text-xs font-bold text-orange-900">{{ $keyword->keyword_id }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium mb-1">Redeem</p>
                    <p class="text-xs font-bold text-gray-900">{{ $keyword->redeem ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                    <p class="text-[11px] text-gray-500 font-medium mb-1">Diskon</p>
                    <p class="text-xs font-bold text-gray-900">{{ $keyword->diskon ?? '-' }}</p>
                </div>
            </div>

            @if($keyword->skb)
            <div class="mb-4 bg-purple-50 rounded-lg p-2.5 border border-purple-100">
                <p class="text-[11px] text-purple-600 font-medium mb-1 uppercase tracking-wide">SKB</p>
                <p class="text-xs text-gray-700 leading-relaxed line-clamp-3">{{ $keyword->skb }}</p>
            </div>
            @endif

            @if($keyword->start_date || $keyword->end_date)
            <div class="mb-4">
                <p class="text-[11px] text-gray-500 font-medium mb-1.5 uppercase tracking-wide">Periode</p>
                <p class="text-xs font-medium text-gray-700">
                    {{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }} - 
                    {{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}
                </p>
            </div>
            @endif

            @if($keyword->cta_link)
            <div class="mb-4">
                <p class="text-[11px] text-gray-500 font-medium mb-1.5 uppercase tracking-wide">CTA Link</p>
                <a href="{{ $keyword->cta_link }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700 hover:underline truncate block font-medium" title="{{ $keyword->cta_link }}">{{ $keyword->cta_link }}</a>
            </div>
            @endif

            @if($keyword->image)
            <div class="mt-2 pt-2 border-t border-gray-200">
                <button type="button" 
                        onclick="previewKeywordImage('{{ asset('storage/' . $keyword->image) }}', '{{ basename($keyword->image) }}')"
                        class="w-full h-20 rounded-md overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors">
                    <img src="{{ asset('storage/' . $keyword->image) }}" 
                         alt="{{ $keyword->nama_produk }}" 
                         class="h-full w-full object-cover">
                </button>
            </div>
            @endif
        </div>
    @empty
        <p class="text-sm text-center text-gray-500">Belum ada data keyword.</p>
    @endforelse
    
    @if($keywordPaginator->hasPages())
        <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col items-center justify-center space-y-3 rounded-xl">
            <div class="text-sm text-gray-600 text-center">
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

                {{-- Pagination Elements (Simplified for Mobile) --}}
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


            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>
</body>
</html>

