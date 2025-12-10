@extends('layouts.app')
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $merchant->nama_merchant }} - Voucher | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="fixed inset-0 bg-white z-50 flex items-center justify-center" style="opacity: 1; display: flex;">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
            <div class="text-sm font-semibold text-neutral-600">Loading Please wait...</div>
        </div>
    </div>

    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full ">
        <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6">
            <div class="flex items-center">
                <a href="{{ route('home') }}">
                    <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="mx-auto max-w-[1120px]">
        <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
            @include('partials.banner-carousel', ['iklans' => $iklans])

                <div class="flex flex-wrap items-start justify-between gap-4 pl-1 mt-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mt-4">Voucher</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</h1>
                
                </div>
            </div>

            <!-- Voucher Section -->
            <section class="mt-4 md:mt-6">

                @if($keywords->count() > 0)
                    <!-- Voucher Grid -->
                    <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-5 items-stretch px-1">
                        @foreach($keywords as $keyword)
                            @php
                                $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
                                $productName = $keyword->nama_produk ?? '';
                                $locationName = extractKabupatenKota(optional($keyword->merchant)->daerah ?? '');
                                $searchName = strtolower(trim($merchantName . ' ' . $productName));
                                $searchLocation = strtolower($locationName);
                                $uniqueId = 'pelanggan-card-' . $keyword->id;
                                $canRedeem = !$keyword->start_date || \Carbon\Carbon::now()->startOfDay()->gte(\Carbon\Carbon::parse($keyword->start_date)->startOfDay());
                                $startDateFormatted = $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d-M-y') : '';
                            @endphp
                            <article data-voucher-card="true" data-point="{{ (int) $keyword->redeem }}" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" @if($canRedeem) onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" @endif class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200/80 bg-white shadow-md transition-all duration-300 h-full min-h-[280px] {{ $canRedeem ? 'hover:shadow-xl hover:border-orange-300 hover:-translate-y-1 cursor-pointer' : 'opacity-75 cursor-not-allowed' }}">
                                
                                <!-- Mobile Layout -->
                                <div class="lg:hidden flex flex-col h-full">
                                    <div class="relative">
                                        <div class="aspect-[4/3] rounded-t-xl bg-gradient-to-br from-neutral-100 to-neutral-200 shadow-inner overflow-hidden">
                                            <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" 
                                                 alt="{{ $keyword->nama_produk }}" 
                                                 class="w-full h-full object-cover" 
                                                 loading="lazy">
                                        </div>
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
                                                        class="hidden mt-1 text-orange-600 font-semibold items-center gap-1 hover:text-orange-700 transition-colors"
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
                                        @if(!$canRedeem)
                                        <div class="mt-2 w-full inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2 px-4 rounded-lg text-xs">
                                            Open {{ $startDateFormatted }}
                                        </div>
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
                                                    <div class="absolute inset-0 rounded-xl blur-sm"></div>
                                                    <img src="{{ asset('storage/' . $keyword->merchant->logo_merchant) }}" alt="{{ $merchantName }}" class="relative w-12 h-12 md:w-16 md:h-16 object-contain rounded-xl shadow-md">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 md:w-16 md:h-16 flex-shrink-0"></div>
                                            @endif
                                        </div>
                                        @if($keyword->diskon)
                                            <div class="text-right flex-shrink-0 ml-2">
                                                <div class="inline-flex items-center gap-2">
                                                    <img src="{{ asset('icon-diskon.png') }}" alt="Diskon" class="w-10 h-10 object-contain">
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
                                            {{ $productName ?: $merchantName }}
                                        </h4>
                                        @if($keyword->skb)
                                            <div class="relative">
                                                <p id="{{ $uniqueId }}-text-desktop" class="text-xs md:text-sm text-neutral-600 mb-2.5 leading-relaxed line-clamp-2 transition-all duration-300">
                                                    {{ $keyword->skb }}
                                                </p>
                                                <button 
                                                    id="{{ $uniqueId }}-btn-desktop" 
                                                    onclick="event.stopPropagation(); toggleDescriptionDesktop('{{ $uniqueId }}')" 
                                                    class="hidden text-orange-600 font-semibold items-center gap-1 hover:text-orange-700 transition-colors text-xs"
                                                >
                                                    <span id="{{ $uniqueId }}-btn-text-desktop">See details</span>
                                                    <svg id="{{ $uniqueId }}-arrow-desktop" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </div>
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
                                                @if($canRedeem)
                                                <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                    </svg>
                                                </div>
                                                @endif
                                            </div>
                                            @if(!$canRedeem)
                                            <div class="w-full inline-flex items-center justify-center bg-gray-400 text-white font-bold py-2.5 px-4 rounded-lg text-sm md:text-base">
                                                Open {{ $startDateFormatted }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12 md:py-16">
                        <div class="inline-block p-6 bg-gradient-to-br from-orange-50 to-rose-50 rounded-3xl mb-4">
                            <i class="fas fa-ticket-alt text-6xl text-orange-400"></i>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-neutral-900 mb-2">
                            Belum Ada Voucher
                        </h3>
                        <p class="text-sm md:text-base text-neutral-600">
                            Saat ini belum ada voucher yang tersedia untuk merchant ini.
                        </p>
                    </div>
                @endif
            </section>

            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

    <!-- Script untuk hide loading spinner -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const spinner = document.getElementById('loadingSpinner');
            if (spinner) {
                setTimeout(() => {
                    spinner.style.opacity = '0';
                    spinner.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        spinner.style.display = 'none';
                    }, 300);
                }, 500);
            }
        });

        // Toggle description functions (same as welcome.blade.php)
        function toggleDescription(uniqueId) {
            const textEl = document.getElementById(uniqueId + '-text');
            const btnEl = document.getElementById(uniqueId + '-btn');
            const btnTextEl = document.getElementById(uniqueId + '-btn-text');
            const arrowEl = document.getElementById(uniqueId + '-arrow');

            if (textEl && btnEl) {
                if (textEl.classList.contains('line-clamp-3')) {
                    textEl.classList.remove('line-clamp-3');
                    btnTextEl.textContent = 'Show less';
                    arrowEl.style.transform = 'rotate(180deg)';
                } else {
                    textEl.classList.add('line-clamp-3');
                    btnTextEl.textContent = 'See details';
                    arrowEl.style.transform = 'rotate(0deg)';
                }
            }
        }

        function toggleDescriptionDesktop(uniqueId) {
            const textEl = document.getElementById(uniqueId + '-text-desktop');
            const btnEl = document.getElementById(uniqueId + '-btn-desktop');
            const btnTextEl = document.getElementById(uniqueId + '-btn-text-desktop');
            const arrowEl = document.getElementById(uniqueId + '-arrow-desktop');

            if (textEl && btnEl) {
                if (textEl.classList.contains('line-clamp-2')) {
                    textEl.classList.remove('line-clamp-2');
                    btnTextEl.textContent = 'Show less';
                    arrowEl.style.transform = 'rotate(180deg)';
                } else {
                    textEl.classList.add('line-clamp-2');
                    btnTextEl.textContent = 'See details';
                    arrowEl.style.transform = 'rotate(0deg)';
                }
            }
        }

        // Check if description needs "See details" button
        document.querySelectorAll('[id$="-text"]').forEach(el => {
            if (el.scrollHeight > el.clientHeight) {
                const uniqueId = el.id.replace('-text', '');
                const btnEl = document.getElementById(uniqueId + '-btn');
                if (btnEl) btnEl.classList.remove('hidden');
            }
        });

        document.querySelectorAll('[id$="-text-desktop"]').forEach(el => {
            if (el.scrollHeight > el.clientHeight) {
                const uniqueId = el.id.replace('-text-desktop', '');
                const btnEl = document.getElementById(uniqueId + '-btn-desktop');
                if (btnEl) btnEl.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>