    <section id="section-entertain" class="mt-10 md:mt-14">
     <div class="mb-4 md:mb-6 flex items-center justify-between">
      <h2 class="text-2xl md:text-3xl font-black text-neutral-900">🎬 Entertain</h2>
     </div>
     <div class="grid grid-cols-2 gap-3 lg:grid-cols-1 lg:gap-5 items-stretch">
      <article onclick="window.open('https://instagram.com', '_blank')" class="group overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] hover:border-indigo-200 cursor-pointer opacity-0 translate-y-2 transition-all duration-200 ease-out h-full min-h-[280px]" style="animation-delay: 0.05s">
       <!-- Mobile Layout (default) -->
       <div class="lg:hidden flex flex-col h-full">
        <div class="relative">
         
         <!-- Promo Image -->
         <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
          <img src="{{ asset('storage/promo/promo-cgv.jpg') }}" alt="CGV Cinema" class="w-full h-full object-cover" loading="lazy">
         </div>
        </div>
        
        <!-- Card Content Below Image -->
        <div class="flex flex-col p-3 space-y-2 flex-1">
         <!-- Title -->
         <h3 class="text-2xl font-bold text-neutral-900 leading-tight">Voucher CGV </h3>
         
         <!-- Description -->
         <div class="text-sm text-neutral-600 leading-relaxed">
          <div class="font-bold text-neutral-800">Voucher <span class="text-xl font-bold text-neutral-800">Rp 50.000</span></div>
          <div>Voucher Nonton CGV Cinema senilai Rp 50,000</div>
         </div>
          <!-- Badge Points -->
         <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-1.5 self-start">
          <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
          <span class="text-[20px] font-bold text-red-600">50.000</span>
         </div>

         <!-- Stock & Valid Until -->
         <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100 mt-auto">
          <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
           <span class="font-medium">Stock:</span>
           <span class="font-semibold text-neutral-800">250</span>
          </div>
          <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
           <span class="font-medium">Valid until:</span>
           <span class="font-semibold text-neutral-800">20 Jun 2025</span>
          </div>
         </div>
        </div>
       </div>
       
       <!-- Desktop Layout (md and up) -->
       <div class="hidden lg:block">
        <div class="grid grid-cols-[auto_1fr_auto] gap-0 items-center">
         <!-- Kolom Kiri: Poin + Logo -->
         <div class="p-4 flex flex-col items-start gap-3">
          <!-- Badge Poin -->
          <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-3 py-1.5 shadow-md border border-orange-200">
           <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
           <span class="text-sm font-bold text-red-600">60000</span>
          </div>
          <!-- Logo -->
          <div>
           <img src="{{ asset('storage/logo/cgv.png') }}" alt="CGV" class="w-[140px] h-[140px] object-contain rounded-full" loading="lazy">
          </div>
         </div>
         
         <!-- Kolom Tengah: Nama Merchant + Diskon + Deskripsi -->
         <div class="p-4 flex flex-col justify-center">
          <!-- Nama Merchant -->
          <h3 class="text-5xl font-bold text-neutral-900 mb-3 leading-tight">CGV Cinema</h3>
          
          <!-- Diskon -->
          <div class="mb-2">
           <div class="text-xl font-bold text-neutral-900 mb-1">Voucher</div>
           <div class="text-6xl font-bold text-neutral-900 leading-none mb-1">Rp<span class="text-6xl">50</span><span class="text-2xl font-bold">ribu</span></div>
          </div>
          
          <!-- Deskripsi -->
          <div class="text-sm text-neutral-700 font-normal">
           Voucher Nonton CGV Cinema Rp 50,000
          </div>
         </div>
         
         <!-- Kolom Kanan: Gambar Promo -->
         <div class="card-image-wrapper p-2 max-w-[520px]">
          <div class="aspect-[6/3] md:h-full rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
           <img src="{{ asset('storage/promo/promo-cgv.jpg') }}" alt="" class="w-full h-full object-cover" loading="lazy">
          </div>
         </div>
        </div>
       </div>
       
       <!-- Desktop Footer -->
       <div class="hidden lg:flex flex-col md:flex-row items-start md:items-center justify-between px-3 md:px-4 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600 gap-1.5 md:gap-0">
        <span class="font-medium">Stock • 180</span>
        <span class="font-medium">Valid until • 31 Mar 2025</span>
       </div>
      </article>

      <article onclick="window.open('https://instagram.com', '_blank')" class="group overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] hover:border-purple-200 cursor-pointer opacity-0 translate-y-2 transition-all duration-200 ease-out h-full min-h-[280px]" style="animation-delay: 0.08s">
       <!-- Mobile Layout (default) -->
       <div class="lg:hidden flex flex-col h-full">
        <div class="relative">
         
         <!-- Promo Image -->
         <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
          <img src="{{ asset('storage/promo/promo-spotify.jpg') }}" alt="Spotify Premium" class="w-full h-full object-cover" loading="lazy">
         </div>
        </div>
        
        <!-- Card Content Below Image -->
        <div class="flex flex-col p-3 space-y-2 flex-1">
         <!-- Title -->
         <h3 class="text-2xl font-bold text-neutral-900 leading-tight">Spotify </h3>
         
         <!-- Description -->
         <div class="text-sm text-neutral-600 leading-relaxed">
          <div class="font-bold text-neutral-800">Premium <span class="text-xl font-bold text-neutral-800">1 Bulan</span></div>
          <div>Spotify Premium selama 1 Bulan untuk akun Individual</div>
         </div>
          <!-- Badge Points -->
         <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-1.5 self-start">
          <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
          <span class="text-[20px] font-bold text-red-600">80.000</span>
         </div>

         <!-- Stock & Valid Until -->
         <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100 mt-auto">
          <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
           <span class="font-medium">Stock:</span>
           <span class="font-semibold text-neutral-800">150</span>
          </div>
          <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
           <span class="font-medium">Valid until:</span>
           <span class="font-semibold text-neutral-800">20 Jun 2025</span>
          </div>
         </div>
        </div>
       </div>
       
       <!-- Desktop Layout (md and up) -->
       <div class="hidden lg:block">
        <div class="grid grid-cols-[auto_1fr_auto] gap-0 items-center">
         <!-- Kolom Kiri: Poin + Logo -->
         <div class="p-4 flex flex-col items-start gap-3">
          <!-- Badge Poin -->
          <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-3 py-1.5 shadow-md border border-orange-200">
           <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
           <span class="text-sm font-bold text-red-600">80000</span>
          </div>
          <!-- Logo -->
          <div>
           <img src="{{ asset('storage/logo/spotify.png') }}" alt="SPOTIFY" class="w-[140px] h-[140px] object-contain rounded-full" loading="lazy">
          </div>
         </div>
         
         <!-- Kolom Tengah: Nama Merchant + Diskon + Deskripsi -->
         <div class="p-4 flex flex-col justify-center">
          <!-- Nama Merchant -->
          <h3 class="text-5xl font-bold text-neutral-900 mb-3 leading-tight">Spotify</h3>
          
          <!-- Diskon -->
          <div class="mb-2">
           <div class="text-xl font-bold text-neutral-900 mb-1">Premium</div>
           <div class="text-6xl font-bold text-neutral-900 leading-none mb-1"><span class="text-6xl">1</span><span class="text-2xl font-bold">Bulan</span></div>
          </div>
          
          <!-- Deskripsi -->
          <div class="text-sm text-neutral-700 font-normal">
           Spotify Premium 1 Bulan Individual
          </div>
         </div>
         
         <!-- Kolom Kanan: Gambar Promo -->
         <div class="card-image-wrapper p-2 max-w-[520px]">
          <div class="aspect-[6/3] md:h-full rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
           <img src="{{ asset('storage/promo/promo-spotify.jpg') }}" alt="" class="w-full h-full object-cover" loading="lazy">
          </div>
         </div>
        </div>
       </div>
       
       <!-- Desktop Footer -->
       <div class="hidden lg:flex flex-col md:flex-row items-start md:items-center justify-between px-3 md:px-4 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600 gap-1.5 md:gap-0">
        <span class="font-medium">Stock • 250</span>
        <span class="font-medium">Valid until • 31 Dec 2025</span>
       </div>
      </article>

      <article id="extraEntertainCard" onclick="window.open('https://instagram.com', '_blank')" class="mt-2 group overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all duration-500 ease-in-out hover:shadow-xl hover:scale-[1.01] hover:border-pink-200 max-h-0 opacity-0 scale-y-0 origin-top cursor-pointer h-full">
       <!-- Mobile Layout (default) -->
       <div class="lg:hidden flex flex-col h-full">
        <div class="relative">
         
         <!-- Promo Image -->
         <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
          <img src="{{ asset('storage/promo/promo-netflix.jpg') }}" alt="Netflix Premium" class="w-full h-full object-cover" loading="lazy">
         </div>
        </div>
        
        <!-- Card Content Below Image -->
        <div class="flex flex-col p-3 space-y-2 flex-1">
         <!-- Title -->
         <h3 class="text-2xl font-bold text-neutral-900 leading-tight">Netflix </h3>
         
         <!-- Description -->
         <div class="text-sm text-neutral-600 leading-relaxed">
          <div class="font-bold text-neutral-800">Premium <span class="text-xl font-bold text-neutral-800">1 Bulan</span></div>
          <div>Netflix Premium 1 Bulan Individual</div>
         </div>
          <!-- Badge Points -->
         <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-1.5 self-start">
          <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
          <span class="text-[20px] font-bold text-red-600">100.000</span>
         </div>

         <!-- Stock & Valid Until -->
         <div class="flex flex-col gap-0.5 pt-1 border-t border-neutral-100 mt-auto">
          <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
           <span class="font-medium">Stock:</span>
           <span class="font-semibold text-neutral-800">150</span>
          </div>
          <div class="flex items-center gap-1.5 text-[10px] text-neutral-600">
           <span class="font-medium">Valid until:</span>
           <span class="font-semibold text-neutral-800">20 Jun 2025</span>
          </div>
         </div>
        </div>
       </div>
       
       <!-- Desktop Layout (md and up) -->
       <div class="hidden lg:block">
        <div class="grid grid-cols-[auto_1fr_auto] gap-0 items-center">
         <!-- Kolom Kiri: Poin + Logo -->
         <div class="p-4 flex flex-col items-start gap-3">
          <!-- Badge Poin -->
          <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-3 py-1.5 shadow-md border border-orange-200">
           <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
           <span class="text-sm font-bold text-red-600">70000</span>
          </div>
          <!-- Logo -->
          <div>
           <img src="{{ asset('storage/logo/netflix.png') }}" alt="NETFLIX" class="w-[140px] h-[140px] object-contain rounded-full" loading="lazy">
          </div>
         </div>
         
         <!-- Kolom Tengah: Nama Merchant + Diskon + Deskripsi -->
         <div class="p-4 flex flex-col justify-center">
          <!-- Nama Merchant -->
          <h3 class="text-5xl font-bold text-neutral-900 mb-3 leading-tight">Netflix</h3>
          
          <!-- Diskon -->
          <div class="mb-2">
           <div class="text-xl font-bold text-neutral-900 mb-1">Premium</div>
           <div class="text-6xl font-bold text-neutral-900 leading-none mb-1"><span class="text-6xl">1</span><span class="text-2xl font-bold">Bulan</span></div>
          </div>
          
          <!-- Deskripsi -->
          <div class="text-sm text-neutral-700 font-normal">
           Netflix Premium 1 Bulan Mobile
          </div>
         </div>
         
         <!-- Kolom Kanan: Gambar Promo -->
         <div class="card-image-wrapper p-2 max-w-[520px]">
          <div class="aspect-[6/3] md:h-full rounded-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
           <img src="{{ asset('storage/promo/promo-netflix.jpg') }}" alt="" class="w-full h-full object-cover" loading="lazy">
          </div>
         </div>
        </div>
       </div>
       
       <!-- Desktop Footer -->
       <div class="hidden lg:flex flex-col md:flex-row items-start md:items-center justify-between px-3 md:px-4 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600 gap-1.5 md:gap-0">
        <span class="font-medium">Stock • 150</span>
        <span class="font-medium">Valid until • 31 Dec 2025</span>
       </div>
      </article>
     </div>
     <div class="mt-4 md:mt-6 flex justify-center">
      <button onclick="toggleEntertainCards()" id="entertainSeeAllBtn" class="text-sm font-bold text-rose-600 hover:text-rose-700 transition-all flex items-center gap-2 group px-4 py-2 rounded-full hover:bg-rose-50">
       <span id="entertainSeeAllText">See All</span>
       <span id="entertainSeeAllArrow" class="transition-transform group-hover:translate-y-1">↓</span>
      </button>
     </div>
    </section>