@props([
    'paginator' => null,
    'onFirstPage' => true,
    'hasMorePages' => false,
    'currentPage' => 1,
    'lastPage' => 1,
    'previousPageUrl' => '#',
    'nextPageUrl' => '#',
    'showInfo' => true,
    'info' => '',
    'class' => ''
])

@if($paginator && $paginator->lastPage() > 1)
<div class="w-full {{ $class }}">
    <!-- Info Text -->
    @if($showInfo && $paginator)
    <div class="text-sm text-gray-600 mb-4">
        <span>Menampilkan</span>
        <span class="font-semibold">{{ $paginator->firstItem() ?? 0 }}</span>
        <span>hingga</span>
        <span class="font-semibold">{{ $paginator->lastItem() ?? 0 }}</span>
        <span>dari</span>
        <span class="font-semibold">{{ $paginator->total() ?? 0 }}</span>
        <span>data</span>
    </div>
    @endif

    <!-- Pagination Container -->
    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
        {{-- Previous Button --}}
        @if($paginator->onFirstPage())
            <button disabled class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed transition-all">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all duration-200">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $range = 2;
            $start = max(1, $currentPage - $range);
            $end = min($lastPage, $currentPage + $range);
        @endphp

        {{-- First Page --}}
        @if($start > 1)
            <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all duration-200">
                1
            </a>
        @endif

        {{-- Left Ellipsis --}}
        @if($start > 2)
            <div class="inline-flex items-center justify-center w-10 h-10 text-gray-400">
                <span class="text-lg leading-none">…</span>
            </div>
        @endif

        {{-- Middle Pages --}}
        @for($page = $start; $page <= $end; $page++)
            @if($page == $currentPage)
                <button disabled class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-r from-orange-500 to-red-500 text-sm font-semibold text-white shadow-md cursor-default transition-all duration-200">
                    {{ $page }}
                </button>
            @else
                <a href="{{ $paginator->url($page) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all duration-200">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Right Ellipsis --}}
        @if($end < $lastPage - 1)
            <div class="inline-flex items-center justify-center w-10 h-10 text-gray-400">
                <span class="text-lg leading-none">…</span>
            </div>
        @endif

        {{-- Last Page --}}
        @if($end < $lastPage)
            <a href="{{ $paginator->url($lastPage) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all duration-200">
                {{ $lastPage }}
            </a>
        @endif

        {{-- Next Button --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:border-orange-300 hover:text-orange-600 transition-all duration-200">
                <i class="fas fa-chevron-right text-sm"></i>
            </a>
        @else
            <button disabled class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed transition-all">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
        @endif
    </nav>
</div>
@endif
