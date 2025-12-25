<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Not Matched Detail | BlanjaPoin Admin</title>
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
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
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
    </style>
</head>
<body class="min-h-screen bg-white font-poppins">
    @include('partials.navbar-admin')

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin Panel</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Not Matched Detail</h1>
                <p class="text-sm text-gray-600 mt-2">Komparasi MSISDN yang sama dengan matched dan not matched redemption</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('click.history.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-300 text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('click.history.not-matched-detail') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                                   placeholder="MSISDN, Keyword ID..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Merchant Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merchant</label>
                        <div class="relative" id="merchant-dropdown-container">
                            <input type="hidden" name="merchant_id" id="merchant_id_input" value="{{ $filters['merchant_id'] ?? '' }}">
                            <input type="text" 
                                   id="merchant_search_input"
                                   value="{{ $merchants->where('id', $filters['merchant_id'] ?? '')->first()->nama_merchant ?? 'Semua Merchant' }}"
                                   placeholder="Cari merchant..."
                                   readonly
                                   autocomplete="off"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm cursor-pointer bg-white">
                            <div id="merchant_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-hidden hidden">
                                <div class="p-2 border-b border-gray-200 bg-white sticky top-0 z-10">
                                    <input type="text" 
                                           id="merchant_search_inner"
                                           placeholder="Ketik untuk mencari merchant..."
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-gray-50"
                                           autocomplete="off">
                                </div>
                                <div class="py-1 overflow-y-auto max-h-52" id="merchant_options_list">
                                    <div class="merchant-option px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm transition-colors" data-value="">Semua Merchant</div>
                                    @foreach($merchants as $merchant)
                                        <div class="merchant-option px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm transition-colors" data-value="{{ $merchant->id }}" data-name="{{ strtolower($merchant->nama_merchant) }}">{{ $merchant->nama_merchant }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('click.history.not-matched-detail') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Comparison Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">MSISDN & Keyword</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="badge badge-success">✓ Matched</span>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="badge badge-danger">✗ Not Matched</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($comparisons as $comparison)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-semibold text-gray-900 mb-2">
                                        <span class="text-sm font-mono">{{ $comparison['msisdn'] }}</span>
                                    </div>
                                    <div class="text-sm text-gray-700">
                                        <span class="font-mono font-semibold">{{ $comparison['keyword_id'] }}</span>
                                    </div>
                                    @if($comparison['keyword_desc'])
                                        <div class="text-xs text-gray-500 mt-1">{{ $comparison['keyword_desc'] }}</div>
                                    @endif
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex gap-4 text-xs">
                                            <div>
                                                <span class="text-gray-500">Matched:</span>
                                                <span class="font-semibold text-green-600">{{ count($comparison['matched']) }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Not Matched:</span>
                                                <span class="font-semibold text-red-600">{{ count($comparison['not_matched']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top bg-green-50/30">
                                    <div class="space-y-3">
                                        @foreach($comparison['matched'] as $matched)
                                            <div class="bg-white rounded-lg p-3 border border-green-200 shadow-sm">
                                                <div class="text-xs space-y-1.5">
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Merchant:</span>
                                                        <div class="text-gray-900 font-medium mt-0.5">{{ isset($matched['merchant']) && $matched['merchant'] ? $matched['merchant']->nama_merchant : 'N/A' }}</div>
                                                        @if(isset($matched['click_history']) && $matched['click_history'])
                                                            <div class="text-gray-500">ID: {{ $matched['click_history']->merchant_id ?? 'N/A' }}</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Time Diff:</span>
                                                        <span class="text-gray-600">{{ $matched['redeem']->time_diff_human ?? 'N/A' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Confidence:</span>
                                                        @if(($matched['redeem']->confidence ?? 'low') === 'high')
                                                            <span class="text-green-600 font-semibold">● High</span> <span class="text-gray-500">(≤5 menit)</span>
                                                        @elseif(($matched['redeem']->confidence ?? 'low') === 'medium')
                                                            <span class="text-yellow-600 font-semibold">● Medium</span> <span class="text-gray-500">(≤15 menit)</span>
                                                        @else
                                                            <span class="text-red-600 font-semibold">● Low</span> <span class="text-gray-500">(>15 menit)</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Poin:</span>
                                                        <span class="text-orange-600 font-semibold">{{ number_format($matched['redeem']->poin_redeem ?? 0) }}</span>
                                                    </div>
                                                    <div class="pt-1.5 border-t border-gray-100">
                                                        <span class="font-semibold text-gray-700">Click:</span>
                                                        <div class="text-gray-600">{{ $matched['click_history']->clicked_at->format('d M Y H:i:s') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top bg-red-50/30">
                                    <div class="space-y-3">
                                        @foreach($comparison['not_matched'] as $notMatched)
                                            <div class="bg-white rounded-lg p-3 border border-red-200 shadow-sm">
                                                <div class="text-xs space-y-1.5">
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Merchant:</span>
                                                        <div class="text-gray-900 font-medium mt-0.5">{{ $notMatched['merchant']->nama_merchant ?? 'N/A' }}</div>
                                                        @if(isset($notMatched['merchant']) && $notMatched['merchant'])
                                                            <div class="text-gray-500">ID: {{ $notMatched['merchant']->id ?? 'N/A' }}</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Time Diff:</span>
                                                        <span class="text-red-600 font-semibold">{{ $notMatched['redeem']->time_diff_human ?? 'N/A' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Confidence:</span>
                                                        @if(($notMatched['redeem']->confidence ?? 'low') === 'high')
                                                            <span class="text-green-600 font-semibold">● High</span> <span class="text-gray-500">(≤5 menit)</span>
                                                        @elseif(($notMatched['redeem']->confidence ?? 'low') === 'medium')
                                                            <span class="text-yellow-600 font-semibold">● Medium</span> <span class="text-gray-500">(≤15 menit)</span>
                                                        @else
                                                            <span class="text-red-600 font-semibold">● Low</span> <span class="text-gray-500">(>15 menit)</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-gray-700">Poin:</span>
                                                        <span class="text-orange-600 font-semibold">{{ number_format($notMatched['redeem']->poin_redeem ?? 0) }}</span>
                                                    </div>
                                                    <div class="pt-1.5 border-t border-gray-100">
                                                        <span class="font-semibold text-gray-700">Click:</span>
                                                        <div class="text-gray-600">{{ $notMatched['click_history']->clicked_at->format('d M Y H:i:s') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data</p>
                                        <p class="text-gray-400 text-sm mt-2">Tidak ada komparasi Not Matched yang ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($comparisons->hasPages())
            <div class="mt-6 flex items-center justify-center">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    {{ $comparisons->links() }}
                </div>
            </div>
        @endif

        <!-- Summary -->
        @if($comparisons->total() > 0)
            <div class="mt-6 bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Komparasi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $comparisons->total() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Menampilkan {{ $comparisons->count() }} dari {{ $comparisons->total() }} komparasi</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Matched</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ $totalMatched }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Not Matched</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">
                            {{ $totalNotMatched }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <script>
        // Merchant searchable dropdown
        (function() {
            const container = document.getElementById('merchant-dropdown-container');
            if (!container) return;
            
            const input = document.getElementById('merchant_search_input');
            const hiddenInput = document.getElementById('merchant_id_input');
            const dropdown = document.getElementById('merchant_dropdown');
            const searchInner = document.getElementById('merchant_search_inner');
            const optionsList = document.getElementById('merchant_options_list');
            
            let selectedValue = hiddenInput.value || '';
            let selectedName = input.value || 'Semua Merchant';
            
            // Show dropdown on input focus/click
            input.addEventListener('click', function(e) {
                e.preventDefault();
                dropdown.classList.remove('hidden');
                searchInner.value = ''; // Clear search when opening
                filterMerchants();
                setTimeout(() => {
                    searchInner.focus();
                }, 50);
            });
            
            input.addEventListener('focus', function(e) {
                e.preventDefault();
                input.blur(); // Prevent keyboard from opening on mobile
                dropdown.classList.remove('hidden');
                searchInner.value = '';
                filterMerchants();
                setTimeout(() => {
                    searchInner.focus();
                }, 50);
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    searchInner.value = ''; // Clear search when closing
                }
            });
            
            // Prevent dropdown from closing when clicking inside
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Filter merchants based on search
            function filterMerchants() {
                const searchTerm = searchInner.value.toLowerCase().trim();
                const options = optionsList.querySelectorAll('.merchant-option');
                
                options.forEach(option => {
                    const merchantName = option.getAttribute('data-name') || option.textContent.toLowerCase();
                    if (searchTerm === '' || merchantName.includes(searchTerm)) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
            
            // Search in inner input (real-time filtering)
            searchInner.addEventListener('input', function() {
                filterMerchants();
            });
            
            // Allow Enter key to select first visible option
            searchInner.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const visibleOptions = Array.from(optionsList.querySelectorAll('.merchant-option')).filter(
                        opt => opt.style.display !== 'none'
                    );
                    if (visibleOptions.length > 0) {
                        visibleOptions[0].click();
                    }
                }
            });
            
            // Handle option click
            optionsList.addEventListener('click', function(e) {
                const option = e.target.closest('.merchant-option');
                if (option) {
                    selectedValue = option.getAttribute('data-value') || '';
                    selectedName = option.textContent.trim();
                    hiddenInput.value = selectedValue;
                    input.value = selectedName;
                    dropdown.classList.add('hidden');
                    searchInner.value = '';
                    
                    // Highlight selected option
                    optionsList.querySelectorAll('.merchant-option').forEach(opt => {
                        opt.classList.remove('bg-orange-50', 'font-semibold');
                        if (opt.getAttribute('data-value') === selectedValue) {
                            opt.classList.add('bg-orange-50', 'font-semibold');
                        }
                    });
                }
            });
            
            // Highlight selected option on load
            setTimeout(() => {
                optionsList.querySelectorAll('.merchant-option').forEach(opt => {
                    opt.classList.remove('bg-orange-50', 'font-semibold');
                    if (opt.getAttribute('data-value') === selectedValue) {
                        opt.classList.add('bg-orange-50', 'font-semibold');
                    }
                });
            }, 100);
        })();
    </script>
</body>
</html>
