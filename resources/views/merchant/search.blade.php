@extends('layouts.app')

@section('content')
@php
    $query = $searchTerm ?? request('q', '');
@endphp

<div class="min-h-screen bg-[#e6ecf5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="fixed inset-0 bg-white z-50 flex items-center justify-center" style="opacity: 1; display: flex;">
            <div class="flex flex-col items-center gap-4">
                <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
                <div class="text-sm font-semibold text-neutral-600">Loading Please wait...</div>
            </div>
        </div>

        <!-- Header with Back Button and Points -->
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-neutral-600 hover:text-neutral-900 transition-colors">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white shadow-sm hover:shadow-md transition-shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            </a>
            
        </div>
        <!-- Search Form -->
        <form action="{{ route('merchant.search') }}" method="GET" class="mb-4 md:mb-5">
            <div class="relative">
                <input
                    type="text"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Cari voucher favorit kamu"
                    class="w-full rounded-xl md:rounded-2xl bg-white py-3 md:py-3.5 pl-11 md:pl-12 pr-4 text-sm md:text-base text-neutral-900 placeholder:text-neutral-400 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition-shadow"
                >
                <span class="absolute inset-y-0 left-3 md:left-4 flex items-center text-neutral-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
        </form>

        <!-- Search Results Header -->
        <div class="mb-3 md:mb-4 text-xs md:text-sm text-neutral-600">
            Search Results for <span class="font-bold text-neutral-900">"{{ $query }}"</span>
        </div>

        <!-- Results Grid (mengikuti layout card merchant: 2 mobile / 3 desktop) -->
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
            @forelse($searchResults as $result)
                @php
                    $merchant = $result->merchant;
                    $merchantName = optional($merchant)->nama_merchant ?? '';
                    $productName = $result->nama_produk ?? '';
                    $locationName = extractKabupatenKota(optional($merchant)->daerah ?? '');
                    $searchName = strtolower(trim($merchantName . ' ' . $productName));
                    $searchLocation = strtolower($locationName);
                    $uniqueId = 'search-card-' . $result->id;
                @endphp
                
                <article 
                    data-voucher-card="true"
                    data-point="{{ (int) $result->redeem }}"
                    data-search-name="{{ $searchName }}"
                    data-search-location="{{ $searchLocation }}"
                    class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 h-full min-h-[280px]"
                >
                    <!-- Mobile Layout -->
                    <div class="lg:hidden flex flex-col h-full">
                        <div class="relative">
                            <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                                <img 
                                    src="{{ $result->image ? asset('storage/' . $result->image) : asset('storage/promo/promo-default.jpg') }}" 
                                    alt="{{ $productName }}" 
                                    class="w-full h-full object-cover" 
                                    loading="lazy"
                                >
                            </div>
                        </div>
                        <div class="flex flex-col p-3 space-y-2 flex-1">
                            <h3 class="text-xl font-bold text-neutral-900 leading-tight">
                                {{ $merchantName }}
                            </h3>
                            <div class="text-[10px] text-neutral-600 -mt-1 -mb-1">
                                <span class="font-bold">Promo</span>
                            </div>
                            <div class="text-[11px] text-neutral-600 leading-relaxed">
                                @if(!is_null($result->diskon))
                                    <div class="font-bold text-red-500 flex items-center gap-2 mb-1">
                                        <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-7 h-7 object-contain">
                                        <span class="text-xl font-bold text-red-500">{{ formatDiskon($result->diskon) }}</span>
                                    </div>
                                @endif
                                @if($productName)
                                <div class="mb-1 font-semibold text-neutral-700">
                                    {{ $productName }}
                                </div>
                                @endif
                                @if($result->skb)
                                <div id="skb-mobile-{{ $result->id }}" class="hidden text-[11px] text-neutral-600 leading-relaxed mt-2">
                                    {{ $result->skb }}
                                </div>
                                <button onclick="event.stopPropagation(); toggleSkb('{{ $result->id }}', 'mobile')" class="mt-1 text-[10px] font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                    See Details skb
                                </button>
                                @endif
                            </div>
                            <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-0.5 self-start">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                <span class="text-[20px] font-bold text-red-600">{{ number_format($result->redeem, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100 mt-auto">
                                <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                    <span class="font-medium">Stock:</span>
                                    <span class="font-semibold text-neutral-800">{{ $result->stock }}</span>
                                </div>
                                @if($result->end_date)
                                <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                    <span class="font-medium">Valid until:</span>
                                    <span class="font-semibold text-neutral-800">
                                        {{ \Carbon\Carbon::parse($result->end_date)->format('d M Y') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <button onclick="window.open('{{ $result->cta_link ?? '#' }}', '_blank')" class="mt-2 w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-2 px-4 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-xs">
                                Redeem Voucher
                            </button>
                        </div>
                    </div>

                    <!-- Desktop Layout -->
                    <div class="hidden lg:flex flex-col h-full">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b border-neutral-100 flex-shrink-0 min-h-[80px] md:min-h-[90px]">
                            <div class="flex items-center gap-3 flex-1">
                                @if($merchant && $merchant->logo_merchant)
                                <div class="relative flex-shrink-0">
                                    <div class="absolute inset-0 rounded-xl blur-sm "></div>
                                    <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" alt="{{ $merchantName }}" class="relative w-12 h-12 md:w-16 md:h-16 object-contain rounded-xl  shadow-md">
                                </div>
                                @else
                                <div class="w-12 h-12 md:w-16 md:h-16 flex-shrink-0"></div>
                                @endif
                            </div>
                            @if($result->diskon)
                            <div class="text-right flex-shrink-0 ml-2">
                                <div class="inline-flex items-center gap-2">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-14 h-14 object-contain">
                                    <span class="text-base md:text-2xl font-black text-red-600">{{ formatDiskon($result->diskon) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Image with Stock Overlay -->
                        <div class="relative px-4 md:px-5 pt-4 pb-3 flex-shrink-0">
                            <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                <img 
                                    src="{{ $result->image ? asset('storage/' . $result->image) : asset('storage/promo/promo-default.jpg') }}" 
                                    alt="{{ $productName }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    loading="lazy"
                                >
                            </div>
                            <div class="absolute bottom-2 right-4 md:bottom-3 md:right-5 bg-gradient-to-r from-black/60 to-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-xs md:text-sm font-bold shadow-lg border border-white/10">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                    <span>Stock: {{ $result->stock }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="flex flex-col px-4 md:px-5 pb-4 md:pb-5 flex-1 min-h-0">
                            <h4 class="text-base md:text-lg font-black text-neutral-900 mb-2 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                                {{ $merchantName }}
                            </h4>
                            @if($productName)
                            <p class="text-xs md:text-sm text-neutral-600 mb-2.5 leading-relaxed font-semibold">
                                {{ $productName }}
                            </p>
                            @endif
                            @if($result->skb)
                            <div id="skb-desktop-{{ $result->id }}" class="hidden text-xs md:text-sm text-neutral-600 mb-2.5 leading-relaxed">
                                {{ $result->skb }}
                            </div>
                            <button onclick="event.stopPropagation(); toggleSkb('{{ $result->id }}', 'desktop')" class="self-start text-left mb-2.5 text-xs md:text-sm font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                See Details skb
                            </button>
                            @endif
                            @if($result->end_date)
                            <div class="flex items-center gap-1.5 text-xs text-neutral-500 mb-3">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">Valid until: <span class="font-semibold text-neutral-700">{{ \Carbon\Carbon::parse($result->end_date)->format('d M Y') }}</span></span>
                            </div>
                            @endif
                            <div class="mt-auto pt-3 border-t border-neutral-100">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                        <span class="text-xl md:text-2xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                            {{ number_format($result->redeem, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <button onclick="window.open('{{ $result->cta_link ?? '#' }}', '_blank')" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-2.5 px-4 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm md:text-base">
                                    Redeem Voucher
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-2 lg:col-span-3 rounded-2xl border-2 border-dashed border-neutral-300 bg-white p-8 md:p-10 text-center">
                    <div class="text-neutral-400 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-neutral-500 text-sm md:text-base">Tidak ada voucher yang sesuai dengan pencarian "<strong>{{ $query }}</strong>"</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        var spinner = document.getElementById('loadingSpinner');
        if (!spinner) {
            return;
        }

        spinner.style.transition = 'opacity 0.3s ease';
        spinner.style.opacity = '0';
        spinner.style.pointerEvents = 'none';

        setTimeout(function () {
            spinner.style.display = 'none';
        }, 300);
    });
</script>

<script>
function toggleSkb(keywordId, layout) {
    const skbElement = document.getElementById(`skb-${layout}-${keywordId}`);
    const button = event.target;
    
    if (skbElement.classList.contains('hidden')) {
        skbElement.classList.remove('hidden');
        button.textContent = 'Hide Details';
    } else {
        skbElement.classList.add('hidden');
        button.textContent = 'See Details';
    }
}
</script>
@endsection
