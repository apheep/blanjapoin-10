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

        <!-- Results Grid -->
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-1 lg:gap-5">
            @forelse($searchResults as $result)
                @php
                    $merchant = $result->merchant;
                    $merchantName = optional($merchant)->nama_merchant ?? '';
                    $productName = $result->nama_produk ?? '';
                    $locationName = optional($merchant)->daerah ?? '';
                @endphp
                
                <article 
                    onclick="window.open('{{ $result->cta_link ?? '#' }}', '_blank')" 
                    class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] hover:border-blue-200 cursor-pointer h-full"
                >
                    <!-- Mobile Layout (2 columns) -->
                    <div class="lg:hidden flex flex-col h-full">
                        <!-- Image -->
                        <div class="relative">
                            <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 overflow-hidden">
                                <img 
                                    src="{{ $result->image ? asset('storage/' . $result->image) : asset('storage/promo/promo-default.jpg') }}" 
                                    alt="{{ $productName }}" 
                                    class="w-full h-full object-cover" 
                                    loading="lazy"
                                >
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex flex-col p-3 space-y-2 flex-1">
                            <!-- Merchant Name -->
                            <h3 class="text-lg md:text-xl font-bold text-neutral-900 leading-tight line-clamp-2">
                                {{ $merchantName ?: strtoupper(trim($productName)) }}
                            </h3>
                            
                            <!-- Description -->
                            <div class="text-xs md:text-sm text-neutral-600 leading-relaxed">
                                @if(!is_null($result->diskon))
                                <div class="font-bold text-neutral-800 mb-1">
                                    Diskon <span class="text-base md:text-lg font-bold">{{ $result->diskon }}</span>
                                </div>
                                @endif
                                @if($result->skb)
                                <div class="line-clamp-2">{{ $result->skb }}</div>
                                @endif
                            </div>
                            
                            <!-- Points Badge -->
                            <div class="inline-flex items-center gap-1.5 rounded-full px-0.5 py-0.5 self-start">
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                <span class="text-lg md:text-xl font-bold text-red-600">{{ number_format($result->redeem, 0, ',', '.') }}</span>
                            </div>
                            
                            <!-- Stock & Valid Info -->
                            <div class="flex flex-col gap-0.5 pt-2 mt-auto border-t border-neutral-100">
                                <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                    <span class="font-medium">Stock:</span>
                                    <span class="font-bold text-neutral-800">{{ $result->stock }}</span>
                                </div>
                                @if($result->end_date)
                                <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                    <span class="font-medium">Valid until:</span>
                                    <span class="font-bold text-neutral-800">
                                        {{ \Carbon\Carbon::parse($result->end_date)->format('d M Y') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Layout (1 column horizontal) -->
                    <div class="hidden lg:block">
                        <div class="grid grid-cols-[auto_1fr_auto] gap-0 items-center">
                            <!-- Left: Points & Logo -->
                            <div class="p-4 md:p-6 flex flex-col items-start gap-3">
                                <div class="inline-flex items-center gap-2 bg-white rounded-full px-3 py-1.5 shadow-md border border-orange-200">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
                                    <span class="text-sm font-bold text-red-600">{{ number_format($result->redeem, 0, ',', '.') }}</span>
                                </div>
                                @if($merchant && $merchant->logo_merchant)
                                <div>
                                    <img 
                                        src="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                        alt="{{ $merchantName }}" 
                                        class="w-[100px] h-[100px] md:w-[140px] md:h-[140px] object-contain rounded-full" 
                                        loading="lazy"
                                    >
                                </div>
                                @endif
                            </div>
                            
                            <!-- Middle: Content -->
                            <div class="p-4 md:p-6 flex flex-col justify-center">
                                <h3 class="text-2xl md:text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 leading-tight">
                                    {{ $merchantName ?: strtoupper(trim($productName)) }}
                                </h3>
                                @if(!is_null($result->diskon))
                                <div class="mb-2">
                                    <div class="text-lg md:text-xl font-bold text-neutral-900 mb-1">Diskon</div>
                                    <div class="text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-900 leading-none mb-1">
                                        {{ $result->diskon }}
                                    </div>
                                </div>
                                @endif
                                <div class="text-sm md:text-base text-neutral-700 leading-relaxed">
                                    {{ $result->skb }}
                                </div>
                            </div>
                            
                            <!-- Right: Image -->
                            <div class="p-2 max-w-[400px] lg:max-w-[520px]">
                                <div class="aspect-[6/3] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 overflow-hidden">
                                    <img 
                                        src="{{ $result->image ? asset('storage/' . $result->image) : asset('storage/promo/promo-default.jpg') }}" 
                                        alt="{{ $productName }}" 
                                        class="w-full h-full object-cover" 
                                        loading="lazy"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bottom Footer -->
                        <div class="flex items-center justify-between px-4 md:px-6 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600">
                            <span class="font-medium">Stock {{ $result->stock }}</span>
                            @if($result->end_date)
                            <span class="font-medium">
                                Valid until {{ \Carbon\Carbon::parse($result->end_date)->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-2 lg:col-span-1 rounded-2xl border-2 border-dashed border-neutral-300 bg-white p-8 md:p-10 text-center">
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
@endsection
