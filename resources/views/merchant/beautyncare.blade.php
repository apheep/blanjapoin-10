<section id="section-beauty" class="mt-10 md:mt-14 mb-10 md:mb-14">
    <div class="mb-4 md:mb-6 flex items-center justify-between">
        <h2 class="text-2xl md:text-3xl font-black text-neutral-900">💄Kecantikan</h2>
    </div>

    @php
        $beautyCategory = 'kecantikan';
        $beautyKeywords = $keywords->filter(function ($keyword) use ($beautyCategory) {
            return $keyword->merchant && $keyword->merchant->kategori === $beautyCategory
                && $keyword->status === 'approve'
                && $keyword->is_active == 1
                && $keyword->merchant->is_active == 1;
        })->values();
        $visibleKeywords = $beautyKeywords->take(3);
        $extraKeywords = $beautyKeywords->slice(3);
    @endphp

    <!-- Card utama (2 pertama) -->
    <div id="beautyCardContainer" data-voucher-container="true" data-voucher-section="beauty" data-container-type="primary" class="card-container grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
        @forelse($visibleKeywords as $keyword)
            @php
                $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
                $productName = $keyword->nama_produk ?? '';
                $locationName = optional($keyword->merchant)->daerah ?? '';
                $searchName = strtolower(trim($merchantName . ' ' . $productName));
                $searchLocation = strtolower($locationName);
                $uniqueId = 'beauty-card-' . $keyword->id;
            @endphp
            <article data-voucher-card="true" data-point="{{ (int) $keyword->redeem }}" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 cursor-pointer opacity-0 translate-y-2 duration-200 ease-out h-full min-h-[280px] {{ $loop->iteration === 3 ? 'hidden lg:block' : '' }}">
                <!-- Mobile Layout -->
                <div class="lg:hidden flex flex-col h-full">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    </div>
                    <div class="flex flex-col p-3 space-y-2 flex-1">
                        <h3 class="text-xl font-bold text-neutral-900 leading-tight">
                            {{ ($keyword->merchant)->nama_merchant}}
                        </h3>
                        <div class="text-[11px] text-neutral-600 leading-relaxed">
                            @if(!is_null($keyword->diskon))
                                <div class="font-bold text-red-500 flex items-center gap-2 mb-1">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-5 h-5 object-contain">
                                    <span class="text-xl font-bold text-red-500">{{ $keyword->diskon }}</span>
                                </div>
                            @endif
                            @if($keyword->skb)
                                <div class="relative">
                                    <div id="{{ $uniqueId }}-text" class="line-clamp-3 transition-all duration-300">
                                        {{ $keyword->skb }}
                                    </div>
                                    <button 
                                        id="{{ $uniqueId }}-btn" 
                                        onclick="event.stopPropagation(); toggleDescription('{{ $uniqueId }}')" 
                                        class="hidden mt-1 text-orange-600 font-semibold flex items-center gap-1 hover:text-orange-700 transition-colors"
                                    >
                                        <span id="{{ $uniqueId }}-btn-text">See details</span>
                                        <svg id="{{ $uniqueId }}-arrow" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-0.5 self-start">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                            <span class="text-[20px] font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100 mt-auto">
                            <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                <span class="font-medium">Stock:</span>
                                <span class="font-semibold text-neutral-800">{{ $keyword->stock }}</span>
                            </div>
                            @if($keyword->end_date)
                                <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                    <span class="font-medium">Valid until:</span>
                                    <span class="font-semibold text-neutral-800">
                                        {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout -->
                <div class="hidden lg:flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-neutral-100 flex-shrink-0 min-h-[80px] md:min-h-[90px]">
                        <div class="flex items-center gap-3 flex-1">
                            @if($keyword->merchant && $keyword->merchant->logo_merchant)
                                <div class="relative flex-shrink-0">
                                    <div class="absolute inset-0 rounded-xl blur-sm "></div>
                                    <img src="{{ asset('storage/' . $keyword->merchant->logo_merchant) }}" alt="{{ $merchantName }}" class="relative w-12 h-12 md:w-16 md:h-16 object-contain rounded-xl  shadow-md">
                                </div>
                            @else
                                <div class="w-12 h-12 md:w-16 md:h-16 flex-shrink-0"></div>
                            @endif
                        </div>
                        @if($keyword->diskon)
                            <div class="text-right flex-shrink-0 ml-2">
                                <div class="inline-flex items-center gap-2">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-10 h-10 object-contain">
                                    <span class="text-base md:text-2xl font-black text-red-600">{{ $keyword->diskon }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Image with Stock Overlay -->
                    <div class="relative px-4 md:px-5 pt-4 pb-3 flex-shrink-0">
                        <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        </div>
                        <div class="absolute bottom-2 right-4 md:bottom-3 md:right-5 bg-gradient-to-r from-black/60 to-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-xs md:text-sm font-bold shadow-lg border border-white/10">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                <span>Stock: {{ $keyword->stock }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col px-4 md:px-5 pb-4 md:pb-5 flex-1 min-h-0">
                        <h4 class="text-base md:text-lg font-black text-neutral-900 mb-2 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                            {{ $productName ?: $merchantName }}
                        </h4>
                        @if($keyword->skb)
                            <div class="relative">
                                <p id="{{ $uniqueId }}-text-desktop" class="text-xs md:text-sm text-neutral-600 mb-2.5 leading-relaxed line-clamp-2 transition-all duration-300">
                                    {{ $keyword->skb }}
                                </p>
                                <button 
                                    id="{{ $uniqueId }}-btn-desktop" 
                                    onclick="event.stopPropagation(); toggleDescriptionDesktop('{{ $uniqueId }}')" 
                                    class="hidden text-orange-600 font-semibold items-center gap-1 hover:text-orange-700 transition-colors text-xs"
                                >
                                    <span id="{{ $uniqueId }}-btn-text-desktop">See details</span>
                                    <svg id="{{ $uniqueId }}-arrow-desktop" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        @if($keyword->end_date)
                            <div class="flex items-center gap-1.5 text-xs text-neutral-500 mb-3">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">Valid until: <span class="font-semibold text-neutral-700">{{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}</span></span>
                            </div>
                        @endif
                        <div class="mt-auto pt-3 border-t border-neutral-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                    <span class="text-xl md:text-2xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                        {{ number_format($keyword->redeem, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-2 text-center text-neutral-500 text-sm py-6">
                Belum ada data promo untuk kategori Beauty &amp; Care.
            </div>
        @endforelse
    </div>

    <!-- Card ekstra (See All) -->
    @if($extraKeywords->isNotEmpty())
        <div id="extraBeautyCard" class="group max-h-0 overflow-hidden opacity-0 scale-y-0 origin-top transition-all duration-500 ease-in-out mt-6 md:mt-10">
            <div data-voucher-container="true" data-voucher-section="beauty" data-container-type="extra" class="card-container grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
                @foreach($extraKeywords as $keyword)
                    @php
                        $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
                        $productName = $keyword->nama_produk ?? '';
                        $locationName = optional($keyword->merchant)->daerah ?? '';
                        $searchName = strtolower(trim($merchantName . ' ' . $productName));
                        $searchLocation = strtolower($locationName);
                        $uniqueId = 'beauty-card-extra-' . $keyword->id;
                    @endphp
                    <article data-voucher-card="true" data-point="{{ (int) $keyword->redeem }}" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 cursor-pointer h-full min-h-[280px]">
                        <!-- Mobile Layout -->
                        <div class="lg:hidden flex flex-col h-full">
                            <div class="relative">
                                <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                                    <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            </div>
                            <div class="flex flex-col p-3 space-y-2 flex-1">
                                <h3 class="text-xl font-bold text-neutral-900 leading-tight">
                                    {{ ($keyword->merchant)->nama_merchant}}
                                </h3>
                                <div class="text-[11px] text-neutral-600 leading-relaxed">
                                    @if(!is_null($keyword->diskon))
                                        <div class="font-bold text-red-500 flex items-center gap-2 mb-1">
                                            <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-5 h-5 object-contain">
                                            <span class="text-xl font-bold text-red-500">{{ $keyword->diskon }}</span>
                                        </div>
                                    @endif
                                    @if($keyword->skb)
                                        <div class="relative">
                                            <div id="{{ $uniqueId }}-text" class="line-clamp-3 transition-all duration-300">
                                                {{ $keyword->skb }}
                                            </div>
                                            <button 
                                                id="{{ $uniqueId }}-btn" 
                                                onclick="event.stopPropagation(); toggleDescription('{{ $uniqueId }}')" 
                                                class="hidden mt-1 text-orange-600 font-semibold flex items-center gap-1 hover:text-orange-700 transition-colors"
                                            >
                                                <span id="{{ $uniqueId }}-btn-text">See details</span>
                                                <svg id="{{ $uniqueId }}-arrow" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-0.5 self-start">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                    <span class="text-[20px] font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100 mt-auto">
                                    <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                        <span class="font-medium">Stock:</span>
                                        <span class="font-semibold text-neutral-800">{{ $keyword->stock }}</span>
                                    </div>
                                    @if($keyword->end_date)
                                        <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
                                            <span class="font-medium">Valid until:</span>
                                            <span class="font-semibold text-neutral-800">
                                                {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Layout -->
                        <div class="hidden lg:flex flex-col h-full">
                            <!-- Header -->
                            <div class="flex items-center justify-between p-4 md:p-5  border-b border-neutral-100 flex-shrink-0 min-h-[80px] md:min-h-[90px]">
                                <div class="flex items-center gap-3 flex-1">
                                    @if($keyword->merchant && $keyword->merchant->logo_merchant)
                                        <div class="relative flex-shrink-0">
                                            <div class="absolute inset-0 rounded-xl blur-sm "></div>
                                            <img src="{{ asset('storage/' . $keyword->merchant->logo_merchant) }}" alt="{{ $merchantName }}" class="relative w-12 h-12 md:w-16 md:h-16 object-contain rounded-xl shadow-md">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 md:w-16 md:h-16 flex-shrink-0"></div>
                                    @endif
                                </div>
                                @if($keyword->diskon)
                                    <div class="text-right flex-shrink-0 ml-2">
                                        <div class="inline-flex items-center gap-2">
                                            <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-10 h-10 object-contain">
                                            <span class="text-base md:text-2xl font-black text-red-600">{{ $keyword->diskon }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Image with Stock Overlay -->
                            <div class="relative px-4 md:px-5 pt-4 pb-3 flex-shrink-0">
                                <div class="aspect-[2/1] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                    <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                </div>
                                <div class="absolute bottom-2 right-4 md:bottom-3 md:right-5 bg-gradient-to-r from-black/60 to-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-xs md:text-sm font-bold shadow-lg border border-white/10">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                        <span>Stock: {{ $keyword->stock }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex flex-col px-4 md:px-5 pb-4 md:pb-5 flex-1 min-h-0">
                                <h4 class="text-base md:text-lg font-black text-neutral-900 mb-2 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                                    {{ $productName ?: $merchantName }}
                                </h4>
                                @if($keyword->skb)
                                    <p class="text-xs md:text-sm text-neutral-600 mb-2.5 leading-relaxed line-clamp-2">
                                        {{ $keyword->skb }}
                                    </p>
                                @endif
                                @if($keyword->end_date)
                                    <div class="flex items-center gap-1.5 text-xs text-neutral-500 mb-3">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate">Valid until: <span class="font-semibold text-neutral-700">{{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}</span></span>
                                    </div>
                                @endif
                                <div class="mt-auto pt-3 border-t border-neutral-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                            <span class="text-xl md:text-2xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                                {{ number_format($keyword->redeem, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-12 md:mt-10 flex justify-center relative z-20 md:static md:z-auto pointer-events-auto">
        <button onclick="toggleBeautyCards()" id="beautySeeAllBtn" class="relative inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-rose-600 bg-white border-2 border-rose-200 rounded-full shadow-md hover:shadow-lg hover:bg-rose-50 hover:border-rose-300 transition-all duration-300 group">
            <span id="beautySeeAllText">See All</span>
            <span id="beautySeeAllArrow" class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-rose-100 group-hover:bg-rose-200 transition-all duration-300 group-hover:translate-y-1">
                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                </svg>
            </span>
        </button>
    </div>

    <!-- JavaScript untuk toggle description dan See All (pola Shop) -->
    <script>
        function toggleDescription(uniqueId) {
            const textElement = document.getElementById(uniqueId + '-text');
            const btnElement = document.getElementById(uniqueId + '-btn');
            const btnTextElement = document.getElementById(uniqueId + '-btn-text');
            const arrowElement = document.getElementById(uniqueId + '-arrow');
            
            if (textElement.classList.contains('line-clamp-3')) {
                textElement.classList.remove('line-clamp-3');
                btnTextElement.textContent = 'Show less';
                arrowElement.classList.add('rotate-180');
            } else {
                textElement.classList.add('line-clamp-3');
                btnTextElement.textContent = 'See details';
                arrowElement.classList.remove('rotate-180');
            }
        }

        function toggleDescriptionDesktop(uniqueId) {
            const textElement = document.getElementById(uniqueId + '-text-desktop');
            const btnElement = document.getElementById(uniqueId + '-btn-desktop');
            const btnTextElement = document.getElementById(uniqueId + '-btn-text-desktop');
            const arrowElement = document.getElementById(uniqueId + '-arrow-desktop');
            
            if (textElement.classList.contains('line-clamp-2')) {
                textElement.classList.remove('line-clamp-2');
                btnTextElement.textContent = 'Show less';
                arrowElement.classList.add('rotate-180');
            } else {
                textElement.classList.add('line-clamp-2');
                btnTextElement.textContent = 'See details';
                arrowElement.classList.remove('rotate-180');
            }
        }

        function checkTruncatedTextBeauty() {
            const mobileTextElements = document.querySelectorAll('[id$="-text"]:not([id$="-text-desktop"])');
            mobileTextElements.forEach(function(textElement) {
                const parentCard = textElement.closest('.voucher-card');
                if (!parentCard || parentCard.offsetParent === null) return;

                const uniqueId = textElement.id.replace('-text', '');
                const btnElement = document.getElementById(uniqueId + '-btn');
                if (btnElement) {
                    const isOverflowing = textElement.scrollHeight > textElement.clientHeight + 2;
                    if (isOverflowing) {
                        btnElement.classList.remove('hidden');
                        btnElement.classList.add('flex');
                    } else {
                        btnElement.classList.add('hidden');
                        btnElement.classList.remove('flex');
                    }
                }
            });

            const desktopTextElements = document.querySelectorAll('[id$="-text-desktop"]');
            desktopTextElements.forEach(function(textElement) {
                const parentCard = textElement.closest('.voucher-card');
                if (!parentCard || parentCard.offsetParent === null) return;

                const uniqueId = textElement.id.replace('-text-desktop', '');
                const btnElement = document.getElementById(uniqueId + '-btn-desktop');
                if (btnElement) {
                    const isOverflowing = textElement.scrollHeight > textElement.clientHeight + 2;
                    if (isOverflowing) {
                        btnElement.classList.remove('hidden');
                        btnElement.classList.add('flex');
                    } else {
                        btnElement.classList.add('hidden');
                        btnElement.classList.remove('flex');
                    }
                }
            });
        }

        const originalToggleBeautyCards = window.toggleBeautyCards;
        window.toggleBeautyCards = function() {
            if (typeof originalToggleBeautyCards === 'function') {
                originalToggleBeautyCards();
            }

            setTimeout(function() {
                checkTruncatedTextBeauty();
            }, 500);
        };

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                checkTruncatedTextBeauty();
            }, 100);
        });

        let resizeTimerBeauty;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimerBeauty);
            resizeTimerBeauty = setTimeout(function() {
                checkTruncatedTextBeauty();
            }, 250);
        });
    </script>
</section>
