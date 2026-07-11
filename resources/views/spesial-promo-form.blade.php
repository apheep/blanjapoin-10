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
        
        /* Custom Input Search Class */
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
        
        /* Custom Toggle Switch Class */
        .toggle-switch {
            width: 2.25rem;
            height: 1.25rem;
            background-color: #e5e7eb;
            border-radius: 9999px;
            position: relative;
            transition: all 0.3s ease-in-out;
        }
        .toggle-switch:hover {
            background-color: #d1d5db;
        }
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 1rem;
            height: 1rem;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 9999px;
            transition: all 0.3s ease-in-out;
        }
        
        /* Custom Badge Status Classes */
        .badge-approved {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: linear-gradient(to right, #dcfce7, #d1fae5);
            color: #15803d;
            font-weight: 500;
            font-size: 0.875rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .badge-pending {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: linear-gradient(to right, #fef3c7, #fde68a);
            color: #b45309;
            font-weight: 500;
            font-size: 0.875rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .badge-rejected {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: linear-gradient(to right, #fee2e2, #fecdd3);
            color: #991b1b;
            font-weight: 500;
            font-size: 0.875rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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
        <form method="GET" action="{{ route('spesial-promo.form') }}" id="specialPromoSearchForm" class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 overflow-visible">
            <div class="relative w-full max-w-[280px] sm:max-w-[180px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input name="q"
                       id="specialPromoSearchInput"
                       value="{{ request()->query('q', '') }}"
                       class="search-input"
                       placeholder="Search"
                       onkeydown="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('specialPromoSearchForm').submit(); }" />
                @if(request()->has('q'))
                    <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
            <div class="flex-shrink-0 overflow-visible" style="position: relative; z-index: 40;">
                @include('partials.date-withdraw', ['filterId' => 'specialPromoDateFilter'])
            </div>
            @if(request()->has('q') || request()->has('date'))
                <a href="{{ route('spesial-promo.form') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-1"></i>Clear
                </a>
            @endif
        </form>

        <!-- Keywords Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden" style="overflow: visible; position: relative; isolation: isolate;" id="tableContainer">
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
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('spesial_form')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Best Offer</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconSpesialForm"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('merchant')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Merchant</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconMerchant"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('nama_produk')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Nama Produk</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconNamaProduk"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('keyword_id')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Keyword ID</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconKeywordId"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA LINK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('redeem')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Redeem</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconRedeem"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('diskon')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Diskon</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconDiskon"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-48">SKB</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('stock')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Stock</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconStock"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">TRX</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sisa Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <button onclick="handleSort('periode')" class="flex items-center gap-1 hover:text-gray-900 transition-colors focus:outline-none">
                                    <span>Periode</span>
                                    <i class="fas fa-sort text-[10px] text-gray-400" id="sortIconPeriode"></i>
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                        </tr>
                    </thead>
                    <tbody id="keywordTableBody" class="bg-white divide-y divide-gray-200" style="position: relative; overflow: visible;">
                        @forelse($keywords as $keyword)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    @if(request()->get('sort_by') === 'no' && request()->get('sort_order') === 'desc')
                                        {{ $keywords->total() - ($keywords->firstItem() + $loop->index) + 1 }}
                                    @else
                                        {{ $keywords->firstItem() + $loop->index }}
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($keyword->status === 'approve')
                                        <div class="badge-approved">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            <span>Approved</span>
                                        </div>
                                    @elseif($keyword->status === 'pending')
                                        <div class="badge-pending">
                                            <i class="fas fa-clock text-yellow-600"></i>
                                            <span>Pending</span>
                                        </div>
                                    @elseif($keyword->status === 'reject')
                                        <div class="badge-rejected">
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
                                        <div class="toggle-switch peer-checked:bg-green-600 hover:peer-checked:bg-green-700 peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
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
                                <td class="px-4 py-4 w-48">
                                    @if($keyword->skb)
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500 truncate flex-1" title="{{ $keyword->skb }}">
                                                {{ Str::limit($keyword->skb, 20, '...') }}
                                            </span>
                                            <button type="button"
                                                    onclick="showSKBDetail({{ json_encode($keyword->skb) }}, {{ json_encode($keyword->nama_produk) }}, {{ json_encode($keyword->merchant->nama_merchant ?? '-') }}, {{ json_encode($keyword->diskon ? formatDiskon($keyword->diskon) : '-') }})"
                                                    class="px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 rounded transition-colors whitespace-nowrap flex-shrink-0"
                                                    title="Lihat Detail SKB">
                                                Detail
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
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
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    @if($keywords->total() > 0)
                        Menampilkan <span class="font-semibold">{{ $keywords->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywords->lastItem() }}</span> dari <span class="font-semibold">{{ $keywords->total() }}</span> data
                    @else
                        Tidak ada data
                    @endif
                </div>
                
                @if($keywords->total() > 0)
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($keywords->onFirstPage())
                        <button disabled class="pagination-btn-disabled">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $keywords->previousPageUrl() }}" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements with Smart Pagination --}}
                    @php
                        $currentPage = $keywords->currentPage();
                        $lastPage = $keywords->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp
                    
                    @if($lastPage > 1)
                        @if($startPage > 1)
                            <a href="{{ $keywords->url(1) }}" class="pagination-btn">1</a>
                            @if($startPage > 2)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                        @endif
                        
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $keywords->currentPage())
                                <button disabled class="pagination-btn-active">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $keywords->url($page) }}" class="pagination-btn">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor
                        
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $keywords->url($lastPage) }}" class="pagination-btn">{{ $lastPage }}</a>
                        @endif
                    @else
                        {{-- Show current page even if only 1 page --}}
                        <button disabled class="pagination-btn-active">
                            1
                        </button>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($keywords->hasMorePages())
                        <a href="{{ $keywords->nextPageUrl() }}" class="pagination-btn">
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
            window.location.href = '{{ route("spesial-promo.form") }}';
        }
        
        // Search form handling
        document.addEventListener('DOMContentLoaded', function() {
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
                    window.calendarState['specialPromoDateFilter'] = {
                        currentMonth: dateObj.getMonth(),
                        currentYear: dateObj.getFullYear(),
                        selectedDate: dateObj,
                        activeType: 'date'
                    };
                }
                
                // Update input field
                const dateInput = document.getElementById('dateInputspecialPromoDateFilter');
                if (dateInput && dateObj) {
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const year = dateObj.getFullYear();
                    dateInput.value = `${day}/${month}/${year}`;
                }
            }
            
            // Search form handling
            const specialPromoSearchForm = document.getElementById('specialPromoSearchForm');
            const specialPromoSearchInput = document.getElementById('specialPromoSearchInput');
            
            if (specialPromoSearchForm && specialPromoSearchInput) {
                // Prevent form submission on Enter if date filter is open
                specialPromoSearchForm.addEventListener('submit', function(e) {
                    // Check if any date filter dropdown is open
                    const openDateFilters = document.querySelectorAll('[id^="withdrawDateFilter"]:not(.hidden), [id^="dateFilter"]:not(.hidden), [id^="specialPromoDateFilter"]:not(.hidden)');
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
                            specialPromoSearchForm.submit();
                        }, 200);
                        return false;
                    }
                });
                
                // Handle Enter key in search input
                specialPromoSearchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        // Close any open date filter dropdowns
                        const openDateFilters = document.querySelectorAll('[id^="withdrawDateFilter"]:not(.hidden), [id^="dateFilter"]:not(.hidden), [id^="specialPromoDateFilter"]:not(.hidden)');
                        openDateFilters.forEach(filter => {
                            filter.classList.add('hidden');
                            filter.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                            filter.classList.add('opacity-0', 'scale-95', 'translate-y-2');
                        });
                        // Submit form
                        setTimeout(() => {
                            specialPromoSearchForm.submit();
                        }, 100);
                    }
                });

                // Live search: debounce 300ms on every keystroke
                let specialPromoSearchTimeout = null;
                specialPromoSearchInput.addEventListener('input', function() {
                    if (specialPromoSearchTimeout) clearTimeout(specialPromoSearchTimeout);
                    specialPromoSearchTimeout = setTimeout(() => {
                        const urlParams = new URLSearchParams(window.location.search);
                        const q = specialPromoSearchInput.value.trim();
                        if (q) {
                            urlParams.set('q', q);
                        } else {
                            urlParams.delete('q');
                        }
                        urlParams.set('page', 1);

                        window.history.pushState({}, '', '?' + urlParams.toString());

                        const loadingOverlay = document.getElementById('sortLoadingOverlay');
                        const tableBody = document.getElementById('keywordTableBody');
                        const tableContainer = document.getElementById('tableContainer');

                        if (loadingOverlay && tableBody && tableContainer) {
                            loadingOverlay.classList.remove('hidden');

                            fetch('{{ route("spesial-promo.form") }}?' + urlParams.toString(), {
                                method: 'GET',
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
                            })
                            .then(r => r.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newTableBody = doc.getElementById('keywordTableBody');
                                const newPagination = doc.querySelector('.bg-white.px-4.py-4.border-t.flex');

                                if (newTableBody) {
                                    tableBody.style.opacity = '0';
                                    tableBody.style.transition = 'opacity 0.2s';
                                    setTimeout(() => {
                                        tableBody.innerHTML = newTableBody.innerHTML;
                                        document.querySelectorAll('.toggle-special-promo').forEach(toggle => {
                                            toggle.addEventListener('change', (e) => {
                                                const keywordId = e.target.dataset.keywordId;
                                                if (keywordId) toggleSpecialPromo(keywordId);
                                            });
                                        });
                                        const currentPagination = document.querySelector('.bg-white.px-4.py-4.border-t.flex');
                                        if (currentPagination && newPagination) {
                                            currentPagination.outerHTML = newPagination.outerHTML;
                                        }
                                        tableBody.style.opacity = '1';
                                        loadingOverlay.classList.add('hidden');
                                    }, 200);
                                } else {
                                    loadingOverlay.classList.add('hidden');
                                }
                            })
                            .catch(() => { loadingOverlay.classList.add('hidden'); });
                        }
                    }, 300);
                });
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

        // Function to show SKB detail in modal
        function showSKBDetail(skbText, productName, merchantName, promoText) {
            // Create modal overlay
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end md:items-center justify-center p-0 md:p-4';
            overlay.id = 'skb-modal-overlay';
            overlay.onclick = function(e) {
                if (e.target === overlay) {
                    closeSKBModal();
                }
            };

            // Create modal content - responsive: bottom sheet on mobile, centered on desktop
            const modal = document.createElement('div');
            modal.className = 'bg-white rounded-t-3xl md:rounded-xl shadow-2xl max-w-2xl w-full max-h-[85vh] md:max-h-[80vh] overflow-hidden flex flex-col';
            
            // Set initial state for animation
            const isMobile = window.innerWidth < 768;
            if (isMobile) {
                modal.style.transform = 'translateY(100%)';
                modal.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            } else {
                modal.style.transform = 'scale(0.95) translateY(-10px)';
                modal.style.opacity = '0';
                modal.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            }
            overlay.style.opacity = '0';
            overlay.style.transition = 'opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            
            // Drag handle for mobile (top bar)
            const dragHandle = document.createElement('div');
            dragHandle.className = 'md:hidden pt-3 pb-2 flex justify-center';
            dragHandle.innerHTML = `
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            `;
            
            // Modal header
            const header = document.createElement('div');
            header.className = 'px-6 py-4 border-b border-gray-200 flex items-center justify-between';
            header.innerHTML = `
                <h3 class="text-lg font-semibold text-gray-900">Deskripsi</h3>
                <button onclick="closeSKBModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            `;

            // Modal body
            const body = document.createElement('div');
            body.className = 'px-6 py-4 overflow-y-auto flex-1';
            body.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Merchant:</p>
                        <p class="text-sm text-gray-900">${merchantName}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Produk:</p>
                        <p class="text-sm text-gray-900">${productName}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Promo:</p>
                        <p class="text-sm text-gray-900">${promoText}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">SKB:</p>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mt-2">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap break-words">${skbText}</p>
                        </div>
                    </div>
                </div>
            `;

            // Modal footer
            const footer = document.createElement('div');
            footer.className = 'px-6 py-4 border-t border-gray-200 flex justify-end gap-2';
            const copyButton = document.createElement('button');
            copyButton.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors';
            copyButton.innerHTML = '<i class="fas fa-copy mr-2"></i>Copy';
            copyButton.onclick = function() {
                copySKBToClipboard(skbText, copyButton);
            };
            
            const closeButton = document.createElement('button');
            closeButton.className = 'px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors';
            closeButton.textContent = 'Tutup';
            closeButton.onclick = closeSKBModal;
            
            footer.appendChild(copyButton);
            footer.appendChild(closeButton);

            modal.appendChild(dragHandle);
            modal.appendChild(header);
            modal.appendChild(body);
            modal.appendChild(footer);
            overlay.appendChild(modal);
            document.body.appendChild(overlay);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Trigger animation
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    if (isMobile) {
                        modal.style.transform = 'translateY(0)';
                    } else {
                        modal.style.transform = 'scale(1) translateY(0)';
                        modal.style.opacity = '1';
                    }
                    overlay.style.opacity = '1';
                });
            });
        }

        function closeSKBModal() {
            const overlay = document.getElementById('skb-modal-overlay');
            if (overlay) {
                const modal = overlay.querySelector('div[class*="rounded"]');
                if (modal) {
                    const isMobile = window.innerWidth < 768;
                    
                    // Ensure transitions are set before animating
                    if (isMobile) {
                        modal.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        modal.style.transform = 'translateY(100%)';
                    } else {
                        modal.style.transition = 'transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
                        modal.style.transform = 'scale(0.95) translateY(-10px)';
                        modal.style.opacity = '0';
                    }
                    
                    overlay.style.transition = 'opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    overlay.style.opacity = '0';
                    
                    // Remove after animation completes
                    setTimeout(() => {
                        overlay.remove();
                        document.body.style.overflow = '';
                    }, 300);
                }
            }
        }

        function copySKBToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                // Show success message
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600', 'hover:bg-green-700');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Gagal menyalin teks');
            });
        }
        
        // Sorting function with AJAX
        function handleSort(column) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort_by');
            const currentOrder = urlParams.get('sort_order');
            
            let newSort = column;
            let newOrder = 'asc';
            
            if (currentSort === column) {
                // Toggle between asc and desc
                if (currentOrder === 'asc') {
                    newOrder = 'desc';
                } else {
                    // Reset to default (no sort)
                    newSort = null;
                    newOrder = null;
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
            const tableBody = document.getElementById('keywordTableBody');
            const tableContainer = document.getElementById('tableContainer');
            
            if (loadingOverlay && tableBody && tableContainer) {
                // Store current height to prevent layout shift
                const currentHeight = tableContainer.offsetHeight;
                tableContainer.style.minHeight = currentHeight + 'px';
                
                loadingOverlay.classList.remove('hidden');
                
                // Make AJAX request
                fetch('{{ route("spesial-promo.form") }}?' + urlParams.toString(), {
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
                    const newTableBody = doc.getElementById('keywordTableBody');
                    const newPaginationContainer = doc.querySelector('.bg-white.px-4.py-4.border-t.flex');
                    
                    if (newTableBody) {
                        // Smooth transition
                        tableBody.style.opacity = '0';
                        tableBody.style.transition = 'opacity 0.2s';
                        
                        setTimeout(() => {
                            // Replace table body content
                            tableBody.innerHTML = newTableBody.innerHTML;
                            
                            // Re-attach toggle listeners for new checkboxes
                            document.querySelectorAll('.toggle-special-promo').forEach(toggle => {
                                toggle.addEventListener('change', (e) => {
                                    const keywordId = e.target.dataset.keywordId;
                                    if (!keywordId) return;
                                    toggleSpecialPromo(keywordId);
                                });
                            });
                            
                            // Replace pagination container if exists
                            const currentPaginationContainer = document.querySelector('.bg-white.px-4.py-4.border-t.flex');
                            if (currentPaginationContainer && newPaginationContainer) {
                                currentPaginationContainer.outerHTML = newPaginationContainer.outerHTML;
                            }
                            
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
            const iconMap = {
                'no': 'sortIconNo',
                'spesial_form': 'sortIconSpesialForm',
                'merchant': 'sortIconMerchant',
                'nama_produk': 'sortIconNamaProduk',
                'keyword_id': 'sortIconKeywordId',
                'redeem': 'sortIconRedeem',
                'diskon': 'sortIconDiskon',
                'stock': 'sortIconStock',
                'periode': 'sortIconPeriode'
            };
            
            Object.keys(iconMap).forEach(col => {
                const iconId = iconMap[col];
                const icon = document.getElementById(iconId);
                if (icon) {
                    if (col === activeColumn && order) {
                        if (order === 'asc') {
                            icon.className = 'fas fa-sort-up text-[10px] text-gray-700';
                        } else if (order === 'desc') {
                            icon.className = 'fas fa-sort-down text-[10px] text-gray-700';
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
    </script>
</body>
</html>
