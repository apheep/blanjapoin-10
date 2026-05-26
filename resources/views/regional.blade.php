<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merchant Regional {{ $locationName }} - BlanjaPoin</title>
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
                    <span class="text-sm font-semibold text-gray-700">Regional</span>
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
                // Helper function to check if category has data
                $isRegional = $isRegional ?? false;
                $hasCategoryData = function($category) use ($keywords, $isRegional) {
                    return $keywords->filter(function ($keyword) use ($category, $isRegional) {
                        $keywordCategory = !empty($keyword->kategori_keyword) ? $keyword->kategori_keyword : ($keyword->merchant->kategori ?? null);
                        $baseCondition = $keyword->merchant && $keywordCategory === $category
                            && $keyword->status === 'approve'
                            && $keyword->is_active == 1;
                        // Skip validasi merchant->is_active jika di halaman regional
                        return $isRegional 
                            ? $baseCondition 
                            : ($baseCondition && $keyword->merchant->is_active == 1);
                    })->isNotEmpty();
                };
            @endphp

            <!-- shop Section -->
            @if($hasCategoryData('belanja'))
                @include('merchant.shop')
            @endif

            <!-- food Section -->
            @if($hasCategoryData('kuliner'))
                @include('merchant.food')
            @endif

            <!-- telkomsel Section -->
            @if($hasCategoryData('telkomsel'))
                @include('merchant.telkomsel')
            @endif

            <!-- entertain Section -->
            @if($hasCategoryData('hiburan'))
                @include('merchant.entertain')
            @endif

            <!-- vacation Section -->
            @if($hasCategoryData('liburan'))
                @include('merchant.vacation')
            @endif

            <!-- beauty Section -->
            @if($hasCategoryData('kecantikan'))
                @include('merchant.beautyncare')
            @endif

            <!-- merchandise Section -->
            @if($hasCategoryData('merchandise'))
                @include('merchant.merchandise')
            @endif

            <!-- paketvideo Section -->
            @if($hasCategoryData('paket_video'))
                @include('merchant.paketvideo')
            @endif

            <!-- paketgames Section -->
            @if($hasCategoryData('paket_games'))
                @include('merchant.paketgames')
            @endif

            <!-- paketinternet Section -->
            @if($hasCategoryData('paket_internet'))
                @include('merchant.paketinternet')
            @endif



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



        // === Location Page Filter: Search + Sort + Category ===
        (function() {
            let locCurrentSort = 'Lowest', locSortOpen = false;
            const locVoucherSections = new Map();
            let locVoucherCards = [];
            function locRefreshCards() { locVoucherCards = Array.from(document.querySelectorAll('[data-voucher-card="true"]')); }
            function locRegisterSections() {
                locVoucherSections.clear(); let idx = 0;
                document.querySelectorAll('[data-voucher-container="true"]').forEach(container => {
                    const key = container.dataset.voucherSection || `c-${idx++}`;
                    if (!locVoucherSections.has(key)) locVoucherSections.set(key, []);
                    locVoucherSections.get(key).push({ element: container, slotCount: container.querySelectorAll('[data-voucher-card="true"]').length });
                });
            }
            function locApplySearch(q) {
                const query = (q || '').toLowerCase().trim();
                locVoucherCards.forEach(card => { const name = (card.dataset.searchName || '').toLowerCase(); card.style.display = (query === '' || name.includes(query)) ? '' : 'none'; });
            }
            function locApplySort(order) {
                locCurrentSort = order === 'Highest' ? 'Highest' : 'Lowest';
                if (locVoucherSections.size === 0) locRegisterSections();
                locVoucherSections.forEach(infos => {
                    const cards = []; infos.forEach(info => cards.push(...info.element.querySelectorAll('[data-voucher-card="true"]'))); if (!cards.length) return;
                    const sorted = cards.slice().sort((a, b) => { const ap = parseFloat((a.dataset.point||'0').replace(/[^\d.-]/g,''))||0; const bp = parseFloat((b.dataset.point||'0').replace(/[^\d.-]/g,''))||0; return locCurrentSort==='Lowest'?ap-bp:bp-ap; });
                    let cur = 0; infos.forEach(info => { const slice = sorted.slice(cur, cur+info.slotCount); cur+=info.slotCount; info.element.innerHTML=''; slice.forEach(c=>info.element.appendChild(c)); });
                }); locRefreshCards();
            }
            window.toggleLocSortDropdown = function() {
                const dd=document.getElementById('locSortDropdown'),arrow=document.getElementById('locSortDropdownArrow');
                if(!locSortOpen){dd.classList.remove('opacity-0','invisible','scale-95','pointer-events-none');dd.classList.add('opacity-100','visible','scale-100','pointer-events-auto');arrow.classList.add('rotate-180');locSortOpen=true;}
                else{dd.classList.remove('opacity-100','visible','scale-100','pointer-events-auto');dd.classList.add('opacity-0','invisible','scale-95','pointer-events-none');arrow.classList.remove('rotate-180');locSortOpen=false;}
            };
            window.selectLocSortOption = function(option) {
                document.getElementById('locSortSelectedText').textContent=option;
                const dd=document.getElementById('locSortDropdown'),arrow=document.getElementById('locSortDropdownArrow');
                dd.classList.remove('opacity-100','visible','scale-100','pointer-events-auto');dd.classList.add('opacity-0','invisible','scale-95','pointer-events-none');
                arrow.classList.remove('rotate-180');locSortOpen=false;locApplySort(option);
            };
            window.locFilterCategory = function(cat) {
                const el=document.getElementById('section-'+cat);
                if(el){const nb=document.getElementById('navbar');const offset=nb?nb.offsetHeight:0;window.scrollTo({top:el.getBoundingClientRect().top+window.pageYOffset-offset-20,behavior:'smooth'});}
            };
            document.addEventListener('click',function(e){
                const dd=document.getElementById('locSortDropdown'),btn=document.getElementById('locSortDropdownBtn');
                if(dd&&btn&&!btn.contains(e.target)&&!dd.contains(e.target)&&locSortOpen){dd.classList.remove('opacity-100','visible','scale-100','pointer-events-auto');dd.classList.add('opacity-0','invisible','scale-95','pointer-events-none');document.getElementById('locSortDropdownArrow').classList.remove('rotate-180');locSortOpen=false;}
            });
            document.addEventListener('DOMContentLoaded',function(){
                locRefreshCards();locRegisterSections();
                const inp=document.getElementById('locationSearchInput');
                if(inp)inp.addEventListener('input',e=>locApplySearch(e.target.value));
            });
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


