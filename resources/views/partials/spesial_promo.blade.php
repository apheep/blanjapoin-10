<section class="relative z-20 mt-5 md:mt-14 mb-8 sm:mb-10">
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

            @php
                $specialPromos = collect($keywords ?? [])
                    ->filter(fn ($keyword) => 
                        $keyword->merchant && 
                        $keyword->status === 'approve' && 
                        $keyword->is_active == 1 &&
                        $keyword->merchant->is_active == 1 &&
                        ($keyword->is_special_promo ?? 0) == 1
                    )
                    ->take(4);
            @endphp

            @if($specialPromos->isEmpty())
                <div class="text-center text-sm text-neutral-500 py-10">
                    Belum ada voucher spesial yang bisa ditampilkan.
                </div>
            @else
                <div class="relative">
                    <!-- Carousel Container -->
                    <div class="relative overflow-hidden">
                        <div id="specialPromoCarousel" class="flex transition-transform duration-500 ease-in-out gap-3 sm:gap-4 md:gap-5" style="transform: translateX(0);">
                            @foreach($specialPromos as $keyword)
                                @php
                                    $merchantName = optional($keyword->merchant)->nama_merchant ?? '-';
                                    $productName = $keyword->nama_produk ?: $merchantName;
                                    $subtitle = $keyword->skb ?: 'Voucher pilihan spesial untuk kamu.';
                                    $image = $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg');
                                    $point = (int) $keyword->redeem;
                                    $merchantCategory = optional($keyword->merchant)->kategori ?? '';
                                    // Map kategori ke section ID
                                    $categoryMap = [
                                        'belanja' => 'shop',
                                        'kuliner' => 'food',
                                        'kecantikan' => 'beauty',
                                        'hiburan' => 'entertain',
                                        'liburan' => 'vacation',
                                        'telkomsel' => 'telkomsel'
                                    ];
                                    $sectionId = $categoryMap[$merchantCategory] ?? '';
                                @endphp

                                <a
                                    href="#"
                                    onclick="event.preventDefault(); scrollToPromoCard({{ $keyword->id }}, '{{ $sectionId }}');"
                                    data-voucher-card="true"
                                    data-point="{{ $point }}"
                                    data-keyword-id="{{ $keyword->id }}"
                                    data-merchant-category="{{ $merchantCategory }}"
                                    data-section-id="{{ $sectionId }}"
                                    class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col flex-shrink-0 special-promo-card cursor-pointer"
                                    data-carousel-item
                                >
                            <div class="relative px-3 pt-3 pb-2 sm:px-4 sm:pt-4 sm:pb-3 flex-shrink-0">
                                <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                    <img src="{{ $image }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                </div>
                            </div>

                            <div class="flex flex-col px-3 pb-2 sm:px-4 sm:pb-2 flex-1">
                                @if(!is_null($keyword->diskon))
                                <div class="flex items-center gap-1.5 mb-1">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-4 h-4 sm:w-5 sm:h-5 object-contain">
                                    <p class="text-[11px] sm:text-xs font-semibold text-red-500 uppercase">
                                        {{ formatDiskon($keyword->diskon) }}
                                    </p>
                                </div>
                                @endif
                                <h3 class="text-sm sm:text-base font-black text-neutral-900 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                                    {{ $productName }}
                                </h3>
                                <!-- <p class="text-[11px] sm:text-xs text-neutral-600 my-2 leading-relaxed line-clamp-2">
                                    {{ $subtitle }}
                                </p> -->
                            </div>

                            <!-- Footer -->
                            <div class="px-3 pb-3 sm:px-4 sm:pb-4 pt-0 bg-white">
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
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

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
        let animationID = 0;
        
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

    // Function to scroll to promo card in merchant section
    function scrollToPromoCard(keywordId, sectionId) {
        if (!sectionId || !keywordId) {
            console.warn('Missing sectionId or keywordId');
            return;
        }

        // Map section ID to card ID prefix
        const cardIdPrefix = sectionId + '-card-';
        const targetCardId = cardIdPrefix + keywordId;
        
        // Find the target card
        const targetCard = document.querySelector(`[id="${targetCardId}"]`);
        
        if (targetCard) {
            // Scroll to section first
            const section = document.getElementById('section-' + sectionId);
            if (section) {
                // Scroll to section with offset for header
                const headerOffset = 100;
                const sectionPosition = section.getBoundingClientRect().top;
                const offsetPosition = sectionPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // After scrolling, highlight the card
                setTimeout(() => {
                    // Scroll card into view
                    targetCard.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Add highlight effect
                    targetCard.style.transition = 'all 0.3s ease';
                    targetCard.style.transform = 'scale(1.05)';
                    targetCard.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
                    targetCard.style.borderColor = '#fb923c';
                    
                    // Remove highlight after 2 seconds
                    setTimeout(() => {
                        targetCard.style.transform = '';
                        targetCard.style.boxShadow = '';
                        targetCard.style.borderColor = '';
                    }, 2000);
                }, 500);
            } else {
                // If section not found, just scroll to card
                targetCard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        } else {
            // Card not found, try to scroll to section only
            const section = document.getElementById('section-' + sectionId);
            if (section) {
                const headerOffset = 100;
                const sectionPosition = section.getBoundingClientRect().top;
                const offsetPosition = sectionPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            } else {
                console.warn('Section or card not found:', 'section-' + sectionId, targetCardId);
            }
        }
    }
</script>


