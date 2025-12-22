
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Transaksi | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
    <style>
        .page-enter {
            opacity: 0;
            transform: translateY(8px);
        }
        .page-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .fade-in-up {
            opacity: 0;
            transform: translateY(10px);
        }
        .fade-in-up.show {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        /* Better table scroll on mobile */
        @media (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }
            .overflow-x-auto::-webkit-scrollbar {
                height: 6px;
            }
            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }
            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        }
    </style>
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    @include('partials.navbar-admin')

    
    <div class="mx-auto max-w-7xl">
        <main class="px-2 sm:px-4 md:px-6 lg:px-8 pb-12 md:pb-16 page-enter">
            <div class="flex flex-wrap items-center justify-between gap-4 mt-2 sm:mt-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">History</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Riwayat Transaksi</h1>
                </div>
                <div class="flex-shrink-0">
                    @php
                        $code = request()->route('code');
                        $decodedCode = $code ? urldecode($code) : '';
                    @endphp
                    <a href="{{ route('link.dashboard', $decodedCode) }}"
                       class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-700 transition-colors gap-1">
                        <i class="fas fa-arrow-left text-s"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ request()->url() }}" class="mt-6 flex flex-row items-center gap-2 sm:gap-3 justify-start mb-6">
                <div class="relative flex-1 max-w-[200px] sm:flex-initial sm:max-w-[240px]">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input name="q"
                           value="{{ request()->query('q', '') }}"
                           class="w-full rounded-full border border-gray-200 bg-white px-10 py-2 text-sm placeholder:text-gray-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none"
                           placeholder="Search keyword..." />
                </div>
                <div class="flex-shrink-0">
                    @include('partials.date-filter', ['filterId' => 'trxHistoryDateFilter'])
                </div>
            </form>
            @php
                $historySource = $histories ?? collect();

                if ($historySource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                    $historyPaginator = $historySource;
                } else {
                    $collection = $historySource instanceof \Illuminate\Support\Collection
                        ? $historySource
                        : collect($historySource);

                    $perPage = 12;
                    $currentPage = request()->integer('page', 1);
                    $items = $collection->forPage($currentPage, $perPage)->values();

                    $historyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                        $items,
                        $collection->count(),
                        $perPage,
                        $currentPage,
                        ['path' => request()->url(), 'query' => request()->query()]
                    );
                }
            @endphp

            <div class="bg-white rounded-xl shadow overflow-hidden -mx-2 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">No</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">MSISDN</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Merchant</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Product</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Keywords</th>
                                <th class="px-3 sm:px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Total Poin</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap hidden md:table-cell">Merchant City</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Status</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($historyPaginator as $index => $history)
                                @php
                                    $statusClass = match(strtolower($history->status ?? 'pending')) {
                                        'approved', 'completed' => 'bg-green-100 text-green-800',
                                        'pending', 'waiting' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $statusText = match(strtolower($history->status ?? 'pending')) {
                                        'approved' => 'Disetujui',
                                        'completed' => 'Selesai',
                                        'pending' => 'Pending',
                                        'waiting' => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                        default => ucfirst($history->status ?? 'Pending')
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-4 py-3 text-xs font-medium text-gray-900">
                                        {{ $historyPaginator->firstItem() + $index }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs text-gray-700">
                                        {{ $history->created_at ? $history->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs text-gray-700">
                                        {{ $history->msisdn ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs font-medium text-gray-900">
                                        {{ $history->merchant->nama_merchant ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs text-gray-700">
                                        {{ $history->nama_produk ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs text-gray-700">
                                        {{ $history->keyword_id ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs text-right font-semibold text-gray-900">
                                        {{ number_format($history->redeem ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 text-xs text-gray-700 hidden md:table-cell">
                                        {{ $history->merchant->merchant_city ?? '-' }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-sm font-medium text-gray-500">Belum ada riwayat transaksi</p>
                                            <p class="text-xs text-gray-400 mt-1">Riwayat transaksi akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                                        
                    </table>
                </div>

                @if($historyPaginator->hasPages())
                    <div class="bg-white px-3 sm:px-4 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-xs sm:text-sm text-gray-600 text-center sm:text-left">
                            Menampilkan <span class="font-semibold">{{ $historyPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $historyPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $historyPaginator->total() }}</span> data
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-2">
                            @if ($historyPaginator->onFirstPage())
                                <button disabled class="px-2 sm:px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            @else
                                <a href="{{ $historyPaginator->previousPageUrl() }}" class="px-2 sm:px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            @foreach ($historyPaginator->getUrlRange(1, $historyPaginator->lastPage()) as $page => $url)
                                @if ($page == $historyPaginator->currentPage())
                                    <button disabled class="px-2 sm:px-3 py-2 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $url }}" class="px-2 sm:px-3 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($historyPaginator->hasMorePages())
                                <a href="{{ $historyPaginator->nextPageUrl() }}" class="px-2 sm:px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <button disabled class="px-2 sm:px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <footer class="mt-16 pb-12 text-center">
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin.</div>
            </footer>
        </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Smooth page transition
        const mainEl = document.querySelector('main.page-enter');
        if (mainEl) {
            mainEl.classList.add('page-enter-active');
            setTimeout(function() {
                mainEl.classList.remove('page-enter');
            }, 300);
        }

        // Navbar animation
        const nav = document.getElementById('navbar');
        if (nav) {
            nav.classList.add('page-enter-active');
            setTimeout(function() {
                nav.classList.remove('page-enter');
            }, 300);
        }

        // Date filter toggle
        const dateToggle = document.querySelector("button[onclick*=\"toggleDateFilterCompact('trxHistoryDateFilter')\"]");
        if (!dateToggle) {
            return;
        }

        dateToggle.setAttribute('type', 'button');
        dateToggle.addEventListener('click', function (event) {
            event.preventDefault();
        });
    });
</script>
</body>
</html>
