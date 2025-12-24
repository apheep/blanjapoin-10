<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics Click Redeem | BlanjaPoin Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white font-poppins">
    @include('partials.navbar-admin')

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
            <!-- Header -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Analytics</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Click Redeem Analytics</h1>
                    <p class="text-sm text-gray-600 mt-2">Statistik dan analytics untuk click history</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('click.history.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Back to History
                    </a>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('click.history.analytics') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-filter mr-2"></i>Apply Filter
                    </button>
                </form>
            </div>

            <!-- Overview Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Overview</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mouse-pointer text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Clicks</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalClicks) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-gift text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Redeems</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRedeems) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-percentage text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Conversion Rate</p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        @if($totalClicks > 0)
                                            {{ number_format(($totalRedeems / $totalClicks) * 100, 2) }}%
                                        @else
                                            0%
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Merchants by Clicks -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Merchants by Clicks</h3>
                    <div class="space-y-3">
                        @forelse($clicksByMerchant as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $item->merchant->nama_merchant ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-500">ID: {{ $item->merchant_id }}</p>
                                </div>
                                <div class="ml-4 flex items-center gap-2">
                                    <span class="text-lg font-bold text-orange-600">{{ number_format($item->total_clicks) }}</span>
                                    <i class="fas fa-mouse-pointer text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p class="text-sm">No data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Keywords by Clicks -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Keywords by Clicks</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($clicksByKeyword as $item)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-lg border border-orange-100">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-mono font-semibold text-gray-900">
                                    {{ $item->keyword_id ?? 'Unknown' }}
                                </p>
                                @if($item->keyword)
                                    <p class="text-xs text-gray-600 truncate mt-1">
                                        {{ $item->keyword->nama_produk ?? '' }}
                                    </p>
                                @endif
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-orange-600 text-white">
                                    {{ number_format($item->total_clicks) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm">No data available</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-blue-900">Analytics Insights</h3>
                        <div class="mt-2 text-sm text-blue-800 space-y-1">
                            <p>📊 Conversion Rate menunjukkan persentase klik yang berhasil menjadi redeem</p>
                            <p>🎯 Merchants dengan klik tinggi = lokasi strategis atau promo menarik</p>
                            <p>🔑 Keywords populer dapat dijadikan benchmark untuk campaign baru</p>
                            <p>⚠️ Rate conversion rendah mungkin indikasi masalah UI/UX atau fraud</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>


</body>
</html>

