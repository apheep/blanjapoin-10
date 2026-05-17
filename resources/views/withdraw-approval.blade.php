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
        /* Prevent layout shift during font loading */
        @font-face {
            font-family: 'Poppins';
            font-display: swap;
        }
        
        /* Reserve space for content to prevent layout shift */
        .dashboard-entrance::before {
            content: '';
            display: block;
            height: 0;
            visibility: hidden;
        }
        
        /* Font optimization for Poppins */
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;    
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'kern' 1;
            letter-spacing: -0.01em;
            animation: fadeIn 0.3s ease-in-out;
            /* Prevent layout shift */
            min-height: 100vh;
        }
        /* Prevent horizontal scroll on mobile */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        * {
            box-sizing: border-box;
        }
        
        /* Prevent layout shift for main content */
        main {
            min-height: calc(100vh - 200px);
            contain: layout style;
        }
        
        /* Prevent layout shift for table */
        table {
            table-layout: auto;
            width: 100%;
        }
        
        thead {
            position: relative;
        }
        
        /* Prevent layout shift during loading */
        .dashboard-entrance {
            will-change: opacity, transform;
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
            /* Prevent layout shift */
            min-height: 400px;
            /* Use will-change for better performance */
            will-change: opacity, transform;
        }
        .dashboard-entrance.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Prevent FOUC (Flash of Unstyled Content) */
        html {
            visibility: visible;
        }
        
        /* Ensure stable rendering */
        body {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        
        /* Ensure table container has stable height (only during sorting) */
        #tableContainer {
            contain: layout;
        }
        
        /* Prevent layout shift for table rows */
        tbody tr {
            contain: layout;
        }
        
        /* Dropdown animations */
        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-menu {
            animation: dropdownFadeIn 0.15s ease-out;
        }
        
        .chevron-rotate {
            transform: rotate(180deg);
        }
        
        /* Dropdown positioning for bottom rows */
        .dropdown-up {
            transform-origin: bottom center;
        }
        
        .dropdown-menu {
            transform-origin: top center;
        }
        
        /* Ensure dropdown is above table and doesn't get clipped */
        [id^="statusDropdown"] {
            position: relative;
            z-index: 1;
        }
        
        [id^="dropdownMenu"] {
            position: absolute !important;
            z-index: 10000 !important;
            margin-top: 0.5rem !important;
        }
        
        /* Ensure table containers don't clip dropdown */
        table, tbody, tr {
            overflow: visible !important;
        }
        
        /* Table cells should allow overflow but maintain structure */
        td {
            overflow: visible !important;
            position: relative;
        }
        
        /* Fix for table overflow */
        .overflow-x-auto {
            overflow-y: visible !important;
        }
        
        /* Ensure table container allows dropdowns to show */
        #tableContainer {
            overflow: visible !important;
        }
        
        /* Prevent dropdown overlap - ensure proper stacking */
        [id^="statusDropdown"]:has([id^="dropdownMenu"]:not(.hidden)) {
            z-index: 10001 !important;
        }
        
        /* Modal animations */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-backdrop {
            animation: modalFadeIn 0.2s ease-out;
        }
        
        .modal-content {
            animation: modalSlideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        /* Button hover animations */
        .btn-detail {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-detail:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.15);
        }
        
        .btn-detail:active {
            transform: translateY(0);
        }
        
        /* Custom Input Search Class */
        .search-input {
            width: 100%;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: 0.5rem 2rem 0.5rem 2.25rem;
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
        
        /* Custom Pagination Button */
        .pagination-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .pagination-btn:hover {
            background-color: #f9fafb;
        }
        
        .pagination-btn-active {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(to right, #F81611, #F0B100);
            border-radius: 0.5rem;
        }
        
        .pagination-btn-disabled {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #9ca3af;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            cursor: not-allowed;
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
            <div class="relative w-full max-w-[280px] sm:max-w-[180px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input name="q"
                       id="withdrawSearchInput"
                       value="{{ request()->query('q', '') }}"
                       class="search-input"
                       placeholder="Search" />
                @if(request()->has('q'))
                    <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
            <div class="flex-shrink-0 overflow-visible" style="position: relative; z-index: 40;">
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
        <div class="bg-white rounded-xl shadow" style="overflow: visible; position: relative; isolation: isolate;" id="tableContainer">
            <div id="sortLoadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-75 z-30 flex items-center justify-center rounded-xl">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-6 h-6 border-2 border-gray-300 border-t-orange-500 rounded-full animate-spin"></div>
                    <span class="text-xs text-gray-600">Mengurutkan...</span>
                </div>
            </div>
                <div class="overflow-x-auto" style="overflow-y: visible; position: relative;">
                    <table class="min-w-full divide-y divide-gray-200" style="position: relative;">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('no')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>No</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconNo"></i>
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('status')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>Status</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconStatus"></i>
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('nama')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>Nama</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconNama"></i>
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('merchant')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>Merchant</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconMerchant"></i>
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('metode')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>Metode</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconMetode"></i>
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Rek/E-Wallet</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('jumlah')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>Jumlah</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconJumlah"></i>
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button onclick="handleSort('tanggal')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span>Tanggal</span>
                                        <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconTanggal"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="withdrawTableBody" class="bg-white divide-y divide-gray-200" style="position: relative; overflow: visible;">
                            @foreach($withdraws as $withdraw)
                                @php
                                    $isEWallet = in_array($withdraw->metode_penarikan, ['linkaja', 'dana']);
                                    // no_ewallet is already stored with +62 prefix in database, so use as-is
                                    $displayAccount = $isEWallet ? ($withdraw->no_ewallet ?? '') : ($withdraw->no_rekening ?? '');
                                    $statusClass = match($withdraw->status) {
                                        'approved', 'completed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $statusText = match($withdraw->status) {
                                        'approved' => 'Approve',
                                        'completed' => 'Berhasil',
                                        'pending' => 'Pending',
                                        'rejected' => 'Reject',
                                        default => $withdraw->status
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors withdraw-row" data-date="{{ $withdraw->created_at->format('Y-m-d') }}">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                        @if(request()->get('sort_by') === 'no' && request()->get('sort_order') === 'desc')
                                            {{ $withdraws->total() - ($withdraws->firstItem() + $loop->index) + 1 }}
                                        @else
                                            {{ $withdraws->firstItem() + $loop->index }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-4" style="position: relative;">
                                        @if($withdraw->status === 'pending')
                                            @php
                                                $isEWallet = in_array($withdraw->metode_penarikan, ['linkaja', 'dana']);
                                                // no_ewallet is already stored with +62 prefix in database, so use as-is
                                    $displayAccount = $isEWallet ? ($withdraw->no_ewallet ?? '') : ($withdraw->no_rekening ?? '');
                                            @endphp
                                            <div class="relative" id="statusDropdown{{ $withdraw->id }}" style="position: relative; z-index: 1; isolation: isolate;">
                                                <button type="button" 
                                                        onclick="toggleStatusDropdown({{ $withdraw->id }})" 
                                                        class="px-3 py-1.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full hover:bg-yellow-200 transition-all duration-200 hover:scale-105 flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-yellow-300 relative z-10">
                                                    <span>Waiting</span>
                                                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" id="chevronIcon{{ $withdraw->id }}"></i>
                                                </button>
                                                <div id="dropdownMenu{{ $withdraw->id }}"
                                                     class="hidden absolute left-0 top-full bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden w-[120px] dropdown-menu" 
                                                     style="position: absolute !important; z-index: 10000 !important; margin-top: 0.5rem !important; transition: all 0.2s ease-out;">
                                                    <button type="button" 
                                                            onclick="openApproveWithdrawModal({{ $withdraw->id }}, {
                                                                nama: '{{ addslashes($withdraw->nama) }}',
                                                                merchant: '{{ addslashes($withdraw->merchant->nama_merchant ?? '-') }}',
                                                                method: '{{ addslashes($withdraw->metode_penarikan_name) }}',
                                                                account: '{{ addslashes($displayAccount) }}',
                                                                amount: 'Rp {{ number_format($withdraw->jumlah, 0, ',', '.') }}',
                                                                date: '{{ $withdraw->created_at->format('d M Y') }}'
                                                            }); closeStatusDropdown({{ $withdraw->id }});" 
                                                            class="w-full px-3 py-2 text-left text-xs text-gray-700 hover:bg-gray-50 transition-colors duration-100 flex items-center gap-2 focus:outline-none">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                        <span>Approve</span>
                                                    </button>
                                                    <button type="button" 
                                                            onclick="openRejectWithdrawModal({{ $withdraw->id }}, {
                                                                nama: '{{ addslashes($withdraw->nama) }}',
                                                                merchant: '{{ addslashes($withdraw->merchant->nama_merchant ?? '-') }}',
                                                                method: '{{ addslashes($withdraw->metode_penarikan_name) }}',
                                                                account: '{{ addslashes($displayAccount) }}',
                                                                amount: 'Rp {{ number_format($withdraw->jumlah, 0, ',', '.') }}',
                                                                date: '{{ $withdraw->created_at->format('d M Y') }}'
                                                            }); closeStatusDropdown({{ $withdraw->id }});" 
                                                            class="w-full px-3 py-2 text-left text-xs text-gray-700 hover:bg-gray-50 transition-colors duration-100 flex items-center gap-2 focus:outline-none border-t border-gray-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                        <span>Reject</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }} inline-block w-fit transition-all duration-200 hover:scale-105">{{ $statusText }}</span>
                                                @if($withdraw->status === 'rejected' && $withdraw->dec_reject)
                                                    <button onclick="showRejectReasonModal('{{ addslashes($withdraw->dec_reject) }}', '{{ addslashes($withdraw->merchant->nama_pic ?? $withdraw->nama) }}')" 
                                                            class="btn-detail px-2 py-1 text-xs font-normal text-red-500 hover:text-red-600 transition-colors duration-150 flex items-center gap-1">
                                                        <i class="fas fa-info-circle text-[10px]"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <div class="font-medium">{{ $withdraw->merchant->nama_pic ?? $withdraw->nama }}</div>
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
                
                @if($withdraws->total() > 0)
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($withdraws->onFirstPage())
                        <button disabled class="pagination-btn-disabled">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $withdraws->previousPageUrl() }}" class="pagination-btn">
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
                    
                    @if($lastPage > 1)
                        @if($startPage > 1)
                            <a href="{{ $withdraws->url(1) }}" class="pagination-btn">1</a>
                            @if($startPage > 2)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                        @endif
                        
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $withdraws->currentPage())
                                <button disabled class="pagination-btn-active">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $withdraws->url($page) }}" class="pagination-btn">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor
                        
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $withdraws->url($lastPage) }}" class="pagination-btn">{{ $lastPage }}</a>
                        @endif
                    @else
                        {{-- Show current page even if only 1 page --}}
                        <button disabled class="pagination-btn-active">
                            1
                        </button>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($withdraws->hasMorePages())
                        <a href="{{ $withdraws->nextPageUrl() }}" class="pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button disabled class="pagination-btn-disabled">
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

    <!-- Modal for Reject Reason -->
    <div id="rejectReasonModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 modal-backdrop">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto modal-content">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center transition-transform duration-200 hover:scale-110">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Alasan Penolakan</h3>
                        <p class="text-xs text-gray-500" id="rejectReasonModalName"></p>
                    </div>
                </div>
                <button onclick="closeRejectReasonModal()" class="text-gray-400 hover:text-gray-600 transition-all duration-200 hover:rotate-90 hover:scale-110">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="px-6 py-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 transition-all duration-300 hover:bg-red-100 hover:shadow-sm">
                    <p class="text-sm text-gray-700 leading-relaxed" id="rejectReasonModalText"></p>
                </div>
            </div>
            <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex justify-end">
                <button onclick="closeRejectReasonModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium hover:scale-105 active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @include('partials.validation-withdraw')

    <script>
        // Dashboard entrance animation - prevent layout shift
        document.addEventListener('DOMContentLoaded', function() {
            const main = document.querySelector('.dashboard-entrance');
            if (main) {
                // Ensure layout is stable before animation
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        main.classList.add('is-visible');
                    }, 50);
                });
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
        
        // Status dropdown functions
        function toggleStatusDropdown(withdrawId) {
            const dropdown = document.getElementById('dropdownMenu' + withdrawId);
            const dropdownContainer = document.getElementById('statusDropdown' + withdrawId);
            const chevron = document.getElementById('chevronIcon' + withdrawId);
            
            // Close all other dropdowns FIRST to prevent overlap
            document.querySelectorAll('[id^="dropdownMenu"]').forEach(menu => {
                if (menu.id !== 'dropdownMenu' + withdrawId) {
                    menu.classList.add('hidden');
                    menu.classList.remove('dropdown-menu');
                    menu.style.zIndex = '';
                }
            });
            
            // Reset all chevrons
            document.querySelectorAll('[id^="chevronIcon"]').forEach(icon => {
                if (icon.id !== 'chevronIcon' + withdrawId) {
                    icon.classList.remove('chevron-rotate');
                }
            });
            
            // Reset z-index of all containers
            document.querySelectorAll('[id^="statusDropdown"]').forEach(container => {
                if (container.id !== 'statusDropdown' + withdrawId) {
                    container.style.zIndex = '';
                }
            });
            
            // Toggle current dropdown
            if (dropdown && dropdownContainer) {
                const isHidden = dropdown.classList.contains('hidden');
                
                if (isHidden) {
                    // Set high z-index for active container
                    dropdownContainer.style.zIndex = '10001';
                    dropdown.style.zIndex = '10002';
                    
                    // Reset all positioning first
                    dropdown.style.top = '';
                    dropdown.style.bottom = '';
                    dropdown.style.left = '';
                    dropdown.style.right = '';
                    dropdown.style.marginTop = '';
                    dropdown.style.marginBottom = '';
                    
                    // Calculate positions
                    const rect = dropdownContainer.getBoundingClientRect();
                    const viewportWidth = window.innerWidth;
                    const viewportHeight = window.innerHeight;
                    
                    // All dropdowns appear below button
                    dropdown.style.top = '100%';
                    dropdown.style.bottom = 'auto';
                    dropdown.style.marginTop = '0.5rem';
                    dropdown.style.marginBottom = '';
                    dropdown.classList.remove('dropdown-up');
                    
                    // Ensure dropdown doesn't go beyond right edge
                    if (rect.left + 120 > viewportWidth - 20) {
                        dropdown.style.left = 'auto';
                        dropdown.style.right = '0';
                    } else {
                        dropdown.style.left = '0';
                        dropdown.style.right = 'auto';
                    }
                    
                    // Check if dropdown would go below viewport
                    const dropdownHeight = dropdown.offsetHeight || 80; // Approximate height
                    if (rect.bottom + dropdownHeight + 8 > viewportHeight) {
                        // Show above if there's not enough space below
                        dropdown.style.top = 'auto';
                        dropdown.style.bottom = '100%';
                        dropdown.style.marginTop = '';
                        dropdown.style.marginBottom = '0.5rem';
                        dropdown.classList.add('dropdown-up');
                    }
                    
                    // Now show dropdown
                    dropdown.classList.remove('hidden');
                    dropdown.classList.add('dropdown-menu');
                } else {
                    // Hide dropdown
                    dropdown.classList.add('hidden');
                    dropdown.classList.remove('dropdown-menu');
                    dropdownContainer.style.zIndex = '';
                    dropdown.style.zIndex = '';
                }
            }
            
            // Rotate chevron
            if (chevron) {
                chevron.classList.toggle('chevron-rotate');
            }
        }
        
        function closeStatusDropdown(withdrawId) {
            const dropdown = document.getElementById('dropdownMenu' + withdrawId);
            const dropdownContainer = document.getElementById('statusDropdown' + withdrawId);
            const chevron = document.getElementById('chevronIcon' + withdrawId);
            
            if (dropdown) {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('dropdown-menu');
                dropdown.style.zIndex = '';
            }
            
            if (dropdownContainer) {
                dropdownContainer.style.zIndex = '';
            }
            
            if (chevron) {
                chevron.classList.remove('chevron-rotate');
            }
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const clickedElement = event.target;
            const isInsideDropdown = clickedElement.closest('[id^="statusDropdown"]') || 
                                     clickedElement.closest('[id^="dropdownMenu"]');
            
            if (!isInsideDropdown) {
                // Close all dropdowns
                document.querySelectorAll('[id^="dropdownMenu"]').forEach(menu => {
                    menu.classList.add('hidden');
                    menu.classList.remove('dropdown-menu');
                    menu.style.zIndex = '';
                });
                // Reset all containers z-index
                document.querySelectorAll('[id^="statusDropdown"]').forEach(container => {
                    container.style.zIndex = '';
                });
                // Reset all chevrons
                document.querySelectorAll('[id^="chevronIcon"]').forEach(icon => {
                    icon.classList.remove('chevron-rotate');
                });
            }
        });
        
        // Sorting function with AJAX
        function handleSort(column) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort_by');
            const currentOrder = urlParams.get('sort_order');
            
            let newSort = column;
            let newOrder = 'asc';
            
            if (currentSort === column) {
                if (column === 'status') {
                    // Status: 3 states (asc -> desc -> reject -> reset)
                    if (currentOrder === 'asc') {
                        // Klik 2: desc (waiting first)
                        newOrder = 'desc';
                    } else if (currentOrder === 'desc') {
                        // Klik 3: reject (reject first)
                        newOrder = 'reject';
                    } else {
                        // Klik 4: reset
                        newSort = null;
                        newOrder = null;
                    }
                } else {
                    // Other columns: 2 states (asc -> desc -> asc)
                    if (currentOrder === 'asc') {
                        newOrder = 'desc';
                    } else {
                        newOrder = 'asc';
                    }
                }
            }
            
            // Update URL params
            if (newSort) {
                urlParams.set('sort_by', newSort);
                urlParams.set('sort_order', newOrder);
            } else {
                urlParams.delete('sort_by');
                urlParams.delete('sort_order');
            }
            
            // Update icons immediately
            updateSortIcons(column, newOrder);
            
            // Update URL without reload
            window.history.pushState({}, '', '?' + urlParams.toString());
            
            // Show loading overlay
            const loadingOverlay = document.getElementById('sortLoadingOverlay');
            const tableBody = document.getElementById('withdrawTableBody');
            const tableContainer = document.getElementById('tableContainer');
            
            if (loadingOverlay && tableBody && tableContainer) {
                // Store current height to prevent layout shift
                const currentHeight = tableContainer.offsetHeight;
                tableContainer.style.minHeight = currentHeight + 'px';
                
                loadingOverlay.classList.remove('hidden');
                
                // Make AJAX request
                fetch('{{ route("withdraw.approval") }}?' + urlParams.toString(), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse HTML response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.getElementById('withdrawTableBody');
                    const newPaginationContainer = doc.querySelector('.bg-white.px-4.py-4.border-t.flex');
                    
                    if (newTableBody) {
                        // Smooth transition
                        tableBody.style.opacity = '0';
                        tableBody.style.transition = 'opacity 0.2s';
                        
                        setTimeout(() => {
                            // Replace table body content
                            tableBody.innerHTML = newTableBody.innerHTML;
                            
                            // Replace pagination container if exists
                            const currentPaginationContainer = document.querySelector('.bg-white.px-4.py-4.border-t.flex');
                            if (currentPaginationContainer && newPaginationContainer) {
                                currentPaginationContainer.outerHTML = newPaginationContainer.outerHTML;
                            }
                            
                            // Re-initialize any event listeners if needed
                            // (dropdowns will work because they use onclick attributes)
                            
                            // Restore opacity
                            tableBody.style.opacity = '1';
                            
                            // Remove min-height after transition
                            setTimeout(() => {
                                tableContainer.style.minHeight = '';
                            }, 300);
                            
                            // Hide loading
                            loadingOverlay.classList.add('hidden');
                        }, 200);
                    } else {
                        // Fallback: reload if parsing fails
                        loadingOverlay.classList.add('hidden');
                        tableContainer.style.minHeight = '';
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingOverlay.classList.add('hidden');
                    tableContainer.style.minHeight = '';
                    // Fallback: reload on error
                    window.location.reload();
                });
            }
        }
        
        // Update sort icons based on current state
        function updateSortIcons(activeColumn, order) {
            const columns = ['no', 'status', 'nama', 'merchant', 'metode', 'jumlah', 'tanggal'];
            columns.forEach(col => {
                const icon = document.getElementById('sortIcon' + col.charAt(0).toUpperCase() + col.slice(1));
                if (icon) {
                    if (col === activeColumn && order) {
                        if (order === 'asc') {
                            icon.className = 'fas fa-sort-up text-[10px] text-gray-700';
                        } else if (order === 'desc') {
                            icon.className = 'fas fa-sort-down text-[10px] text-gray-700';
                        } else if (order === 'reject') {
                            // Special icon for reject state (status only)
                            icon.className = 'fas fa-sort-down text-[10px] text-red-600';
                        } else {
                            icon.className = 'fas fa-sort text-[10px] text-gray-400';
                        }
                    } else {
                        // Reset to default
                        icon.className = 'fas fa-sort text-[10px] text-gray-400';
                    }
                }
            });
        }
        
        // Initialize sort icons on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort_by');
            const currentOrder = urlParams.get('sort_order');
            
            if (currentSort) {
                updateSortIcons(currentSort, currentOrder);
            }
            
            // Ensure min-height is reset on page load
            const tableContainer = document.getElementById('tableContainer');
            if (tableContainer) {
                tableContainer.style.minHeight = '';
            }
        });
        
        // Modal functions for reject reason
        function showRejectReasonModal(reason, name) {
            const modal = document.getElementById('rejectReasonModal');
            const reasonText = document.getElementById('rejectReasonModalText');
            const nameText = document.getElementById('rejectReasonModalName');
            
            if (modal && reasonText && nameText) {
                reasonText.textContent = reason;
                nameText.textContent = name;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Trigger animation
                setTimeout(() => {
                    modal.querySelector('.modal-content')?.classList.add('modal-content');
                }, 10);
            }
        }

        function closeRejectReasonModal() {
            const modal = document.getElementById('rejectReasonModal');
            if (modal) {
                const content = modal.querySelector('.modal-content');
                if (content) {
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(20px) scale(0.95)';
                    content.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
                }
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    if (content) {
                        content.style.opacity = '';
                        content.style.transform = '';
                        content.style.transition = '';
                    }
                }, 200);
            }
        }

        // Close modal when clicking outside
        document.getElementById('rejectReasonModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectReasonModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRejectReasonModal();
            }
        });
        
        // User dropdown toggle function
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const arrow = document.getElementById('userDropdownArrow');
            if (!dropdown) return;

            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.add('opacity-100', 'visible', 'scale-100');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const btn = document.getElementById('userDropdownBtn');
            const dropdown = document.getElementById('userDropdown');
            if (!btn || !dropdown) return;

            if (!btn.contains(event.target) && !dropdown.contains(event.target) && dropdown.classList.contains('opacity-100')) {
                toggleUserDropdown();
            }
        });

    </script>
</body>
</html>
