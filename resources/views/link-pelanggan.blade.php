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

                @if($hasCategoryData('paket_video'))
                    @include('merchant.paketvideo')
                @endif

                @if($hasCategoryData('paket_games'))
                    @include('merchant.paketgames')
                @endif
                
                @if($hasCategoryData('merchandise'))
                    @include('merchant.merchandise')
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
        let userLocation = null;
        let isWithinRadius = true; // Default true jika tidak ada validasi radius
        
        // Extract coordinates from Google Maps link
        function extractCoordinatesFromGmapsLink(gmapLink) {
            if (!gmapLink) return null;
            
            // Pattern 1: https://www.google.com/maps?q=lat,lng
            let match = gmapLink.match(/[?&]q=([-\d.]+),([-\d.]+)/);
            if (match) {
                return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
            }
            
            // Pattern 2: https://www.google.com/maps/@lat,lng,zoom
            match = gmapLink.match(/@([-\d.]+),([-\d.]+),/);
            if (match) {
                return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
            }
            
            // Pattern 3: https://maps.google.com/?q=lat,lng
            match = gmapLink.match(/[?&]q=([-\d.]+),([-\d.]+)/);
            if (match) {
                return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
            }
            
            // Pattern 4: https://www.google.com/maps/place/@lat,lng
            match = gmapLink.match(/place\/@?([-\d.]+),([-\d.]+)/);
            if (match) {
                return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
            }
            
            return null;
        }
        
        // Calculate distance between two coordinates using Haversine formula (in meters)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Earth's radius in meters
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c; // Distance in meters
        }
        
        // Update all redeem buttons based on location status
        function updateRedeemButtons() {
            const redeemButtons = document.querySelectorAll('[data-redeem-btn]');
            console.log('🔄 Updating buttons...', {
                buttonsFound: redeemButtons.length,
                isWithinRadius: isWithinRadius,
                hasRadiusValidation: !!(merchantData.radius && merchantData.link_gmap)
            });
            
            redeemButtons.forEach((btn, index) => {
                if (!isWithinRadius && merchantData.radius && merchantData.link_gmap) {
                    // User di luar radius - disable button dan ganti text
                    console.log(`❌ Button ${index}: Disabling (outside radius)`);
                    btn.disabled = true;
                    btn.classList.remove('bg-gradient-to-r', 'from-orange-500', 'to-red-500', 'hover:from-orange-600', 'hover:to-red-600');
                    btn.classList.add('bg-gray-400', 'cursor-not-allowed');
                    btn.innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i>Harus ke Lokasi';
                    btn.title = `Anda harus berada dalam radius ${merchantData.radius} meter dari merchant`;
                } else {
                    // User dalam radius atau tidak ada validasi - enable button
                    console.log(`✅ Button ${index}: Enabling (within radius or no validation)`);
                    btn.disabled = false;
                    btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    btn.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-red-500', 'hover:from-orange-600', 'hover:to-red-600');
                    btn.innerHTML = 'Redeem';
                    btn.title = '';
                }
            });
        }
        
        // Check location on page load
        async function checkLocationOnLoad() {
            console.log('🔍 Checking location...', {
                merchantRadius: merchantData.radius,
                merchantGmap: merchantData.link_gmap
            });
            
            // Check if merchant has radius validation
            if (!merchantData.radius || !merchantData.link_gmap) {
                // No radius validation needed
                console.log('✅ No radius validation - allowing all redeems');
                isWithinRadius = true;
                updateRedeemButtons();
                return;
            }
            
            // Extract merchant coordinates
            const merchantCoords = extractCoordinatesFromGmapsLink(merchantData.link_gmap);
            console.log('📍 Merchant coordinates:', merchantCoords);
            
            if (!merchantCoords) {
                // Can't extract coordinates, allow redeem
                console.log('⚠️ Cannot extract merchant coordinates - allowing redeem');
                isWithinRadius = true;
                updateRedeemButtons();
                return;
            }
            
            // Check if browser supports geolocation
            if (!navigator.geolocation) {
                console.log('❌ Browser does not support geolocation');
                isWithinRadius = false;
                updateRedeemButtons();
                return;
            }
            
            try {
                console.log('🌍 Getting user location...');
                // Get user's current position
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 30000 // Cache for 30 seconds
                    });
                });
                
                userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                console.log('📍 User location:', userLocation);
                
                // Calculate distance
                const distance = calculateDistance(
                    userLocation.lat, userLocation.lng,
                    merchantCoords.lat, merchantCoords.lng
                );
                
                console.log('📏 Distance calculated:', {
                    distance: Math.round(distance) + ' meters',
                    maxRadius: merchantData.radius + ' meters',
                    withinRadius: distance <= merchantData.radius
                });
                
                // Check if within radius
                isWithinRadius = distance <= merchantData.radius;
                
                // Update all buttons
                updateRedeemButtons();
                
            } catch (error) {
                console.error('❌ Error getting location:', error);
                // If error getting location, block redeem (strict security)
                // User mungkin reject permission atau GPS off
                isWithinRadius = false; // Block redeem if location access fails
                updateRedeemButtons();

                // Show modal error to user
                setTimeout(() => {
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4';
                    modal.innerHTML = `
                        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0"
                             style="animation: modalFadeIn 0.3s ease-out forwards;">
                            <!-- Close button -->
                            <button onclick="this.closest('.fixed').remove()"
                                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>

                            <div class="p-6 text-center">
                                <!-- Icon -->
                                <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                    <i class="fas fa-exclamation-triangle text-3xl text-white"></i>
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-800 mb-3">Akses Lokasi Gagal! 🚫</h3>

                                <!-- Message -->
                                <div class="text-sm text-gray-600 mb-6 space-y-2">
                                    <p class="font-medium text-red-600">Tidak dapat mengakses lokasi Anda</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Redeem voucher tidak dapat dilakukan tanpa akses lokasi.
                                        Pastikan GPS aktif dan izinkan akses lokasi untuk perangkat ini.
                                    </p>
                                </div>

                                <!-- Action buttons -->
                                <div class="space-y-3">
                                    <button onclick="this.closest('.fixed').remove()"
                                            class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <i class="fas fa-times mr-2"></i>Mengerti
                                    </button>
                                    <button onclick="window.location.reload()"
                                            class="w-full px-6 py-2 text-gray-600 hover:text-gray-800 font-medium text-sm">
                                        <i class="fas fa-refresh mr-1"></i>Coba Lagi
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    // Add modal fade in animation
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes modalFadeIn {
                            to {
                                opacity: 1;
                                transform: scale(1);
                            }
                        }
                    `;
                    document.head.appendChild(style);

                    document.body.appendChild(modal);

                    // Auto remove after 8 seconds
                    setTimeout(() => {
                        if (modal.parentNode) {
                            modal.remove();
                        }
                    }, 8000);
                }, 1000);
            }
        }
        
        // Function called when user clicks redeem button
        function handleRedeemClick(redeemUrl) {
            if (isWithinRadius) {
                // User dalam radius, langsung redeem
                window.open(redeemUrl, '_blank');
            } else {
                // User di luar radius, show info
                const distanceText = userLocation && merchantData.link_gmap ? 
                    (() => {
                        const merchantCoords = extractCoordinatesFromGmapsLink(merchantData.link_gmap);
                        if (merchantCoords) {
                            const distance = calculateDistance(
                                userLocation.lat, userLocation.lng,
                                merchantCoords.lat, merchantCoords.lng
                            );
                            return distance < 1000 ? Math.round(distance) + ' meter' : (distance / 1000).toFixed(2) + ' km';
                        }
                        return 'tidak diketahui';
                    })() : 'tidak diketahui';
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'fixed inset-0 bg-black/50 z-[10000] flex items-center justify-center';
                errorDiv.innerHTML = `
                    <div class="bg-white rounded-2xl p-6 mx-4 max-w-sm text-center shadow-2xl">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-4xl text-red-500"></i>
                        </div>
                        <div class="text-xl font-bold text-gray-800 mb-2">Lokasi Terlalu Jauh! ❌</div>
                        <div class="text-sm text-gray-600 mb-1">Jarak Anda: <strong>${distanceText}</strong></div>
                        <div class="text-xs text-gray-500 mb-2">Radius maksimal: <strong>${merchantData.radius}m</strong></div>
                        <div class="text-xs text-orange-600 bg-orange-50 p-3 rounded-lg mb-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda harus berada dalam radius ${merchantData.radius} meter dari lokasi merchant untuk redeem voucher ini.
                        </div>
                        <a href="${merchantData.link_gmap}" 
                           target="_blank"
                           class="w-full inline-block px-6 py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition-all shadow-lg mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Lihat Lokasi Merchant
                        </a>
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full px-6 py-2 text-gray-600 hover:text-gray-800 font-medium">
                            Tutup
                        </button>
                    </div>
                `;
                document.body.appendChild(errorDiv);
            }
        }
        
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
            checkLocationOnLoad();
        });

        // Animate cards on page load
        document.addEventListener('DOMContentLoaded', function() {
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
            <div id="desktopModalContent" class="overflow-y-auto p-6 max-h-[70vh]"></div>
        </div>
    </div>
</body>
</html>