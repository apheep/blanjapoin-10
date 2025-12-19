@extends('layouts.app')
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $merchant->nama_merchant }} - Voucher | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-white z-50 flex items-center justify-center" style="opacity: 1; display: flex;">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
            <div class="text-sm font-semibold text-neutral-600">Loading Please wait...</div>
        </div>
    </div>

    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full ">
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
            @include('partials.banner-carousel', ['iklans' => $iklans])

                <div class="flex flex-wrap items-start justify-between gap-4 pl-1 mt-10 md:mt-12">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</h1>
                </div>
            </div>

            <!-- Voucher Section -->
            <section class="mt-4 md:mt-6">

                @if($keywords->count() > 0)
                    <!-- Voucher Grid -->
                    <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
                        @foreach($keywords as $keyword)
                            @php
                                $merchant = $keyword->merchant;
                                $merchantName = optional($merchant)->nama_merchant ?? '';
                                $productName = $keyword->nama_produk ?? '';
                                $locationName = extractKabupatenKota(optional($merchant)->daerah ?? '');
                                $searchName = strtolower(trim($merchantName . ' ' . $productName));
                                $searchLocation = strtolower($locationName);
                                $canRedeem = !$keyword->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($keyword->start_date)->startOfDay());
                                $startDateFormatted = $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d-M-y') : '';
                            @endphp
                            <article 
                                data-voucher-card="true"
                                data-point="{{ (int) $keyword->redeem }}"
                                data-search-name="{{ $searchName }}"
                                data-search-location="{{ $searchLocation }}"
                                class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 opacity-0 translate-y-2 duration-200 ease-out h-full min-h-[280px]"
                            >
                                <!-- Mobile Layout -->
                                <div class="lg:hidden flex flex-col h-full">
                                    <div class="relative">
                                        <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" 
                                                 alt="{{ $keyword->nama_produk }}" 
                                                 class="w-full h-full object-cover" 
                                                 loading="lazy">
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
                                            @if(!is_null($keyword->diskon))
                                            <div class="font-bold text-red-500 flex items-center gap-1.5 mb-0.5">
                                                <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-7 h-7 object-contain">
                                                <span class="text-xl font-bold text-red-500">{{ formatDiskon($keyword->diskon) }}</span>
                                            </div>
                                            @endif
                                            @if($productName)
                                            <div class="mb-0.5 font-semibold text-neutral-700 text-sm truncate">
                                                {{ $productName }}
                                            </div>
                                            @endif
                                            @if($keyword->skb)
                                            <button onclick="event.stopPropagation(); openTerritorialDescriptionSheet({{ $keyword->id }}, {{ json_encode($merchantName) }}, {{ json_encode($productName) }}, {{ json_encode($keyword->skb) }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : null) }})" class="mt-0.5 text-[9px] font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                                Lihat Deskripsi
                                            </button>
                                            @endif
                                        </div>
                                        <div class="inline-flex items-center gap-1 bg-white rounded-full px-0.5 py-0.5 self-start">
                                            <span class="inline-flex h-[18px] w-[18px] items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[7px] font-bold shadow-sm">P</span>
                                            <span class="text-[18px] font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex flex-col gap-0.5 pt-0.5 border-t border-neutral-100 mt-auto">
                                            <div class="flex items-center gap-1 text-[9px] text-neutral-600">
                                                <span class="font-medium">Stock:</span>
                                                <span class="font-semibold text-neutral-800">{{ $keyword->stock }}</span>
                                            </div>
                                            @if($keyword->end_date)
                                            <div class="flex items-center gap-1 text-[9px] text-neutral-600">
                                                <span class="font-medium">Valid until:</span>
                                                <span class="font-semibold text-neutral-800">
                                                    {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>

                                        @if($canRedeem)
                                        <button onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="mt-1.5 w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-1 px-2.5 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-[9px]">
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
                                        @if($keyword->diskon)
                                        <div class="text-right flex-shrink-0 ml-2">
                                            <div class="inline-flex items-center gap-1.5">
                                                <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-12 h-12 object-contain">
                                                <span class="text-base md:text-xl font-black text-red-600">{{ formatDiskon($keyword->diskon) }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Image with Stock Overlay -->
                                    <div class="relative px-3 md:px-4 pt-3 pb-2 flex-shrink-0">
                                        <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" 
                                                 alt="{{ $productName }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                                 loading="lazy">
                                        </div>
                                        <div class="absolute bottom-1.5 right-3 md:bottom-2 md:right-4 bg-gradient-to-r from-black/60 to-black/50 backdrop-blur-sm text-white px-2 py-0.5 rounded-lg text-[10px] md:text-xs font-bold shadow-lg border border-white/10">
                                            <span class="inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                                <span>Stock: {{ $keyword->stock }}</span>
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
                                        @if($keyword->skb)
                                        <button onclick="event.stopPropagation(); openTerritorialDescriptionSheet({{ $keyword->id }}, {{ json_encode($merchantName) }}, {{ json_encode($productName) }}, {{ json_encode($keyword->skb) }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : null) }})" class="self-start text-left mb-1.5 text-[10px] md:text-xs font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                            Lihat Deskripsi
                                        </button>
                                        @endif
                                        @if($keyword->end_date)
                                        <div class="flex items-center gap-1 text-[10px] text-neutral-500 mb-2">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="truncate">Valid until: <span class="font-semibold text-neutral-700">{{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}</span></span>
                                        </div>
                                        @endif
                                        <div class="mt-auto pt-2 border-t border-neutral-100">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="inline-flex h-[18px] w-[18px] items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[7px] font-bold shadow-sm">P</span>
                                                    <span class="text-lg md:text-xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                                        {{ number_format($keyword->redeem, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if($canRedeem)
                                            <button onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-2 px-3.5 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-xs md:text-sm">
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
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12 md:py-16">
                        <div class="inline-block p-6 bg-gradient-to-br from-orange-50 to-rose-50 rounded-3xl mb-4">
                            <i class="fas fa-ticket-alt text-6xl text-orange-400"></i>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-neutral-900 mb-2">
                            Belum Ada Voucher
                        </h3>
                        <p class="text-sm md:text-base text-neutral-600">
                            Saat ini belum ada voucher yang tersedia untuk merchant ini.
                        </p>
                    </div>
                @endif
            </section>

            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

    <!-- Script untuk hide loading spinner -->
    <script>
        // Hide loading spinner when page loads
        window.addEventListener('load', function() {
            const spinner = document.getElementById('loadingSpinner');
            if (spinner) {
                spinner.style.opacity = '0';
                setTimeout(() => {
                    spinner.style.display = 'none';
                }, 300);
            }
        });

        // Animate cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards with intersection observer
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        cardObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '0px 0px -10px 0px' });
            
            // Observe all cards
            const cards = document.querySelectorAll('article[class*="opacity-0"]');
            cards.forEach((card, index) => {
                // Immediately observe the card
                cardObserver.observe(card);
                
                // Fallback: animate cards that are already visible
                setTimeout(() => {
                    const rect = card.getBoundingClientRect();
                    const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;
                    if (isInViewport && (card.style.opacity === '0' || !card.style.opacity)) {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                        cardObserver.unobserve(card);
                    }
                }, 200 + (index * 50));
            });
            
            // Final fallback: show all cards after 1 second if still hidden
            setTimeout(() => {
                cards.forEach(card => {
                    if (card.style.opacity === '0' || !card.style.opacity) {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }
                });
            }, 1000);
        });

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

        // Territorial Description Bottom Sheet Function
        function openTerritorialDescriptionSheet(keywordId, merchantName, productName, skb, diskon) {
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
</body>
</html>