<!doctype html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BlanjaPoin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    /* ===== WhatsApp CS Floating Button (anti bentrok) ===== */
    .cs-wa-btn{
      position: fixed;
      right: 18px;
      bottom: 18px;
      z-index: 999999;

      width: 64px;
      height: 64px;
      border-radius: 999px;

      background: #00d757;
      box-shadow: 2px 4px 14px rgba(0,0,0,.28);
      text-decoration: none;
      overflow: hidden;

      display: flex;
      align-items: center;
      justify-content: center;

      transition: all .25s ease;
    }

    .cs-wa-sign{
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cs-wa-svg{
      width: 34px;
      height: 34px;
      display: block;
    }
    .cs-wa-svg path{ fill:#fff; }

    /* default: text hidden (mobile icon only) */
    .cs-wa-text{
      display: none;
    }

    /* Desktop: hover expand (hanya device yang bisa hover) */
    @media (hover:hover) and (pointer:fine){
      .cs-wa-btn{
        justify-content: flex-start;
      }

      .cs-wa-text{
        display: block;
        position: absolute;
        right: 0;
        width: 0;
        opacity: 0;
        padding-right: 0;

        color: #fff;
        font-size: 15px;
        font-weight: 700;
        white-space: nowrap;

        transition: all .25s ease;
      }

      .cs-wa-btn:hover{
        width: 220px;
        border-radius: 44px;
      }

      .cs-wa-btn:hover .cs-wa-sign{
        width: 30%;
        padding-left: 14px;
        justify-content: flex-start;
      }

      .cs-wa-btn:hover .cs-wa-text{
        width: 70%;
        opacity: 1;
        padding-right: 16px;
      }
    }

   /* Wave background height: lebih tinggi di mobile, tetap seperti semula di desktop */
   .wave-bg-mobile {
    height: 1000px;
   }
   @media (min-width: 600px) {
    .wave-bg-mobile {
     height: 780px;
    }
   }
   /* Khusus non-desktop (maks 1023px) */
   .wave-img-mobile {
    object-fit: cover;
   }
   /* HP kecil */
   @media (max-width: 599px) {
    .wave-img-mobile {
     height: 540px;
    }
   }
   /* Tablet / hp lebar, tapi masih bukan desktop */
   @media (min-width: 600px) and (max-width: 1023px) {
    .wave-img-mobile {
     height: 830px;
    }
   }
   
  </style>
 </head>
 @include('partials.head')
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
  <!-- Loading Spinner -->
  <div id="loadingSpinner" class="fixed inset-0 bg-white z-50 flex items-center justify-center" style="opacity: 1; display: flex;">
   <div class="flex flex-col items-center gap-4">
    <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
    <div class="text-sm font-semibold text-neutral-600">Loading Please wait...</div>
   </div>
  </div>
  <div class="w-full bg-white relative overflow-hidden"></div>
  <div class="absolute inset-y-0 left-0 w-1/2 pointer-events-none block md:block"
     style="background-image: url('{{ asset('dot_background.png') }}');
            background-repeat: repeat;
            background-size: cover;
            opacity: 0.8;">
</div>
   <div class="relative"></div>
   <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm transition-shadow duration-300 w-full">
    <div class="mx-auto w-full max-w-[1400px] px-4 md:px-6 lg:px-10 py-4 md:py-5 lg:py-6">
     <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
       <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
      </div>
     </div>
    </div>
   </nav>

   <div class="mx-auto w-full max-w-[1400px] px-4 md:px-8 lg:px-10 pb-12 relative z-10">
    @include('partials.banner-carousel', ['iklans' => $iklans])

    <div class="relative mt-4 md:mt-8">
     <div class="pointer-events-none select-none absolute left-1/2 top-0 md:-top-10 -z-10 wave-bg-mobile"
          style="transform: translateX(-50%); width: 100vw; overflow: hidden;">
      <img src="{{ asset('wave.png') }}" alt="" class="w-full h-135 md:h-auto mt-0 md:-mt-36 object-cover wave-img-mobile">
     </div>

     <section class="relative z-10 space-y-10 md:space-y-12"></section>
     <div class="mt-1 md:mt-0">
       @include('partials.spesial_promo', ['specialPromos' => $specialPromos ?? null])
      </div>

      <div class="opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200 pt-1 md:pt-2" id="categorySection">
       <!-- Mobile Version: 5 columns (4 categories + See All) -->
       <div class="grid grid-cols-5 gap-2 md:hidden">
      <button onclick="filterCategory('food')" class="group flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center shadow-md drop-shadow-sm ring-1 ring-neutral-100/50 transition-all hover:shadow-xl hover:scale-105 hover:ring-rose-300 active:scale-95">
       <span class="grid h-10 w-10 place-items-center rounded-full bg-white transition-transform group-hover:scale-110">
        <img src="{{ asset('images/categories/food.png') }}" alt="Food" class="w-full h-full object-contain">
       </span>
       <span class="text-[9px] font-bold text-neutral-700 group-hover:text-rose-600 transition-colors leading-tight">Kuliner</span>
      </button>
      <button onclick="filterCategory('entertain')" class="group flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center shadow-md drop-shadow-sm ring-1 ring-neutral-100/50 transition-all hover:shadow-xl hover:scale-105 hover:ring-indigo-300 active:scale-95">
       <span class="grid h-10 w-10 place-items-center rounded-full bg-white transition-transform group-hover:scale-110">
        <img src="{{ asset('images/categories/entertain.png') }}" alt="Entertain" class="w-full h-full object-contain">
       </span>
       <span class="text-[9px] font-bold text-neutral-700 group-hover:text-indigo-600 transition-colors leading-tight">Hiburan</span>
      </button>
      <button onclick="filterCategory('vacation')" class="group flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center shadow-md drop-shadow-sm ring-1 ring-neutral-100/50 transition-all hover:shadow-xl hover:scale-105 hover:ring-purple-300 active:scale-95">
       <span class="grid h-10 w-10 place-items-center rounded-full bg-white transition-transform group-hover:scale-110">
        <img src="{{ asset('images/categories/vacation.png') }}" alt="Vacation" class="w-full h-full object-contain">
       </span>
       <span class="text-[9px] font-bold text-neutral-700 group-hover:text-purple-600 transition-colors leading-tight">Liburan</span>
      </button>
      <button onclick="filterCategory('beauty')" class="group flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center shadow-md drop-shadow-sm ring-1 ring-neutral-100/50 transition-all hover:shadow-xl hover:scale-105 hover:ring-pink-300 active:scale-95">
       <span class="grid h-10 w-10 place-items-center rounded-full bg-white transition-transform group-hover:scale-110">
        <img src="{{ asset('images/categories/beauty.png') }}" alt="Beauty" class="w-full h-full object-contain">
       </span>
       <span class="text-[9px] font-bold text-neutral-700 group-hover:text-pink-600 transition-colors leading-tight">Kecantikan</span>
      </button>
      <button onclick="openCategorySheet()" class="group flex flex-col items-center gap-1.5 rounded-xl bg-white p-2.5 text-center shadow-md drop-shadow-sm ring-1 ring-neutral-100/50 transition-all hover:shadow-xl hover:scale-105 hover:ring-orange-300 active:scale-95">
       <span class="grid h-10 w-10 place-items-center rounded-full bg-white transition-transform group-hover:scale-110">
        <img src="{{ asset('images/categories/all.png') }}" alt="Lihat Semua" class="w-full h-full object-contain">
       </span>
       <span class="text-[9px] font-bold text-neutral-700 group-hover:text-orange-600 transition-colors leading-tight">Lihat Semua</span>
      </button>
     </div>

       <!-- Desktop Version: 6 columns (5 categories + See All) -->
       <div class="hidden md:grid grid-cols-6 gap-4">
      <button onclick="filterCategory('food')" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-rose-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-16 w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/food.png') }}" alt="Food" class="w-full h-full object-contain">
       </span>
       <span class="text-xs font-bold text-neutral-700 group-hover:text-rose-600 transition-colors leading-tight">Kuliner</span>
      </button>
      <button onclick="filterCategory('entertain')" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-indigo-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-16 w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/entertain.png') }}" alt="Entertain" class="w-full h-full object-contain">
       </span>
       <span class="text-xs font-bold text-neutral-700 group-hover:text-indigo-600 transition-colors leading-tight">Hiburan</span>
      </button>
      <button onclick="filterCategory('vacation')" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-purple-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-16 w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/vacation.png') }}" alt="Vacation" class="w-full h-full object-contain">
       </span>
       <span class="text-xs font-bold text-neutral-700 group-hover:text-purple-600 transition-colors leading-tight">Liburan</span>
      </button>
      <button onclick="filterCategory('beauty')" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-pink-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-16 w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/beauty.png') }}" alt="Beauty" class="w-full h-full object-contain">
       </span>
       <span class="text-xs font-bold text-neutral-700 group-hover:text-pink-600 transition-colors leading-tight">Kecantikan</span>
      </button>
      <button onclick="filterCategory('shop')" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-orange-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-16 w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/shop.png') }}" alt="Shop" class="w-full h-full object-contain">
       </span>
       <span class="text-xs font-bold text-neutral-700 group-hover:text-orange-600 transition-colors leading-tight">Belanja</span>
      </button>
      <button onclick="openCategorySheet()" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-orange-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-16 w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/all.png') }}" alt="Lihat Semua" class="w-full h-full object-contain">
       </span>
       <span class="text-xs font-bold text-neutral-700 group-hover:text-orange-600 transition-colors leading-tight">Lihat Semua</span>
      </button>
       </div>
      </div>
     </section>
    </div>
   </div>
  </div>

<div class="mx-auto w-full max-w-[1400px]">
 <main class="px-4 md:px-8 lg:px-10 pb-12 md:pb-16">
   <section class="mt-0 md:mt-6 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-400 relative" id="searchSection" style="overflow: visible !important; z-index: 10;">
     <!-- Mobile Version -->
     <div class="md:hidden flex items-center gap-2">
      <div class="flex-1 rounded-lg bg-white px-3 py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all focus-within:ring-2 focus-within:ring-orange-400 focus-within:shadow-lg">
       <div class="flex items-center gap-2 text-neutral-500">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-neutral-400">
         <circle cx="11" cy="11" r="8"></circle>
         <path d="m21 21-4.35-4.35"></path>
        </svg>
        <input id="mobileSearchInput" class="w-full bg-transparent text-xs outline-none placeholder:text-neutral-400 font-semibold" placeholder="Search Product" />
       </div>
      </div>
      <button onclick="openMobileLocationSheet()" id="mobileLocationBtn" class="rounded-lg bg-white px-3 py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all hover:shadow-lg active:scale-95">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-neutral-500">
        <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
       </svg>
      </button>
      <button onclick="openMobilePointSheet()" id="mobilePointBtn" class="rounded-lg bg-white px-3 py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all hover:shadow-lg active:scale-95">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-neutral-500">
        <path d="M7 10l5-5 5 5M7 14l5 5 5-5"/>
       </svg>
      </button>
     </div>

     <!-- Desktop Version -->
     @php
      $locationList = collect($locations ?? [])->filter()->values();
     @endphp
     <div class="hidden md:flex flex-col md:flex-row items-stretch md:items-center gap-2 md:gap-3 max-w-3xl" style="overflow: visible !important; position: relative; z-index: 10;">
      <div class="flex-1 rounded-lg md:rounded-xl bg-white px-3 md:px-4 py-2 md:py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all focus-within:ring-2 focus-within:ring-orange-400 focus-within:shadow-lg">
       <div class="flex items-center gap-2 text-neutral-500">
        <span class="text-base md:text-lg">🔍</span>
        <input id="desktopSearchInput" class="w-full bg-transparent text-xs md:text-sm outline-none placeholder:text-neutral-400 font-semibold" placeholder="Cari produk atau voucher..." />
       </div>
      </div>
      <div class="relative rounded-lg md:rounded-xl border border-neutral-200 bg-white px-3 md:px-4 py-2 md:py-2.5 shadow-md">
       <div class="flex items-center gap-2 text-neutral-500">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-neutral-400">
         <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>
        <input id="locationInput" autocomplete="off" class="bg-transparent text-xs md:text-sm outline-none placeholder:text-neutral-400 font-semibold w-32" placeholder="Location" />
       </div>
       <div id="locationDropdown" class="absolute left-0 right-0 mt-1 z-50 bg-white border border-neutral-200 rounded-lg shadow-lg max-h-56 overflow-auto hidden backdrop-blur-sm"></div>
      </div>
     <div class="relative z-10" style="z-index: 10 !important; position: relative; overflow: visible !important;">
      <button onclick="toggleSortDropdown()" id="sortDropdownBtn" class="flex items-center justify-between w-full rounded-lg md:rounded-xl border border-neutral-200 bg-white px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm font-semibold shadow-md transition-all hover:shadow-lg hover:border-orange-400 focus:ring-2 focus:ring-orange-400 outline-none cursor-pointer min-w-[180px] relative z-10" style="z-index: 10 !important; position: relative;">
       <span id="sortSelectedText">According To Your Point</span>
        <svg id="sortDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-neutral-500 transition-transform duration-300">
         <path d="M7 10l5 5 5-5z"/>
        </svg>
       </button>
       <div id="sortDropdown" class="absolute left-0 right-0 mt-1 w-full rounded-lg md:rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top transition-all duration-300 ease-out z-[60] backdrop-blur-sm pointer-events-none" style="z-index: 60 !important; position: absolute !important;">
        <div class="py-1">
         <button onclick="selectSortOption('Lowest')" class="w-full text-left px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-neutral-700 hover:bg-orange-50 hover:text-orange-600 transition-colors flex items-center gap-2 cursor-pointer pointer-events-auto relative z-10">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-green-500 pointer-events-none">
           <path d="M7 14l5-5 5 5z"/>
          </svg>
          <span class="pointer-events-none">Lowest</span>
         </button>
         <button type="button" onclick="selectSortOption('Highest'); return false;" class="w-full text-left px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-neutral-700 hover:bg-orange-50 hover:text-orange-600 transition-colors flex items-center gap-2 cursor-pointer pointer-events-auto relative" style="pointer-events: auto !important; position: relative; z-index: 60 !important;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-red-500 pointer-events-none" style="pointer-events: none;">
           <path d="M7 10l5 5 5-5z"/>
          </svg>
          <span class="pointer-events-none" style="pointer-events: none;">Highest</span>
         </button>
        </div>
      </div>
     </div>
    </div>

    </section>

    @php
        // Helper function to check if category has data
        $hasCategoryData = function($category) use ($keywords) {
            return $keywords->filter(function ($keyword) use ($category) {
                $keywordCategory = !empty($keyword->kategori_keyword) ? $keyword->kategori_keyword : ($keyword->merchant->kategori ?? null);
                return $keyword->merchant 
                    && $keywordCategory === $category
                    && $keyword->status === 'approve'
                    && $keyword->is_active == 1
                    && $keyword->merchant->is_active == 1;
            })->isNotEmpty();
        };
    @endphp

    <!-- shop Section -->
    @if($hasCategoryData('belanja'))
    <div id="shopSection">
     @include('merchant.shop')
    </div>
    @endif
 
    <!-- food Section -->
    @if($hasCategoryData('kuliner'))
    <div id="foodSection">
     @include('merchant.food')
    </div>
    @endif
 
    <!-- telkomsel Section -->
    @if($hasCategoryData('telkomsel'))
    <div id="telkomselSection">
     @include('merchant.telkomsel')
    </div>
    @endif
 
    <!-- entertain Section -->
    @if($hasCategoryData('hiburan'))
    <div id="entertainSection">
     @include('merchant.entertain')
    </div>
    @endif
 
    <!-- vacation Section -->
    @if($hasCategoryData('liburan'))
    <div id="vacationSection">
     @include('merchant.vacation')
    </div>
    @endif
 
    <!-- beauty Section -->
    @if($hasCategoryData('kecantikan'))
    <div id="beautySection">
     @include('merchant.beautyncare')
    </div>
    @endif

    <!-- merchandise Section -->
    @if($hasCategoryData('merchandise'))
    <div id="merchandiseSection">
     @include('merchant.merchandise')
    </div>
    @endif

    <!-- paketvideo Section -->
    @if($hasCategoryData('paket_video'))
    <div id="paketvideoSection">
     @include('merchant.paketvideo')
    </div>
    @endif

    <!-- paketgames Section -->
    @if($hasCategoryData('paket_games'))
    <div id="paketgamesSection">
     @include('merchant.paketgames')
    </div>
    @endif

    <!-- paketinternet Section -->
    @if($hasCategoryData('paket_internet'))
    <div id="paketinternetSection">
     @include('merchant.paketinternet')
    </div>
    @endif
   

    <footer class="mt-16 pb-12 text-center">
     <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
      <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
     </div>
     <div class="text-xs text-neutral-500 font-medium"> 2025 BelanjaPoin. All rights reserved.</div>
    </footer>
   </main>
  </div>

  <!-- Bottom Sheet / Modal (Responsive) - Placed at body level for proper z-index -->
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
    <!-- Floating WhatsApp CS Button -->
    <a
      href="https://wa.me/628112500066?text=Halo%20CS%20BlanjaPoin%2C%20saya%20butuh%20bantuan."
      class="cs-wa-btn"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Chat WhatsApp Customer Service"
      title="Chat WhatsApp"
    >
        <span class="cs-wa-sign" aria-hidden="true">
          <svg class="cs-wa-svg" viewBox="0 0 16 16">
            <path
              d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"
            ></path>
          </svg>
        </span>

        <span class="cs-wa-text">Customer Service</span>
      </a>


  <script>
   // Page Load Animation
   document.addEventListener('DOMContentLoaded', function() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const pageBody = document.getElementById('pageBody');
    
    // Show spinner immediately
    if (loadingSpinner) {
     loadingSpinner.style.opacity = '1';
     loadingSpinner.style.display = 'flex';
    }
    
    // Hide spinner after 800ms
    setTimeout(() => {
     if (loadingSpinner) {
      loadingSpinner.style.opacity = '0';
      loadingSpinner.style.transform = 'scale(0.95)';
      setTimeout(() => {
       loadingSpinner.style.display = 'none';
      }, 500);
     }
    }, 300);
    
    // Animate sections (category and search) with intersection observer
    const sections = [
     'categorySection', 
     'searchSection'
    ];
    
    const observerOptions = {
     threshold: 0.1,
     rootMargin: '0px 0px -20px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
     entries.forEach(entry => {
      if (entry.isIntersecting) {
       entry.target.style.opacity = '1';
       entry.target.style.transform = 'translateY(0)';
       observer.unobserve(entry.target);
      }
     });
    }, observerOptions);
    
    sections.forEach(sectionId => {
     const section = document.getElementById(sectionId);
     if (section) {
      observer.observe(section);
     }
    });
    
    // Remove animation classes from all cards to make them visible immediately (no animation for cards)
    const cards = document.querySelectorAll('[data-voucher-card="true"]');
    cards.forEach(card => {
     card.classList.remove('opacity-0', 'translate-y-2');
     card.style.opacity = '1';
     card.style.transform = 'none';
    });
   });

   // Toggle Shop Cards
   let shopCardsExpanded = false;
   
   function toggleShopCards() {
    const extraCard = document.getElementById('extraShopCard');
    const arrow = document.getElementById('shopSeeAllArrow');
    const text = document.getElementById('shopSeeAllText');
    
    if (!shopCardsExpanded) {
     extraCard.style.maxHeight = extraCard.scrollHeight + 'px';
     extraCard.classList.remove('opacity-0', 'scale-y-0');
     extraCard.classList.add('opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     shopCardsExpanded = true;
    } else {
     extraCard.style.maxHeight = '0px';
     extraCard.classList.remove('opacity-100', 'scale-y-100');
     extraCard.classList.add('opacity-0', 'scale-y-0');
     arrow.textContent = '↓';
     text.textContent = 'See All';
     arrow.classList.remove('group-hover:-translate-y-1');
     arrow.classList.add('group-hover:translate-y-1');
     shopCardsExpanded = false;
    }
   }

   // Toggle Food Cards
   let foodCardsExpanded = false;
   
   function toggleFoodCards() {
    const extraCard = document.getElementById('extraFoodCard');
    const arrow = document.getElementById('foodSeeAllArrow');
    const text = document.getElementById('foodSeeAllText');
    
    if (!foodCardsExpanded) {
     extraCard.style.maxHeight = extraCard.scrollHeight + 'px';
     extraCard.classList.remove('opacity-0', 'scale-y-0');
     extraCard.classList.add('opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     foodCardsExpanded = true;
    } else {
     extraCard.style.maxHeight = '0px';
     extraCard.classList.remove('opacity-100', 'scale-y-100');
     extraCard.classList.add('opacity-0', 'scale-y-0');
     arrow.textContent = '↓';
     text.textContent = 'See All';
     arrow.classList.remove('group-hover:-translate-y-1');
     arrow.classList.add('group-hover:translate-y-1');
     foodCardsExpanded = false;
    }
   }

   // Toggle Telkomsel Cards
   let telkomselCardsExpanded = false;
   
   function toggleTelkomselCards() {
    const extraCard = document.getElementById('extraTelkomselCard');
    const arrow = document.getElementById('telkomselSeeAllArrow');
    const text = document.getElementById('telkomselSeeAllText');
    
    if (!telkomselCardsExpanded) {
     extraCard.style.maxHeight = extraCard.scrollHeight + 'px';
     extraCard.classList.remove('opacity-0', 'scale-y-0');
     extraCard.classList.add('opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     telkomselCardsExpanded = true;
    } else {
     extraCard.style.maxHeight = '0px';
     extraCard.classList.remove('opacity-100', 'scale-y-100');
     extraCard.classList.add('opacity-0', 'scale-y-0');
     arrow.textContent = '↓';
     text.textContent = 'See All';
     arrow.classList.remove('group-hover:-translate-y-1');
     arrow.classList.add('group-hover:translate-y-1');
     telkomselCardsExpanded = false;
    }
   }

   // Toggle Entertain Cards
   let entertainCardsExpanded = false;
   
   function toggleEntertainCards() {
    const extraCard = document.getElementById('extraEntertainCard');
    const arrow = document.getElementById('entertainSeeAllArrow');
    const text = document.getElementById('entertainSeeAllText');
    
    if (!entertainCardsExpanded) {
     extraCard.style.maxHeight = extraCard.scrollHeight + 'px';
     extraCard.classList.remove('opacity-0', 'scale-y-0');
     extraCard.classList.add('opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     entertainCardsExpanded = true;
    } else {
     extraCard.style.maxHeight = '0px';
     extraCard.classList.remove('opacity-100', 'scale-y-100');
     extraCard.classList.add('opacity-0', 'scale-y-0');
     arrow.textContent = '↓';
     text.textContent = 'See All';
     arrow.classList.remove('group-hover:-translate-y-1');
     arrow.classList.add('group-hover:translate-y-1');
     entertainCardsExpanded = false;
    }
   }

   // Toggle Vacation Cards
   let vacationCardsExpanded = false;
   
   function toggleVacationCards() {
    const extraCard = document.getElementById('extraVacationCard');
    const arrow = document.getElementById('vacationSeeAllArrow');
    const text = document.getElementById('vacationSeeAllText');
    
    if (!vacationCardsExpanded) {
     extraCard.style.maxHeight = extraCard.scrollHeight + 'px';
     extraCard.classList.remove('opacity-0', 'scale-y-0');
     extraCard.classList.add('opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     vacationCardsExpanded = true;
    } else {
     extraCard.style.maxHeight = '0px';
     extraCard.classList.remove('opacity-100', 'scale-y-100');
     extraCard.classList.add('opacity-0', 'scale-y-0');
     arrow.textContent = '↓';
     text.textContent = 'See All';
     arrow.classList.remove('group-hover:-translate-y-1');
     arrow.classList.add('group-hover:translate-y-1');
     vacationCardsExpanded = false;
    }
   }

   // Toggle Beauty Cards
   let beautyCardsExpanded = false;
   
   function toggleBeautyCards() {
    const extraCard = document.getElementById('extraBeautyCard');
    const arrow = document.getElementById('beautySeeAllArrow');
    const text = document.getElementById('beautySeeAllText');
    
    if (!beautyCardsExpanded) {
     extraCard.style.maxHeight = extraCard.scrollHeight + 'px';
     extraCard.classList.remove('opacity-0', 'scale-y-0');
     extraCard.classList.add('opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     beautyCardsExpanded = true;
    } else {
     extraCard.style.maxHeight = '0px';
     extraCard.classList.remove('opacity-100', 'scale-y-100');
     extraCard.classList.add('opacity-0', 'scale-y-0');
     arrow.textContent = '↓';
     text.textContent = 'See All';
     arrow.classList.remove('group-hover:-translate-y-1');
     arrow.classList.add('group-hover:translate-y-1');
     beautyCardsExpanded = false;
    }
   }

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

   // Toggle User Dropdown
   let userDropdownOpen = false;

   function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const arrow = document.getElementById('userDropdownArrow');
    
    if (!userDropdownOpen) {
     dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
     dropdown.classList.add('opacity-100', 'visible', 'scale-100');
     arrow.classList.add('rotate-180');
     userDropdownOpen = true;
    } else {
     dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
     dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
     arrow.classList.remove('rotate-180');
     userDropdownOpen = false;
    }
   }

   // Close dropdown when clicking outside
   document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const button = document.getElementById('userDropdownBtn');
    const arrow = document.getElementById('userDropdownArrow');
    
    if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
     if (userDropdownOpen) {
      dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
      dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
      arrow.classList.remove('rotate-180');
      userDropdownOpen = false;
     }
    }
   });

   // Toggle Sort Dropdown
   let sortDropdownOpen = false;

   function toggleSortDropdown() {
    const dropdown = document.getElementById('sortDropdown');
    const arrow = document.getElementById('sortDropdownArrow');
    
    if (!sortDropdownOpen) {
     // Remove hidden classes first
     dropdown.classList.remove('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
     
     // Force visibility with inline styles (before adding visible classes)
     dropdown.style.zIndex = '60';
     dropdown.style.display = 'block';
     dropdown.style.visibility = 'visible';
     dropdown.style.opacity = '1';
     dropdown.style.position = 'absolute';
     
     // Add visible classes
     dropdown.classList.add('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
     
     arrow.classList.add('rotate-180');
     sortDropdownOpen = true;
    } else {
     // Hide dropdown
     dropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
     dropdown.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
     dropdown.style.opacity = '0';
     dropdown.style.visibility = 'hidden';
     arrow.classList.remove('rotate-180');
     sortDropdownOpen = false;
    }
   }

   function selectSortOption(option) {
    const selectedText = document.getElementById('sortSelectedText');
    const dropdown = document.getElementById('sortDropdown');
    const arrow = document.getElementById('sortDropdownArrow');

    selectedText.textContent = option;
    mobilePointFilter = option;
    applyPointSort(option);

    // Close dropdown - ensure it's fully hidden
    dropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
    dropdown.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
    dropdown.style.opacity = '0';
    dropdown.style.visibility = 'hidden';
    arrow.classList.remove('rotate-180');
    sortDropdownOpen = false;
   }

   // Ensure sort dropdown buttons are clickable - add direct event listeners
   const sortDropdown = document.getElementById('sortDropdown');
   if (sortDropdown) {
    // Use event delegation as backup
    sortDropdown.addEventListener('click', function(e) {
     const button = e.target.closest('button[onclick*="selectSortOption"]');
     if (button) {
      const onclickAttr = button.getAttribute('onclick');
      if (onclickAttr && onclickAttr.includes('Highest')) {
       // Ensure Highest button works
       e.stopPropagation();
       selectSortOption('Highest');
       return false;
      }
     }
    }, true); // Use capture phase
   }

   // Close sort dropdown when clicking outside
   document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('sortDropdown');
    const button = document.getElementById('sortDropdownBtn');
    const arrow = document.getElementById('sortDropdownArrow');
    
    if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
     if (sortDropdownOpen) {
      dropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
      dropdown.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
      dropdown.style.opacity = '0';
      dropdown.style.visibility = 'hidden';
      arrow.classList.remove('rotate-180');
      sortDropdownOpen = false;
     }
    }
   });

   // Add shadow to navbar on scroll
   window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 0) {
     navbar.classList.add('shadow-lg');
    } else {
     navbar.classList.remove('shadow-lg');
    }
   });

   // Location searchable select (combobox)
  const serverLocations = <?php echo $locationList->toJson(); ?>;
   const locations = ['All', ...serverLocations];
   const locationInput = document.getElementById('locationInput');
   const locationDropdown = document.getElementById('locationDropdown');
   const voucherSections = new Map();
   let voucherCards = [];

   function refreshVoucherCards() {
    voucherCards = Array.from(document.querySelectorAll('[data-voucher-card="true"]'));
   }

   function registerVoucherSections() {
    voucherSections.clear();
    let fallbackIndex = 0;
    document.querySelectorAll('[data-voucher-container="true"]').forEach(container => {
     const sectionKey = container.dataset.voucherSection || `container-${fallbackIndex++}`;
     if (!voucherSections.has(sectionKey)) {
      voucherSections.set(sectionKey, []);
     }
     voucherSections.get(sectionKey).push({
      element: container,
      slotCount: container.querySelectorAll('[data-voucher-card="true"]').length
     });
    });
   }

   let currentLocationFilter = '';
   let currentPointSort = 'Lowest';
   let mobilePointFilter = 'Lowest';

   function renderLocationOptions(filter = '', dropdownElement, inputElement) {
    const f = filter.trim().toLowerCase();
    const options = locations.filter(l => f === '' ? true : l.toLowerCase().includes(f));
    if (options.length === 0) {
     dropdownElement.innerHTML = '<div class="px-3 py-2 text-sm text-neutral-500">No results</div>';
     return;
    }
    dropdownElement.innerHTML = options.map(l => `
     <div class="px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer" data-value="${l}">${l}</div>
    `).join('');
   }

   function openLocationDropdown(dropdownElement) {
    dropdownElement.classList.remove('hidden');
   }

   function closeLocationDropdown(dropdownElement) {
   dropdownElement.classList.add('hidden');
  }

   function normalizeQuery(value) {
    return (value ?? '').toString().toLowerCase().trim();
   }

   function applyVoucherFilters() {
    const locationQuery = normalizeQuery(currentLocationFilter);

    voucherCards.forEach(card => {
     const cardLocation = (card.dataset.searchLocation || '').toLowerCase();
     const matchesLocation = locationQuery === '' || cardLocation.includes(locationQuery);
     card.style.display = matchesLocation ? '' : 'none';
    });
   }

   function updateLocationFilter(value) {
    const normalizedValue = normalizeQuery(value);
    currentLocationFilter = normalizedValue === 'all' ? '' : normalizedValue;
    applyVoucherFilters();
   }

   function applyPointSort(order = 'Lowest') {
    currentPointSort = order === 'Highest' ? 'Highest' : 'Lowest';
    if (voucherSections.size === 0) {
     registerVoucherSections();
    }

    voucherSections.forEach(containerInfos => {
     const cards = [];
     containerInfos.forEach(info => {
      cards.push(...info.element.querySelectorAll('[data-voucher-card="true"]'));
     });

     if (cards.length === 0) {
      return;
     }

     const sortedCards = cards.slice().sort((a, b) => {
      const aPoint = parseFloat(cardPointValue(a)) || 0;
      const bPoint = parseFloat(cardPointValue(b)) || 0;
      return currentPointSort === 'Lowest' ? aPoint - bPoint : bPoint - aPoint;
     });

     let cursor = 0;
     containerInfos.forEach(info => {
      const slice = sortedCards.slice(cursor, cursor + info.slotCount);
      cursor += info.slotCount;

      info.element.innerHTML = '';
      slice.forEach(card => info.element.appendChild(card));
     });
    });

    refreshVoucherCards();
    applyVoucherFilters();
   }

   function cardPointValue(card) {
    // Get the redeem value from data-point attribute (which contains keyword->redeem)
    const pointValue = card?.dataset?.point ?? '0';
    // Remove any formatting (commas, dots) and parse as number
    return pointValue.toString().replace(/[^\d.-]/g, '') || '0';
   }

   if (locationInput && locationDropdown) {
    locationInput.addEventListener('focus', () => {
     renderLocationOptions(locationInput.value, locationDropdown, locationInput);
     openLocationDropdown(locationDropdown);
    });
    locationInput.addEventListener('input', () => {
     renderLocationOptions(locationInput.value, locationDropdown, locationInput);
     openLocationDropdown(locationDropdown);
     updateLocationFilter(locationInput.value);
    });
    locationDropdown.addEventListener('click', (e) => {
     const item = e.target.closest('[data-value]');
     if (!item) return;
     const selectedLocation = item.getAttribute('data-value');
     locationInput.value = selectedLocation;
     goToLocationSearch(selectedLocation);
     closeLocationDropdown(locationDropdown);
    });
    locationInput.addEventListener('keydown', (e) => {
     if (e.key === 'Enter') {
      e.preventDefault();
      goToLocationSearch(e.target.value);
     }
    });
    document.addEventListener('click', (e) => {
     if (!locationDropdown.contains(e.target) && e.target !== locationInput) {
      closeLocationDropdown(locationDropdown);
     }
    });
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

  // Helpers to render radio list
  function buildRadioList(options, selectedValue) {
   return `
    <div class="py-2">
     ${options.map(o => `
      <button type="button" class="w-full flex items-center justify-between px-6 py-4 text-base text-neutral-800 hover:bg-neutral-50" data-value="${o}">
       <span>${o}</span>
       <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border ${o===selectedValue? 'border-green-600':'border-neutral-300'}">
        <span class="w-3 h-3 rounded-full ${o===selectedValue? 'bg-green-600':'bg-transparent'}"></span>
       </span>
      </button>
     `).join('')}
    </div>
   `;
  }

  // Mobile Sort -> open sheet like screenshot "Urutkan"
  function toggleMobileSortDropdown() {
   const options = ['Paling Sesuai','Ulasan','Terbaru','Harga Tertinggi','Harga Terendah','Terlaris'];
   const html = buildRadioList(options, 'Paling Sesuai');
   openBottomSheet('Urutkan', html);
   const holder = document.getElementById('bottomSheetContent');
   holder.addEventListener('click', function onClick(e){
    const btn = e.target.closest('[data-value]');
    if (!btn) return;
    const val = btn.getAttribute('data-value');
    console.log('Mobile Sort by:', val);
    closeBottomSheet();
    holder.removeEventListener('click', onClick);
   });
  }


   // Mobile Point -> open sheet radios
  function openMobilePointSheet() {
   const options = ['Lowest','Highest'];
   const html = buildRadioList(options, mobilePointFilter);
   openBottomSheet('Filter Poin', html);
   const holder = document.getElementById('bottomSheetContent');
   holder.addEventListener('click', function onClick(e){
    const btn = e.target.closest('[data-value]');
    if (!btn) return;
    const val = btn.getAttribute('data-value');
    mobilePointFilter = val;
    const desktopSelectedText = document.getElementById('sortSelectedText');
    if (desktopSelectedText) {
     desktopSelectedText.textContent = val;
    }
    applyPointSort(val);
    closeBottomSheet();
    holder.removeEventListener('click', onClick);
   });
  }

   // Mobile Location via bottom sheet
  window.openMobileLocationSheet = function() {
   const searchId = 'mobileLocationSearchField';
   const listId = 'mobileLocationListHolder';
   const listHtml = `
    <div class="p-4">
     <div class="flex items-center gap-2 rounded-xl border border-neutral-200 px-4 py-2.5">
      <span>🔍</span>
      <input id="${searchId}" class="w-full bg-transparent outline-none text-sm" placeholder="Cari lokasi" />
     </div>
    </div>
    <div id="${listId}" class="pb-4"></div>
   `;
   openBottomSheet('Pilih Lokasi', listHtml);
   const renderList = (q='') => {
    const holder = document.getElementById(listId);
    const f = q.trim().toLowerCase();
    const opts = locations.filter(l => f===''? true : l.toLowerCase().includes(f));
    holder.innerHTML = opts.map(l => `
     <button type="button" class="w-full text-left px-6 py-4 text-base hover:bg-neutral-50" data-value="${l}">${l}</button>
    `).join('') || '<div class="px-6 py-4 text-neutral-500">Tidak ada hasil</div>';
   };
   renderList();
   const search = document.getElementById(searchId);
   search?.addEventListener('input', (e) => renderList(e.target.value));
   const content = document.getElementById('bottomSheetContent');
   content.addEventListener('click', function onClick(e){
    const item = e.target.closest('[data-value]');
    if (!item) return;
    const selectedLocation = item.getAttribute('data-value');
    if (locationInput) {
     locationInput.value = selectedLocation;
    }
    goToLocationSearch(selectedLocation);
    closeBottomSheet();
    content.removeEventListener('click', onClick);
   });
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


   // Search functionality (sync mobile and desktop)
   const mobileSearchInput = document.getElementById('mobileSearchInput');
   const desktopSearchInput = document.getElementById('desktopSearchInput');
   const searchPageUrl = "{{ route('merchant.search') }}";

   function goToSearchPage(query) {
    const trimmedQuery = (query || '').trim();
    if (trimmedQuery.length === 0) {
     return;
    }
    window.location.href = `${searchPageUrl}?q=${encodeURIComponent(trimmedQuery)}`;
   }

   if (mobileSearchInput) {
    mobileSearchInput.addEventListener('keydown', (e) => {
     if (e.key === 'Enter') {
      e.preventDefault();
      goToSearchPage(e.target.value);
     }
    });
   }

   if (desktopSearchInput) {
    desktopSearchInput.addEventListener('keydown', (e) => {
     if (e.key === 'Enter') {
      e.preventDefault();
      goToSearchPage(e.target.value);
     }
    });
   }

   // Function to convert location name to slug (similar to PHP territorialSlug)
   function locationToSlug(location) {
    if (!location) return '';
    
    let slug = location.trim();
    
    // Remove prefix "Kota" or "Kabupaten"
    slug = slug.replace(/^(Kota|Kabupaten)\s+/i, '');
    
    // Convert to lowercase
    slug = slug.toLowerCase();
    
    // Replace spaces with dash
    slug = slug.replace(/\s+/g, '-');
    
    // Remove special characters, keep only alphanumeric and dash
    slug = slug.replace(/[^a-z0-9\-]/g, '');
    
    // Remove multiple dashes
    slug = slug.replace(/-+/g, '-');
    
    // Trim dashes from start and end
    slug = slug.replace(/^-+|-+$/g, '');
    
    return slug;
   }

   function goToLocationSearch(locationValue) {
    const normalizedLocation = (locationValue || '').trim();
    if (normalizedLocation.length === 0 || normalizedLocation.toLowerCase() === 'all') {
     // If "All" is selected, just clear the filter and stay on the page
     updateLocationFilter('');
     if (locationInput) {
      locationInput.value = '';
     }
     return;
    }
    
    // Convert location name to slug
    const locationSlug = locationToSlug(normalizedLocation);
    if (!locationSlug) {
     return;
    }
    
    // Redirect to city.show route (territorial page)
    // Using url() helper to build the base URL
    const baseUrl = "{{ url('/city') }}";
    window.location.href = `${baseUrl}/${locationSlug}`;
   }

   // Initialize filters and sorting after DOM is ready
   function initializeFiltersAndSorting() {
    // Remove animation classes from all cards to make them visible immediately
    const cards = document.querySelectorAll('[data-voucher-card="true"]');
    cards.forEach(card => {
     card.classList.remove('opacity-0', 'translate-y-2');
     card.style.opacity = '1';
     card.style.transform = 'none';
    });
    
    refreshVoucherCards();
    registerVoucherSections();
    applyPointSort(currentPointSort);
   }
   
   // Run immediately and also after a short delay to catch any dynamically loaded content
   initializeFiltersAndSorting();
   setTimeout(initializeFiltersAndSorting, 500);

   // Function for redeem button click (welcome page - no location validation)
   function handleRedeemClick(redeemUrl, merchantId = null, keywordId = null) {
     if (redeemUrl && redeemUrl !== '#') {
       // Track click before redirect (if tracking function exists)
       if (typeof trackClick === 'function' && merchantId) {
         trackClick(merchantId, keywordId).then(() => {
           // Open redeem page in new tab after tracking
           window.open(redeemUrl, '_blank');
         }).catch(() => {
           // If tracking fails, still open the page
           window.open(redeemUrl, '_blank');
         });
       } else {
         // If no tracking, direct redeem
         window.open(redeemUrl, '_blank');
       }
     }
   }
  </script>
   </body>
</html>
