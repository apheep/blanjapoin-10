<section id="section-vacation" class="mt-10 md:mt-14">
 <div class="mb-4 md:mb-6 flex items-center justify-between">
  <h2 class="text-2xl md:text-3xl font-black text-neutral-900">🏖️ Liburan</h2>
 </div>

 @php
  $vacationCategory = 'liburan';
  $vacationKeywords = $keywords->filter(function ($keyword) use ($vacationCategory) {
   return $keyword->merchant && $keyword->merchant->kategori === $vacationCategory
    && $keyword->status === 'approve';
  })->values();
  $visibleKeywords = $vacationKeywords->take(2);
  $extraKeywords = $vacationKeywords->slice(2);
 @endphp

 <!-- Card utama (2 pertama) -->
<div class="card-container grid grid-cols-2 gap-3 lg:grid-cols-1 lg:gap-5 items-stretch">
 @forelse($visibleKeywords as $keyword)
  @php
   $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
   $productName = $keyword->nama_produk ?? '';
   $locationName = optional($keyword->merchant)->daerah ?? '';
   $searchName = strtolower(trim($merchantName . ' ' . $productName));
   $searchLocation = strtolower($locationName);
  @endphp
  <article data-voucher-card="true" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] hover:border-blue-200 cursor-pointer h-full min-h-[280px]">
   <!-- Mobile Layout -->
   <div class="lg:hidden flex flex-col h-full">
    <div class="relative">
     <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
       <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
     </div>
    </div>
    <div class="flex flex-col p-3 space-y-2 flex-1">
     <h3 class="text-2xl font-bold text-neutral-900 leading-tight">
      {{ $keyword->merchant->nama_merchant }}
     </h3>
     <div class="text-sm text-neutral-600 leading-relaxed">
      @if(!is_null($keyword->diskon))
      <div class="font-bold text-neutral-800">
       Diskon <span class="text-xl font-bold text-neutral-800">{{ $keyword->diskon }}</span>
      </div>
      @endif
      @if($keyword->skb)
      <div>{{ $keyword->skb }}</div>
      @endif
     </div>
     <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-0.5 self-start">
      <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
      <span class="text-[20px] font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
     </div>
     <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100">
      <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
       <span class="font-medium">Stock:</span>
       <span class="font-semibold text-neutral-800">{{ $keyword->stok }}</span>
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
   <div class="hidden lg:block">
    <div class="grid grid-cols-[auto_1fr_auto] gap-0 items-center">
     <div class="p-4 flex flex-col items-start gap-3">
      <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-3 py-1.5 shadow-md border border-orange-200">
       <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
       <span class="text-sm font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
      </div>
      <div>
   <img src="{{ asset('storage/' . $keyword->merchant->logo_merchant) }}" alt="{{ $keyword->nama_produk }}" class="w-[140px] h-[140px] object-contain rounded-full" loading="lazy">      
      </div>
     </div>

     <div class="p-4 flex flex-col justify-center">
      <h3 class="text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-900 mb-3 leading-tight">
       {{ $keyword->merchant->nama_merchant }}
      </h3>
      @if(!is_null($keyword->diskon))
      <div class="mb-2">
       <div class="text-xl font-bold text-neutral-900 mb-1">Diskon</div>
       <div class="text-4xl md:text-5xl lg:text-6xl font-bold text-neutral-900 leading-none mb-1">
        {{ $keyword->diskon }}<span class="text-2xl font-bold"></span>
       </div>
      </div>
      @endif
      <div class="text-sm text-neutral-700 font-normal">
       {{ $keyword->skb }}
      </div>
     </div>

     <div class="card-image-wrapper p-2 max-w-[520px]">
      <div class="aspect-[6/3] md:h-full rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
       <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
      </div>
     </div>
    </div>
   </div>

   <div class="hidden lg:flex flex-col md:flex-row items-start md:items-center justify-between px-3 md:px-4 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600 gap-1.5 md:gap-0">
    <span class="font-medium">Stock {{ $keyword->stok }}</span>
    @if($keyword->end_date)
    <span class="font-medium">
     Valid until {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
    </span>
    @endif
   </div>
  </article>
  @empty
  <div class="col-span-2 lg:col-span-1 text-center text-neutral-500 text-sm py-6">
   Belum ada data promo untuk kategori Vacation.
  </div>
  @endforelse
 </div>

 <!-- Card ekstra (See All) -->
 @if($extraKeywords->isNotEmpty())
 <div id="extraVacationCard" class="group max-h-0 overflow-y-hidden opacity-0 scale-y-0 origin-top transition-all duration-500 ease-in-out mt-6 md:mt-10">
  <div class="card-container grid grid-cols-2 gap-3 lg:grid-cols-1 lg:gap-5 items-stretch">
   @foreach($extraKeywords as $keyword)
   @php
    $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
    $productName = $keyword->nama_produk ?? '';
    $locationName = optional($keyword->merchant)->daerah ?? '';
    $searchName = strtolower(trim($merchantName . ' ' . $productName));
    $searchLocation = strtolower($locationName);
   @endphp
   <article data-voucher-card="true" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] hover:border-blue-200 cursor-pointer h-full min-h-[280px]">
    <!-- Mobile Layout -->
    <div class="lg:hidden flex flex-col h-full">
     <div class="relative">
      <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
       <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
      </div>
     </div>
     <div class="flex flex-col p-3 space-y-2 flex-1">
      <h3 class="text-2xl font-bold text-neutral-900 leading-tight">
       {{ $keyword->merchant->nama_merchant }}
      </h3>
      <div class="text-sm text-neutral-600 leading-relaxed">
       @if(!is_null($keyword->diskon))
       <div class="font-bold text-neutral-800">
        Diskon <span class="text-xl font-bold text-neutral-800">{{ $keyword->diskon }}</span>
       </div>
       @endif
       @if($keyword->skb)
       <div>{{ $keyword->skb }}</div>
       @endif
      </div>
      <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-0.5 self-start">
       <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
       <span class="text-[20px] font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
      </div>
      <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100">
       <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
        <span class="font-medium">Stock:</span>
        <span class="font-semibold text-neutral-800">{{ $keyword->stok }}</span>
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
    <div class="hidden lg:block">
     <div class="grid grid-cols-[auto_1fr_auto] gap-0 items-center">
      <div class="p-4 flex flex-col items-start gap-3">
       <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-3 py-1.5 shadow-md border border-orange-200">
        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
        <span class="text-sm font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
       </div>
       <div>
   <img src="{{ asset('storage/' . $keyword->merchant->logo_merchant) }}" alt="{{ $keyword->nama_produk }}" class="w-[140px] h-[140px] object-contain rounded-full" loading="lazy">      
       </div>
      </div>

      <div class="p-4 flex flex-col justify-center">
       <h3 class="text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-900 mb-3 leading-tight">
        {{ $keyword->merchant->nama_merchant }}
       </h3>
       @if(!is_null($keyword->diskon))
       <div class="mb-2">
        <div class="text-xl font-bold text-neutral-900 mb-1">Diskon</div>
        <div class="text-4xl md:text-5xl lg:text-6xl font-bold text-neutral-900 leading-none mb-1">
         {{ $keyword->diskon }}<span class="text-2xl font-bold"></span>
        </div>
       </div>
       @endif
       <div class="text-sm text-neutral-700 font-normal">
        {{ $keyword->skb }}
       </div>
      </div>

      <div class="card-image-wrapper p-2 max-w-[520px]">
       <div class="aspect-[6/3] md:h-full rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
       <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" alt="{{ $keyword->nama_produk }}" class="w-full h-full object-cover" loading="lazy">
       </div>
      </div>
     </div>
    </div>

    <div class="hidden lg:flex flex-col md:flex-row items-start md:items-center justify-between px-3 md:px-4 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600 gap-1.5 md:gap-0">
     <span class="font-medium">Stock {{ $keyword->stok }}</span>
     @if($keyword->end_date)
     <span class="font-medium">
      Valid until {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
     </span>
     @endif
    </div>
   </article>
   @endforeach
  </div>
 </div>
 @endif

 <div class="mt-12 md:mt-10 flex justify-center relative z-20 md:static md:z-auto pointer-events-auto">
  <button onclick="toggleVacationCards()" id="vacationSeeAllBtn" class="text-sm font-bold text-rose-600 hover:text-rose-700 transition-all flex items-center gap-2 group px-4 py-2 rounded-full hover:bg-rose-50 bg-white/80 backdrop-blur-sm shadow-sm md:bg-transparent md:backdrop-blur-0 md:shadow-none">
   <span id="vacationSeeAllText">See All</span>
   <span id="vacationSeeAllArrow" class="transition-transform group-hover:translate-y-1">&#8595;</span>
  </button>
 </div>
</section>
