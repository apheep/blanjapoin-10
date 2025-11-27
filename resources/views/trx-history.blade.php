
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
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full shadow-sm">
        <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6">
            <div class="flex items-center">
                <a href="{{ route('home') }}">
                    <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
                </a>
            </div>
        </div>
    </nav>

    <div class="mx-auto max-w-[1120px]">
        <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mt-4">History</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Riwayat Transaksi</h1>
                    <p class="text-sm text-gray-600 max-w-2xl mt-2">
                        Koleksi semua transaksi pengguna dengan detail MSISDN, merchant, produk, poin, dan status terbaru.
                    </p>
                </div>
            </div>

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

            <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">MSISDN</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keywords</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Poin</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant City</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($historyPaginator as $history)
                                @php
                                    $displayDate = $history->tanggal ?? $history->transaction_date ?? $history->created_at ?? null;
                                    $formattedDate = $displayDate ? \Carbon\Carbon::parse($displayDate)->format('d/m/Y H:i') : '-';
                                    $msisdn = $history->msisdn ?? $history->phone ?? $history->client_msisdn ?? '-';
                                    $merchantName = $history->merchant->nama_merchant ?? $history->merchant_name ?? '-';
                                    $productName = $history->product_name ?? $history->product ?? $history->nama_produk ?? '-';
                                    $keywordsDisplay = $history->keywords ?? $history->keyword ?? '-';
                                    $pointsValue = $history->total_poin ?? $history->total_points ?? $history->points ?? $history->poin ?? '-';
                                    $merchantCity = $history->merchant->daerah ?? $history->merchant_city ?? $history->city ?? '-';
                                    $statusRaw = $history->status ?? $history->transaction_status ?? null;
                                    $statusLabel = $statusRaw ? ucfirst($statusRaw) : '-';
                                    $statusNormalized = $statusRaw ? strtolower(trim((string) $statusRaw)) : '';
                                    $statusClasses = 'bg-gray-100 text-gray-700';

                                    if ($statusNormalized !== '') {
                                        if (str_contains($statusNormalized, 'success') || str_contains($statusNormalized, 'selesai') || str_contains($statusNormalized, 'done')) {
                                            $statusClasses = 'bg-green-100 text-green-800';
                                        } elseif (str_contains($statusNormalized, 'pending') || str_contains($statusNormalized, 'proses') || str_contains($statusNormalized, 'menunggu')) {
                                            $statusClasses = 'bg-yellow-100 text-yellow-800';
                                        } elseif (str_contains($statusNormalized, 'fail') || str_contains($statusNormalized, 'reject') || str_contains($statusNormalized, 'gagal')) {
                                            $statusClasses = 'bg-red-100 text-red-800';
                                        } else {
                                            $statusClasses = 'bg-blue-100 text-blue-800';
                                        }
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                        {{ ($historyPaginator->currentPage() - 1) * $historyPaginator->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $formattedDate }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $msisdn }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $merchantName }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $productName }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $keywordsDisplay }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-right text-gray-900">
                                        {{ $pointsValue }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $merchantCity }}
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Belum ada data transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($historyPaginator->hasPages())
                    <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $historyPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $historyPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $historyPaginator->total() }}</span> data
                        </div>
                        <div class="flex items-center space-x-2">
                            @if ($historyPaginator->onFirstPage())
                                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            @else
                                <a href="{{ $historyPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            @foreach ($historyPaginator->getUrlRange(1, $historyPaginator->lastPage()) as $page => $url)
                                @if ($page == $historyPaginator->currentPage())
                                    <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($historyPaginator->hasMorePages())
                                <a href="{{ $historyPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
            </div>

            <div class="md:hidden space-y-4 mt-6">
                @forelse($historyPaginator as $history)
                    @php
                        $displayDate = $history->tanggal ?? $history->transaction_date ?? $history->created_at ?? null;
                        $formattedDate = $displayDate ? \Carbon\Carbon::parse($displayDate)->format('d/m/Y H:i') : '-';
                        $msisdn = $history->msisdn ?? $history->phone ?? $history->client_msisdn ?? '-';
                        $merchantName = $history->merchant->nama_merchant ?? $history->merchant_name ?? '-';
                        $pointsValue = $history->total_poin ?? $history->total_points ?? $history->points ?? $history->poin ?? '-';
                        $statusLabel = $history->status ?? $history->transaction_status ?? '-';
                    @endphp
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ ($historyPaginator->currentPage() - 1) * $historyPaginator->perPage() + $loop->iteration }}.
                            </span>
                            <span class="text-xs font-semibold text-gray-500">
                                {{ $formattedDate }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">MSISDN</p>
                                <p class="font-semibold text-gray-900">{{ $msisdn }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Merchant</p>
                                <p class="font-semibold text-gray-900">{{ $merchantName }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Points</p>
                                <p class="font-semibold text-gray-900">{{ $pointsValue }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Status</p>
                                <p class="font-semibold text-gray-900">{{ ucfirst($statusLabel) }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Product</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $history->product_name ?? $history->product ?? $history->nama_produk ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Keywords</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $history->keywords ?? $history->keyword ?? '-' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-center text-gray-500">Belum ada data transaksi.</p>
                @endforelse

                @if($historyPaginator->hasPages())
                    <div class="bg-white px-4 py-4 border border-gray-200 rounded-2xl text-center space-y-3">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $historyPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $historyPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $historyPaginator->total() }}</span> data
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            @if ($historyPaginator->onFirstPage())
                                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            @else
                                <a href="{{ $historyPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif
                            @foreach ($historyPaginator->getUrlRange(1, $historyPaginator->lastPage()) as $page => $url)
                                @if ($page == $historyPaginator->currentPage())
                                    <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                            @if ($historyPaginator->hasMorePages())
                                <a href="{{ $historyPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
            </div>

            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Riwayat Transaksi BlanjaPoin</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>
</body>
</html>
