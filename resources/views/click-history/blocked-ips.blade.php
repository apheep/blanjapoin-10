<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blocked IPs | BlanjaPoin Admin</title>
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
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('click.history.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <span class="text-gray-300">|</span>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin Panel</p>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Blocked IPs</h1>
                    <p class="text-sm text-gray-600 mt-2">Daftar IP yang diblokir hari ini karena melebihi batas klik (>100 klik/hari)</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('click.history.blocked') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search IP / Device ID</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" name="search" value="{{ $search ?? '' }}" 
                                   placeholder="Cari IP Address atau Device ID..." 
                                   class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('click.history.blocked') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Latest Device ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Clicks (Today)</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($blockedIps as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-mono text-gray-900 font-medium">{{ $item->ip_address }}</span>
                                            <button onclick="copyToClipboard('{{ $item->ip_address }}')" class="text-gray-400 hover:text-blue-500 transition-colors" title="Copy IP">
                                                <i class="fas fa-copy text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->device_id)
                                            <div class="flex items-center gap-2 group">
                                                <span class="text-xs font-mono text-gray-600 truncate max-w-[200px]" title="{{ $item->device_id }}">
                                                    {{ $item->device_id }}
                                                </span>
                                                <button onclick="copyToClipboard('{{ $item->device_id }}')" class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-blue-500" title="Copy Device ID">
                                                    <i class="fas fa-copy text-xs"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ number_format($item->total_clicks) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('click.history.unlock') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin unlock IP {{ $item->ip_address }}? History klik hari ini akan dihapus.');" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="ip_address" value="{{ $item->ip_address }}">
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-300 transition-all duration-200 text-xs font-medium shadow-sm">
                                                <i class="fas fa-unlock"></i> Unlock
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-shield-alt text-green-500 text-3xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium">Tidak ada IP yang terblokir</p>
                                            <p class="text-gray-400 text-sm mt-2">Semua user masih dalam batas wajar (< 100 klik/hari)</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($blockedIps->hasPages())
                <div class="mt-6">
                    {{ $blockedIps->links() }}
                </div>
            @endif
    </main>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Optional: Show toast or feedback
                // For now just console log
                console.log('Copied: ' + text);
            });
        }
    </script>
</body>
</html>