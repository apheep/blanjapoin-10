<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History Redeem | BlanjaPoin Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
    <style>
        .search-input {
            width: 100%;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: 0.5rem 2.5rem;
            font-size: 0.875rem;
        }
        .search-input::placeholder {
            color: #9ca3af;
        }
        .search-input:focus {
            border-color: #fb923c;
            outline: none;
            box-shadow: 0 0 0 2px rgba(251, 146, 60, 0.1);
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
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
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">History Redeem</h1>
                    <p class="text-sm text-gray-600 mt-2">Track dan analisa klik sebelum redeem untuk mendeteksi potensi cheating</p>
                </div>
                <!-- <div class="flex gap-2">
                    <a href="{{ route('click.history.analytics') }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-chart-bar mr-2"></i>Analytics
                    </a>
                </div> -->
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('click.history.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                                   placeholder="IP, Device ID, Keyword ID..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>

                        <!-- Merchant Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Merchant</label>
                            <select name="merchant_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                                <option value="">Semua Merchant</option>
                                @foreach($merchants as $merchant)
                                    <option value="{{ $merchant->id }}" {{ ($filters['merchant_id'] ?? '') == $merchant->id ? 'selected' : '' }}>
                                        {{ $merchant->nama_merchant }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('click.history.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Klik</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $clickHistories->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mouse-pointer text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Matched</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">
                                {{ $clickHistories->filter(fn($item) => !is_null($item->matched_redeem))->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Unmatched</p>
                            <p class="text-2xl font-bold text-gray-600 mt-1">
                                {{ $clickHistories->filter(fn($item) => is_null($item->matched_redeem))->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-gray-600 text-xl"></i>
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
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Merchant</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keyword ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Device ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Klik</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail Match</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($clickHistories as $click)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $click->merchant->nama_merchant ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $click->merchant_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-mono text-gray-900">{{ $click->keyword_id ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-mono text-gray-700">{{ $click->ip_address ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-mono text-gray-600 block max-w-[150px] truncate" title="{{ $click->device_id }}">
                                            {{ $click->device_id ? \Illuminate\Support\Str::limit($click->device_id, 20) : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $click->clicked_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $click->clicked_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($click->matched_redeem)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i>Matched
                                            </span>
                                        @else
                                            <span class="badge badge-info">
                                                <i class="fas fa-clock mr-1"></i>No Redeem
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($click->matched_redeem)
                                            <div class="space-y-1">
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">MSISDN:</span> 
                                                    <span class="text-gray-600">{{ $click->matched_redeem->msisdn }}</span>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Time Diff:</span> 
                                                    <span class="text-gray-600">{{ $click->matched_redeem->time_diff_human ?? 'N/A' }}</span>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Confidence:</span> 
                                                    @if(($click->matched_redeem->confidence ?? 'low') === 'high')
                                                        <span class="text-green-600 font-semibold">● High</span> <span class="text-gray-500">(≤5 menit)</span>
                                                    @elseif(($click->matched_redeem->confidence ?? 'low') === 'medium')
                                                        <span class="text-yellow-600 font-semibold">● Medium</span> <span class="text-gray-500">(≤15 menit)</span>
                                                    @else
                                                        <span class="text-red-600 font-semibold">● Low</span> <span class="text-gray-500">(>15 menit)</span>
                                                    @endif
                                                </div>
                                                 <div class="text-xs">
                                                    <span class="font-semibold text-gray-700">Poin:</span> 
                                                    <span class="text-orange-600 font-semibold">{{ number_format($click->matched_redeem->poin_redeem ?? 0) }}</span>
                                                </div>
                                                <div class="text-xs mt-1 pt-1 border-t border-gray-200">
                                                    <span class="font-semibold text-green-700">✓ Merchant dari Click:</span> 
                                                    <span class="text-gray-900 font-medium">{{ $click->merchant->nama_merchant ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada redeem setelah klik ini</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-500 text-lg font-medium">Tidak ada data click history</p>
                                            <p class="text-gray-400 text-sm mt-2">Data akan muncul ketika ada user yang klik merchant/keyword</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($clickHistories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $clickHistories->links() }}
                    </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="mt-3"><strong>Confidence Level</strong> (dengan memperhitungkan processing time):</p>
                            <ul class="list-disc ml-5 space-y-1">
                                <li><span class="text-green-600 font-semibold">● High</span> - Time diff ≤5 menit (termasuk processing, sangat mungkin dari klik langsung)</li>
                                <li><span class="text-yellow-600 font-semibold">● Medium</span> - Time diff ≤15 menit (kemungkinan besar dari klik)</li>
                                <li><span class="text-red-600 font-semibold">● Low</span> - Time diff >15 menit (perlu dicek manual, kemungkinan sharelink)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>

</body>
</html>

