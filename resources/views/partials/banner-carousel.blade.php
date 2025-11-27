@php
    $bannerItems = collect($iklans ?? [])
        ->map(function ($iklan) {
            $imagePath = $iklan?->image_path;
            if (!$imagePath) {
                return null;
            }

            $link = trim((string) ($iklan->link_iklan ?? ''));

            return [
                'image' => '/storage/' . ltrim($imagePath, '/'),
                'link' => $link !== '' ? $link : null,
            ];
        })
        ->filter()
        ->values();

    if ($bannerItems->isEmpty()) {
        $bannerItems = collect([
            [
                'image' => '/logo.png',
                'link' => null,
            ],
        ]);
    }

    $activeBannerSrc = data_get($bannerItems->first(), 'image', '/logo.png');
@endphp

<section class="mt-1 md:mt-1 opacity-0 translate-y-8 transition-all duration-700 ease-out" id="bannerSection">
    <div class="relative group">
        <!-- Navigation Arrows -->
        <button onclick="prevSlide()" class="absolute left-3 md:left-5 top-1/2 -translate-y-1/2 z-10 grid h-10 w-10 md:h-14 md:w-14 place-items-center rounded-full bg-white/95 backdrop-blur-sm shadow-xl transition-all hover:bg-white hover:scale-110 active:scale-95 text-neutral-700 font-bold text-xl md:text-3xl">
            ‹
        </button>

        <button onclick="nextSlide()" class="absolute right-3 md:right-5 top-1/2 -translate-y-1/2 z-10 grid h-10 w-10 md:h-14 md:w-14 place-items-center rounded-full bg-white/95 backdrop-blur-sm shadow-xl transition-all hover:bg-white hover:scale-110 active:scale-95 text-neutral-700 font-bold text-xl md:text-3xl">
            ›
        </button>

        <div class="relative h-56 sm:h-64 md:h-80 lg:h-96 rounded-3xl md:rounded-[2.5rem] overflow-hidden shadow-2xl md:shadow-3xl shadow-neutral-400/20 drop-shadow-2xl md:drop-shadow-3xl ring-1 ring-white/20 transition-all duration-300 hover:shadow-3xl hover:scale-[1.02]">
            <a id="bannerLink" href="{{ route('home') }}" target="_self" class="block h-full w-full focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-400" aria-label="Buka banner">
                <!-- Background Image -->
                <img id="bannerImage"
                    src="{{ $activeBannerSrc }}"
                    alt="Banner Promo"
                    class="w-full h-full object-cover transition-all duration-700 rounded-3xl md:rounded-[2.5rem]"
                    loading="lazy">
            </a>

            <!-- Gradient Overlay 1 -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/10 to-transparent rounded-3xl md:rounded-[2.5rem] pointer-events-none"></div>

            <!-- Gradient Overlay 2 -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent rounded-3xl md:rounded-[2.5rem] pointer-events-none"></div>
        </div>

        <!-- Carousel Dots -->
        <div class="mt-2 md:mt-3/2 flex items-center justify-center gap-3 md:gap-3">
            @php
                $dotsHtml = $bannerItems->map(function ($item, $index) {
                    return '<span onclick="goToSlide(' . $index . ')" class="carousel-dot h-3 w-3 md:h-3 md:w-3 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>';
                })->implode('');
            @endphp
            {!! $dotsHtml !!}
        </div>
    </div>
</section>

<script>
    (function () {
        const slides = <?php echo $bannerItems->values()->toJson(); ?>;
        const defaultLink = <?php echo json_encode(route('home')); ?>;
        let currentSlide = 0;
        let autoSlideInterval;
        const bannerImage = document.getElementById('bannerImage');
        const bannerLink = document.getElementById('bannerLink');
        const dots = document.querySelectorAll('.carousel-dot');
        const carouselSection = document.getElementById('bannerSection');
        if (carouselSection) {
            carouselSection.style.opacity = '1';
            carouselSection.style.transform = 'translateY(0)';
        }

        function updateSlide(index) {
            if (!slides.length || !bannerImage) {
                return;
            }

            currentSlide = index;
            const slide = slides[currentSlide];
            if (!slide) {
                return;
            }

            const slideImage = slide.image ?? '';
            const slideLinkValue = slide.link ?? '';
            const normalizedLink = typeof slideLinkValue === 'string' ? slideLinkValue.trim() : '';
            const hasLink = normalizedLink.length > 0;

            if (bannerLink) {
                bannerLink.href = hasLink ? normalizedLink : defaultLink;
                if (hasLink) {
                    bannerLink.setAttribute('target', '_blank');
                    bannerLink.setAttribute('rel', 'noopener noreferrer');
                } else {
                    bannerLink.setAttribute('target', '_self');
                    bannerLink.removeAttribute('rel');
                }
            }

            bannerImage.style.opacity = '0';

            setTimeout(() => {
                if (slideImage) {
                    bannerImage.src = slideImage;
                }
                bannerImage.style.opacity = '1';
            }, 250);

            dots.forEach((dot, i) => {
                if (i === currentSlide) {
                    dot.classList.remove('bg-neutral-300', 'w-2', 'md:w-2.5');
                    dot.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-rose-500', 'w-8', 'md:w-10', 'shadow-md');
                } else {
                    dot.classList.remove('bg-gradient-to-r', 'from-orange-500', 'to-rose-500', 'w-8', 'md:w-10', 'shadow-md');
                    dot.classList.add('bg-neutral-300', 'w-2', 'md:w-2.5');
                }
            });
        }

        function startAutoSlide() {
            if (slides.length < 2) {
                return;
            }
            autoSlideInterval = setInterval(() => {
                window.nextSlide();
            }, 5000);
        }

        function resetAutoSlide() {
            if (slides.length < 2) {
                return;
            }
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        window.nextSlide = function () {
            if (!slides.length) {
                return;
            }
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlide(currentSlide);
            resetAutoSlide();
        };

        window.prevSlide = function () {
            if (!slides.length) {
                return;
            }
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateSlide(currentSlide);
            resetAutoSlide();
        };

        window.goToSlide = function (index) {
            if (!slides.length) {
                return;
            }
            const normalizedIndex = Math.max(0, Math.min(index, slides.length - 1));
            updateSlide(normalizedIndex);
            resetAutoSlide();
        };

        updateSlide(0);
        if (slides.length > 1) {
            startAutoSlide();
        }

        if (carouselSection && slides.length > 1) {
            carouselSection.addEventListener('mouseenter', () => {
                clearInterval(autoSlideInterval);
            });

            carouselSection.addEventListener('mouseleave', () => {
                startAutoSlide();
            });
        }
    })();
</script>

