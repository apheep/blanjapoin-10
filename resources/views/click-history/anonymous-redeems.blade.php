<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anonymous Redeems | BlanjaPoin Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
    <style>
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body class="min-h-screen bg-white font-poppins">
    @include('partials.navbar-admin')

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin Panel</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Anonymous Redeems</h1>
                
            </div>
            <div class="flex gap-2">
                <a href="{{ route('click.history.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Click History
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('click.history.anonymous') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                               placeholder="MSISDN, Keyword ID, Description..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                    </div>

                    <!-- Keyword ID Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keyword ID</label>
                        <input type="text" name="keyword_id" value="{{ $filters['keyword_id'] ?? '' }}" 
                               placeholder="Masukkan Keyword ID..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('click.history.anonymous') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Anonymous</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $anonymousRedeems->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ route('click.history.anonymous', array_merge(request()->query(), ['sort' => 'coupon', 'dir' => (request('sort') == 'coupon' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                    Coupon / Keyword ID
                                    @if(request('sort') == 'coupon')
                                        @if(request('dir') == 'asc')
                                            <i class="fas fa-sort-up text-orange-500"></i>
                                        @else
                                            <i class="fas fa-sort-down text-orange-500"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keyword Desc</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ route('click.history.anonymous', array_merge(request()->query(), ['sort' => 'msisdn', 'dir' => (request('sort') == 'msisdn' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                    MSISDN
                                    @if(request('sort') == 'msisdn')
                                        @if(request('dir') == 'asc')
                                            <i class="fas fa-sort-up text-orange-500"></i>
                                        @else
                                            <i class="fas fa-sort-down text-orange-500"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ route('click.history.anonymous', array_merge(request()->query(), ['sort' => 'poin', 'dir' => (request('sort') == 'poin' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                    Poin Redeem
                                    @if(request('sort') == 'poin')
                                        @if(request('dir') == 'asc')
                                            <i class="fas fa-sort-up text-orange-500"></i>
                                        @else
                                            <i class="fas fa-sort-down text-orange-500"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ route('click.history.anonymous', array_merge(request()->query(), ['sort' => 'created_date', 'dir' => (request('sort') == 'created_date' && request('dir') == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-orange-500 transition-colors">
                                    Waktu Redeem
                                    @if(request('sort') == 'created_date')
                                        @if(request('dir') == 'asc')
                                            <i class="fas fa-sort-up text-orange-500"></i>
                                        @else
                                            <i class="fas fa-sort-down text-orange-500"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($anonymousRedeems as $redeem)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono text-gray-600">#{{ $redeem->id ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono text-gray-900">{{ $redeem->coupon ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $redeem->keyword_desc ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono text-gray-700">{{ $redeem->msisdn ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-orange-600">{{ number_format($redeem->poin_redeem ?? 0) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($redeem->created_date)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($redeem->created_date)->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-danger">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Anonymous
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada anonymous redeem</p>
                                        <p class="text-gray-400 text-sm mt-2">Semua redeem memiliki matching click history</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($anonymousRedeems->hasPages())
            <div class="mt-6 bg-white rounded-xl shadow-sm px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Info -->
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $anonymousRedeems->firstItem() ?? 0 }}</span> hingga <span class="font-semibold">{{ $anonymousRedeems->lastItem() ?? 0 }}</span> dari <span class="font-semibold">{{ $anonymousRedeems->total() }}</span> data
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($anonymousRedeems->onFirstPage())
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $anonymousRedeems->previousPageUrl() }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $current = $anonymousRedeems->currentPage();
                            $last = $anonymousRedeems->lastPage();
                            $range = 2;
                            $start = max(1, $current - $range);
                            $end = min($last, $current + $range);
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $anonymousRedeems->url(1) }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">1</a>
                            @if ($start > 2)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                        @endif

                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $current)
                                <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $anonymousRedeems->url($page) }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $anonymousRedeems->url($last) }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $last }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($anonymousRedeems->hasMorePages())
                            <a href="{{ $anonymousRedeems->nextPageUrl() }}" class="pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </main>

</body>
</html>

