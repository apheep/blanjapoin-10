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
      <div class="flex items-center gap-2 md:gap-3">
       <div class="flex items-center gap-1.5 md:gap-2 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 px-3 md:px-4 py-1.5 md:py-2 shadow-lg drop-shadow-md ring-1 ring-amber-200/50 transition-all hover:shadow-xl hover:drop-shadow-lg hover:scale-105">
        <span class="inline-flex h-5 w-5 md:h-6 md:w-6 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] md:text-xs font-bold shadow-sm">P</span>
        <span class="text-xs md:text-sm font-bold text-neutral-800">1,289</span>
       </div>
       <div class="relative">
        <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white shadow-lg shadow-orange-300/50 drop-shadow-lg ring-1 ring-white/30 transition-all hover:shadow-xl hover:shadow-orange-400/50 hover:drop-shadow-xl hover:scale-105 active:scale-95">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 md:h-4 md:w-4 opacity-95">
          <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
         </svg>
         <span class="tracking-tight">Willy</span>
         <svg id="userDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 md:h-3.5 md:w-3.5 opacity-95 transition-transform duration-300">
          <path d="M7 10l5 5 5-5z"/>
         </svg>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top-right transition-all duration-300 ease-out z-50 backdrop-blur-sm">
         <div class="py-1">
          <form method="POST" action="{{ route('logout') }}">
           @csrf
           <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
             <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd" />
            </svg>
            <span>Logout</span>
           </button>
          </form>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </nav>

  <div class="mx-auto max-w-[1120px]">
   <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
    <section class="mt-1 md:mt-1 opacity-0 translate-y-8 transition-all duration-700 ease-out" id="bannerSection">
     <div class="relative group">
      <!-- Navigation Arrows -->
      <button onclick="prevSlide()" class="absolute left-3 md:left-6 top-1/2 -translate-y-1/2 z-10 grid h-10 w-10 md:h-14 md:w-14 place-items-center rounded-full bg-white/95 backdrop-blur-sm shadow-xl transition-all hover:bg-white hover:scale-110 active:scale-95 text-neutral-700 font-bold text-xl md:text-3xl">
       ‹
      </button>
      
      <button onclick="nextSlide()" class="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 z-10 grid h-10 w-10 md:h-14 md:w-14 place-items-center rounded-full bg-white/95 backdrop-blur-sm shadow-xl transition-all hover:bg-white hover:scale-110 active:scale-95 text-neutral-700 font-bold text-xl md:text-3xl">
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
      <div class="mt-8 md:mt-10 flex items-center justify-center gap-4 md:gap-5">
       <span onclick="goToSlide(0)" class="carousel-dot h-4 w-4 md:h-5 md:w-5 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>
       <span onclick="goToSlide(1)" class="carousel-dot h-4 w-4 md:h-5 md:w-5 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>
       <span onclick="goToSlide(2)" class="carousel-dot h-4 w-4 md:h-5 md:w-5 rounded-full bg-neutral-300 transition-all hover:scale-125 cursor-pointer hover:bg-orange-400 shadow-lg"></span>
      </div>
     </div>
    </section>

    <section class="mt-8 md:mt-10 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200" id="categorySection">
     <div class="grid grid-cols-3 gap-2.5 md:gap-4 sm:grid-cols-6">
      <button onclick="filterCategory('food')" class="group flex flex-col items-center gap-2 md:gap-3 rounded-xl md:rounded-2xl bg-white p-3 md:p-5 text-center shadow-md md:shadow-lg drop-shadow-sm md:drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-rose-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-12 w-12 md:h-16 md:w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/food.png') }}" alt="Food" class="w-full h-full object-contain">
       </span>
       <span class="text-[10px] md:text-xs font-bold text-neutral-700 group-hover:text-rose-600 transition-colors leading-tight">Food & Beverage</span>
      </button>
      <button onclick="filterCategory('entertain')" class="group flex flex-col items-center gap-2 md:gap-3 rounded-xl md:rounded-2xl bg-white p-3 md:p-5 text-center shadow-md md:shadow-lg drop-shadow-sm md:drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-indigo-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-12 w-12 md:h-16 md:w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/entertain.png') }}" alt="Entertain" class="w-full h-full object-contain">
       </span>
       <span class="text-[10px] md:text-xs font-bold text-neutral-700 group-hover:text-indigo-600 transition-colors leading-tight">Entertain</span>
      </button>
      <button onclick="filterCategory('vacation')" class="group flex flex-col items-center gap-2 md:gap-3 rounded-xl md:rounded-2xl bg-white p-3 md:p-5 text-center shadow-md md:shadow-lg drop-shadow-sm md:drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-purple-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-12 w-12 md:h-16 md:w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/vacation.png') }}" alt="Vacation" class="w-full h-full object-contain">
       </span>
       <span class="text-[10px] md:text-xs font-bold text-neutral-700 group-hover:text-purple-600 transition-colors leading-tight">Vacation</span>
      </button>
      <button onclick="filterCategory('beauty')" class="group flex flex-col items-center gap-2 md:gap-3 rounded-xl md:rounded-2xl bg-white p-3 md:p-5 text-center shadow-md md:shadow-lg drop-shadow-sm md:drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-pink-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-12 w-12 md:h-16 md:w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/beauty.png') }}" alt="Beauty" class="w-full h-full object-contain">
       </span>
       <span class="text-[10px] md:text-xs font-bold text-neutral-700 group-hover:text-pink-600 transition-colors leading-tight">Beauty & Care</span>
      </button>
      <button onclick="filterCategory('shop')" class="group flex flex-col items-center gap-2 md:gap-3 rounded-xl md:rounded-2xl bg-white p-3 md:p-5 text-center shadow-md md:shadow-lg drop-shadow-sm md:drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-orange-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-12 w-12 md:h-16 md:w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/shop.png') }}" alt="Shop" class="w-full h-full object-contain">
       </span>
       <span class="text-[10px] md:text-xs font-bold text-neutral-700 group-hover:text-orange-600 transition-colors leading-tight">Shop</span>
      </button>
      <button onclick="filterCategory('telkomsel')" class="group flex flex-col items-center gap-2 md:gap-3 rounded-xl md:rounded-2xl bg-white p-3 md:p-5 text-center shadow-md md:shadow-lg drop-shadow-sm md:drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-2xl hover:drop-shadow-xl hover:scale-110 hover:ring-red-300 hover:-translate-y-1 active:scale-95">
       <span class="grid h-12 w-12 md:h-16 md:w-16 place-items-center rounded-full bg-white transition-transform group-hover:scale-125 group-hover:rotate-12">
        <img src="{{ asset('images/categories/telkomsel.png') }}" alt="telkomsel" class="w-20 h-20 md:w-15 md:h-15 object-contain mx-auto">
       </span>
       <span class="text-[10px] md:text-xs font-bold text-neutral-700 group-hover:text-red-600 transition-colors leading-tight">Telkomsel Data</span>
      </button>
     </div>
    </section>

    <section class="mt-8 md:mt-12 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-400" id="searchSection">
     <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 md:gap-3 max-w-3xl">
      <div class="flex-1 rounded-lg md:rounded-xl bg-white px-3 md:px-4 py-2 md:py-2.5 shadow-md ring-1 ring-neutral-200/50 transition-all focus-within:ring-2 focus-within:ring-orange-400 focus-within:shadow-lg">
       <div class="flex items-center gap-2 text-neutral-500">
        <span class="text-base md:text-lg">🔍</span>
        <input class="w-full bg-transparent text-xs md:text-sm outline-none placeholder:text-neutral-400 font-semibold" placeholder="Cari produk atau voucher..." />
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
     extraCard.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
     extraCard.classList.add('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     shopCardsExpanded = true;
    } else {
     extraCard.classList.remove('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     extraCard.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
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
     extraCard.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
     extraCard.classList.add('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     foodCardsExpanded = true;
    } else {
     extraCard.classList.remove('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     extraCard.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
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
     extraCard.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
     extraCard.classList.add('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     telkomselCardsExpanded = true;
    } else {
     extraCard.classList.remove('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     extraCard.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
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
     extraCard.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
     extraCard.classList.add('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     entertainCardsExpanded = true;
    } else {
     extraCard.classList.remove('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     extraCard.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
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
     extraCard.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
     extraCard.classList.add('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     vacationCardsExpanded = true;
    } else {
     extraCard.classList.remove('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     extraCard.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
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
     extraCard.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
     extraCard.classList.add('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     arrow.textContent = '↑';
     text.textContent = 'Show Less';
     arrow.classList.remove('group-hover:translate-y-1');
     arrow.classList.add('group-hover:-translate-y-1');
     beautyCardsExpanded = true;
    } else {
     extraCard.classList.remove('max-h-[1000px]', 'opacity-100', 'scale-y-100');
     extraCard.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
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
     selectedSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
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

   function renderLocationOptions(filter = '') {
    const f = filter.trim().toLowerCase();
    const options = locations.filter(l => f === '' ? true : l.toLowerCase().startsWith(f));
    if (options.length === 0) {
     locationDropdown.innerHTML = '<div class="px-3 py-2 text-sm text-neutral-500">No results</div>';
     return;
    }
    locationDropdown.innerHTML = options.map(l => `
     <div class="px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer" data-value="${l}">${l}</div>
    `).join('');
   }

   function openLocationDropdown() {
    locationDropdown.classList.remove('hidden');
   }

   function closeLocationDropdown() {
    locationDropdown.classList.add('hidden');
   }

   if (locationInput && locationDropdown) {
    locationInput.addEventListener('focus', () => {
     renderLocationOptions(locationInput.value);
     openLocationDropdown();
    });
    locationInput.addEventListener('input', () => {
     renderLocationOptions(locationInput.value);
     openLocationDropdown();
    });
    locationDropdown.addEventListener('click', (e) => {
     const item = e.target.closest('[data-value]');
     if (!item) return;
     locationInput.value = item.getAttribute('data-value');
     closeLocationDropdown();
    });
    document.addEventListener('click', (e) => {
     if (!locationDropdown.contains(e.target) && e.target !== locationInput) {
      closeLocationDropdown();
     }
    });
   }
  </script>
 </body>
</html>
