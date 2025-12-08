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
        <div class="absolute inset-y-0 left-0 w-1/2 pointer-events-none block md:block"
             style="background-image: url('{{ asset('dot_background.png') }}');
                    background-repeat: repeat;
                    background-size: cover;
                    opacity: 0.8;">
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
            <div class="mt-8 md:mt-12 mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-sm font-semibold text-gray-700">City</span>
                    <span class="text-gray-400">/</span>
                    <span class="text-sm font-semibold text-orange-600">{{ $locationName }}</span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                    Merchant di {{ $locationName }}
                </h1>
                <p class="text-gray-600">
                    Temukan merchant dan promo menarik di {{ $locationName }}
                </p>
            </div>

            <!-- Keywords Grid -->
            @if($keywords->count() > 0)
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
                @foreach($keywords as $keyword)
                    @php
                        $merchant = $keyword->merchant;
                        $merchantName = optional($merchant)->nama_merchant ?? '';
                        $productName = $keyword->nama_produk ?? '';
                        $locationName = extractKabupatenKota(optional($merchant)->daerah ?? '');
                        $searchName = strtolower(trim($merchantName . ' ' . $productName));
                        $searchLocation = strtolower($locationName);
                        $uniqueId = 'territorial-card-' . $keyword->id;
                    @endphp
                    
                    <article 
                        data-voucher-card="true"
                        data-point="{{ (int) $keyword->redeem }}"
                        data-search-name="{{ $searchName }}"
                        data-search-location="{{ $searchLocation }}"
                        onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" 
                        class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md hover:shadow-xl transition-all duration-300 hover:border-orange-300 hover:-translate-y-1 cursor-pointer h-full min-h-[280px]"
                    >
                        <!-- Mobile Layout -->
                        <div class="lg:hidden flex flex-col h-full">
                            <div class="relative h-32 flex-shrink-0 overflow-hidden">
                                <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" 
                                     alt="{{ $keyword->nama_produk }}" 
                                     class="w-full h-full object-cover" 
                                     loading="lazy">
                                @if($keyword->stock)
                                <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-lg flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                                    Stock: {{ $keyword->stock }}
                                </div>
                                @endif
                            </div>
                            <div class="flex flex-col p-3 space-y-2 flex-1">
                                <h3 class="text-2xl font-bold text-neutral-900 leading-tight">
                                    {{ $merchantName }}
                                </h3>
                                <div class="text-[11px] text-neutral-600 leading-relaxed">
                                    @if(!is_null($keyword->diskon))
                                    <div class="font-bold text-red-500 flex items-center gap-2 mb-1">
                                        <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-5 h-5 object-contain">
                                        <span class="text-xl font-bold text-red-500">{{ formatDiskon($keyword->diskon) }}</span>
                                    </div>
                                    @endif
                                    @if($keyword->skb)
                                    <div class="relative">
                                        <div id="{{ $uniqueId }}-text" class="line-clamp-3 transition-all duration-300">
                                            {{ $keyword->skb }}
                                        </div>
                                        <button 
                                            id="{{ $uniqueId }}-btn" 
                                            onclick="event.stopPropagation(); toggleDescription('{{ $uniqueId }}')" 
                                            class="hidden mt-1 text-orange-600 font-semibold flex items-center gap-1 hover:text-orange-700 transition-colors"
                                        >
                                            <span id="{{ $uniqueId }}-btn-text">See details</span>
                                            <svg id="{{ $uniqueId }}-arrow" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <div class="inline-flex items-center gap-1.5 bg-white rounded-full px-0.5 py-0.5 self-start">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[8px] font-bold shadow-sm">P</span>
                                    <span class="text-[20px] font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Layout -->
                        <div class="hidden lg:flex flex-col h-full">
                            <div class="relative h-40 flex-shrink-0 overflow-hidden">
                                <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" 
                                     alt="{{ $keyword->nama_produk }}" 
                                     class="w-full h-full object-cover" 
                                     loading="lazy">
                                @if($keyword->stock)
                                <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                    Stock: {{ $keyword->stock }}
                                </div>
                                @endif
                            </div>
                            <div class="flex flex-col p-4 space-y-3 flex-1">
                                <h3 class="text-xl font-bold text-neutral-900 leading-tight">
                                    {{ $merchantName }}
                                </h3>
                                <div class="text-xs text-neutral-600 leading-relaxed">
                                    @if(!is_null($keyword->diskon))
                                    <div class="font-bold text-red-500 flex items-center gap-2 mb-2">
                                        <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-6 h-6 object-contain">
                                        <span class="text-2xl font-bold text-red-500">{{ formatDiskon($keyword->diskon) }}</span>
                                    </div>
                                    @endif
                                    @if($keyword->skb)
                                    <div class="relative">
                                        <div id="{{ $uniqueId }}-desktop-text" class="line-clamp-3 transition-all duration-300">
                                            {{ $keyword->skb }}
                                        </div>
                                        <button 
                                            id="{{ $uniqueId }}-desktop-btn" 
                                            onclick="event.stopPropagation(); toggleDescription('{{ $uniqueId }}-desktop')" 
                                            class="hidden mt-1 text-orange-600 font-semibold flex items-center gap-1 hover:text-orange-700 transition-colors"
                                        >
                                            <span id="{{ $uniqueId }}-desktop-btn-text">See details</span>
                                            <svg id="{{ $uniqueId }}-desktop-arrow" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <div class="inline-flex items-center gap-2 bg-white rounded-full px-1 py-1 self-start">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white text-[10px] font-bold shadow-sm">P</span>
                                    <span class="text-2xl font-bold text-red-600">{{ number_format($keyword->redeem, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-store text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada merchant di {{ $locationName }}</h3>
                <p class="text-gray-600 mb-6">Coba pilih teritorial lain atau kembali ke beranda</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>
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

        // Toggle description function (from welcome.blade.php)
        function toggleDescription(uniqueId) {
            const textElement = document.getElementById(uniqueId + '-text');
            const btnElement = document.getElementById(uniqueId + '-btn');
            const btnTextElement = document.getElementById(uniqueId + '-btn-text');
            const arrowElement = document.getElementById(uniqueId + '-arrow');

            if (!textElement || !btnElement) return;

            if (textElement.classList.contains('line-clamp-3')) {
                textElement.classList.remove('line-clamp-3');
                if (btnTextElement) btnTextElement.textContent = 'See less';
                if (arrowElement) arrowElement.style.transform = 'rotate(180deg)';
            } else {
                textElement.classList.add('line-clamp-3');
                if (btnTextElement) btnTextElement.textContent = 'See details';
                if (arrowElement) arrowElement.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>
</html>

