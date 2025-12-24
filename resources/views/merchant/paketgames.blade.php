<section id="section-paketgames" class="mt-10 md:mt-14 mb-10 md:mb-14">
    <div class="mb-4 md:mb-6 flex items-center justify-between">
        <h2 class="text-xl md:text-3xl font-black text-neutral-900">🎮 Paket Games</h2>
    </div>

    @php
        $paketgamesCategory = 'paket_games';
        $isLinkPelanggan = $isLinkPelanggan ?? false;
        $isTerritorial = $isTerritorial ?? false;
        $isRegional = $isRegional ?? false;
        $isBranch = $isBranch ?? false;
        $isCluster = $isCluster ?? false;
        $skipMerchantValidation = $isLinkPelanggan || $isTerritorial || $isRegional || $isBranch || $isCluster;
        $paketgamesKeywords = $keywords->filter(function ($keyword) use ($paketgamesCategory, $skipMerchantValidation) {
            // Prioritaskan kategori dari keyword, jika tidak ada gunakan kategori dari merchant
            $keywordCategory = !empty($keyword->kategori_keyword) ? $keyword->kategori_keyword : ($keyword->merchant->kategori ?? null);
            $baseCondition = $keyword->merchant && strtolower($keywordCategory) === strtolower($paketgamesCategory)
                && $keyword->status === 'approve'
                && $keyword->is_active == 1;
            // Skip validasi merchant->is_active jika di halaman link-pelanggan atau location-based pages
            return $skipMerchantValidation 
                ? $baseCondition 
                : ($baseCondition && $keyword->merchant->is_active == 1);
        })->values();
    @endphp

    <!-- All Cards -->
    <div id="paketgamesCardContainer" data-voucher-container="true" data-voucher-section="paketgames" data-container-type="primary" class="card-container grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
        @forelse($paketgamesKeywords as $keyword)
            @php
                $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
                $productName = $keyword->nama_produk ?? '';
                $locationName = extractKabupatenKota(optional($keyword->merchant)->daerah ?? '');
                $searchName = strtolower(trim($merchantName . ' ' . $productName));
                $searchLocation = strtolower($locationName);
                $uniqueId = 'paketgames-card-' . $keyword->id;
            @endphp
            <article id="{{ $uniqueId }}" data-voucher-card="true" data-point="{{ (int) $keyword->redeem }}" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 opacity-0 translate-y-2 duration-200 ease-out h-full min-h-[280px]">
                <!-- Mobile Layout -->
                <div class="lg:hidden flex flex-col h-full">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    </div>
                    <div class="flex flex-col p-2.5 space-y-1 flex-1">
                        <h3 class="text-base font-bold text-neutral-900 leading-tight truncate">
                            {{ ($keyword->merchant)->nama_merchant}}
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
                            <button onclick="event.stopPropagation(); openTerritorialDescriptionSheet({{ $keyword->id }}, {{ json_encode(($keyword->merchant)->nama_merchant) }}, {{ json_encode($productName) }}, {{ json_encode($keyword->skb) }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : null) }})" class="mt-0.5 text-[9px] font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
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
                                <span class="font-semibold text-neutral-800">{{ $keyword->sisa_stock ?? $keyword->stock }}</span>
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
                        @php
                            $sisaStock = (int)($keyword->sisa_stock ?? $keyword->stock ?? 0);
                            $canRedeem = (!$keyword->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($keyword->start_date)->startOfDay())) && $sisaStock > 0;
                            $startDateFormatted = $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d-M-y') : '';
                            $isStockEmpty = ($keyword->stock ?? 0) <= 0;
                        @endphp
                        @if($isStockEmpty)
                        <button disabled class="mt-1.5 w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-1 px-2.5 rounded-lg cursor-not-allowed text-[9px]">
                            Voucher Habis
                        </button>
                        @elseif($canRedeem)
                        <a href="{{ route('track.redirect', ['merchantId' => $keyword->merchant_key, 'keywordId' => $keyword->keyword_id]) }}" target="_blank" data-redeem-btn class="mt-1.5 w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-1 px-2.5 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-[9px]">
                            Redeem
                        </a>
                        @elseif($sisaStock <= 0)
                        <button disabled class="mt-1.5 w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-1 px-2.5 rounded-lg cursor-not-allowed text-[9px]">
                            Voucher Habis
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
                            @if($keyword->merchant && $keyword->merchant->logo_merchant)
                                <div class="relative flex-shrink-0">
                                    <div class="absolute inset-0 rounded-xl blur-sm "></div>
                                    <img src="{{ asset('storage/' . $keyword->merchant->logo_merchant) }}" alt="{{ $merchantName }}" class="relative w-11 h-11 md:w-14 md:h-14 object-contain rounded-xl  shadow-md">
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
                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        </div>
                    <div class="absolute bottom-1.5 right-3 md:bottom-2 md:right-4 bg-gradient-to-r from-black/60 to-black/50 backdrop-blur-sm text-white px-2 py-0.5 rounded-lg text-[10px] md:text-xs font-bold shadow-lg border border-white/10">
                        <span class="inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                            <span>Stock: {{ $keyword->sisa_stock ?? $keyword->stock }}</span>
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
                            @php
                                $sisaStock = (int)($keyword->sisa_stock ?? $keyword->stock ?? 0);
                                $canRedeem = (!$keyword->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($keyword->start_date)->startOfDay())) && $sisaStock > 0;
                                $startDateFormatted = $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d-M-y') : '';
                                $isStockEmpty = ($keyword->stock ?? 0) <= 0;
                            @endphp
                            @if($isStockEmpty)
                            <button disabled class="w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2 px-3.5 rounded-lg cursor-not-allowed text-xs md:text-sm">
                                Voucher Habis
                            </button>
                            @elseif($canRedeem)
                            <a href="{{ route('track.redirect', ['merchantId' => $keyword->merchant_key, 'keywordId' => $keyword->keyword_id]) }}" target="_blank" data-redeem-btn class="w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-2 px-3.5 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-xs md:text-sm">
                                Redeem
                            </a>
                            @elseif($sisaStock <= 0)
                            <button disabled class="w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2 px-3.5 rounded-lg cursor-not-allowed text-xs md:text-sm">
                                Voucher Habis
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
            <div class="col-span-10 text-center text-neutral-500 text-sm py-6">
                Belum ada data promo untuk kategori Paket Games.
            </div>
        @endforelse
    </div>
</section>

