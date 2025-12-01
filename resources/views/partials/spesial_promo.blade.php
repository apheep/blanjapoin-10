<section class="relative z-20 mt-6 sm:mt-24 mb-8 sm:mb-10">
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
                    ->filter(fn ($keyword) => $keyword->merchant && $keyword->status === 'approve')
                    ->take(4);
            @endphp

            @if($specialPromos->isEmpty())
                <div class="text-center text-sm text-neutral-500 py-10">
                    Belum ada voucher spesial yang bisa ditampilkan.
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-5 pb-1 card-container" data-voucher-container="true" data-voucher-section="special" data-container-type="primary">
                    @foreach($specialPromos as $keyword)
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
                            class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-2xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 flex flex-col h-full"
                        >
                            <div class="relative px-3 pt-3 pb-2 sm:px-4 sm:pt-4 sm:pb-3 flex-shrink-0">
                                <div class="aspect-[10/5] rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                    <img src="{{ $image }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                </div>
                            </div>

                            <div class="flex flex-col px-3 pb-3 sm:px-4 sm:pb-4 flex-1 min-h-0">
                                @if(!is_null($keyword->diskon))
                                <div class="flex items-center gap-1.5 mb-1">
                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-4 h-4 sm:w-5 sm:h-5 object-contain">
                                    <p class="text-[11px] sm:text-xs font-semibold text-red-500 uppercase">
                                        {{ $keyword->diskon }}
                                    </p>
                                </div>
                                @endif
                                <h3 class="text-sm sm:text-base font-black text-neutral-900 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors">
                                    {{ $productName }}
                                </h3>
                                <p class="text-[11px] sm:text-xs text-neutral-600 my-2 leading-relaxed line-clamp-2">
                                    {{ $subtitle }}
                                </p>

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
            @endif
        </div>
    </div>
</section>


