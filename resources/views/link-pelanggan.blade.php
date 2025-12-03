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
                    <div class="grid grid-cols-2 gap-3 lg:grid-cols-1 lg:gap-5 items-stretch px-1">
                        @foreach($keywords as $keyword)
                            @php
                                $merchantName = optional($keyword->merchant)->nama_merchant ?? '';
                                $productName = $keyword->nama_produk ?? '';
                                $locationName = optional($keyword->merchant)->daerah ?? '';
                                $searchName = strtolower(trim($merchantName . ' ' . $productName));
                                $searchLocation = strtolower($locationName);
                            @endphp
                            <article data-voucher-card="true" data-point="{{ (int) $keyword->redeem }}" data-search-name="{{ $searchName }}" data-search-location="{{ $searchLocation }}" onclick="window.open('{{ $keyword->cta_link ?? '#' }}', '_blank')" class="group voucher-card overflow-hidden rounded-xl md:rounded-2xl border border-neutral-200 bg-white shadow-md transition-all hover:shadow-xl hover:scale-[1.01] hover:border-emerald-200 cursor-pointer h-full min-h-[280px]">
                                
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
                                            {{ $merchant->nama_merchant }}
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
                                            @if($merchant->logo_merchant)
                                                <div>
                                                    <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                                         alt="{{ $merchant->nama_merchant }}" 
                                                         class="w-[140px] h-[140px] object-contain rounded-full" 
                                                         loading="lazy">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4 flex flex-col justify-center">
                                            <h3 class="text-3xl md:text-4xl lg:text-5xl font-bold text-neutral-900 mb-3 leading-tight">
                                                {{ $merchant->nama_merchant }}
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
                                                <img src="{{ $keyword->image ? asset('storage/' . $keyword->image) : asset('storage/promo/promo-default.jpg') }}" 
                                                     alt="{{ $keyword->nama_produk }}" 
                                                     class="w-full h-full object-cover" 
                                                     loading="lazy">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden lg:flex flex-col md:flex-row items-start md:items-center justify-between px-3 md:px-4 py-2 bg-neutral-50 text-[10px] md:text-[11px] text-neutral-600 gap-1.5 md:gap-0">
                                        <span class="font-medium">Stock • {{ $keyword->stock }}</span>
                                        @if($keyword->end_date)
                                            <span class="font-medium">
                                                Valid until • {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
                                            </span>
                                        @endif
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
    </script>
</body>
</html>