<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Withdraw Approval • BlanjaPoin</title>
    
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

    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if(session('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

    @include('partials.navbar-admin')
    
    <main class="dashboard-entrance max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">Withdraw Approval</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola pengajuan penarikan saldo dari merchant</p>
        </div>

        <!-- Search and Date Filter -->
        <form method="GET" action="{{ route('withdraw.approval') }}" id="withdrawSearchForm" class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 overflow-visible">
            <div class="relative flex-1 w-full sm:max-w-[400px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input name="q"
                       id="withdrawSearchInput"
                       value="{{ request()->query('q', '') }}"
                       class="w-full rounded-full border border-gray-200 bg-white px-10 py-2 text-sm placeholder:text-gray-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none"
                       placeholder="Search nama, merchant, metode..." />
                @if(request()->has('q'))
                    <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
            <div class="flex-shrink-0 overflow-visible" style="position: relative; z-index: 50;">
                @include('partials.date-withdraw', ['filterId' => 'withdrawApprovalDateFilter'])
            </div>
            @if(request()->has('q') || request()->has('date'))
                <a href="{{ route('withdraw.approval') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-1"></i>Clear
                </a>
            @endif
        </form>

        <!-- Withdraws Table -->
        @if($withdraws->count() > 0)
        <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Rek/E-Wallet</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="withdrawTableBody" class="bg-white divide-y divide-gray-200">
                            @foreach($withdraws as $withdraw)
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
                                <tr class="hover:bg-gray-50 transition-colors withdraw-row" data-date="{{ $withdraw->created_at->format('Y-m-d') }}">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $withdraws->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <div class="font-medium">{{ $withdraw->nama }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($withdraw->status === 'pending')
                                            @php
                                                $isEWallet = in_array($withdraw->metode_penarikan, ['linkaja', 'dana']);
                                                $displayAccount = $isEWallet ? '+62' . ($withdraw->no_ewallet ?? '') : ($withdraw->no_rekening ?? '');
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <button type="button" onclick="openApproveWithdrawModal({{ $withdraw->id }}, {
                                                    nama: '{{ addslashes($withdraw->nama) }}',
                                                    merchant: '{{ addslashes($withdraw->merchant->nama_merchant ?? '-') }}',
                                                    method: '{{ addslashes($withdraw->metode_penarikan_name) }}',
                                                    account: '{{ addslashes($displayAccount) }}',
                                                    amount: 'Rp {{ number_format($withdraw->jumlah, 0, ',', '.') }}',
                                                    date: '{{ $withdraw->created_at->format('d M Y') }}'
                                                })" 
                                                        class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-check mr-1"></i>Approve
                                                </button>
                                                <button type="button" onclick="openRejectWithdrawModal({{ $withdraw->id }}, {
                                                    nama: '{{ addslashes($withdraw->nama) }}',
                                                    merchant: '{{ addslashes($withdraw->merchant->nama_merchant ?? '-') }}',
                                                    method: '{{ addslashes($withdraw->metode_penarikan_name) }}',
                                                    account: '{{ addslashes($displayAccount) }}',
                                                    amount: 'Rp {{ number_format($withdraw->jumlah, 0, ',', '.') }}',
                                                    date: '{{ $withdraw->created_at->format('d M Y') }}'
                                                })" 
                                                        class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                                    <i class="fas fa-times mr-1"></i>Reject
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex flex-col gap-1.5">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }} inline-block w-fit">{{ $statusText }}</span>
                                                @if($withdraw->status === 'rejected' && $withdraw->dec_reject)
                                                    <div class="relative group">
                                                        <div class="flex items-start gap-1.5">
                                                            <i class="fas fa-info-circle text-red-500 text-xs mt-0.5 flex-shrink-0"></i>
                                                            <span class="text-xs text-red-600 italic max-w-xs line-clamp-2 break-words" title="{{ $withdraw->dec_reject }}">
                                                                {{ strlen($withdraw->dec_reject) > 60 ? substr($withdraw->dec_reject, 0, 60) . '...' : $withdraw->dec_reject }}
                                                            </span>
                                                        </div>
                                                        @if(strlen($withdraw->dec_reject) > 60)
                                                            <div class="absolute left-0 top-full mt-2 z-20 hidden group-hover:block bg-gray-900 text-white text-xs rounded-lg px-3 py-2 max-w-sm shadow-xl">
                                                                <div class="whitespace-normal break-words">{{ $withdraw->dec_reject }}</div>
                                                                <div class="absolute -top-1 left-4 w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700">{{ $withdraw->merchant->nama_merchant ?? '-' }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700">{{ $withdraw->metode_penarikan_name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700 font-mono">{{ $displayAccount }}</td>
                                    <td class="px-4 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($withdraw->jumlah, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-xs text-gray-500">{{ $withdraw->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    @if($withdraws->total() > 0)
                        Menampilkan <span class="font-semibold">{{ $withdraws->firstItem() }}</span> hingga <span class="font-semibold">{{ $withdraws->lastItem() }}</span> dari <span class="font-semibold">{{ $withdraws->total() }}</span> data
                    @else
                        Tidak ada data
                    @endif
                </div>
                
                @if($withdraws->hasPages())
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($withdraws->onFirstPage())
                        <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $withdraws->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $currentPage = $withdraws->currentPage();
                        $lastPage = $withdraws->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp
                    
                    @if($startPage > 1)
                        <a href="{{ $withdraws->url(1) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">1</a>
                        @if($startPage > 2)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endif
                    
                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $withdraws->currentPage())
                            <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $withdraws->url($page) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor
                    
                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                        <a href="{{ $withdraws->url($lastPage) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $lastPage }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($withdraws->hasMorePages())
                        <a href="{{ $withdraws->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
            <p class="text-sm font-medium text-gray-500">Tidak ada pengajuan withdraw</p>
        </div>
        @endif
    </main>

    @include('partials.validation-withdraw')

    <script>
        // Dashboard entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const main = document.querySelector('.dashboard-entrance');
            if (main) {
                setTimeout(() => {
                    main.classList.add('is-visible');
                }, 50);
            }

            // Flash message handling
            const successMessage = document.querySelector('[data-flash-message][data-flash-type="success"]');
            const errorMessage = document.querySelector('[data-flash-message][data-flash-type="error"]');
            
            if (successMessage) {
                const message = successMessage.getAttribute('data-flash-message');
                setTimeout(() => {
                    showWithdrawSuccessModal(message);
                }, 300);
            }
            
            if (errorMessage) {
                const message = errorMessage.getAttribute('data-flash-message');
                setTimeout(() => {
                    showWithdrawErrorModal(message);
                }, 300);
            }
            
            // Initialize date filter from URL params
            const urlParams = new URLSearchParams(window.location.search);
            const dateParam = urlParams.get('date');
            
            if (dateParam) {
                // Initialize calendar state if not exists
                if (!window.calendarState) {
                    window.calendarState = {};
                }
                
                // Convert date string to Date object
                const toDateObj = (dateStr) => {
                    if (!dateStr) return null;
                    // Handle YYYY-MM-DD format
                    const parts = dateStr.split('-');
                    if (parts.length === 3) {
                        const year = parseInt(parts[0]);
                        const month = parseInt(parts[1]) - 1; // Month is 0-indexed
                        const day = parseInt(parts[2]);
                        const dateObj = new Date(year, month, day);
                        dateObj.setHours(0, 0, 0, 0);
                        return dateObj;
                    }
                    return null;
                };
                
                const dateObj = toDateObj(dateParam);
                
                // Set calendar state
                if (dateObj) {
                    window.calendarState['withdrawApprovalDateFilter'] = {
                        currentMonth: dateObj.getMonth(),
                        currentYear: dateObj.getFullYear(),
                        selectedDate: dateObj,
                        activeType: 'date'
                    };
                }
                
                // Update input field
                const dateInput = document.getElementById('dateInputwithdrawApprovalDateFilter');
                if (dateInput && dateObj) {
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const year = dateObj.getFullYear();
                    dateInput.value = `${day}/${month}/${year}`;
                }
            }
            
            // Search form handling
            const withdrawSearchForm = document.getElementById('withdrawSearchForm');
            const withdrawSearchInput = document.getElementById('withdrawSearchInput');
            
            if (withdrawSearchForm && withdrawSearchInput) {
                // Prevent form submission on Enter if date filter is open
                withdrawSearchForm.addEventListener('submit', function(e) {
                    // Check if any date filter dropdown is open
                    const openDateFilters = document.querySelectorAll('[id^="withdrawDateFilter"]:not(.hidden), [id^="dateFilter"]:not(.hidden)');
                    if (openDateFilters.length > 0) {
                        e.preventDefault();
                        // Close all date filters
                        openDateFilters.forEach(filter => {
                            filter.classList.add('hidden');
                            filter.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                            filter.classList.add('opacity-0', 'scale-95', 'translate-y-2');
                        });
                        // Submit form after closing filters
                        setTimeout(() => {
                            withdrawSearchForm.submit();
                        }, 200);
                        return false;
                    }
                });
                
                // Handle Enter key in search input
                withdrawSearchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        // Close any open date filter dropdowns
                        const openDateFilters = document.querySelectorAll('[id^="withdrawDateFilter"]:not(.hidden), [id^="dateFilter"]:not(.hidden)');
                        openDateFilters.forEach(filter => {
                            filter.classList.add('hidden');
                            filter.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                            filter.classList.add('opacity-0', 'scale-95', 'translate-y-2');
                        });
                        // Submit form
                        setTimeout(() => {
                            withdrawSearchForm.submit();
                        }, 100);
                    }
                });
            }
        });
        
        // Clear search function
        function clearSearch() {
            window.location.href = '{{ route("withdraw.approval") }}';
        }

    </script>
</body>
</html>
