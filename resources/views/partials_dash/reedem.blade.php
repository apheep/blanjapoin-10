<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reedem • {{ $merchant->nama_merchant }} | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.head')
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-orange-50 text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Navbar -->
    @php
        $code = request()->route('code');
        $decodedCode = $code ? urldecode($code) : '';
    @endphp
    <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg transition-shadow duration-300 w-full shadow-sm">
        <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}">
                    <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                </a>
                <a href="{{ route('link.dashboard', $decodedCode) }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="mx-auto max-w-[1120px]">
        <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
            @php
            // Pastikan $keywords adalah paginator
            if ($keywords instanceof \Illuminate\Support\Collection) {
                $perPage = 10;
                $currentPage = request()->integer('page', 1);
                $items = $keywords->forPage($currentPage, $perPage)->values();
                $keywords = new \Illuminate\Pagination\LengthAwarePaginator(
                    $items,
                    $keywords->count(),
                    $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
            @endphp

            <!-- Header Section -->
            <div class="mt-6 animate-fade-in-up">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-orange-600 flex items-center gap-2">
                            <i class="fas fa-gift"></i> Reedem Points
                        </p>
                        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent mt-1">
                            {{ $merchant->nama_merchant }}
                        </h1>
                        <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-100 rounded-full">
                                <i class="fas fa-map-marker-alt text-orange-600"></i>
                                <span class="font-medium">{{ $merchant->daerah ?? '-' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-100 rounded-full">
                                <i class="fas fa-tags text-orange-600"></i>
                                <span class="font-medium">{{ $merchant->kategori ?? '-' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full font-bold">
                                <i class="fas fa-gift"></i>
                                <span>{{ $keywords->total() }} Produk</span>
                            </span>
                        </div>
                    </div>
                    @if($merchant->logo_merchant)
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white shadow-lg border-2 border-orange-200 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" alt="Logo {{ $merchant->nama_merchant }}" class="w-full h-full object-contain p-2">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reedem Cards Grid -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($keywords as $keyword)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border-2 border-orange-100 hover:border-orange-300 group">
                        <!-- Image Section -->
                        @if($keyword->image)
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-orange-50 to-red-50">
                                <img src="{{ asset('storage/' . $keyword->image) }}" 
                                     alt="{{ $keyword->nama_produk }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute top-3 right-3">
                                    @if($keyword->stock > 0)
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-orange-300"></i>
                            </div>
                        @endif

                        <!-- Content Section -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $keyword->nama_produk }}</h3>
                            
                            @if($keyword->keyword_id)
                                <div class="mb-3">
                                    <span class="text-xs font-semibold text-gray-500 uppercase">Keyword ID</span>
                                    <p class="text-sm font-bold text-orange-600">{{ $keyword->keyword_id }}</p>
                                </div>
                            @endif

                            <!-- Reedem Points - Featured -->
                            <div class="mb-4 p-4 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border-2 border-orange-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-orange-400 to-red-500 text-white text-xs font-bold shadow-sm">
                                            P
                                        </span>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-600 uppercase">Reedem Points</p>
                                            <p class="text-2xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                                                {{ number_format($keyword->redeem ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                @if($keyword->diskon)
                                    <div class="bg-blue-50 rounded-lg p-2 border border-blue-100">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Diskon</p>
                                        <p class="text-sm font-bold text-blue-900">{{ $keyword->diskon }}</p>
                                    </div>
                                @endif
                                <div class="bg-purple-50 rounded-lg p-2 border border-purple-100">
                                    <p class="text-xs text-purple-600 font-medium mb-1">Stock</p>
                                    <p class="text-sm font-bold text-purple-900">{{ $keyword->stock ?? 0 }}</p>
                                </div>
                            </div>

                            @if($keyword->start_date || $keyword->end_date)
                                <div class="mb-4 p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium mb-1">Periode</p>
                                    <p class="text-xs font-semibold text-gray-700">
                                        {{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }} - 
                                        {{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}
                                    </p>
                                </div>
                            @endif

                            @if($keyword->cta_link)
                                <a href="{{ $keyword->cta_link }}" 
                                   target="_blank"
                                   class="block w-full mt-4 px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl text-center hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Reedem Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-orange-100 mb-4">
                                <i class="fas fa-gift text-4xl text-orange-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Produk Reedem</h3>
                            <p class="text-gray-600">Tidak ada produk yang tersedia untuk direedem saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($keywords->hasPages())
                <div class="mt-8 bg-white rounded-xl shadow-lg px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $keywords->firstItem() }}</span> hingga 
                        <span class="font-semibold">{{ $keywords->lastItem() }}</span> dari 
                        <span class="font-semibold">{{ $keywords->total() }}</span> produk
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if ($keywords->onFirstPage())
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $keywords->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach ($keywords->getUrlRange(1, $keywords->lastPage()) as $page => $url)
                            @if ($page == $keywords->currentPage())
                                <button disabled class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-600 to-red-600 rounded-lg">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($keywords->hasMorePages())
                            <a href="{{ $keywords->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Reedem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</body>
</html>

