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
   <nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full">
    <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6">
     <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
       <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
      </div>
     </div>
    </div>
   </nav>

  <div class="mx-auto max-w-[1120px]">
   <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
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

      <!-- Background Image -->
      <img id="bannerImage"
          src="{{ asset('storage/iklan/iklan1.jpeg') }}"
          alt="Banner Promo"
          class="w-full h-full object-cover transition-all duration-700 rounded-3xl md:rounded-[2.5rem]"
          loading="lazy">

      <!-- Gradient Overlay 1 -->
      <div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/10 to-transparent rounded-3xl md:rounded-[2.5rem]"></div>

      <!-- Gradient Overlay 2 -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent rounded-3xl md:rounded-[2.5rem]"></div>

      </div>

      
      <!-- Carousel Dots -->
      <div class="mt-2 md:mt-3/2 flex items-center justify-center gap-3 md:gap-3">
       <span onclick="goToSlide(0)" class="carousel-dot h-3 w-3 md:h-3 md:w-3 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>
       <span onclick="goToSlide(1)" class="carousel-dot h-3 w-3 md:h-3 md:w-3 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>
       <span onclick="goToSlide(2)" class="carousel-dot h-3 w-3 md:h-3 md:w-3 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>
      </div>
     </div>
    </section>

    <section class="mt-8 md:mt-10 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200" id="categorySection">
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
       <span class="text-[9px] font-bold text-neutral-700 group-hover:text-indigo-600 transition-colors leading-tight">Lifestyle</span>
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
       <span class="text-xs font-bold text-neutral-700 group-hover:text-pink-600 transition-colors leading-tight">Kesehatan & Kecantikan</span>
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
    </section>

    <section class="mt-8 md:mt-12 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-400 relative" id="searchSection">
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
      <button onclick="openMobilePointSheet()" id="mobilePointBtn" class="rounded-lg bg-white px-3 py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all hover:shadow-lg active:scale-95">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-neutral-500">
        <path d="M7 10l5-5 5 5M7 14l5 5 5-5"/>
       </svg>
      </button>
      <button onclick="openMobileLocationSheet()" id="mobileLocationBtn" class="rounded-lg bg-white px-3 py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all hover:shadow-lg active:scale-95">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-neutral-500">
        <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
       </svg>
      </button>
     </div>

     <!-- Desktop Version -->
     <div class="hidden md:flex flex-col md:flex-row items-stretch md:items-center gap-2 md:gap-3 max-w-3xl">
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
     <div class="relative">
      <button onclick="toggleSortDropdown()" id="sortDropdownBtn" class="flex items-center justify-between w-full rounded-lg md:rounded-xl border border-neutral-200 bg-white px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm font-semibold shadow-md transition-all hover:shadow-lg hover:border-orange-400 focus:ring-2 focus:ring-orange-400 outline-none cursor-pointer min-w-[180px]">
       <span id="sortSelectedText">According To Your Point</span>
        <svg id="sortDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-neutral-500 transition-transform duration-300">
         <path d="M7 10l5 5 5-5z"/>
        </svg>
       </button>
       <div id="sortDropdown" class="absolute left-0 right-0 mt-1 w-full rounded-lg md:rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top transition-all duration-300 ease-out z-50 backdrop-blur-sm">
        <div class="py-1">
         <button onclick="selectSortOption('Lowest')" class="w-full text-left px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-neutral-700 hover:bg-orange-50 hover:text-orange-600 transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-green-500">
           <path d="M7 14l5-5 5 5z"/>
          </svg>
          <span>Lowest</span>
         </button>
         <button onclick="selectSortOption('Highest')" class="w-full text-left px-3 md:px-4 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-neutral-700 hover:bg-orange-50 hover:text-orange-600 transition-colors flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-red-500">
           <path d="M7 10l5 5 5-5z"/>
          </svg>
          <span>Highest</span>
         </button>
        </div>
      </div>
     </div>
    </div>

    <div id="searchEmptyState" class="hidden mt-4 text-center text-xs md:text-sm font-semibold text-neutral-500">
     Voucher yang kamu cari belum ditemukan.
    </div>

    </section>

    <!-- shop Section -->
    <div class="opacity-0 translate-y-4 transition-all duration-300 ease-out delay-100" id="shopSection">
     @include('merchant.shop')
    </div>
 
    <!-- food Section -->
    <div class="opacity-0 translate-y-4 transition-all duration-300 ease-out delay-100" id="foodSection">
     @include('merchant.food')
    </div>
 
    <!-- telkomsel Section -->
    <div class="opacity-0 translate-y-4 transition-all duration-300 ease-out delay-100" id="telkomselSection">
     @include('merchant.telkomsel')
    </div>
 
    <!-- entertain Section -->
    <div class="opacity-0 translate-y-4 transition-all duration-300 ease-out delay-100" id="entertainSection">
     @include('merchant.entertain')
    </div>
 
    <!-- vacation Section -->
    <div class="opacity-0 translate-y-4 transition-all duration-300 ease-out delay-100" id="vacationSection">
     @include('merchant.vacation')
    </div>
 
    <!-- beauty Section -->
    <div class="opacity-0 translate-y-4 transition-all duration-300 ease-out delay-350" id="beautySection">
     @include('merchant.beautyncare')
    </div>
   

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
   <div id="desktopModal" class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-3xl shadow-2xl overflow-hidden transition-all duration-300 ease-out w-full max-w-2xl" style="opacity: 0; transform: translate(-50%, -50%) scale(0.95);">
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
    
    // Animate sections with intersection observer
    const sections = [
     'bannerSection',
     'categorySection', 
     'searchSection',
     'shopSection',
     'foodSection',
     'telkomselSection',
     'entertainSection',
     'vacationSection',
     'beautySection'
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
    
    // Animate cards within sections with faster timing
    const cardObserver = new IntersectionObserver((entries) => {
     entries.forEach(entry => {
      if (entry.isIntersecting) {
       entry.target.style.opacity = '1';
       entry.target.style.transform = 'translateY(0)';
       cardObserver.unobserve(entry.target);
      }
     });
    }, { threshold: 0.05, rootMargin: '0px 0px -10px 0px' });
    
    // Observe all cards
    const cards = document.querySelectorAll('article[class*="opacity-0"]');
    cards.forEach(card => {
     cardObserver.observe(card);
    });
   });

   // Carousel configuration
   const slides = [
    '{{ asset("storage/iklan/iklan1.jpeg") }}',
    '{{ asset("storage/iklan/iklan2.jpeg") }}',
    '{{ asset("storage/iklan/iklan3.jpeg") }}'
   ];
   
   let currentSlide = 0;
   let autoSlideInterval;
   
   // Get elements
   const bannerImage = document.getElementById('bannerImage');
   const dots = document.querySelectorAll('.carousel-dot');
   
   // Update slide
   function updateSlide(index) {
    currentSlide = index;
    
    // Fade effect
    bannerImage.style.opacity = '0';
    
    setTimeout(() => {
     bannerImage.src = slides[currentSlide];
     bannerImage.style.opacity = '1';
    }, 250);
    
    // Update dots
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
   
   // Next slide
   function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    updateSlide(currentSlide);
    resetAutoSlide();
   }
   
   // Previous slide
   function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    updateSlide(currentSlide);
    resetAutoSlide();
   }
   
   // Go to specific slide
   function goToSlide(index) {
    updateSlide(index);
    resetAutoSlide();
   }
   
   // Auto slide
   function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
     nextSlide();
    }, 2000); // Change slide every 5 seconds
   }
   
   // Reset auto slide
   function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
   }
   
   // Initialize
   updateSlide(0);
   startAutoSlide();
   
   // Pause on hover
   const carouselSection = document.querySelector('section');
   carouselSection.addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
   });
   
   carouselSection.addEventListener('mouseleave', () => {
    startAutoSlide();
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
     dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
     dropdown.classList.add('opacity-100', 'visible', 'scale-100');
     arrow.classList.add('rotate-180');
     sortDropdownOpen = true;
    } else {
     dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
     dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
     arrow.classList.remove('rotate-180');
     sortDropdownOpen = false;
    }
   }

   function selectSortOption(option) {
    const selectedText = document.getElementById('sortSelectedText');
    const dropdown = document.getElementById('sortDropdown');
    const arrow = document.getElementById('sortDropdownArrow');
    
    selectedText.textContent = option;
    
    // Close dropdown
    dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
    dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
    arrow.classList.remove('rotate-180');
    sortDropdownOpen = false;
    
    // Here you can add logic to actually sort the content based on the selected option
    console.log('Sort by:', option);
   }

   // Close sort dropdown when clicking outside
   document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('sortDropdown');
    const button = document.getElementById('sortDropdownBtn');
    const arrow = document.getElementById('sortDropdownArrow');
    
    if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
     if (sortDropdownOpen) {
      dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
      dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
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
   const locations = ['All','Surabaya','Sidoarja','Malang','Madiun','Jakarta','Jogja','Bandung','Bali'];
   const locationInput = document.getElementById('locationInput');
   const locationDropdown = document.getElementById('locationDropdown');
   const voucherCards = Array.from(document.querySelectorAll('[data-voucher-card="true"]'));
   const totalVoucherCards = voucherCards.length;
   const searchEmptyState = document.getElementById('searchEmptyState');
   let currentSearchQuery = '';
   let currentLocationFilter = '';

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
    const nameQuery = normalizeQuery(currentSearchQuery);
    const locationQuery = normalizeQuery(currentLocationFilter);
    let visibleCount = 0;

    voucherCards.forEach(card => {
     const cardName = card.dataset.searchName || '';
     const cardLocation = card.dataset.searchLocation || '';
     const matchesName = nameQuery === '' || cardName.includes(nameQuery);
     const matchesLocation = locationQuery === '' || cardLocation.includes(locationQuery);
     const shouldShow = matchesName && matchesLocation;
     card.style.display = shouldShow ? '' : 'none';
     if (shouldShow) {
      visibleCount++;
     }
    });

    if (searchEmptyState) {
     const shouldHideEmpty = visibleCount !== 0 || totalVoucherCards === 0;
     searchEmptyState.classList.toggle('hidden', shouldHideEmpty);
    }
   }

   function updateLocationFilter(value) {
    const normalizedValue = normalizeQuery(value);
    currentLocationFilter = normalizedValue === 'all' ? '' : normalizedValue;
    applyVoucherFilters();
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
     locationInput.value = item.getAttribute('data-value');
     updateLocationFilter(locationInput.value);
     closeLocationDropdown(locationDropdown);
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
  let mobilePointFilter = 'Lowest'; // Store selected filter
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
    console.log('Mobile Point Filter:', val);
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
    updateLocationFilter(selectedLocation);
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
    { id: 'telkomsel', name: 'Telkomsel Data', icon: 'telkomsel.png', color: 'red' }
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

   function handleSearch(value) {
    const textValue = (value ?? '').toString();
    currentSearchQuery = textValue;
    if (mobileSearchInput && mobileSearchInput.value !== textValue) {
     mobileSearchInput.value = textValue;
    }
    if (desktopSearchInput && desktopSearchInput.value !== textValue) {
     desktopSearchInput.value = textValue;
    }
    applyVoucherFilters();
   }

   if (mobileSearchInput) {
    mobileSearchInput.addEventListener('input', (e) => {
     handleSearch(e.target.value);
    });
   }

   if (desktopSearchInput) {
    desktopSearchInput.addEventListener('input', (e) => {
     handleSearch(e.target.value);
    });
   }

   applyVoucherFilters();
  </script>
 </body>
</html>
