<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merchant {{ $locationName }} - BlanjaPoin</title>
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

      /* Mobile: smaller size */
      @media (max-width: 768px) {
        .cs-wa-btn{
          width: 52px;
          height: 52px;
          right: 14px;
          bottom: 14px;
        }

        .cs-wa-svg{
          width: 28px;
          height: 28px;
        }
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
    </style>
</head>
@include('partials.head')
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen">
    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-white z-50 flex items-center justify-center" style="opacity: 1; display: flex;">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
            <div class="text-sm font-semibold text-neutral-600">Loading Please wait...</div>
        </div>
    </div>

    <div class="w-full bg-white relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-full pointer-events-none block md:block"
             style="background-image: url('{{ asset('dot_background.png') }}');
                    background-repeat: repeat;
                    background-size: 1750px 1750px;
                    opacity: 0.8;
                    -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,0.9) 20%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.1) 80%, rgba(0,0,0,0) 100%);
                    mask-image: linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,0.9) 20%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.1) 80%, rgba(0,0,0,0) 100%);">
        </div>

        <!-- Navbar -->
        <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm transition-shadow duration-300 w-full">
            <div class="mx-auto w-full max-w-[1400px] px-4 md:px-6 lg:px-10 py-4 md:py-5 lg:py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}">
                            <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                        </a>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mx-auto w-full max-w-[1400px] px-4 md:px-8 lg:px-10 pb-12 relative z-10">
            <!-- Banner Carousel -->
            @include('partials.banner-carousel', ['iklans' => $iklans])

            <!-- Header Section -->
            <div class="mt-8 md:mt-12 mb-2">
                 <div class="flex items-center gap-2 mb-4">
                     <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-home"></i> Beranda  
                     </a>
                     <span class="text-gray-400">/</span>
                     <span class="text-sm font-semibold text-gray-700">City</span>
                     <span class="text-gray-400">/</span>
                     <span class="text-sm font-semibold text-orange-600">{{ $locationName }}</span>
                 </div>
                            
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">
                                Merchant di {{ $locationName }}
                        </h1>
                    <p class="text-gray-600">
                            Temukan merchant dan promo menarik di {{ $locationName }}                        
                    </p>
            </div>

            @php
                $allDaerah = \App\Models\Merchant::query()
                    ->whereNotNull('daerah')
                    ->where('daerah', '!=', '')
                    ->distinct()
                    ->pluck('daerah');
                
                $locationList = collect($allDaerah->map(function($daerah) {
                    return extractKabupatenKota($daerah);
                })
                ->filter()
                ->unique()
                ->values());
            @endphp

            <!-- Category Section -->
            <div class="opacity-0 translate-y-8 transition-all duration-700 ease-out delay-200 pt-1 md:pt-2 mt-4 md:mt-8" id="categorySection">
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
                    <button onclick="filterCategory('beauty')" class="group flex flex-col items-center gap-3 rounded-2xl bg-white p-5 text-center shadow-lg drop-shadow-md ring-1 ring-neutral-100/50 transition-all hover:shadow-xl hover:scale-110 hover:ring-pink-300 hover:-translate-y-1 active:scale-95">
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

            <!-- Search Section -->
            <section class="mt-4 md:mt-6 opacity-0 translate-y-8 transition-all duration-700 ease-out delay-400 relative" id="searchSection" style="overflow: visible !important; z-index: 10;">
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

            {{-- <!-- Spesial Promo Section -->
            <div class="mt-0 md:mt-1 -mb-6 md:-mb-8">
                <style>
                    main .special-promo-wrapper > div > section {
                        margin-top: 0.5rem !important;
                        margin-bottom: 1rem !important;
                    }
                    @media (min-width: 640px) {
                        main .special-promo-wrapper > div > section {
                            margin-top: 0.75rem !important;
                            margin-bottom: 1.25rem !important;
                        }
                    }
                </style>
                <div class="special-promo-wrapper">
                    @include('partials.spesial_promo')
                </div>
            </div> --}}

            <!-- Category Sections -->
            @php
                $isTerritorial = $isTerritorial ?? false;

                // Determine route type and specific value for category ordering
                $routeSegment = request()->segment(1);
                $routeTypeMap = ['u' => 'u', 'reg' => 'reg', 'poin-tsel' => 'poin-tsel', 'cluster' => 'cluster', 'city' => 'city'];
                $currentRouteType  = $routeTypeMap[$routeSegment] ?? 'default';
                $currentRouteValue = ($currentRouteType !== 'default') ? (request()->segment(2) ?? '') : '';

                // Get ordered category list from DB (falls back: specific → generic → default → hardcoded)
                $orderedCategories = \App\Models\CategoryOrder::getOrderedCategories($currentRouteType, $currentRouteValue);

                // Admin-configured default sort for vouchers within each category (by redeem point),
                // keyed by the category's data-voucher-section value so JS can target the right cards.
                $categoryItemSortMap = collect($orderedCategories)
                    ->mapWithKeys(fn($cat) => [($cat['section'] ?? $cat['key']) => ($cat['item_sort'] ?? 'none')])
                    ->all();

                // Helper: check if a category has displayable data
                $hasCategoryData = function($category) use ($keywords, $isTerritorial) {
                    return $keywords->filter(function ($keyword) use ($category, $isTerritorial) {
                        $keywordCategory = !empty($keyword->kategori_keyword) ? $keyword->kategori_keyword : ($keyword->merchant->kategori ?? null);
                        $baseCondition = $keyword->merchant && $keywordCategory === $category
                            && $keyword->status === 'approve'
                            && $keyword->is_active == 1;
                        return $isTerritorial
                            ? $baseCondition
                            : ($baseCondition && $keyword->merchant->is_active == 1);
                    })->isNotEmpty();
                };
            @endphp

            @foreach($orderedCategories as $cat)
                @if($hasCategoryData($cat['key']))
                    @include($cat['view'])
                @endif
            @endforeach



            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

    <script>
        // Hide loading spinner when page loads
        window.addEventListener('load', function() {
            const spinner = document.getElementById('loadingSpinner');
            if (spinner) {
                spinner.style.opacity = '0';
                setTimeout(() => {
                    spinner.style.display = 'none';
                }, 300);
            }
        });

        // Animate cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards with intersection observer
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.target.style.display !== 'none') {
                        entry.target.classList.remove('opacity-0', 'translate-y-2');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        cardObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '0px 0px -10px 0px' });
            
            // Observe all cards
            const cards = document.querySelectorAll('article[class*="opacity-0"]');
            cards.forEach((card, index) => {
                if (card.style.display === 'none') return;
                cardObserver.observe(card);
                
                setTimeout(() => {
                    if (card.style.display === 'none') return;
                    const rect = card.getBoundingClientRect();
                    const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;
                    if (isInViewport && card.classList.contains('opacity-0')) {
                        card.classList.remove('opacity-0', 'translate-y-2');
                        card.classList.add('opacity-100', 'translate-y-0');
                        cardObserver.unobserve(card);
                    }
                }, 200 + (index * 50));
            });
            
            setTimeout(() => {
                cards.forEach(card => {
                    if (card.style.display === 'none') return;
                    if (card.classList.contains('opacity-0')) {
                        card.classList.remove('opacity-0', 'translate-y-2');
                        card.classList.add('opacity-100', 'translate-y-0');
                    }
                });
            }, 1000);
        });

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

        // Function to format SKB text - split numbered items into separate lines
        function formatSKB(text) {
         if (!text) return '';
         
         // Check if text contains numbered items (1., 2., 3., etc.)
         const numberedPattern = /(\d+\.\s+)/g;
         const matches = text.match(numberedPattern);
         
         if (matches && matches.length > 1) {
          // Split by numbered patterns and format each item on a new line
          const parts = text.split(/(\d+\.\s+)/);
          let formatted = '';
          
          for (let i = 0; i < parts.length; i++) {
           const part = parts[i];
           if (!part.trim()) continue;
           
           // If this is a numbered item (like "1. "), add newline before it (except first)
           if (part.match(numberedPattern)) {
            if (i > 0 && formatted.trim()) {
             formatted += '\n';
            }
            formatted += part;
           } else {
            // This is content, add it
            formatted += part.trim();
            // Add newline if next part is a numbered item
            if (i < parts.length - 1 && parts[i + 1] && parts[i + 1].match(numberedPattern)) {
             formatted += '\n';
            }
           }
          }
          
          return formatted.trim();
         }
         
         // If no numbered pattern found, preserve existing line breaks
         return text;
        }



        // === Location Page Filter: Search + Sort + Category (Welcome Style) ===
        (function() {
            // Location searchable select (combobox)
            const serverLocations = <?php echo $locationList->toJson(); ?>;
            const uniqueLocations = serverLocations.reduce((acc, location) => {
                const normalized = location.toLowerCase();
                if (!acc.map.has(normalized)) {
                    acc.map.set(normalized, location);
                    acc.list.push(location);
                }
                return acc;
            }, { map: new Map(), list: [] }).list;
            const locations = ['All', ...uniqueLocations];

            const locationInput = document.getElementById('locationInput');
            const locationDropdown = document.getElementById('locationDropdown');
            const mobileSearchInput = document.getElementById('mobileSearchInput');
            const desktopSearchInput = document.getElementById('desktopSearchInput');

            let voucherSections = new Map();
            let voucherCards = [];
            let currentLocationFilter = '';
            let currentPointSort = 'Lowest';
            let mobilePointFilter = 'Lowest';
            const CATEGORY_ITEM_SORT = @json($categoryItemSortMap ?? []);
            let defaultItemSortApplied = false;

            // Apply each category's own admin-configured point sort (if any) to its section only.
            function applyDefaultItemSorts() {
                if (voucherSections.size === 0) registerVoucherSections();
                let appliedAny = false;

                voucherSections.forEach((containerInfos, sectionKey) => {
                    const order = CATEGORY_ITEM_SORT[sectionKey];
                    if (order !== 'redeem_desc' && order !== 'redeem_asc') return;
                    appliedAny = true;

                    const cards = [];
                    containerInfos.forEach(info => cards.push(...info.element.querySelectorAll('[data-voucher-card="true"]')));
                    if (cards.length === 0) return;

                    const sortedCards = cards.slice().sort((a, b) => {
                        const aPoint = parseFloat(cardPointValue(a)) || 0;
                        const bPoint = parseFloat(cardPointValue(b)) || 0;
                        return order === 'redeem_asc' ? aPoint - bPoint : bPoint - aPoint;
                    });

                    let cursor = 0;
                    containerInfos.forEach(info => {
                        const slice = sortedCards.slice(cursor, cursor + info.slotCount);
                        cursor += info.slotCount;
                        info.element.innerHTML = '';
                        slice.forEach(card => info.element.appendChild(card));
                    });
                });

                if (appliedAny) {
                    refreshVoucherCards();
                    const searchQ = (mobileSearchInput && mobileSearchInput.value) || (desktopSearchInput && desktopSearchInput.value) || '';
                    applyClientSearch(searchQ);
                }
            }

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

            function cardPointValue(card) {
                const pointValue = card?.dataset?.point ?? '0';
                return pointValue.toString().replace(/[^\d.-]/g, '') || '0';
            }

            // Real-time client-side search by name
            function applyClientSearch(q) {
                const query = (q || '').toLowerCase().trim();
                const locationQuery = (currentLocationFilter || '').toLowerCase().trim();
                
                voucherCards.forEach(card => {
                    const name = (card.dataset.searchName || '').toLowerCase();
                    const cardLocation = (card.dataset.searchLocation || '').toLowerCase();
                    const matchesName = query === '' || name.startsWith(query);
                    const matchesLocation = locationQuery === '' || cardLocation.includes(locationQuery);
                    card.style.display = (matchesName && matchesLocation) ? '' : 'none';
                });
            }

            function updateLocationFilter(value) {
                const normalizedValue = (value ?? '').toString().toLowerCase().trim();
                currentLocationFilter = normalizedValue === 'all' ? '' : normalizedValue;
                const searchQ = (mobileSearchInput && mobileSearchInput.value) || (desktopSearchInput && desktopSearchInput.value) || '';
                applyClientSearch(searchQ);
            }

            window.applyPointSort = function(order = 'Lowest') {
                currentPointSort = order === 'Highest' ? 'Highest' : 'Lowest';
                if (voucherSections.size === 0) registerVoucherSections();

                voucherSections.forEach(containerInfos => {
                    const cards = [];
                    containerInfos.forEach(info => cards.push(...info.element.querySelectorAll('[data-voucher-card="true"]')));
                    if (cards.length === 0) return;

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
                const searchQ = (mobileSearchInput && mobileSearchInput.value) || (desktopSearchInput && desktopSearchInput.value) || '';
                applyClientSearch(searchQ);
            }

            // --- Dropdowns & Selection ---
            let sortDropdownOpen = false;
            window.toggleSortDropdown = function() {
                const dropdown = document.getElementById('sortDropdown');
                const arrow = document.getElementById('sortDropdownArrow');
                if (!sortDropdownOpen) {
                    dropdown.classList.remove('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
                    dropdown.style.zIndex = '60';
                    dropdown.style.display = 'block';
                    dropdown.style.visibility = 'visible';
                    dropdown.style.opacity = '1';
                    dropdown.style.position = 'absolute';
                    dropdown.classList.add('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
                    arrow.classList.add('rotate-180');
                    sortDropdownOpen = true;
                } else {
                    dropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
                    dropdown.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
                    dropdown.style.opacity = '0';
                    dropdown.style.visibility = 'hidden';
                    arrow.classList.remove('rotate-180');
                    sortDropdownOpen = false;
                }
            }

            window.selectSortOption = function(option) {
                document.getElementById('sortSelectedText').textContent = option;
                mobilePointFilter = option;
                applyPointSort(option);
                const dropdown = document.getElementById('sortDropdown');
                const arrow = document.getElementById('sortDropdownArrow');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
                arrow.classList.remove('rotate-180');
                sortDropdownOpen = false;
            }

            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('sortDropdown');
                const button = document.getElementById('sortDropdownBtn');
                const arrow = document.getElementById('sortDropdownArrow');
                if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target) && sortDropdownOpen) {
                    dropdown.classList.remove('opacity-100', 'visible', 'scale-100', 'pointer-events-auto');
                    dropdown.classList.add('opacity-0', 'invisible', 'scale-95', 'pointer-events-none');
                    dropdown.style.opacity = '0';
                    dropdown.style.visibility = 'hidden';
                    arrow.classList.remove('rotate-180');
                    sortDropdownOpen = false;
                }
                if (locationDropdown && !locationDropdown.contains(event.target) && event.target !== locationInput) {
                    locationDropdown.classList.add('hidden');
                }
            });

            // Location searchable input logic
            function renderLocationOptions(filter = '') {
                const f = filter.trim().toLowerCase();
                const options = locations.filter(l => f === '' ? true : l.toLowerCase().includes(f));
                if (options.length === 0) {
                    locationDropdown.innerHTML = '<div class="px-3 py-2 text-sm text-neutral-500">No results</div>';
                    return;
                }
                locationDropdown.innerHTML = options.map(l => `
                    <div class="px-3 py-2 text-sm hover:bg-neutral-100 cursor-pointer" data-value="${l}">${l.toUpperCase()}</div>
                `).join('');
            }

            function locationToSlug(location) {
                if (!location) return '';
                let slug = location.trim().replace(/^(Kota|Kabupaten)\s+/i, '').toLowerCase();
                slug = slug.replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '').replace(/-+/g, '-').replace(/^-+|-+$/g, '');
                return slug;
            }

            window.goToLocationSearch = function(locationValue) {
                const normalizedLocation = (locationValue || '').trim();
                if (normalizedLocation.length === 0 || normalizedLocation.toLowerCase() === 'all') {
                    updateLocationFilter('');
                    if (locationInput) locationInput.value = '';
                    return;
                }
                const locationSlug = locationToSlug(normalizedLocation);
                if (!locationSlug) return;
                window.location.href = `{{ url('/city') }}/${locationSlug}`;
            }

            if (locationInput && locationDropdown) {
                locationInput.addEventListener('focus', () => {
                    renderLocationOptions(locationInput.value);
                    locationDropdown.classList.remove('hidden');
                });
                locationInput.addEventListener('input', () => {
                    renderLocationOptions(locationInput.value);
                    locationDropdown.classList.remove('hidden');
                    updateLocationFilter(locationInput.value);
                });
                locationDropdown.addEventListener('click', (e) => {
                    const item = e.target.closest('[data-value]');
                    if (!item) return;
                    const selectedLocation = item.getAttribute('data-value');
                    locationInput.value = selectedLocation;
                    goToLocationSearch(selectedLocation);
                    locationDropdown.classList.add('hidden');
                });
                locationInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        goToLocationSearch(e.target.value);
                    }
                });
            }

            // Client side Search Input listener
            const searchPageUrl = "{{ route('merchant.search') }}";
            const searchScope = { source: 'city', source_value: '{{ $location ?? "" }}' };
            window.goToSearchPage = function(query) {
                const trimmedQuery = (query || '').trim();
                if (trimmedQuery.length === 0) return;
                window.location.href = `${searchPageUrl}?q=${encodeURIComponent(trimmedQuery)}&source=${encodeURIComponent(searchScope.source)}&source_value=${encodeURIComponent(searchScope.source_value)}`;
            }

            let searchNavTimeout = null;
            function debouncedNavigate(value) {
                if (searchNavTimeout) clearTimeout(searchNavTimeout);
                searchNavTimeout = setTimeout(() => { goToSearchPage(value); }, 600);
            }

            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('input', (e) => {
                    applyClientSearch(e.target.value);
                    debouncedNavigate(e.target.value);
                });
                mobileSearchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); if (searchNavTimeout) clearTimeout(searchNavTimeout); goToSearchPage(e.target.value); }
                });
            }

            if (desktopSearchInput) {
                desktopSearchInput.addEventListener('input', (e) => {
                    applyClientSearch(e.target.value);
                    debouncedNavigate(e.target.value);
                });
                desktopSearchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); if (searchNavTimeout) clearTimeout(searchNavTimeout); goToSearchPage(e.target.value); }
                });
            }

            // Animation and Initialization
            document.addEventListener('DOMContentLoaded', function() {
                const sections = ['categorySection', 'searchSection'];
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
                
                sections.forEach(id => {
                    const section = document.getElementById(id);
                    if (section) observer.observe(section);
                });

                refreshVoucherCards();
                registerVoucherSections();

                if (!defaultItemSortApplied) {
                    defaultItemSortApplied = true;
                    applyDefaultItemSorts();
                }
            });

            // Category and Sheet logic
            window.filterCategory = function(category) {
                const selectedSection = document.getElementById('section-' + category);
                if (selectedSection) {
                    const navbar = document.getElementById('navbar');
                    const navbarHeight = navbar ? navbar.offsetHeight : 0;
                    window.scrollTo({ top: selectedSection.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 20, behavior: 'smooth' });
                }
            }

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

            window.openMobilePointSheet = function() {
                const options = ['Lowest','Highest'];
                const html = buildRadioList(options, mobilePointFilter);
                openBottomSheet('Filter Poin', html);
                const holder = document.getElementById('bottomSheetContent');
                holder.addEventListener('click', function onClick(e){
                    const btn = e.target.closest('[data-value]');
                    if (!btn) return;
                    const val = btn.getAttribute('data-value');
                    mobilePointFilter = val;
                    const dt = document.getElementById('sortSelectedText');
                    if (dt) dt.textContent = val;
                    applyPointSort(val);
                    closeBottomSheet();
                    holder.removeEventListener('click', onClick);
                });
            }

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
                        <button type="button" class="w-full text-left px-6 py-4 text-base hover:bg-neutral-50" data-value="${l}">${l.toUpperCase()}</button>
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
                    if (locationInput) locationInput.value = selectedLocation;
                    goToLocationSearch(selectedLocation);
                    closeBottomSheet();
                    content.removeEventListener('click', onClick);
                });
            }

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

            window.selectCategoryFromSheet = function(category) {
                closeBottomSheet();
                setTimeout(() => filterCategory(category), 300);
            }
        })();

        // Territorial Description Bottom Sheet Function
        function openTerritorialDescriptionSheet(keywordId, merchantName, productName, skb, diskon) {
            const contentHTML = `
                <div class="px-5 pb-6">
                    <div class="space-y-1">
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
                            <div class="mt-0">
                                <div class="text-sm text-neutral-600 leading-none whitespace-pre-line break-words" style="line-height: 1.2;">
                                    ${formatSKB(skb)}
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;

            openBottomSheet('Deskripsi', contentHTML);
        }
    </script>

    <!-- Bottom Sheet / Modal (Responsive) -->
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
        href="https://wa.me/628113700040?text=Halo%20CS%20BlanjaPoin%2C%20saya%20butuh%20bantuan."
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
    @include('partials.desktop-alert')
    @include('partials.redeem-script')
</body>
</html>

