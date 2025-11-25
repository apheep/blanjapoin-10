<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History • {{ $merchant->nama_merchant }} | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.head')
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full shadow-sm">
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

            <!-- History Section -->
            <section class="mt-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl md:text-3xl font-black text-neutral-900">
                        History Keyword
                    </h2>
                    <span class="text-sm md:text-base text-neutral-600 font-semibold">
                        {{ $keywords->count() }} Items
                    </span>
                </div>

                @if($keywords->count() > 0)
                    <div class="space-y-4">
                        @foreach($keywords as $keyword)
                            <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-shadow p-6">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-neutral-900 mb-2">
                                            {{ $keyword->nama_produk ?: 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            {{ \Carbon\Carbon::parse($keyword->created_at)->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                        @if($keyword->status === 'approve') bg-green-100 text-green-800
                                        @elseif($keyword->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($keyword->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    @if($keyword->diskon)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1">Diskon</p>
                                            <p class="text-lg font-bold text-gray-900">{{ $keyword->diskon }}</p>
                                        </div>
                                    @endif
                                    @if($keyword->redeem)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1">Redeem</p>
                                            <p class="text-lg font-bold text-red-600">{{ $keyword->redeem }} pts</p>
                                        </div>
                                    @endif
                                    @if($keyword->stock !== null)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1">Stock</p>
                                            <p class="text-lg font-bold text-blue-600">{{ $keyword->stock }}</p>
                                        </div>
                                    @endif
                                    @if($keyword->end_date)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1">Valid Until</p>
                                            <p class="text-sm font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($keyword->end_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($keyword->skb)
                                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                        <p class="text-sm text-gray-700">{{ $keyword->skb }}</p>
                                    </div>
                                @endif
                                
                                @if($keyword->cta_link)
                                    <div class="pt-4 border-t border-gray-200">
                                        <a href="{{ $keyword->cta_link }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                                            <i class="fas fa-external-link-alt"></i>
                                            <span>Buka Link</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 md:py-16">
                        <div class="inline-block p-6 bg-gradient-to-br from-orange-50 to-rose-50 rounded-3xl mb-4">
                            <i class="fas fa-history text-6xl text-orange-400"></i>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-neutral-900 mb-2">
                            Belum Ada History
                        </h3>
                        <p class="text-sm md:text-base text-neutral-600">
                            Saat ini belum ada history keyword untuk merchant ini.
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
</body>
</html>

