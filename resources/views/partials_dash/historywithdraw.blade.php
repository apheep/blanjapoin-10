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
        
        /* Table row animations */
        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .table-row-animate {
            animation: rowFadeIn 0.4s ease-out forwards;
            opacity: 0;
        }
        
        /* Button hover animations */
        .btn-detail {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-detail:hover {
            opacity: 0.8;
        }
        
        .btn-detail:active {
            opacity: 0.6;
        }
        
        /* Card animations for mobile */
        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-animate {
            animation: cardFadeIn 0.4s ease-out forwards;
            opacity: 0;
        }
        
        /* Smooth transitions for interactive elements */
        button, a, .hover\:scale-110, .hover\:bg-gray-50 {
            transition-property: color, background-color, border-color, transform, opacity, box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
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

        <!-- Table (Desktop & Mobile) -->
        <div class="bg-white rounded-xl shadow overflow-hidden" style="overflow: visible; position: relative; isolation: isolate;" id="historyTableContainer">
            <div id="historySortLoadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-75 z-30 flex items-center justify-center rounded-xl">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-6 h-6 border-2 border-gray-300 border-t-orange-500 rounded-full animate-spin"></div>
                    <span class="text-xs text-gray-600">Mengurutkan...</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleHistorySort('no')" class="flex items-center gap-0.5 sm:gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>No</span>
                                    <i class="fas fa-sort text-[8px] sm:text-[10px] text-gray-400" id="sortIconNo"></i>
                                </button>
                            </th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleHistorySort('status')" class="flex items-center gap-0.5 sm:gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Status</span>
                                    <i class="fas fa-sort text-[8px] sm:text-[10px] text-gray-400" id="sortIconStatus"></i>
                                </button>
                            </th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleHistorySort('nama')" class="flex items-center gap-0.5 sm:gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Nama</span>
                                    <i class="fas fa-sort text-[8px] sm:text-[10px] text-gray-400" id="sortIconNama"></i>
                                </button>
                            </th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleHistorySort('metode')" class="flex items-center gap-0.5 sm:gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span class="hidden sm:inline">Metode Penarikan</span>
                                    <span class="sm:hidden">Metode</span>
                                    <i class="fas fa-sort text-[8px] sm:text-[10px] text-gray-400" id="sortIconMetode"></i>
                                </button>
                            </th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <span class="hidden sm:inline">No. Rek/E-Wallet</span>
                                <span class="sm:hidden">No. Rek/E-W</span>
                            </th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleHistorySort('tanggal')" class="flex items-center gap-0.5 sm:gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Tanggal</span>
                                    <i class="fas fa-sort text-[8px] sm:text-[10px] text-gray-400" id="sortIconTanggal"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="historyTableBody">
                        @forelse($withdrawHistory as $index => $withdraw)
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
                                    'approved' => 'Disetujui',
                                    'completed' => 'Berhasil',
                                    'pending' => 'Pending',
                                    'rejected' => 'Ditolak',
                                    default => $withdraw->status
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors table-row-animate" style="animation-delay: {{ $index * 0.05 }}s;">
                                <td class="px-2 sm:px-4 py-2 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                    @if(request()->get('sort_by') === 'no' && request()->get('sort_order') === 'desc')
                                        {{ $withdrawHistory->total() - ($withdrawHistory->firstItem() + $index) + 1 }}
                                    @else
                                        {{ $withdrawHistory->firstItem() + $index }}
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-4">
                                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-2">
                                        <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 text-[10px] sm:text-xs font-semibold rounded-full {{ $statusClass }} transition-all duration-200 hover:scale-105 inline-block w-fit">{{ $statusText }}</span>
                                        @if($withdraw->status === 'rejected' && $withdraw->dec_reject)
                                            <button onclick="showRejectReasonModal('{{ addslashes($withdraw->dec_reject) }}', '{{ addslashes($withdraw->nama) }}')" 
                                                    class="btn-detail px-2 py-1 text-[10px] sm:text-xs font-normal text-red-500 hover:text-red-600 transition-colors duration-150 flex items-center gap-1 w-fit">
                                                <i class="fas fa-info-circle text-[9px] sm:text-[10px]"></i>
                                                <span>Detail</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">
                                        <div class="font-medium">{{ $withdraw->nama }}</div>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-4 text-xs sm:text-sm text-gray-700">{{ $withdraw->metode_penarikan_name }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-4 text-[10px] sm:text-sm text-gray-700 font-mono break-all">{{ $displayAccount }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-4 text-[10px] sm:text-xs text-gray-500">{{ $withdraw->created_at->format('d M Y') }}</td>
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
            
            // Initialize sort icons
            const currentSort = urlParams.get('sort_by');
            const currentOrder = urlParams.get('sort_order');
            
            if (currentSort && currentOrder) {
                updateHistorySortIcons(currentSort, currentOrder);
            }
            
            // Ensure min-height is reset on page load
            const historyTableContainer = document.getElementById('historyTableContainer');
            if (historyTableContainer) {
                historyTableContainer.style.minHeight = '';
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

        // Sort function for history with AJAX (sama dengan withdraw approval)
        function handleHistorySort(column) {
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
            updateHistorySortIcons(column, newOrder);
            
            // Update URL without reload
            window.history.pushState({}, '', '?' + urlParams.toString());
            
            // Show loading overlay
            const loadingOverlay = document.getElementById('historySortLoadingOverlay');
            const tableBody = document.getElementById('historyTableBody');
            const tableContainer = document.getElementById('historyTableContainer');
            
            if (loadingOverlay && tableBody && tableContainer) {
                // Store current height to prevent layout shift
                const currentHeight = tableContainer.offsetHeight;
                tableContainer.style.minHeight = currentHeight + 'px';
                
                loadingOverlay.classList.remove('hidden');
                
                // Get current URL path with code
                const currentPath = window.location.pathname;
                const fullUrl = currentPath + '?' + urlParams.toString();
                
                // Make AJAX request
                fetch(fullUrl, {
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
                    const newTableBody = doc.getElementById('historyTableBody');
                    const newPaginationContainer = doc.querySelector('.bg-white.px-4.py-4.border-t.border-gray-200');
                    
                    if (newTableBody) {
                        // Smooth transition
                        tableBody.style.opacity = '0';
                        tableBody.style.transition = 'opacity 0.2s';
                        
                        setTimeout(() => {
                            // Replace table body content
                            tableBody.innerHTML = newTableBody.innerHTML;
                            
                            // Replace pagination container if exists
                            const currentPaginationContainer = document.querySelector('.bg-white.px-4.py-4.border-t.border-gray-200');
                            if (currentPaginationContainer && newPaginationContainer) {
                                currentPaginationContainer.outerHTML = newPaginationContainer.outerHTML;
                            }
                            
                            // Re-initialize any event listeners if needed
                            // (modals will work because they use onclick attributes)
                            
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
        
        // Update sort icons for history (sama dengan withdraw approval)
        function updateHistorySortIcons(activeColumn, order) {
            const columns = ['no', 'status', 'nama', 'metode', 'tanggal'];
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
    </script>
</body>
</html>
