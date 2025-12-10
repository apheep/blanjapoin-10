<section id="section-food" class="mt-10 md:mt-14 mb-10 md:mb-14">
    <div class="mb-4 md:mb-6 flex items-center justify-between">
        <h2 class="text-2xl md:text-3xl font-black text-neutral-900">🍔​ Kuliner</h2>
    </div>

    @php
        $foodCategory = 'kuliner';
        $foodKeywords = $keywords->filter(function ($keyword) use ($foodCategory) {
            return $keyword->merchant && $keyword->merchant->kategori === $foodCategory
                && $keyword->status === 'approve'
                && $keyword->is_active == 1
                && $keyword->merchant->is_active == 1;
        })->values();
    @endphp

    <!-- All Cards -->
    <div id="foodCardContainer" data-voucher-container="true" data-voucher-section="food" data-container-type="primary" class="card-container grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
        @forelse($foodKeywords as $keyword)
            @php
                $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
                $productName = $keyword->nama_produk ?? '';
                $locationName = extractKabupatenKota(optional($keyword->merchant)->daerah ?? '');
                $searchName = strtolower(trim($merchantName . ' ' . $productName));
                $searchLocation = strtolower($locationName);
                $uniqueId = 'food-card-' . $keyword->id;
            @endphp
            <article id="{{ $uniqueId }}" data-voucher-card="true" data-point="{{ (int) $keyword->redeem }}" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 opacity-0 translate-y-2 duration-200 ease-out h-full min-h-[280px]">
                <!-- Mobile Layout -->
                <div class="lg:hidden flex flex-col h-full">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    </div>
                    <div class="flex flex-col p-3 space-y-2 flex-1">
                        <h3 class="text-lg font-bold text-neutral-900 leading-tight truncate">
                            {{ ($keyword->merchant)->nama_merchant}}
                        </h3>
                        <div class="text-[10px] text-gray-500 -mt-1 -mb-1">
                            <span>Promo</span>
                        </div>
                        <div class="text-[11px] text-neutral-600 leading-relaxed">
                            @if(!is_null($keyword->diskon))
                                <div class="font-bold text-red-500 flex items-center gap-2 mb-1">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-7 h-7 object-contain">
                                    <span class="text-xl font-bold text-red-500">{{ formatDiskon($keyword->diskon) }}</span>
                                </div>
                            @endif
                            @if($productName)
                            <div class="mb-1 font-semibold text-neutral-700 text-base truncate">
                                {{ $productName }}
                            </div>
                            @endif
                            @if($keyword->skb)
                            <button onclick="event.stopPropagation(); openFoodDescriptionSheet({{ $keyword->id }}, {{ json_encode(($keyword->merchant)->nama_merchant) }}, {{ json_encode($productName) }}, {{ json_encode($keyword->skb) }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : null) }})" class="mt-1 text-[10px] font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                                Lihat Deskripsi
                            </button>
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
                        @php
                            $canRedeem = !$keyword->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($keyword->start_date)->startOfDay());
                            $startDateFormatted = $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d-M-y') : '';
                        @endphp
                        @if($canRedeem)
                        <button onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="mt-2 w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-1.5 px-3 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-[10px]">
                            Redeem
                        </button>
                        @else
                        <button disabled class="mt-2 w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-1.5 px-3 rounded-lg cursor-not-allowed text-[10px]">
                            Open {{ $startDateFormatted }}
                        </button>
                        @endif
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
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-14 h-14 object-contain">
                                    <span class="text-base md:text-2xl font-black text-red-600">{{ formatDiskon($keyword->diskon) }}</span>
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
                            {{ $merchantName }}
                        </h4>
                        @if($productName)
                        <p class="text-lg md:text-xl text-neutral-600 mb-2.5 leading-relaxed font-semibold truncate">
                            {{ $productName }}
                        </p>
                        @endif
                        @if($keyword->skb)
                        <button onclick="event.stopPropagation(); openFoodDescriptionSheet({{ $keyword->id }}, {{ json_encode($merchantName) }}, {{ json_encode($productName) }}, {{ json_encode($keyword->skb) }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : null) }})" class="self-start text-left mb-2.5 text-xs md:text-sm font-semibold text-orange-600 hover:text-orange-700 underline focus:outline-none">
                            Lihat Deskripsi
                        </button>
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
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                    <span class="text-xl md:text-2xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                        {{ number_format($keyword->redeem, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            @php
                                $canRedeem = !$keyword->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($keyword->start_date)->startOfDay());
                                $startDateFormatted = $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d-M-y') : '';
                            @endphp
                            @if($canRedeem)
                            <button onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="w-auto inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold py-2.5 px-4 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md hover:shadow-lg text-sm md:text-base">
                                Redeem
                            </button>
                            @else
                            <button disabled class="w-auto inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2.5 px-4 rounded-lg cursor-not-allowed text-sm md:text-base">
                                Open {{ $startDateFormatted }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center text-neutral-500 text-sm py-6">
                Belum ada data promo untuk kategori Food.
            </div>
        @endforelse
    </div>


    <script>
// Food Description Bottom Sheet Function
function openFoodDescriptionSheet(keywordId, merchantName, productName, skb, diskon) {
 const contentHTML = `
  <div class="px-5 pb-6">
   <div class="space-y-3">
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
     <div class="mt-2">
      <p class="text-sm text-neutral-600 leading-relaxed">
       ${skb}
      </p>
     </div>
    </div>
    ` : ''}
   </div>
  </div>
 `;
 
 if (typeof openBottomSheet === 'function') {
  openBottomSheet('Deskripsi', contentHTML);
 } else {
  console.error('openBottomSheet function not found');
 }
}
</script>
</section>
