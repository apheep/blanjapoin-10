<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Penarikan Saldo • {{ $merchant->nama_merchant }} | BlanjaPoin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @include('partials.head')
    <style>
        /* Font optimization for Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;    
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'kern' 1;
            letter-spacing: -0.01em;
            animation: fadeIn 0.3s ease-in-out;
        }
        /* Prevent horizontal scroll on mobile */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        * {
            box-sizing: border-box;
        }
        /* Animasi fade-in untuk halaman */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .dashboard-entrance {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 360ms ease-out, transform 360ms ease-out;
        }
        .dashboard-entrance.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="min-h-screen bg-white font-poppins">

    @php
        $code = request()->route('code');
    @endphp

    @include('partials.navbar-admin')
    
    <main class="dashboard-entrance max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Header with Back Button -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Riwayat Penarikan Saldo</h2>
                <p class="text-sm text-gray-600 mt-1">Daftar semua transaksi penarikan saldo</p>
            </div>
            <a href="{{ route('link.reedem', $code) }}" 
               class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left text-gray-600"></i>
                <span class="text-sm font-medium text-gray-700 hidden sm:inline">Kembali</span>
            </a>
        </div>

        <!-- Date Filter -->
        <form method="GET" action="{{ route('link.history-withdraw', $code) }}" id="historyDateFilterForm" class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 overflow-visible" style="position: relative; z-index: 40;">
            <div class="flex-shrink-0 overflow-visible" style="position: relative; z-index: 50;">
                @include('partials.date-withdraw', ['filterId' => 'historyWithdrawDateFilter'])
            </div>
        </form>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Metode Penarikan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Rek/E-Wallet</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="historyTableBody">
                        @forelse($withdrawHistory as $index => $withdraw)
                            @php
                                $isEWallet = in_array($withdraw->metode_penarikan, ['linkaja', 'dana']);
                                $displayAccount = $isEWallet ? '+62' . ($withdraw->no_ewallet ?? '') : ($withdraw->no_rekening ?? '');
                                $statusClass = match($withdraw->status) {
                                    'approved', 'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusText = match($withdraw->status) {
                                    'approved' => 'Disetujui',
                                    'completed' => 'Berhasil',
                                    'pending' => 'Pending',
                                    'rejected' => 'Ditolak',
                                    default => $withdraw->status
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $withdrawHistory->firstItem() + $index }}</td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $withdraw->nama }}</div>
                                    @if($withdraw->status === 'rejected' && $withdraw->dec_reject)
                                        <div class="mt-1 text-xs text-red-600 italic">
                                            <i class="fas fa-info-circle mr-1"></i>{{ $withdraw->dec_reject }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $withdraw->metode_penarikan_name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 font-mono">{{ $displayAccount }}</td>
                                <td class="px-4 py-4 text-xs text-gray-500">{{ $withdraw->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr id="historyEmptyState">
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-sm font-medium text-gray-500">Belum ada riwayat penarikan</p>
                                        <p class="text-xs text-gray-400 mt-1">Riwayat penarikan saldo akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            @if($withdrawHistory->hasPages())
                <div class="bg-white px-4 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $withdrawHistory->firstItem() }}</span> hingga <span class="font-semibold">{{ $withdrawHistory->lastItem() }}</span> dari <span class="font-semibold">{{ $withdrawHistory->total() }}</span> data
                        </div>
                        <div class="flex gap-2">
                            @if($withdrawHistory->onFirstPage())
                                <span class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed">Sebelumnya</span>
                            @else
                                <a href="{{ $withdrawHistory->previousPageUrl() }}" class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded transition-colors">Sebelumnya</a>
                            @endif
                            
                            @if($withdrawHistory->hasMorePages())
                                <a href="{{ $withdrawHistory->nextPageUrl() }}" class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded transition-colors">Selanjutnya</a>
                            @else
                                <span class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed">Selanjutnya</span>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($withdrawHistory->total() > 0)
                <div class="bg-white px-4 py-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $withdrawHistory->total() }}</span> data
                    </div>
                </div>
            @endif
        </div>

        <!-- Mobile Version -->
        <div class="md:hidden space-y-4 mt-6" id="historyCardsContainer">
            @forelse($withdrawHistory as $index => $withdraw)
                @php
                    $isEWallet = in_array($withdraw->metode_penarikan, ['linkaja', 'dana']);
                    $displayAccount = $isEWallet ? '+62' . ($withdraw->no_ewallet ?? '') : ($withdraw->no_rekening ?? '');
                    $statusClass = match($withdraw->status) {
                        'approved', 'completed' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    $statusText = match($withdraw->status) {
                        'approved' => 'Disetujui',
                        'completed' => 'Berhasil',
                        'pending' => 'Pending',
                        'rejected' => 'Ditolak',
                        default => $withdraw->status
                    };
                    $borderColor = match($withdraw->status) {
                        'approved', 'completed' => 'border-l-green-500',
                        'pending' => 'border-l-yellow-500',
                        'rejected' => 'border-l-red-500',
                        default => 'border-l-gray-500'
                    };
                @endphp
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border-l-4 {{ $borderColor }} p-4 pl-5">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <span class="text-sm font-bold text-gray-900">{{ $withdrawHistory->firstItem() + $index }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                        <p class="text-[11px] text-gray-500 font-medium mb-1 uppercase tracking-wide">Nama</p>
                        <p class="text-xs font-bold text-gray-900">{{ $withdraw->nama }}</p>
                        @if($withdraw->status === 'rejected' && $withdraw->dec_reject)
                            <p class="text-[10px] text-red-600 italic mt-1">
                                <i class="fas fa-info-circle mr-1"></i>{{ $withdraw->dec_reject }}
                            </p>
                        @endif
                    </div>
                    <div class="bg-blue-50 rounded-lg p-2.5 border border-blue-100">
                        <p class="text-[11px] text-blue-600 font-medium mb-1 uppercase tracking-wide">Status</p>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-[11px] text-gray-500 font-medium mb-1.5 uppercase tracking-wide">Metode Penarikan</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $withdraw->metode_penarikan_name }}</p>
                </div>
                <div class="mb-4 bg-orange-50 rounded-lg p-2.5 border border-orange-100">
                    <p class="text-[11px] text-orange-600 font-medium mb-1 uppercase tracking-wide">{{ $isEWallet ? 'No. E-Wallet' : 'No. Rekening' }}</p>
                    <p class="text-xs font-bold text-orange-900 font-mono">{{ $displayAccount }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-[11px] text-gray-500 font-medium mb-1.5 uppercase tracking-wide">Tanggal</p>
                    <p class="text-xs font-medium text-gray-700">{{ $withdraw->created_at->format('d M Y') }}</p>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div id="historyEmptyStateMobile" class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                <p class="text-sm font-medium text-gray-500">Belum ada riwayat penarikan</p>
                <p class="text-xs text-gray-400 mt-1">Riwayat penarikan saldo akan muncul di sini</p>
            </div>
            @endforelse
        </div>
    </main>

    <script>
        // Dashboard entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const main = document.querySelector('.dashboard-entrance');
            if (main) {
                setTimeout(() => {
                    main.classList.add('is-visible');
                }, 50);
            }
            
            // Initialize date filter from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const dateParam = urlParams.get('date');
            
            if (dateParam) {
                // Parse date from URL (format: YYYY-MM-DD)
                const dateObj = new Date(dateParam + 'T00:00:00');
                if (!isNaN(dateObj.getTime())) {
                    // Update input field
                    const dateInput = document.getElementById('dateInputhistoryWithdrawDateFilter');
                    if (dateInput) {
                        const day = String(dateObj.getDate()).padStart(2, '0');
                        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const year = dateObj.getFullYear();
                        dateInput.value = `${day}/${month}/${year}`;
                    }
                }
            }
        });
    </script>
</body>
</html>
