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
                        <div class="flex flex-col p-2.5 space-y-1 flex-1">
                            <h3 class="text-base font-bold text-neutral-900 leading-tight truncate">
                                {{ $merchantName }}
                            </h3>
                            <div class="text-[9px] text-gray-500 -mt-0.5 -mb-0.5">
                                <span>Promo</span>
                            </div>
                            <div class="text-[10px] text-neutral-600 leading-snug">
                                @if(!is_null($result->diskon))
                                    <div class="font-bold text-red-500 flex items-center gap-1.5 mb-0.5">
                                        <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-7 h-7 object-contain">
                                        <span class="text-xl font-bold text-red-500">{{ formatDiskon($result->diskon) }}</span>
                                    </div>
                                @endif
                                @if($productName)
                                <div class="mb-0.5 font-semibold text-neutral-700 text-sm truncate">
                                    {{ $productName }}
                                </div>
                                @endif
                                @if($result->skb)
                                <button onclick="event.stopPropagation(); openSearchDescriptionSheet({{ $result->id }}, {{ json_encode($merchantName) }}, {{ json_encode($productName) }}, {{ json_encode($result->skb) }}, {{ json_encode($result->diskon ? formatDiskon($result->diskon) : null) }})" class="mt-0.5 text-[9px] font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                    Lihat Deskripsi
                                </button>
                                @endif
                            </div>
                            <div class="inline-flex items-center gap-1 bg-white rounded-full px-0.5 py-0.5 self-start">
                                <span class="inline-flex h-[18px] w-[18px] items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[7px] font-bold shadow-sm">P</span>
                                <span class="text-[18px] font-bold text-red-600">{{ number_format($result->redeem, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex flex-col gap-0.5 pt-0.5 border-t border-neutral-100 mt-auto">
                                <div class="flex items-center gap-1 text-[9px] text-neutral-600">
                                    <span class="font-medium">Stock:</span>
                                    <span class="font-semibold text-neutral-800">{{ $result->stock }}</span>
                                </div>
                                @if($result->end_date)
                                <div class="flex items-center gap-1 text-[9px] text-neutral-600">
                                    <span class="font-medium">Valid until:</span>
                                    <span class="font-semibold text-neutral-800">
                                        {{ \Carbon\Carbon::parse($result->end_date)->format('d M Y') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            @php
                                $canRedeem = !$result->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($result->start_date)->startOfDay());
                                $startDateFormatted = $result->start_date ? \Carbon\Carbon::parse($result->start_date)->format('d-M-y') : '';
                                $isStockEmpty = ($result->stock ?? 0) <= 0;
                            @endphp
                            @if($isStockEmpty)
                            <button disabled class="mt-1.5 w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-1 px-2.5 rounded-lg cursor-not-allowed text-[9px]">
                                Voucher Habis
                            </button>
                            @elseif($canRedeem)
                            <button onclick="window.open('{{ $result->cta_link ?? '#' }}', '_blank')" class="mt-1.5 w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-1 px-2.5 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-[9px]">
                                Redeem
                            </button>
                            @else
                            <button disabled class="mt-1.5 w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-1 px-2.5 rounded-lg cursor-not-allowed text-[9px]">
                                Open {{ $startDateFormatted }}
                            </button>
                            @endif
                        </div>
                    </div>

                        <!-- Desktop Layout -->
                    <div class="hidden lg:flex flex-col h-full">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-3 md:p-4 border-b border-neutral-100 flex-shrink-0 min-h-[70px] md:min-h-[80px]">
                            <div class="flex items-center gap-2.5 flex-1">
                                @if($merchant && $merchant->logo_merchant)
                                <div class="relative flex-shrink-0">
                                    <div class="absolute inset-0 rounded-xl blur-sm "></div>
                                    <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" alt="{{ $merchantName }}" class="relative w-11 h-11 md:w-14 md:h-14 object-contain rounded-xl  shadow-md">
                                </div>
                                @else
                                <div class="w-11 h-11 md:w-14 md:h-14 flex-shrink-0"></div>
                                @endif
                            </div>
                            @if($result->diskon)
                            <div class="text-right flex-shrink-0 ml-2">
                                <div class="inline-flex items-center gap-1.5">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-12 h-12 object-contain">
                                    <span class="text-base md:text-xl font-black text-red-600">{{ formatDiskon($result->diskon) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Image with Stock Overlay -->
                        <div class="relative px-3 md:px-4 pt-3 pb-2 flex-shrink-0">
                            <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                <img 
                                    src="{{ $result->image ? asset('storage/' . $result->image) : asset('storage/promo/promo-default.jpg') }}" 
                                    alt="{{ $productName }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    loading="lazy"
                                >
                            </div>
                            <div class="absolute bottom-1.5 right-3 md:bottom-2 md:right-4 bg-gradient-to-r from-black/60 to-black/50 backdrop-blur-sm text-white px-2 py-0.5 rounded-lg text-[10px] md:text-xs font-bold shadow-lg border border-white/10">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                    <span>Stock: {{ $result->stock }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="flex flex-col px-3 md:px-4 pb-3 md:pb-4 flex-1 min-h-0">
                            <h4 class="text-sm md:text-base font-black text-neutral-900 mb-1 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                                {{ $merchantName }}
                            </h4>
                            @if($productName)
                            <p class="text-base md:text-lg text-neutral-600 mb-1.5 leading-snug font-semibold truncate">
                                {{ $productName }}
                            </p>
                            @endif
                            @if($result->skb)
                            <button onclick="event.stopPropagation(); openSearchDescriptionSheet({{ $result->id }}, {{ json_encode($merchantName) }}, {{ json_encode($productName) }}, {{ json_encode($result->skb) }}, {{ json_encode($result->diskon ? formatDiskon($result->diskon) : null) }})" class="self-start text-left mb-1.5 text-[10px] md:text-xs font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                Lihat Deskripsi
                            </button>
                            @endif
                            @if($result->end_date)
                            <div class="flex items-center gap-1 text-[10px] text-neutral-500 mb-2">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">Valid until: <span class="font-semibold text-neutral-700">{{ \Carbon\Carbon::parse($result->end_date)->format('d M Y') }}</span></span>
                            </div>
                            @endif
                            <div class="mt-auto pt-2 border-t border-neutral-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex h-[18px] w-[18px] items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[7px] font-bold shadow-sm">P</span>
                                        <span class="text-lg md:text-xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                            {{ number_format($result->redeem, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @php
                                    $canRedeem = !$result->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($result->start_date)->startOfDay());
                                    $startDateFormatted = $result->start_date ? \Carbon\Carbon::parse($result->start_date)->format('d-M-y') : '';
                                    $isStockEmpty = ($result->stock ?? 0) <= 0;
                                @endphp
                                @if($isStockEmpty)
                                <button disabled class="w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2 px-3.5 rounded-lg cursor-not-allowed text-xs md:text-sm">
                                    Voucher Habis
                                </button>
                                @elseif($canRedeem)
                                <button onclick="window.open('{{ $result->cta_link ?? '#' }}', '_blank')" class="w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-2 px-3.5 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-xs md:text-sm">
                                    Redeem
                                </button>
                                @else
                                <button disabled class="w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2 px-3.5 rounded-lg cursor-not-allowed text-xs md:text-sm">
                                    Open {{ $startDateFormatted }}
                                </button>
                                @endif
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

<!-- Bottom Sheet / Modal (Responsive) -->
<div id="bottomSheet" class="fixed inset-0 z-[9999] hidden">
    <div id="bottomSheetOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closeBottomSheet()" style="opacity: 0;"></div>
    
    <!-- Mobile: Bottom Sheet -->
    <div id="bottomSheetPanel" class="md:hidden absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl overflow-hidden transition-transform duration-300 ease-out" style="height: 55vh; transform: translateY(100%);">
        <!-- Drag Indicator Bar -->
        <div class="w-full flex justify-center pt-2 pb-1">
            <div class="w-12 h-1 bg-neutral-300 rounded-full"></div>
        </div>
        <!-- Header -->
        <div class="bg-white px-5 py-3 flex items-center">
            <button onclick="closeBottomSheet()" class="text-neutral-700 hover:text-neutral-900 p-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
            <h3 id="bottomSheetTitle" class="flex-1 text-center text-lg font-bold text-neutral-800 pr-7">Pilihan</h3>
        </div>
        <div id="bottomSheetContent" class="overflow-y-auto" style="height: calc(55vh - 70px);"></div>
    </div>

    <!-- Desktop: Modal Popup -->
    <div id="desktopModal" class="hidden md:block fixed top-3/4 left-3/4 -translate-x-1/2 -translate-y-1/2 bg-white rounded-3xl shadow-2xl overflow-hidden transition-all duration-300 ease-out w-full max-w-2xl" style="opacity: 0; transform: translate(-50%, -50%) scale(0.95);">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-50 to-rose-50 px-6 py-4 flex items-center justify-between border-b border-neutral-200">
            <h3 id="desktopModalTitle" class="text-xl font-bold text-neutral-800">Pilihan</h3>
            <button onclick="closeBottomSheet()" class="text-neutral-700 hover:text-neutral-900 p-1 rounded-lg hover:bg-white/50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="desktopModalContent" class="overflow-y-auto p-6 max-h-[70vh]"></div>
    </div>
</div>

<script>
    // Bottom Sheet / Modal (responsive)
    function openBottomSheet(title, contentHTML) {
        const sheet = document.getElementById('bottomSheet');
        const overlay = document.getElementById('bottomSheetOverlay');
        
        // Mobile elements
        const panel = document.getElementById('bottomSheetPanel');
        const titleEl = document.getElementById('bottomSheetTitle');
        const contentEl = document.getElementById('bottomSheetContent');
        
        // Desktop elements
        const modal = document.getElementById('desktopModal');
        const modalTitleEl = document.getElementById('desktopModalTitle');
        const modalContentEl = document.getElementById('desktopModalContent');
        
        if (!sheet || !overlay) return;
        
        // Set content for both mobile and desktop
        if (titleEl) titleEl.textContent = title;
        if (contentEl) contentEl.innerHTML = contentHTML;
        if (modalTitleEl) modalTitleEl.textContent = title;
        if (modalContentEl) modalContentEl.innerHTML = contentHTML;
        
        // Show sheet/modal
        sheet.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        setTimeout(() => {
            overlay.style.opacity = '1';
            if (panel) panel.style.transform = 'translateY(0)';
            if (modal) {
                modal.style.opacity = '1';
                modal.style.transform = 'translate(-50%, -50%) scale(1)';
            }
        }, 10);
    }

    function closeBottomSheet() {
        const sheet = document.getElementById('bottomSheet');
        const overlay = document.getElementById('bottomSheetOverlay');
        const panel = document.getElementById('bottomSheetPanel');
        const modal = document.getElementById('desktopModal');
        const contentEl = document.getElementById('bottomSheetContent');
        const modalContentEl = document.getElementById('desktopModalContent');
        
        if (!sheet || !overlay) return;
        
        // Animate out
        overlay.style.opacity = '0';
        if (panel) panel.style.transform = 'translateY(100%)';
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transform = 'translate(-50%, -50%) scale(0.95)';
        }
        
        // Hide after animation
        setTimeout(() => {
            sheet.classList.add('hidden');
            document.body.style.overflow = '';
            if (contentEl) contentEl.innerHTML = '';
            if (modalContentEl) modalContentEl.innerHTML = '';
        }, 300);
    }

    // Function to format SKB text - split numbered items into separate lines
    function formatSKB(text) {
     if (!text) return '';
     
     // Check if text contains numbered items (1., 2., 3., etc.)
     const numberedPattern = /(\d+\.\s+)/g;
     const matches = text.match(numberedPattern);
     
     if (matches && matches.length > 1) {
      // Split by numbered patterns and format each item on a new line
      const parts = text.split(/(\d+\.\s+)/);
      let formatted = '';
      
      for (let i = 0; i < parts.length; i++) {
       const part = parts[i];
       if (!part.trim()) continue;
       
       // If this is a numbered item (like "1. "), add newline before it (except first)
       if (part.match(numberedPattern)) {
        if (i > 0 && formatted.trim()) {
         formatted += '\n';
        }
        formatted += part;
       } else {
        // This is content, add it
        formatted += part.trim();
        // Add newline if next part is a numbered item
        if (i < parts.length - 1 && parts[i + 1] && parts[i + 1].match(numberedPattern)) {
         formatted += '\n';
        }
       }
      }
      
      return formatted.trim();
     }
     
     // If no numbered pattern found, preserve existing line breaks
     return text;
    }

    // Search Description Bottom Sheet Function
    function openSearchDescriptionSheet(keywordId, merchantName, productName, skb, diskon) {
        const contentHTML = `
            <div class="px-5 pb-6">
                <div class="space-y-1">
                    <div>
                        <span class="text-sm font-semibold text-neutral-700">Merchant :</span>
                        <span class="text-sm text-neutral-900 ml-2">${merchantName || '-'}</span>
                    </div>
                    
                    ${productName ? `
                    <div>
                        <span class="text-sm font-semibold text-neutral-700">Produk :</span>
                        <span class="text-sm text-neutral-900 ml-2">${productName}</span>
                    </div>
                    ` : ''}
                    
                    <div>
                        <span class="text-sm font-semibold text-neutral-700">Promo :</span>
                        <span class="text-sm text-neutral-900 ml-2">${diskon || '-'}</span>
                    </div>
                    
                    ${skb ? `
                    <div>
                        <span class="text-sm font-semibold text-neutral-700">SKB :</span>
                        <div class="mt-0">
                            <div class="text-sm text-neutral-600 leading-none whitespace-pre-line break-words" style="line-height: 1.2;">
                                ${formatSKB(skb)}
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        openBottomSheet('Deskripsi', contentHTML);
    }
</script>
@endsection
