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
    <script src="{{ asset('js/merchant-radius-validator.js') }}"></script>
    <style>
      /* ===== WhatsApp CS Floating Button (anti bentrok) ===== */
      .cs-wa-btn{
        position: fixed;
        right: 18px;
        bottom: 18px;
        z-index: 999999;

        width: 64px;
        height: 64px;
        border-radius: 999px;

        background: #00d757;
        box-shadow: 2px 4px 14px rgba(0,0,0,.28);
        text-decoration: none;
        overflow: hidden;

        display: flex;
        align-items: center;
        justify-content: center;

        transition: all .25s ease;
      }

      .cs-wa-sign{
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .cs-wa-svg{
        width: 34px;
        height: 34px;
        display: block;
      }
      .cs-wa-svg path{ fill:#fff; }

      /* default: text hidden (mobile icon only) */
      .cs-wa-text{
        display: none;
      }

      /* Mobile: smaller size */
      @media (max-width: 768px) {
        .cs-wa-btn{
          width: 52px;
          height: 52px;
          right: 14px;
          bottom: 14px;
        }

        .cs-wa-svg{
          width: 28px;
          height: 28px;
        }
      }

      /* Desktop: hover expand (hanya device yang bisa hover) */
      @media (hover:hover) and (pointer:fine){
        .cs-wa-btn{
          justify-content: flex-start;
        }

        .cs-wa-text{
          display: block;
          position: absolute;
          right: 0;
          width: 0;
          opacity: 0;
          padding-right: 0;

          color: #fff;
          font-size: 15px;
          font-weight: 700;
          white-space: nowrap;

          transition: all .25s ease;
        }

        .cs-wa-btn:hover{
          width: 220px;
          border-radius: 44px;
        }

        .cs-wa-btn:hover .cs-wa-sign{
          width: 30%;
          padding-left: 14px;
          justify-content: flex-start;
        }

        .cs-wa-btn:hover .cs-wa-text{
          width: 70%;
          opacity: 1;
          padding-right: 16px;
        }
      }
    </style>
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
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mx-auto w-full max-w-[1400px] px-4 md:px-8 lg:px-10 pb-12 relative z-10">
            @include('partials.banner-carousel', ['iklans' => $iklans])

            <div class="flex flex-wrap items-start justify-between gap-4 pl-1 mt-10 md:mt-12">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</h1>
                </div>
            </div>


            <!-- Merchant Sections by Category -->
            <div class="mt-6 md:mt-8">
                @php
                    // Helper function to check if category has data
                    $hasCategoryData = function($category) use ($keywords, $isLinkPelanggan) {
                        return $keywords->filter(function ($keyword) use ($category, $isLinkPelanggan) {
                            $keywordCategory = !empty($keyword->kategori_keyword) ? $keyword->kategori_keyword : ($keyword->merchant->kategori ?? null);
                            $baseCondition = $keyword->merchant && $keywordCategory === $category
                                && $keyword->status === 'approve'
                                && $keyword->is_active == 1;
                            return $isLinkPelanggan 
                                ? $baseCondition 
                                : ($baseCondition && $keyword->merchant->is_active == 1);
                        })->isNotEmpty();
                    };
                @endphp


                @if($hasCategoryData('merchandise'))
                    @include('merchant.merchandise')
                @endif
                
                @if($hasCategoryData('paket_video'))
                    @include('merchant.paketvideo')
                @endif

                @if($hasCategoryData('paket_games'))
                    @include('merchant.paketgames')
                @endif

                @if($hasCategoryData('paket_internet'))
                    @include('merchant.paketinternet')
                @endif
                
              

                @if($hasCategoryData('belanja'))
                    @include('merchant.shop')
                @endif

                @if($hasCategoryData('kuliner'))
                    @include('merchant.food')
                @endif

                @if($hasCategoryData('telkomsel'))
                    @include('merchant.telkomsel')
                @endif

                @if($hasCategoryData('hiburan'))
                    @include('merchant.entertain')
                @endif

                @if($hasCategoryData('liburan'))
                    @include('merchant.vacation')
                @endif

                @if($hasCategoryData('kecantikan'))
                    @include('merchant.beautyncare')
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

    <!-- Script untuk hide loading spinner -->
    <script>
        // Merchant data from server
        const merchantData = @json($merchant);
        
        // Initialize validator dengan merchant data
        initRadiusValidator(merchantData);
        
        // Disable redeem buttons by default until location is checked
        // This will be called immediately after initialization
        if (merchantValidator) {
            updateRedeemButtons();
        }


        
        // Prevent click on "Harus ke Lokasi" buttons
        document.addEventListener('click', function(e) {
            const target = e.target.closest('[data-redeem-btn]');
            if (target) {
                // Check if button contains "Harus ke Lokasi" text or is disabled
                const buttonText = target.textContent.trim();
                if (buttonText.includes('Harus ke Lokasi') || target.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Tombol dinonaktifkan, tidak menampilkan pesan error
                    return false;
                }
            }
        }, true); // Use capture phase to catch before default behavior
        
        // Hide loading spinner when page loads and check location
        window.addEventListener('load', function() {
            const spinner = document.getElementById('loadingSpinner');
            if (spinner) {
                spinner.style.opacity = '0';
                setTimeout(() => {
                    spinner.style.display = 'none';
                }, 300);
            }
            
            // Check user location on page load
            checkUserLocationAndUpdateUI();
        });

        // Animate cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Disable redeem buttons by default until location is checked
            if (merchantValidator) {
                updateRedeemButtons();
            }
            
            // Animate category section
            const categorySection = document.getElementById('categorySection');
            if (categorySection) {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -20px 0px'
                };
                
                const categoryObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                            categoryObserver.unobserve(entry.target);
                        }
                    });
                }, observerOptions);
                
                categoryObserver.observe(categorySection);
            }

            // Animate cards with intersection observer
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.target.style.display !== 'none') {
                        entry.target.classList.remove('opacity-0', 'translate-y-2');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        cardObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '0px 0px -10px 0px' });
            
            // Observe all cards
            const cards = document.querySelectorAll('article[class*="opacity-0"]');
            cards.forEach((card, index) => {
                // Skip cards hidden by see-more
                if (card.style.display === 'none') return;
                cardObserver.observe(card);
                
                // Fallback: animate cards that are already visible
                setTimeout(() => {
                    if (card.style.display === 'none') return;
                    const rect = card.getBoundingClientRect();
                    const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;
                    if (isInViewport && card.classList.contains('opacity-0')) {
                        card.classList.remove('opacity-0', 'translate-y-2');
                        card.classList.add('opacity-100', 'translate-y-0');
                        cardObserver.unobserve(card);
                    }
                }, 200 + (index * 50));
            });
            
            // Final fallback: show all visible cards after 1 second if still hidden
            setTimeout(() => {
                cards.forEach(card => {
                    if (card.style.display === 'none') return;
                    if (card.classList.contains('opacity-0')) {
                        card.classList.remove('opacity-0', 'translate-y-2');
                        card.classList.add('opacity-100', 'translate-y-0');
                    }
                });
            }, 1000);
        });

        // Filter Category - Scroll to section
        function filterCategory(category) {
            const selectedSection = document.getElementById('section-' + category);
            if (selectedSection) {
                // Get navbar height to calculate offset
                const navbar = document.getElementById('navbar');
                const navbarHeight = navbar ? navbar.offsetHeight : 0;
                
                // Calculate position with offset (navbar height + extra spacing)
                const elementPosition = selectedSection.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = elementPosition - navbarHeight - 20; // 20px extra spacing
                
                // Smooth scroll to calculated position
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }

        // Open Category Bottom Sheet
        window.openCategorySheet = function() {
            const baseAssetPath = '{{ asset("images/categories") }}';
            const categories = [
                { id: 'food', name: 'Kuliner', icon: 'food.png', color: 'rose' },
                { id: 'entertain', name: 'Lifestyle', icon: 'entertain.png', color: 'indigo' },
                { id: 'vacation', name: 'Liburan', icon: 'vacation.png', color: 'purple' },
                { id: 'beauty', name: 'Kesehatan & Kecantikan', icon: 'beauty.png', color: 'pink' },
                { id: 'shop', name: 'Belanja', icon: 'shop.png', color: 'orange' },
                { id: 'telkomsel', name: 'Telkomsel Data', icon: 'telkomsel.png', color: 'red' },
                { id: 'merchandise', name: 'Merchandise', icon: 'merchandise.png', color: 'blue' },
                { id: 'paketvideo', name: 'Paket Video', icon: 'paketvideo.png', color: 'purple' },
                { id: 'paketgames', name: 'Paket Games', icon: 'paketgames.png', color: 'green' }
            ];
            
            const categoryHtml = `
                <div class="grid grid-cols-3 gap-3 p-4">
                    ${categories.map(cat => `
                        <button onclick="selectCategoryFromSheet('${cat.id}')" class="group flex flex-col items-center gap-2 rounded-xl bg-white p-4 text-center shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-lg hover:scale-105 hover:ring-${cat.color}-300 active:scale-95">
                            <span class="grid h-14 w-14 place-items-center rounded-full bg-white transition-transform group-hover:scale-110">
                                <img src="${baseAssetPath}/${cat.icon}" alt="${cat.name}" class="${cat.id==='telkomsel' ? 'w-17 h-17' : 'w-full h-full'} object-contain">
                            </span>
                            <span class="text-[10px] font-bold text-neutral-700 group-hover:text-${cat.color}-600 transition-colors leading-tight text-center">${cat.name}</span>
                        </button>
                    `).join('')}
                </div>
            `;
            
            openBottomSheet('Kategori Merchant', categoryHtml);
        }

        // Select category from bottom sheet
        window.selectCategoryFromSheet = function(category) {
            closeBottomSheet();
            setTimeout(() => {
                filterCategory(category);
            }, 300);
        }

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
            <div id="desktopModalContent" class="overflow-y-auto p-6 max-h-[70vh]">            </div>
        </div>
    </div>

    <!-- Floating WhatsApp CS Button -->
    <a
        href="https://wa.me/628113700040?text=Halo%20CS%20BlanjaPoin%2C%20saya%20butuh%20bantuan."
        class="cs-wa-btn"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Chat WhatsApp Customer Service"
        title="Chat WhatsApp"
    >
        <span class="cs-wa-sign" aria-hidden="true">
            <svg class="cs-wa-svg" viewBox="0 0 16 16">
                <path
                    d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"
                ></path>
            </svg>
        </span>

        <span class="cs-wa-text">Customer Service</span>
    </a>
    @include('partials.desktop-alert')
    @include('partials.redeem-script')
</body>
</html>