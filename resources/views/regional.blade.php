<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merchant Regional {{ $locationName }} - BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
@include('partials.head')
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen">
    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-white z-50 flex items-center justify-center" style="opacity: 1; display: flex;">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
            <div class="text-sm font-semibold text-neutral-600">Loading Please wait...</div>
        </div>
    </div>

    <div class="w-full bg-white relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-full pointer-events-none block md:block"
             style="background-image: url('{{ asset('dot_background.png') }}');
                    background-repeat: repeat;
                    background-size: 1750px 1750px;
                    opacity: 0.8;
                    -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,0.9) 20%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.1) 80%, rgba(0,0,0,0) 100%);
                    mask-image: linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,0.9) 20%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.1) 80%, rgba(0,0,0,0) 100%);">
        </div>

        <!-- Navbar -->
        <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm transition-shadow duration-300 w-full">
            <div class="mx-auto w-full max-w-[1400px] px-4 md:px-6 lg:px-10 py-4 md:py-5 lg:py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}">
                            <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                        </a>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mx-auto w-full max-w-[1400px] px-4 md:px-8 lg:px-10 pb-12 relative z-10">
            <!-- Banner Carousel -->
            @include('partials.banner-carousel', ['iklans' => $iklans])

            <!-- Header Section -->
            <div class="mt-8 md:mt-12 mb-2">
                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-sm font-semibold text-gray-700">Regional</span>
                    <span class="text-gray-400">/</span>
                    <span class="text-sm font-semibold text-orange-600">{{ $locationName }}</span>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">
                    Merchant di {{ $locationName }}
                </h1>
                <p class="text-gray-600">
                    Temukan merchant dan promo menarik di {{ $locationName }}
                </p>
            </div>
            
            {{-- <!-- Spesial Promo Section -->
            <div class="mt-0 md:mt-1 -mb-6 md:-mb-8">
                <style>
                    main .special-promo-wrapper > div > section {
                        margin-top: 0.5rem !important;
                        margin-bottom: 1rem !important;
                    }
                    @media (min-width: 640px) {
                        main .special-promo-wrapper > div > section {
                            margin-top: 0.75rem !important;
                            margin-bottom: 1.25rem !important;
                        }
                    }
                </style>
                <div class="special-promo-wrapper">
                    @include('partials.spesial_promo')
                </div>
            </div> --}}

            <!-- Category Sections -->
            @php
                // Helper function to check if category has data
                $isRegional = $isRegional ?? false;
                $hasCategoryData = function($category) use ($keywords, $isRegional) {
                    return $keywords->filter(function ($keyword) use ($category, $isRegional) {
                        $keywordCategory = !empty($keyword->kategori_keyword) ? $keyword->kategori_keyword : ($keyword->merchant->kategori ?? null);
                        $baseCondition = $keyword->merchant && $keywordCategory === $category
                            && $keyword->status === 'approve'
                            && $keyword->is_active == 1;
                        // Skip validasi merchant->is_active jika di halaman regional
                        return $isRegional 
                            ? $baseCondition 
                            : ($baseCondition && $keyword->merchant->is_active == 1);
                    })->isNotEmpty();
                };
            @endphp

            <!-- shop Section -->
            @if($hasCategoryData('belanja'))
                @include('merchant.shop')
            @endif

            <!-- food Section -->
            @if($hasCategoryData('kuliner'))
                @include('merchant.food')
            @endif

            <!-- telkomsel Section -->
            @if($hasCategoryData('telkomsel'))
                @include('merchant.telkomsel')
            @endif

            <!-- entertain Section -->
            @if($hasCategoryData('hiburan'))
                @include('merchant.entertain')
            @endif

            <!-- vacation Section -->
            @if($hasCategoryData('liburan'))
                @include('merchant.vacation')
            @endif

            <!-- beauty Section -->
            @if($hasCategoryData('kecantikan'))
                @include('merchant.beautyncare')
            @endif

            <!-- merchandise Section -->
            @if($hasCategoryData('merchandise'))
                @include('merchant.merchandise')
            @endif

            <!-- paketvideo Section -->
            @if($hasCategoryData('paket_video'))
                @include('merchant.paketvideo')
            @endif

            <!-- paketgames Section -->
            @if($hasCategoryData('paket_games'))
                @include('merchant.paketgames')
            @endif

            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

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

        // Function for redeem button click (location-based pages - no location validation)
        function handleRedeemClick(redeemUrl) {
          if (redeemUrl && redeemUrl !== '#') {
            // Direct redeem without location validation for location-based pages
            window.open(redeemUrl, '_blank');
          }
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


