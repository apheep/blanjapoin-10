<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spesial Promo • BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen">
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

    <style>
        .special-promo-card {
            width: calc(50% - 6px);
            min-width: calc(50% - 6px);
        }
        @media (min-width: 768px) {
            .special-promo-card {
                width: calc(25% - 12px);
                min-width: calc(25% - 12px);
            }
        }
    </style>
    <div class="mx-auto w-full max-w-[1400px] px-4 md:px-8 lg:px-10 pb-12 relative z-10">
        <section class="relative z-20 mt-6 sm:mt-12 mb-8 sm:mb-10">
            <div class="mx-auto w-full max-w-[1200px]">
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border border-orange-100/70 px-4 sm:px-6 md:px-8 py-5 sm:py-6 md:py-7">
                    <div class="flex items-center justify-between gap-3 mb-4 sm:mb-5">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span class="text-lg sm:text-xl">🔥</span>
                            <div>
                                <p class="text-sm sm:text-base font-semibold text-neutral-900">Spesial Buat kamu</p>
                                <p class="hidden sm:block text-xs text-neutral-500">Rekomendasi voucher pilihan berdasarkan kategori populer.</p>
                            </div>
                        </div>
                    </div>

                    @if($keywords->isEmpty())
                        <div class="text-center text-sm text-neutral-500 py-10">
                            Belum ada voucher spesial yang bisa ditampilkan.
                        </div>
                    @else
                        <div class="relative">
                            <!-- Carousel Container -->
                            <div class="relative overflow-hidden">
                                <div id="specialPromoCarousel" class="flex transition-transform duration-500 ease-in-out gap-3 sm:gap-4 md:gap-5" style="transform: translateX(0);">
                                    @foreach($keywords as $keyword)
                                        @php
                                            $merchantName = optional($keyword->merchant)->nama_merchant ?? '-';
                                            $productName = $keyword->nama_produk ?: $merchantName;
                                            $subtitle = $keyword->skb ?: 'Voucher pilihan spesial untuk kamu.';
                                            $image = $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg');
                                            $point = (int) $keyword->redeem;
                                        @endphp

                                        <a
                                            href="{{ $keyword->cta_link ?? '#' }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            data-voucher-card="true"
                                            data-point="{{ $point }}"
                                            class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-2xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 flex flex-col h-full flex-shrink-0 special-promo-card"
                                            style="min-height: 280px;"
                                            data-carousel-item
                                        >
                                    <div class="relative px-3 pt-3 pb-2 sm:px-4 sm:pt-4 sm:pb-3 flex-shrink-0">
                                        <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                            <img src="{{ $image }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                        </div>
                                    </div>

                                    <div class="flex flex-col px-3 pb-3 sm:px-4 sm:pb-4 flex-1 min-h-0" style="min-height: 140px;">
                                        <!-- Mobile Layout -->
                                        <div class="sm:hidden flex flex-col space-y-2">
                                            <h3 class="text-xl font-bold text-neutral-900 leading-tight">
                                                {{ $merchantName }}
                                            </h3>
                                            <div class="text-[10px] text-neutral-600 -mt-1 -mb-1">
                                                <span class="font-bold">Promo</span>
                                            </div>
                                            <div class="text-[11px] text-neutral-600 leading-relaxed">
                                                @if(!is_null($keyword->diskon))
                                                <div class="font-bold text-red-500 flex items-center gap-2 mb-1">
                                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-7 h-7 object-contain">
                                                    <span class="text-xl font-bold text-red-500">{{ formatDiskon($keyword->diskon) }}</span>
                                                </div>
                                                @endif
                                                @if($productName && $productName !== $merchantName)
                                                <div class="mb-1 font-semibold text-neutral-700">
                                                    {{ $productName }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Desktop Layout -->
                                        <div class="hidden sm:flex flex-col">
                                            <h4 class="text-base md:text-lg font-black text-neutral-900 mb-2 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                                                {{ $merchantName }}
                                            </h4>
                                            @if($productName && $productName !== $merchantName)
                                            <p class="text-xs md:text-sm text-neutral-600 mb-2.5 leading-relaxed font-semibold">
                                                {{ $productName }}
                                            </p>
                                            @endif
                                            @if(!is_null($keyword->diskon))
                                            <div class="flex items-center gap-1.5 mb-2.5">
                                                <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-4 h-4 sm:w-5 sm:h-5 object-contain">
                                                <p class="text-[11px] sm:text-xs font-semibold text-red-500 uppercase">
                                                    {{ formatDiskon($keyword->diskon) }}
                                                </p>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="mt-auto pt-2 border-t border-neutral-100">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                                    <span class="text-lg sm:text-xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                                        {{ number_format($point, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <script>
        (function() {
            const carousel = document.getElementById('specialPromoCarousel');
            const items = document.querySelectorAll('[data-carousel-item]');
            
            if (!carousel || items.length <= 2) return;
            
            let currentIndex = 0;
            const itemsPerView = window.innerWidth >= 768 ? 4 : 2;
            const totalSlides = Math.ceil(items.length / itemsPerView);
            let autoSlideInterval;
            
            // Touch/swipe variables
            let touchStartX = 0;
            let touchEndX = 0;
            let isDragging = false;
            let startPos = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            
            // Update carousel position
            function updateCarousel() {
                if (items.length === 0) return;
                const gap = window.innerWidth >= 768 ? 16 : 12; // md:gap-4 = 16px, gap-3 = 12px
                const itemWidth = items[0].offsetWidth + gap;
                const translateX = -currentIndex * itemWidth * itemsPerView;
                carousel.style.transform = `translateX(${translateX}px)`;
            }
            
            // Next slide
            function nextSlide() {
                if (currentIndex < totalSlides - 1) {
                    currentIndex++;
                } else {
                    currentIndex = 0; // Loop back to start
                }
                updateCarousel();
                resetAutoSlide();
            }
            
            // Previous slide
            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                } else {
                    currentIndex = totalSlides - 1; // Loop to end
                }
                updateCarousel();
                resetAutoSlide();
            }
            
            // Auto slide with longer timeout
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000); // 5 seconds
            }
            
            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                }
            }
            
            function resetAutoSlide() {
                stopAutoSlide();
                startAutoSlide();
            }
            
            // Touch/Swipe handlers
            function getPositionX(event) {
                return event.type.includes('mouse') ? event.clientX : event.touches[0].clientX;
            }
            
            function touchStart(event) {
                startPos = getPositionX(event);
                isDragging = true;
                stopAutoSlide();
                carousel.style.transition = 'none';
            }
            
            function touchMove(event) {
                if (!isDragging) return;
                const currentPosition = getPositionX(event);
                currentTranslate = prevTranslate + currentPosition - startPos;
            }
            
            function touchEnd() {
                if (!isDragging) return;
                isDragging = false;
                carousel.style.transition = 'transform 0.5s ease-in-out';
                
                const movedBy = currentTranslate - prevTranslate;
                const threshold = 50; // Minimum swipe distance
                
                if (movedBy < -threshold && currentIndex < totalSlides - 1) {
                    nextSlide();
                } else if (movedBy > threshold && currentIndex > 0) {
                    prevSlide();
                } else {
                    updateCarousel();
                }
                
                prevTranslate = currentTranslate;
                startAutoSlide();
            }
            
            // Add touch event listeners
            carousel.addEventListener('touchstart', touchStart, { passive: true });
            carousel.addEventListener('touchmove', touchMove, { passive: true });
            carousel.addEventListener('touchend', touchEnd);
            
            // Mouse drag support (for desktop testing)
            carousel.addEventListener('mousedown', touchStart);
            carousel.addEventListener('mousemove', touchMove);
            carousel.addEventListener('mouseup', touchEnd);
            carousel.addEventListener('mouseleave', touchEnd);
            
            // Pause on hover (desktop only)
            const carouselContainer = carousel.closest('.relative');
            if (carouselContainer) {
                carouselContainer.addEventListener('mouseenter', stopAutoSlide);
                carouselContainer.addEventListener('mouseleave', startAutoSlide);
            }
            
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    currentIndex = 0;
                    updateCarousel();
                }, 250);
            });
            
            // Initialize
            updateCarousel();
            startAutoSlide();
        })();
    </script>
</body>
</html>
