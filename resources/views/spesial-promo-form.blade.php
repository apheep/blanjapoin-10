<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spesial Promo Form • BlanjaPoin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @include('partials.head')
    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;    
            -moz-osx-font-smoothing: grayscale;
        }
        
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .page-content {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            min-width: 300px;
            max-width: 400px;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease-out;
            font-size: 14px;
        }
        
        .toast.error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        .toast.success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast.hide {
            animation: slideOutRight 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 font-poppins">

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
    
    <main class="page-content max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Spesial Promo Form</h2>
                <p class="text-sm text-gray-600 mt-1">Daftar keyword promo spesial</p>
            </div>
            <!-- Minimalis Info Badge -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium
                @if($activeSpecialPromoCount >= 4)
                    bg-red-100 text-red-700 border border-red-200
                @elseif($activeSpecialPromoCount >= 3)
                    bg-yellow-100 text-yellow-700 border border-yellow-200
                @else
                    bg-blue-100 text-blue-700 border border-blue-200
                @endif">
                <span class="w-2 h-2 rounded-full
                    @if($activeSpecialPromoCount >= 4)
                        bg-red-500
                    @elseif($activeSpecialPromoCount >= 3)
                        bg-yellow-500
                    @else
                        bg-blue-500
                    @endif"></span>
                <span>{{ $activeSpecialPromoCount }}/4 Aktif</span>
            </div>
        </div>

        <!-- Search and Date Filter -->
        <form method="GET" action="{{ route('spesial-promo.form') }}" id="specialPromoSearchForm" class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 overflow-visible" onsubmit="return true;">
            <div class="relative flex-1 w-full sm:max-w-[400px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input name="q"
                       id="specialPromoSearchInput"
                       value="{{ request()->query('q', '') }}"
                       class="w-full rounded-full border border-gray-200 bg-white px-10 py-2 text-sm placeholder:text-gray-400 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none"
                       placeholder="Search nama produk, merchant, keyword ID..."
                       onkeydown="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('specialPromoSearchForm').submit(); }" />
                @if(request()->has('q'))
                    <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
            <div class="flex-shrink-0 overflow-visible" style="position: relative; z-index: 50;">
                @include('partials.date-filter', ['filterId' => 'dateFilterSpecialPromo'])
            </div>
            @if(request()->has('q') || request()->has('start_date') || request()->has('end_date'))
                <a href="{{ route('spesial-promo.form') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-1"></i>Clear
                </a>
            @endif
        </form>

        <!-- Keywords Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Spesial Form</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keyword ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA LINK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">TRX</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sisa Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($keywords as $keyword)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    {{ ($keywords->currentPage() - 1) * $keywords->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-4">
                                    @if($keyword->status === 'approve')
                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-sm shadow-sm">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span>Approved</span>
                                        </div>
                                    @elseif($keyword->status === 'pending')
                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700 font-medium text-sm shadow-sm">
                                            <i class="fas fa-clock text-yellow-600"></i>
                                            <span>Pending</span>
                                        </div>
                                    @elseif($keyword->status === 'reject')
                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-red-100 to-rose-100 text-red-700 font-medium text-sm shadow-sm">
                                            <i class="fas fa-times-circle text-red-600"></i>
                                            <span>Rejected</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle Spesial Form">
                                        <input type="checkbox" 
                                               data-keyword-id="{{ $keyword->id }}" 
                                               class="sr-only peer toggle-special-promo" 
                                               {{ ($keyword->is_special_promo ?? 0) ? 'checked' : '' }} />
                                        <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                                    </label>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $keyword->nama_produk }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $keyword->keyword_id ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    @if($keyword->cta_link)
                                        <a href="{{ $keyword->cta_link }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-xs block">{{ $keyword->cta_link }}</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->redeem ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->diskon ? formatDiskon($keyword->diskon) : '-' }}</td>
                                <td class="px-4 py-4 text-xs text-gray-500">{{ $keyword->skb ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $keyword->trx ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">{{ $keyword->sisa_stock ?? 0 }}</span>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-500">
                                    @if($keyword->start_date || $keyword->end_date)
                                        <div>{{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }}</div>
                                        <div>{{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}</div>
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($keyword->image)
                                        <a href="{{ asset('storage/' . $keyword->image) }}"
                                           target="_blank"
                                           rel="noreferrer"
                                           class="group block w-24 h-12 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition duration-150 hover:border-gray-300">
                                            <img src="{{ asset('storage/' . $keyword->image) }}" 
                                                 alt="{{ $keyword->nama_produk }}" 
                                                 class="h-full w-full object-cover transition-transform duration-150 group-hover:scale-105">
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="px-4 py-4 text-center text-sm text-gray-500">
                                    Belum ada data keyword yang approved.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($keywords->hasPages())
                <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold">{{ $keywords->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywords->lastItem() }}</span> dari <span class="font-semibold">{{ $keywords->total() }}</span> data
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($keywords->onFirstPage())
                            <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @else
                            <a href="{{ $keywords->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($keywords->getUrlRange(1, $keywords->lastPage()) as $page => $url)
                            @if ($page == $keywords->currentPage())
                                <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($keywords->hasMorePages())
                            <a href="{{ $keywords->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
    </main>

    <script>
        // Toggle Special Promo Status
        document.addEventListener('DOMContentLoaded', function() {
            // Attach toggle listeners for server-rendered checkboxes
            document.querySelectorAll('.toggle-special-promo').forEach(toggle => {
                toggle.addEventListener('change', (e) => {
                    const keywordId = e.target.dataset.keywordId;
                    if (!keywordId) return;
                    toggleSpecialPromo(keywordId);
                });
            });
        });

        // Function to toggle special promo status
        async function toggleSpecialPromo(keywordId) {
            try {
                const response = await fetch(`/api/keywords/${keywordId}/toggle-special-promo`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    // Revert checkbox on error
                    const checkbox = document.querySelector(`.toggle-special-promo[data-keyword-id="${keywordId}"]`);
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                    }
                    
                    // Show minimalis toast notification
                    showToast(data.error || 'Gagal memperbarui status spesial promo', 'error');
                    return;
                }

                // Success - reload page to update info box and count
                if (data.success) {
                    showToast('Status spesial promo berhasil diperbarui', 'success');
                    // Reload after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                console.error('Error toggling special promo status:', error);
                // Revert checkbox on error
                const checkbox = document.querySelector(`.toggle-special-promo[data-keyword-id="${keywordId}"]`);
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
                showToast('Gagal memperbarui status spesial promo: ' + error.message, 'error');
            }
        }
        
        // Function to show minimalis toast notification
        function showToast(message, type = 'error') {
            // Remove existing toast if any
            const existingToast = document.getElementById('toast-notification');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast element
            const toast = document.createElement('div');
            toast.id = 'toast-notification';
            toast.className = `toast ${type}`;
            
            // Icon based on type
            const icon = type === 'error' 
                ? '<i class="fas fa-exclamation-circle"></i>'
                : '<i class="fas fa-check-circle"></i>';
            
            toast.innerHTML = `
                ${icon}
                <span>${message}</span>
            `;
            
            // Append to body
            document.body.appendChild(toast);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        }
        
        // Clear search function
        function clearSearch() {
            document.getElementById('specialPromoSearchInput').value = '';
            document.getElementById('specialPromoSearchForm').submit();
        }
        
        // Override applyDateFilterCompact untuk spesial promo form (server-side filtering)
        // Hanya override jika belum ada
        if (!window.applyDateFilterCompactOriginal) {
            window.applyDateFilterCompactOriginal = window.applyDateFilterCompact;
        }
        
        window.applyDateFilterCompact = function(filterId) {
            // Jika filterId adalah dateFilterSpecialPromo, gunakan server-side filtering
            if (filterId === 'dateFilterSpecialPromo') {
                applySpecialPromoDateFilter(filterId);
                return;
            }
            
            // Untuk filter lain, gunakan fungsi asli (client-side)
            if (window.applyDateFilterCompactOriginal) {
                window.applyDateFilterCompactOriginal(filterId);
            }
        };
        
        // Function to apply date filter for special promo (server-side)
        function applySpecialPromoDateFilter(filterId) {
            const state = window.calendarState?.[filterId];
            const form = document.getElementById('specialPromoSearchForm');
            
            if (!form) return;
            
            // Remove existing date inputs
            const existingStartDate = form.querySelector('input[name="start_date"]');
            const existingEndDate = form.querySelector('input[name="end_date"]');
            if (existingStartDate) existingStartDate.remove();
            if (existingEndDate) existingEndDate.remove();
            
            // If no dates selected, clear filter
            if (!state || (!state.startDate && !state.endDate)) {
                form.submit();
                return;
            }
            
            // Format dates to YYYY-MM-DD
            const formatDateForInput = (date) => {
                if (!date) return null;
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const startDate = state.startDate ? formatDateForInput(state.startDate) : null;
            const endDate = state.endDate ? formatDateForInput(state.endDate) : null;
            
            // Add date inputs to form
            if (startDate) {
                const startInput = document.createElement('input');
                startInput.type = 'hidden';
                startInput.name = 'start_date';
                startInput.value = startDate;
                form.appendChild(startInput);
            }
            
            if (endDate) {
                const endInput = document.createElement('input');
                endInput.type = 'hidden';
                endInput.name = 'end_date';
                endInput.value = endDate;
                form.appendChild(endInput);
            }
            
            // Submit form
            form.submit();
        }
        
        // Prevent form submit when clicking date filter button
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('specialPromoSearchForm');
            if (form) {
                // Prevent form submit kecuali dari search input (Enter key) atau Apply button
                let shouldSubmit = false;
                
                form.addEventListener('submit', function(e) {
                    if (!shouldSubmit) {
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Allow submit dari search input (Enter key)
                const searchInput = document.getElementById('specialPromoSearchInput');
                if (searchInput) {
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            shouldSubmit = true;
                        }
                    });
                }
                
                // Prevent form submit when clicking inside date filter dropdown
                setTimeout(function() {
                    const dateFilterDropdown = document.getElementById('dateFilterSpecialPromo');
                    if (dateFilterDropdown) {
                        dateFilterDropdown.addEventListener('click', function(e) {
                            e.stopPropagation();
                        }, true);
                    }
                    
                    // Prevent form submit when clicking date filter toggle button
                    const dateFilterButton = document.querySelector('button[onclick*="dateFilterSpecialPromo"]');
                    if (dateFilterButton) {
                        dateFilterButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            shouldSubmit = false;
                            return false;
                        }, true);
                    }
                }, 100);
                
                // Reset shouldSubmit setelah submit
                form.addEventListener('submit', function() {
                    setTimeout(function() {
                        shouldSubmit = false;
                    }, 100);
                });
            }
            
            const urlParams = new URLSearchParams(window.location.search);
            const startDateParam = urlParams.get('start_date');
            const endDateParam = urlParams.get('end_date');
            
            if (startDateParam || endDateParam) {
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
                
                const startDateObj = toDateObj(startDateParam);
                const endDateObj = toDateObj(endDateParam);
                
                // Set calendar state
                if (startDateObj || endDateObj) {
                    window.calendarState['dateFilterSpecialPromo'] = {
                        currentMonth: startDateObj ? startDateObj.getMonth() : (endDateObj ? endDateObj.getMonth() : new Date().getMonth()),
                        currentYear: startDateObj ? startDateObj.getFullYear() : (endDateObj ? endDateObj.getFullYear() : new Date().getFullYear()),
                        startDate: startDateObj,
                        endDate: endDateObj,
                        activeType: startDateObj ? 'start' : 'end'
                    };
                }
                
                // Update input fields
                const startInput = document.getElementById('startInputdateFilterSpecialPromo');
                const endInput = document.getElementById('endInputdateFilterSpecialPromo');
                
                if (startInput && startDateObj) {
                    const day = String(startDateObj.getDate()).padStart(2, '0');
                    const month = String(startDateObj.getMonth() + 1).padStart(2, '0');
                    const year = startDateObj.getFullYear();
                    startInput.value = `${day}/${month}/${year}`;
                }
                
                if (endInput && endDateObj) {
                    const day = String(endDateObj.getDate()).padStart(2, '0');
                    const month = String(endDateObj.getMonth() + 1).padStart(2, '0');
                    const year = endDateObj.getFullYear();
                    endInput.value = `${day}/${month}/${year}`;
                }
            }
        });
    </script>
</body>
</html>
