@php
    // Pastikan $merchants terdefinisi
    if (!isset($merchants) || !$merchants) {
        $merchants = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
    }
    
    // Pastikan pagination merchant mempertahankan tab=merchant dan keyword_page
    $merchantQueryParams = request()->query();
    $merchantQueryParams['tab'] = 'merchant';
    // Hapus parameter page generik jika ada, karena kita sudah menggunakan merchant_page
    unset($merchantQueryParams['page']);
    // Pastikan keyword_page tetap ada jika sebelumnya ada di URL
    if (request()->has('keyword_page')) {
        $merchantQueryParams['keyword_page'] = request()->get('keyword_page');
    }
    $merchantPaginator = $merchants->appends($merchantQueryParams);
@endphp
<div class="bg-white rounded-xl shadow overflow-hidden" style="overflow: visible; position: relative; isolation: isolate;">
    <!-- Loading Overlay -->
    <div id="merchant-table-loading" class="hidden absolute inset-0 bg-white bg-opacity-75 z-30 flex items-center justify-center rounded-xl">
        <div class="flex flex-col items-center gap-2">
            <div class="w-6 h-6 border-2 border-gray-300 border-t-orange-500 rounded-full animate-spin"></div>
            <span class="text-xs text-gray-600">Mengurutkan...</span>
        </div>
    </div>
    <div class="overflow-x-auto" style="overflow-y: visible; position: relative;">
        <table class="min-w-full divide-y divide-gray-200" id="merchant-table">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="id" onclick="sortMerchantColumn('id', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>No</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'id' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'id' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="id" onclick="sortMerchantColumn('id', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Actions</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'id' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'id' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="id" onclick="sortMerchantColumn('id', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Quick Access</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'id' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'id' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('merchant-table-body', 3, 'text')" data-sortable="true" data-column-index="3">
                        <div class="flex items-center justify-center gap-1">
                            <span>Merchant</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    @if(Auth::check() && Auth::user()->can_approve == 1)
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="is_active" onclick="sortMerchantColumn('is_active', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Status</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'is_active' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'is_active' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="link_status" onclick="sortMerchantColumn('link_status', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Link Status</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'link_status' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'link_status' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    @endif
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="start_date" onclick="sortMerchantColumn('start_date', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Start Periode</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'start_date' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'start_date' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="end_date" onclick="sortMerchantColumn('end_date', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>End Periode</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'end_date' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'end_date' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('merchant-table-body', 5, 'text')" data-sortable="true" data-column-index="5">
                        <div class="flex items-center justify-center gap-1">
                            <span>Kategori</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('merchant-table-body', 6, 'text')" data-sortable="true" data-column-index="6">
                        <div class="flex items-center justify-center gap-1">
                            <span>Nama PIC</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">WA PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Email PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('merchant-table-body', 9, 'text')" data-sortable="true" data-column-index="9">
                        <div class="flex items-center justify-center gap-1">
                            <span>Daerah</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Detail Alamat</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Lat/Long</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Radius (m)</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="total_trx" onclick="sortMerchantColumn('total_trx', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Total TRX</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'total_trx' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'total_trx' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="total_keyword" onclick="sortMerchantColumn('total_keyword', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Total Keyword</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'total_keyword' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'total_keyword' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="keyword_aktif" onclick="sortMerchantColumn('keyword_aktif', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Keyword Aktif</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'keyword_aktif' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'keyword_aktif' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-all duration-200 select-none merchant-sort-header" data-sort-column="link_gmaps" onclick="sortMerchantColumn('link_gmaps', event)">
                        <div class="flex items-center justify-center gap-1.5">
                            <span>Link GMaps</span>
                            <span class="sort-icon text-gray-400 text-[10px] relative">
                                <i class="fas fa-sort{{ request('sort_merchant') === 'link_gmaps' ? (request('sort_merchant_dir', 'asc') === 'asc' ? '-up' : '-down') : '' }} {{ request('sort_merchant') === 'link_gmaps' ? 'text-orange-500' : 'text-gray-400' }} transition-all duration-200"></i>
                                <i class="fas fa-spinner fa-spin sort-loading hidden absolute inset-0 text-orange-500"></i>
                            </span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Dashboard</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Pelanggan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link History</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Logo</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="merchant-table-body">
                @forelse($merchantPaginator as $merchant)
                    @php
                        $codeDashboard = null;
                        $codePelanggan = null;
                        $historyUrl = null;

                        if ($merchant->link_blanjapoin) {
                            $cleanLink = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                            $parts = explode('/', $cleanLink);
                            if (count($parts) >= 3 && $parts[1] === 'dash') {
                                $codeDashboard = end($parts);
                                $codePelanggan = $codeDashboard;
                                $historyUrl = route('link.history.all', $codePelanggan);
                            }
                        }
                    @endphp

                    <tr class="hover:bg-gray-50 transition-colors merchant-row bg-white" data-category="{{ $merchant->kategori ?? 'All' }}">

                        {{-- No --}}
                        <td class="px-4 py-4 w-20 text-center text-sm font-medium text-gray-900">
                            {{ ($merchantPaginator->currentPage() - 1) * $merchantPaginator->perPage() + $loop->iteration }}
                        </td>

                        {{-- Actions (ikon edit & delete center) --}}
                        <td class="px-4 py-4 w-20 text-center">
                            <div class="flex items-center justify-center gap-2 h-full">
                                <button type="button"
                                        id="merchant-edit-btn-{{ $merchant->id }}"
                                        data-merchant-edit-id="{{ $merchant->id }}"
                                        onclick="event.stopPropagation(); openEditMerchant({{ $merchant->id }}, {{ json_encode($merchant) }})"
                                        class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                        title="Edit">
                                    <i class="fas fa-edit text-blue-600 text-lg leading-none"></i>
                                </button>
                                <button type="button"
                                        onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', {{ json_encode($merchant->nama_merchant) }}, {{ $merchant->id }})"
                                        class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                        title="Hapus">
                                    <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                                </button>
                            </div>
                        </td>

                        <td class="px-4 py-4 w-32 text-center">
                            <div class="relative inline-flex justify-center merchant-quick-wrapper">
                                <button type="button" onclick="toggleMerchantQuickMenu(event, {{ $merchant->id }})"
                                        class="view-trigger inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold text-white bg-gradient-to-r from-[#F81611] via-[#F97316] to-[#F0B100] shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F0B100] transition duration-200 ease-out">
                                    <i class="fas fa-eye"></i>
                                    <span class="text-[11px]">View</span>
                                    <i class="fas fa-chevron-down view-trigger-chevron text-[10px]"></i>
                                </button>
                                <div id="merchant-quick-menu-{{ $merchant->id }}"
                                     class="merchant-quick-menu hidden absolute left-1/2 top-full mt-2 w-44 -translate-x-1/2 bg-white border border-gray-200 rounded-2xl py-1 z-50 opacity-0 -translate-y-1 scale-95 pointer-events-none transition-all duration-200 ease-out">
                                    <a href="{{ route('merchants.show', $merchant->id) }}"
                                       onclick="event.stopPropagation();"
                                       class="merchant-quick-option flex items-center gap-2 px-4 py-2 text-[12px] font-semibold text-gray-700">
                                        <i class="fas fa-clipboard-list text-[12px] text-gray-500"></i>
                                        Keyword
                                    </a>
                                    @if($historyUrl)
                                        <a href="{{ $historyUrl }}"
                                           onclick="event.stopPropagation();"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="merchant-quick-option flex items-center gap-2 px-4 py-2 w-full text-[12px] font-semibold text-gray-700 text-left">
                                            <i class="fas fa-clock text-[12px] text-gray-500"></i>
                                            History Transaksi
                                        </a>
                                    @else
                                        <button type="button"
                                                disabled
                                                class="merchant-quick-option flex items-center gap-2 px-4 py-2 w-full text-[12px] font-semibold text-gray-700 text-left cursor-not-allowed">
                                            <i class="fas fa-clock text-[12px] text-gray-500"></i>
                                            History Transaksi
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Merchant --}}
                        <td class="px-4 py-4 w-20 text-center text-sm font-semibold text-gray-900" data-sort-value="{{ strtolower($merchant->nama_merchant) }}">{{ $merchant->nama_merchant }}</td>

                        {{-- Status Toggle --}}
                        @if(Auth::check() && Auth::user()->can_approve == 1)
                        <td class="px-4 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle Status">
                                <input type="checkbox" 
                                       data-merchant-id="{{ $merchant->id }}" 
                                       class="sr-only peer toggle-merchant-status" 
                                       {{ $merchant->is_active ? 'checked' : '' }} />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            </label>
                        </td>
                        {{-- Link Status Toggle --}}
                        <td class="px-4 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle Link Status">
                                <input type="checkbox" 
                                       data-merchant-id="{{ $merchant->id }}" 
                                       class="sr-only peer toggle-link-status" 
                                       {{ $merchant->link_status ? 'checked' : '' }} />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            </label>
                        </td>
                        @endif
                        {{-- Start Periode --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->start_date)
                                {{ \Carbon\Carbon::parse($merchant->start_date)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        {{-- End Periode --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->end_date)
                                {{ \Carbon\Carbon::parse($merchant->end_date)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        {{-- Kategori --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700" data-sort-value="{{ strtolower($merchant->kategori ?? '-') }}">{{ $merchant->kategori ?? '-' }}</td>

                        {{-- Nama PIC --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700" data-sort-value="{{ strtolower($merchant->nama_pic ?? '-') }}">{{ $merchant->nama_pic ?? '-' }}</td>

                        {{-- WA PIC --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->wa_pic)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $merchant->wa_pic) }}" 
                                   onclick="event.stopPropagation();"
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $merchant->wa_pic }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif

                        {{-- Email PIC --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->email_pic)
                                <a href="mailto:{{ $merchant->email_pic }}" 
                                   onclick="event.stopPropagation();"
                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $merchant->email_pic }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        

                        {{-- Daerah --}}
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700" data-sort-value="{{ strtolower($merchant->daerah) }}">{{ $merchant->daerah }}</td>

                        {{-- Detail Daerah --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->detail_daerah)
                                <span class="truncate block max-w-xs" title="{{ $merchant->detail_daerah }}">
                                    {{ strlen($merchant->detail_daerah) > 30 ? substr($merchant->detail_daerah, 0, 30) . '...' : $merchant->detail_daerah }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Lat/Long --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->lat && $merchant->long)
                                <span class="text-xs">{{ $merchant->lat }}, {{ $merchant->long }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Radius --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->radius)
                                <span class="text-xs">{{ number_format($merchant->radius, 0, ',', '.') }} m</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Total TRX --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $merchant->total_trx ?? 0 }}</span>
                        </td>

                        {{-- Total Keyword --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ $merchant->total_keyword ?? 0 }}</span>
                        </td>

                        {{-- Keyword Aktif --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">{{ $merchant->keyword_aktif ?? 0 }}</span>
                        </td>

                        {{-- Link Google Maps --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @php
                                $gmapsLocations = [];
                                if ($merchant->link_gmaps && is_array($merchant->link_gmaps)) {
                                    $gmapsLocations = $merchant->link_gmaps;
                                } elseif ($merchant->link_gmap) {
                                    $gmapsLocations = [['link' => $merchant->link_gmap, 'radius' => $merchant->radius]];
                                }
                            @endphp

                            @if(count($gmapsLocations) > 0)
                                <div class="flex items-center justify-center gap-1">
                                    @foreach($gmapsLocations as $i => $location)
                                        <a href="{{ $location['link'] ?? '#' }}" 
                                           onclick="event.stopPropagation();"
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-50 rounded"
                                           title="Lokasi {{ $i + 1 }}{{ $location['radius'] ? ' (Radius: ' . $location['radius'] . 'm)' : '' }}">
                                            <i class="fas fa-map-marker-alt text-xs"></i>
                                            <span>{{ $i + 1 }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Link Blanjapoin (Dashboard) --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($codeDashboard)
                                <a href="{{ route('link.dashboard', $codeDashboard) }}" 
                                   onclick="event.stopPropagation();"
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-orange-600 hover:text-orange-800 hover:underline inline-flex items-center gap-1">
                                    <i class="fas fa-link text-xs"></i>
                                    <span class="truncate max-w-xs">Link</span>
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Link Pelanggan --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($codePelanggan)
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <a href="{{ route('link.pelanggan', $codePelanggan) }}" 
                                       onclick="event.stopPropagation();"
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                        <i class="fas fa-link text-xs"></i>
                                        <span class="truncate max-w-xs">Link</span>
                                    </a>
                                    <button type="button"
                                            onclick="event.stopPropagation(); openQRCodeModal('{{ route('link.pelanggan', $codePelanggan) }}', '{{ addslashes($merchant->nama_merchant) }}')"
                                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold text-white bg-gradient-to-r from-red-500 to-yellow-500 rounded-lg hover:from-red-600 hover:to-yellow-400 transition-all shadow-sm hover:shadow-md"
                                            title="Preview QR Code">
                                        <i class="fas fa-qrcode text-xs"></i>
                                        <span class="ml-1">QR</span>
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Link History --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($historyUrl)
                                <a href="{{ $historyUrl }}" 
                                   onclick="event.stopPropagation();"
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-purple-600 hover:text-purple-800 hover:underline inline-flex items-center gap-1">
                                    <i class="fas fa-link text-xs"></i>
                                    <span class="truncate max-w-xs">Link</span>
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Logo --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->logo_merchant)
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                       onclick="event.stopPropagation();"
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center h-10 w-10 rounded-lg overflow-hidden border border-gray-300 hover:border-blue-500 transition-colors hover:shadow-md">
                                        <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                             alt="{{ $merchant->nama_merchant }}" 
                                             class="h-full w-full object-cover">
                                    </a>
                                    <a href="{{ route('merchant.logo.download', $merchant->id) }}" 
                                       onclick="event.stopPropagation();"
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-colors"
                                       title="Download Logo">
                                        <i class="fas fa-download text-xs"></i>
                                    </a>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="px-4 py-4 text-center text-sm text-gray-500">
                            Belum ada data merchant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($merchantPaginator->hasPages())
    <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan <span class="font-semibold">{{ $merchantPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $merchantPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $merchantPaginator->total() }}</span> data
        </div>
        
        <div class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($merchantPaginator->onFirstPage())
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $merchantPaginator->previousPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($merchantPaginator->getUrlRange(1, $merchantPaginator->lastPage()) as $page => $url)
                @if ($page == $merchantPaginator->currentPage())
                    <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                        {{ $page }}
                    </button>
                @else
                    <a href="{{ $url }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($merchantPaginator->hasMorePages())
                <a href="{{ $merchantPaginator->nextPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
</div><!-- ======================= MOBILE / CARD VIEW (DINAMIS) ======================= -->
<div class="hidden space-y-4 relative" id="merchant-cards-container">
    <!-- Loading Overlay for Mobile -->
    <div id="merchant-cards-loading" class="hidden absolute inset-0 bg-white bg-opacity-75 z-30 flex items-center justify-center rounded-xl">
        <div class="flex flex-col items-center gap-2">
            <div class="w-6 h-6 border-2 border-gray-300 border-t-orange-500 rounded-full animate-spin"></div>
            <span class="text-xs text-gray-600">Mengurutkan...</span>
        </div>
    </div>
    @forelse($merchants as $merchant)
        @php
            $codeDashboardMobile = null;
            $codePelanggan = null;
            $linkHistory = null;
            if($merchant->link_blanjapoin) {
                $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                $parts = explode('/', $link);
                if(count($parts) >= 3 && $parts[1] === 'dash') {
                    $codeDashboardMobile = end($parts);
                    $codePelanggan = $codeDashboardMobile;
                    $linkHistory = route('link.history.all', $codeDashboardMobile);
                }
            }
        @endphp

        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden merchant-row transition duration-200 hover:shadow-2xl"
             data-category="{{ $merchant->kategori ?? 'All' }}">
            <div class="px-4 py-3 bg-gradient-to-br from-[#FDF7F1] to-white border-b border-gray-100 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] text-orange-700 uppercase tracking-wide">No</p>
                    <p class="text-base font-semibold text-gray-900">{{ ($merchantPaginator->currentPage() - 1) * $merchantPaginator->perPage() + $loop->iteration }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                            id="merchant-edit-btn-mobile-{{ $merchant->id }}"
                            data-merchant-edit-id="{{ $merchant->id }}"
                            onclick="event.stopPropagation(); openEditMerchant({{ $merchant->id }}, {{ json_encode($merchant) }})"
                            class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-white border border-blue-100 text-blue-600 shadow-sm hover:bg-blue-50 transition-colors"
                            title="Edit">
                        <i class="fas fa-edit text-base"></i>
                    </button>
                    <button type="button"
                            onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', {{ json_encode($merchant->nama_merchant) }}, {{ $merchant->id }})"
                            class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-white border border-red-100 text-red-600 shadow-sm hover:bg-red-50 transition-colors"
                            title="Hapus">
                        <i class="fas fa-trash text-base"></i>
                    </button>
                </div>
            </div>

            <div class="px-4 py-4 space-y-3">
                <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Merchant</p>
                        <p class="text-base font-semibold text-gray-900">{{ $merchant->nama_merchant }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Total TRX</p>
                        <p class="text-base font-semibold text-gray-900">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $merchant->total_trx ?? 0 }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Total Keyword</p>
                        <p class="text-base font-semibold text-gray-900">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ $merchant->total_keyword ?? 0 }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Keyword Aktif</p>
                        <p class="text-base font-semibold text-gray-900">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">{{ $merchant->keyword_aktif ?? 0 }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Status</p>
                        <label class="relative inline-flex items-center cursor-pointer mt-1" title="Toggle Status">
                            <input type="checkbox" 
                                   data-merchant-id="{{ $merchant->id }}" 
                                   class="sr-only peer toggle-merchant-status-mobile" 
                                   {{ $merchant->is_active ? 'checked' : '' }} />
                            <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                        </label>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Link Status</p>
                        <label class="relative inline-flex items-center cursor-pointer mt-1" title="Toggle Link Status">
                            <input type="checkbox" 
                                   data-merchant-id="{{ $merchant->id }}" 
                                   class="sr-only peer toggle-link-status-mobile" 
                                   {{ $merchant->link_status ? 'checked' : '' }} />
                            <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                        </label>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Kategori</p>
                        <p class="text-base text-gray-700">{{ $merchant->kategori ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Nama PIC</p>
                        <p class="text-base text-gray-700">{{ $merchant->nama_pic ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Daerah</p>
                        <p class="text-base text-gray-700">{{ $merchant->daerah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Start Periode</p>
                        <p class="text-base text-gray-700">
                            @if($merchant->start_date)
                                {{ \Carbon\Carbon::parse($merchant->start_date)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">End Periode</p>
                        <p class="text-base text-gray-700">
                            @if($merchant->end_date)
                                {{ \Carbon\Carbon::parse($merchant->end_date)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Detail Merchant</span>
                    <button type="button"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#D97706] rounded-full px-3 py-1.5 border border-[#D97706]/40 bg-white shadow-sm hover:bg-[#FDE68A]/50 transition">
                        <i class="fas fa-chevron-down text-[11px] transition-transform duration-200"></i>
                        <span>Lihat detail Merchant</span>
                    </button>
                </div>
                <div class="flex justify-end mt-2">
                    <div class="relative inline-flex merchant-quick-wrapper">
                        <button type="button"
                                onclick="toggleMerchantQuickMenu(event, 'mobile-{{ $merchant->id }}')"
                                class="view-trigger inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold text-white bg-gradient-to-r from-[#F81611] via-[#F97316] to-[#F0B100] shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F0B100] transition duration-200 ease-out">
                            <i class="fas fa-eye"></i>
                            <span class="text-[11px]">View</span>
                            <i class="fas fa-chevron-down view-trigger-chevron text-[10px]"></i>
                        </button>
                        <div id="merchant-quick-menu-mobile-{{ $merchant->id }}"
                             class="merchant-quick-menu hidden absolute left-1/2 top-full mt-2 w-44 -translate-x-1/2 bg-white border border-gray-200 rounded-2xl py-1 z-50 opacity-0 -translate-y-1 scale-95 pointer-events-none transition-all duration-200 ease-out">
                            <a href="{{ route('merchants.show', $merchant->id) }}"
                               onclick="event.stopPropagation();"
                               class="merchant-quick-option flex items-center gap-2 px-4 py-2 text-[12px] font-semibold text-gray-700">
                                <i class="fas fa-clipboard-list text-[12px] text-gray-500"></i>
                                Keyword
                            </a>
                            @if($linkHistory)
                                <a href="{{ $linkHistory }}"
                                   onclick="event.stopPropagation();"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="merchant-quick-option flex items-center gap-2 px-4 py-2 w-full text-[12px] font-semibold text-gray-700 text-left">
                                    <i class="fas fa-clock text-[12px] text-gray-500"></i>
                                    History Transaksi
                                </a>
                            @else
                                <button type="button"
                                        disabled
                                        class="merchant-quick-option flex items-center gap-2 px-4 py-2 w-full text-[12px] font-semibold text-gray-400 text-left cursor-not-allowed">
                                    <i class="fas fa-clock text-[12px] text-gray-300"></i>
                                    History Transaksi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="merchant-details-{{ $merchant->id }}" class="hidden space-y-3 border border-gray-100 rounded-2xl bg-[#fafafc] p-3 text-sm text-gray-700">
                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">WA PIC</p>
                        @if($merchant->wa_pic)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $merchant->wa_pic) }}"
                               onclick="event.stopPropagation();"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                                <i class="fab fa-whatsapp text-[11px]"></i>
                                {{ $merchant->wa_pic }}
                            </a>
                        @else
                            <p class="text-xs text-gray-400 mt-1">-</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Detail Alamat</p>
                        <p class="text-xs text-gray-700 mt-1">{{ Str::limit($merchant->detail_daerah, 80, '...') ?? '-' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Koordinat</p>
                            @if($merchant->lat && $merchant->long)
                                <p class="text-xs text-gray-700 mt-1">{{ $merchant->lat }}, {{ $merchant->long }}</p>
                            @else
                                <p class="text-xs text-gray-400 mt-1">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Link GMaps</p>
                            @php
                                $gmapsLocations = [];
                                if ($merchant->link_gmaps && is_array($merchant->link_gmaps)) {
                                    $gmapsLocations = $merchant->link_gmaps;
                                } elseif ($merchant->link_gmap) {
                                    $gmapsLocations = [['link' => $merchant->link_gmap, 'radius' => $merchant->radius]];
                                }
                            @endphp

                            @if(count($gmapsLocations) > 0)
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($gmapsLocations as $i => $location)
                                        <a href="{{ $location['link'] ?? '#' }}"
                                           onclick="event.stopPropagation();"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 px-2 py-1 bg-blue-50 rounded"
                                           title="Lokasi {{ $i + 1 }}{{ $location['radius'] ? ' (Radius: ' . $location['radius'] . 'm)' : '' }}">
                                            <i class="fas fa-map-marker-alt text-[11px]"></i>
                                            Lok {{ $i + 1 }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400 mt-1">-</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Link Blanjapoin</p>
                            @if($codeDashboardMobile)
                                <a href="{{ route('link.dashboard', $codeDashboardMobile) }}"
                                   onclick="event.stopPropagation();"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-orange-600 hover:text-orange-800">
                                    <i class="fas fa-link text-[11px]"></i>
                                    Buka Link
                                </a>
                            @else
                                <p class="text-xs text-gray-400 mt-1">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Link Pelanggan</p>
                            @if($codePelanggan)
                                <div class="mt-1 flex flex-col gap-2">
                                    <a href="{{ route('link.pelanggan', $codePelanggan) }}"
                                       onclick="event.stopPropagation();"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-link text-[11px]"></i>
                                        Buka Link
                                    </a>
                                    <button type="button"
                                            onclick="event.stopPropagation(); openQRCodeModal('{{ route('link.pelanggan', $codePelanggan) }}', '{{ addslashes($merchant->nama_merchant) }}')"
                                            class="inline-flex items-center justify-center px-2 py-1 text-[10px] font-semibold text-white bg-gradient-to-r from-red-500 to-yellow-500 rounded-lg hover:from-red-600 hover:to-yellow-400 transition-all shadow-sm">
                                        <i class="fas fa-qrcode text-[10px]"></i>
                                        <span class="ml-1">QR</span>
                                    </button>
                                </div>
                            @else
                                <p class="text-xs text-gray-400 mt-1">-</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Link History</p>
                            @if($linkHistory)
                                <a href="{{ $linkHistory }}"
                                   onclick="event.stopPropagation();"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-link text-[11px]"></i>
                                    Buka Link
                                </a>
                            @else
                                <p class="text-xs text-gray-400 mt-1">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-gray-500 font-semibold">Logo</p>
                            @if($merchant->logo_merchant)
                                <div class="mt-1 flex items-center gap-2">
                                    <a href="{{ asset('storage/' . $merchant->logo_merchant) }}"
                                       onclick="event.stopPropagation();"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center h-12 w-12 rounded-xl overflow-hidden border border-gray-200 hover:border-blue-500 transition-colors">
                                        <img src="{{ asset('storage/' . $merchant->logo_merchant) }}"
                                             alt="{{ $merchant->nama_merchant }}"
                                             class="h-full w-full object-cover">
                                    </a>
                                    <a href="{{ route('merchant.logo.download', $merchant->id) }}" 
                                       onclick="event.stopPropagation();"
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-colors"
                                       title="Download Logo">
                                        <i class="fas fa-download text-xs"></i>
                                    </a>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 mt-1 inline-block">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-center text-gray-500">Belum ada data merchant.</p>
    @endforelse
    
    <!-- Mobile Pagination -->
    @if($merchantPaginator->hasPages())
    <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col items-center justify-center space-y-3 rounded-xl">
        <div class="text-sm text-gray-600 text-center">
            Menampilkan <span class="font-semibold">{{ $merchantPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $merchantPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $merchantPaginator->total() }}</span> data
        </div>
        
        <div class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($merchantPaginator->onFirstPage())
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $merchantPaginator->previousPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements (simplified for mobile) --}}
            @foreach ($merchantPaginator->getUrlRange(1, $merchantPaginator->lastPage()) as $page => $url)
                @if ($page == $merchantPaginator->currentPage())
                    <button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">
                        {{ $page }}
                    </button>
                @else
                    <a href="{{ $url }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($merchantPaginator->hasMorePages())
                <a href="{{ $merchantPaginator->nextPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
<script>
    const MENU_ANIMATION_DELAY = 200;

    function toggleMerchantQuickMenu(event, identifier) {
        event.stopPropagation();
        const menu = document.getElementById(`merchant-quick-menu-${identifier}`);
        if (!menu) return;
        const isOpen = menu.classList.contains('quick-menu-open');
        closeAllQuickMenus();
        if (!isOpen) {
            openQuickMenu(menu);
        }
    }

    function openQuickMenu(menu) {
        menu.classList.remove('hidden');
        requestAnimationFrame(() => setMenuState(menu, true));
    }

    function closeQuickMenu(menu) {
        if (!menu.classList.contains('quick-menu-open')) {
            return;
        }
        setMenuState(menu, false);
        setTimeout(() => {
            if (!menu.classList.contains('quick-menu-open')) {
                menu.classList.add('hidden');
            }
        }, MENU_ANIMATION_DELAY);
    }

    function setMenuState(menu, isOpen) {
        menu.classList.toggle('opacity-100', isOpen);
        menu.classList.toggle('opacity-0', !isOpen);
        menu.classList.toggle('translate-y-0', isOpen);
        menu.classList.toggle('-translate-y-1', !isOpen);
        menu.classList.toggle('scale-100', isOpen);
        menu.classList.toggle('scale-95', !isOpen);
        menu.classList.toggle('pointer-events-auto', isOpen);
        menu.classList.toggle('pointer-events-none', !isOpen);
        menu.classList.toggle('quick-menu-open', isOpen);

        const wrapper = menu.closest('.merchant-quick-wrapper');
        const arrow = wrapper?.querySelector('.view-trigger-chevron');
        if (arrow) {
            arrow.classList.toggle('rotate-180', isOpen);
        }
    }

    function closeAllQuickMenus() {
        document.querySelectorAll('.merchant-quick-menu').forEach(menu => closeQuickMenu(menu));
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.merchant-quick-wrapper')) {
            closeAllQuickMenus();
        }
    });
// Update sort icons for merchant table
function updateMerchantSortIcons(column, order) {
    // Reset all sort icons
    document.querySelectorAll('.merchant-sort-header .sort-icon i:not(.sort-loading)').forEach(icon => {
        icon.className = 'fas fa-sort text-gray-400 transition-all duration-200';
    });
    
    // Update icon for active column (handle multiple headers with same column)
    const activeHeaders = document.querySelectorAll(`.merchant-sort-header[data-sort-column="${column}"]`);
    
    if (activeHeaders.length > 0 && order) {
        activeHeaders.forEach(header => {
            const icon = header.querySelector('.sort-icon i:not(.sort-loading)');
            if (icon) {
                icon.className = `fas fa-sort-${order === 'asc' ? 'up' : 'down'} text-orange-500 transition-all duration-200`;
            }
        });
    }
}

// Show loading state
function showMerchantSortLoading(column) {
    const headers = document.querySelectorAll(`.merchant-sort-header[data-sort-column="${column}"]`);
    headers.forEach(header => {
        const loadingIcon = header.querySelector('.sort-loading');
        const sortIcon = header.querySelector('.sort-icon i:not(.sort-loading)');
        if (loadingIcon && sortIcon) {
            sortIcon.classList.add('opacity-30');
            loadingIcon.classList.remove('hidden');
            header.classList.add('opacity-75', 'cursor-wait');
            header.style.pointerEvents = 'none';
        }
    });
    
    // Get container and elements
    const container = document.getElementById('merchant-table-container');
    const tableBody = container?.querySelector('#merchant-table-body');
    const cardsContainer = document.getElementById('merchant-cards-container');
    const loadingOverlay = document.getElementById('merchant-table-loading');
    const cardsLoadingOverlay = document.getElementById('merchant-cards-loading');
    
    // Store current height to prevent layout shift (like table-keyword)
    if (container) {
        const currentHeight = container.offsetHeight;
        container.style.minHeight = currentHeight + 'px';
    }
    
    // Show loading overlay immediately
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    if (cardsLoadingOverlay) {
        cardsLoadingOverlay.classList.remove('hidden');
    }
    
    // Smooth fade out transition (like table-keyword)
    if (tableBody) {
        tableBody.style.transition = 'opacity 0.2s ease';
        tableBody.style.opacity = '0';
    }
    if (cardsContainer) {
        cardsContainer.style.transition = 'opacity 0.2s ease';
        cardsContainer.style.opacity = '0';
    }
}

// Hide loading state
function hideMerchantSortLoading(column) {
    const headers = document.querySelectorAll(`.merchant-sort-header[data-sort-column="${column}"]`);
    headers.forEach(header => {
        const loadingIcon = header.querySelector('.sort-loading');
        const sortIcon = header.querySelector('.sort-icon i:not(.sort-loading)');
        if (loadingIcon && sortIcon) {
            sortIcon.classList.remove('opacity-30');
            loadingIcon.classList.add('hidden');
            header.classList.remove('opacity-75', 'cursor-wait');
            header.style.pointerEvents = '';
        }
    });
    
    // Get elements for smooth fade in
    const container = document.getElementById('merchant-table-container');
    const tableBody = container?.querySelector('#merchant-table-body');
    const cardsContainer = document.getElementById('merchant-cards-container');
    const loadingOverlay = document.getElementById('merchant-table-loading');
    const cardsLoadingOverlay = document.getElementById('merchant-cards-loading');
    
    // Smooth fade in transition (like table-keyword)
    if (tableBody) {
        tableBody.style.transition = 'opacity 0.2s ease';
        tableBody.style.opacity = '0';
        // Trigger reflow
        void tableBody.offsetWidth;
        // Fade in
        tableBody.style.opacity = '1';
    }
    if (cardsContainer) {
        cardsContainer.style.transition = 'opacity 0.2s ease';
        cardsContainer.style.opacity = '0';
        // Trigger reflow
        void cardsContainer.offsetWidth;
        // Fade in
        cardsContainer.style.opacity = '1';
    }
    
    // Hide loading overlay after transition completes (like table-keyword)
    setTimeout(() => {
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
        if (cardsLoadingOverlay) {
            cardsLoadingOverlay.classList.add('hidden');
        }
        // Remove min-height after transition
        if (container) {
            container.style.minHeight = '';
        }
    }, 200);
}

// Main AJAX sort function
function sortMerchantColumn(column, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Prevent multiple simultaneous sorts
    if (window.merchantSortInProgress) {
        return;
    }
    
    // Determine new sort direction
    let newSort = column;
    let newOrder = 'asc';
    
    if (currentMerchantSortColumn === column) {
        if (currentMerchantSortDir === 'asc') {
            newOrder = 'desc';
        } else {
            newSort = null;
            newOrder = null;
        }
    }
    
    currentMerchantSortColumn = newSort;
    currentMerchantSortDir = newOrder;
    
    updateMerchantSortIcons(column, newOrder);
    showMerchantSortLoading(column);
    window.merchantSortInProgress = true;
    
    // ⭐ TAMBAHKAN BAGIAN INI - Simpan posisi scroll
    const container = document.getElementById('merchant-table-container');
    const tableScrollContainer = container?.querySelector('.overflow-x-auto');
    
    // Simpan scroll vertikal (halaman)
    const scrollY = window.scrollY || window.pageYOffset;
    // Simpan scroll horizontal (tabel)
    const tableScrollX = tableScrollContainer ? tableScrollContainer.scrollLeft : 0;
    
    // Store di window agar bisa diakses di fetchMerchantTable
    window.merchantScrollPositions = {
        scrollY: scrollY,
        tableScrollX: tableScrollX
    };
    
    // Use existing fetchMerchantTable function
    if (typeof fetchMerchantTable === 'function') {
        fetchMerchantTable(buildMerchantSearchRequestUrl());
    }
}

// Initialize sort icons on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof currentMerchantSortColumn !== 'undefined' && currentMerchantSortColumn) {
        updateMerchantSortIcons(currentMerchantSortColumn, currentMerchantSortDir);
    }
});

// Make functions globally available
window.sortMerchantColumn = sortMerchantColumn;
window.updateMerchantSortIcons = updateMerchantSortIcons;
    // Toggle Merchant Status
    document.addEventListener('DOMContentLoaded', function() {
        // Attach toggle listeners for server-rendered checkboxes (desktop)
        document.querySelectorAll('.toggle-merchant-status').forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const merchantId = e.target.dataset.merchantId;
                if (!merchantId) return;
                toggleMerchantStatus(merchantId);
            });
        });

        // Attach toggle listeners for mobile checkboxes
        document.querySelectorAll('.toggle-merchant-status-mobile').forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const merchantId = e.target.dataset.merchantId;
                if (!merchantId) return;
                toggleMerchantStatus(merchantId);
            });
        });

        // Attach toggle listeners for link status (desktop)
        document.querySelectorAll('.toggle-link-status').forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const merchantId = e.target.dataset.merchantId;
                if (!merchantId) return;
                toggleLinkStatus(merchantId);
            });
        });

        // Attach toggle listeners for link status (mobile)
        document.querySelectorAll('.toggle-link-status-mobile').forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const merchantId = e.target.dataset.merchantId;
                if (!merchantId) return;
                toggleLinkStatus(merchantId);
            });
        });
    });

    // Function to toggle merchant status
    async function toggleMerchantStatus(merchantId) {
        try {
            const response = await fetch(`/api/merchants/${merchantId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Gagal memperbarui status');
            }

            // Update both desktop and mobile checkboxes
            const desktopCheckbox = document.querySelector(`.toggle-merchant-status[data-merchant-id="${merchantId}"]`);
            const mobileCheckbox = document.querySelector(`.toggle-merchant-status-mobile[data-merchant-id="${merchantId}"]`);
            
            if (desktopCheckbox) {
                desktopCheckbox.checked = data.is_active;
            }
            if (mobileCheckbox) {
                mobileCheckbox.checked = data.is_active;
            }

            console.log('Status merchant berhasil diperbarui');
        } catch (error) {
            console.error('Error toggling merchant status:', error);
            // Revert checkboxes on error
            const desktopCheckbox = document.querySelector(`.toggle-merchant-status[data-merchant-id="${merchantId}"]`);
            const mobileCheckbox = document.querySelector(`.toggle-merchant-status-mobile[data-merchant-id="${merchantId}"]`);
            if (desktopCheckbox) {
                desktopCheckbox.checked = !desktopCheckbox.checked;
            }
            if (mobileCheckbox) {
                mobileCheckbox.checked = !mobileCheckbox.checked;
            }
            alert('Gagal memperbarui status: ' + error.message);
        }
    }

    // Function to toggle link status
    async function toggleLinkStatus(merchantId) {
        try {
            const response = await fetch(`/api/merchants/${merchantId}/toggle-link-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Gagal memperbarui link status');
            }

            // Update both desktop and mobile checkboxes
            const desktopCheckbox = document.querySelector(`.toggle-link-status[data-merchant-id="${merchantId}"]`);
            const mobileCheckbox = document.querySelector(`.toggle-link-status-mobile[data-merchant-id="${merchantId}"]`);
            
            if (desktopCheckbox) {
                desktopCheckbox.checked = data.link_status;
            }
            if (mobileCheckbox) {
                mobileCheckbox.checked = data.link_status;
            }

            console.log('Link status merchant berhasil diperbarui');
        } catch (error) {
            console.error('Error toggling link status:', error);
            // Revert checkboxes on error
            const desktopCheckbox = document.querySelector(`.toggle-link-status[data-merchant-id="${merchantId}"]`);
            const mobileCheckbox = document.querySelector(`.toggle-link-status-mobile[data-merchant-id="${merchantId}"]`);
            if (desktopCheckbox) {
                desktopCheckbox.checked = !desktopCheckbox.checked;
            }
            if (mobileCheckbox) {
                mobileCheckbox.checked = !mobileCheckbox.checked;
            }
            alert('Gagal memperbarui link status: ' + error.message);
        }
    }

    // ── QR Code Modal ──────────────────────────────────────────────
    let _qrInstance = null;

    function openQRCodeModal(url, name) {
        const modal   = document.getElementById('qr-modal');
        const content = document.getElementById('qr-modal-content');
        const box     = document.getElementById('qr-box');
        const title   = document.getElementById('qr-name');
        const sub     = document.getElementById('qr-name-sub');
        const linkEl  = document.getElementById('qr-link');
        if (!modal || !box) return;

        title.textContent  = 'QR Code Pelanggan';
        sub.textContent    = name || 'Merchant';
        linkEl.href        = url;
        const linkSpan = document.getElementById('qr-link-text');
        if (linkSpan) linkSpan.textContent = url;
        box.innerHTML  = '';
        _qrInstance    = null;
        box.dataset.url  = url;
        box.dataset.name = name || 'Merchant';

        // Teleport to body so fixed positioning is never trapped by a parent transform/overflow
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        modal.style.cssText = 'display:flex !important; position:fixed !important; inset:0 !important; top:0 !important; left:0 !important; width:100% !important; height:100% !important; z-index:99999 !important; background:rgba(0,0,0,0.5) !important; align-items:center !important; justify-content:center !important; padding:1rem !important;';
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });

        setTimeout(() => {
            if (typeof QRCodeStyling === 'undefined') {
                box.innerHTML = '<p class="text-red-500 text-sm">Library tidak tersedia, refresh halaman.</p>';
                return;
            }
            _qrInstance = new QRCodeStyling({
                width:  240,
                height: 240,
                type:   'canvas',
                data:   url,
                dotsOptions:          { color: '#111827', type: 'dots' },
                cornersSquareOptions: { color: '#111827', type: 'extra-rounded' },
                cornersDotOptions:    { color: '#111827', type: 'dot' },
                backgroundOptions:    { color: '#ffffff' },
                qrOptions:            { errorCorrectionLevel: 'H' }
            });
            _qrInstance.append(box);
        }, 80);
    }

    function closeQRCodeModal() {
        const modal   = document.getElementById('qr-modal');
        const content = document.getElementById('qr-modal-content');
        if (!modal) return;
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.style.cssText = 'display:none !important;';
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('qr-box').innerHTML = '';
            _qrInstance = null;
        }, 200);
    }

    function downloadQR() {
        const box = document.getElementById('qr-box');
        const src = box ? box.querySelector('canvas') : null;
        if (!src) { alert('QR belum siap, tunggu sebentar.'); return; }

        const pad = 32, txtH = 52;
        const W   = src.width + pad * 2;
        const H   = src.height + pad * 2 + txtH;
        const cv  = document.createElement('canvas');
        cv.width  = W; cv.height = H;
        const ctx = cv.getContext('2d');

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, W, H);
        ctx.drawImage(src, pad, pad);

        const url = box.dataset.url || '';
        let disp  = url;
        ctx.font      = '600 13px "Segoe UI", sans-serif';
        ctx.textAlign = 'center';
        ctx.fillStyle = '#6b7280';
        while (ctx.measureText(disp).width > W - 24 && disp.length > 8)
            disp = disp.slice(0, -4) + '...';
        ctx.fillText(disp, W / 2, src.height + pad + txtH / 2 + 4);

        const a   = document.createElement('a');
        const nm  = (box.dataset.name || 'merchant').replace(/\s+/g, '-');
        const dt  = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        a.download = `qr-${nm}-${dt}.png`;
        a.href     = cv.toDataURL('image/png', 1);
        a.click();
    }

    function printQR() {
        const box = document.getElementById('qr-box');
        const src = box ? box.querySelector('canvas') : null;
        if (!src) { alert('QR belum siap, tunggu sebentar.'); return; }
        const nm  = document.getElementById('qr-name-sub').textContent;
        const url = box.dataset.url || '';
        const w   = window.open('', '_blank');
        if (!w) { alert('Pop-up diblokir browser.'); return; }
        w.document.write(`<!DOCTYPE html><html><head><title>Print QR \u2013 ${nm}</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
        <style>*{box-sizing:border-box}body{margin:0;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f3f4f6;font-family:Poppins,sans-serif}.card{background:#fff;border-radius:20px;padding:36px;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,.10)}h2{margin:0 0 20px;font-size:20px;color:#111}img{border-radius:12px}.url{margin-top:16px;font-size:12px;font-weight:600;color:#6b7280;word-break:break-all}.sub{margin-top:8px;font-size:11px;color:#9ca3af}</style>
        </head><body><div class="card"><h2>${nm}</h2><img src="${src.toDataURL()}" width="260" height="260"><p class="url">${url}</p><p class="sub">Scan untuk akses link pelanggan</p></div></body></html>`);
        w.document.close();
        setTimeout(() => w.print(), 400);
    }

    function copyQRLink() {
        const url = document.getElementById('qr-box')?.dataset.url || '';
        navigator.clipboard.writeText(url).then(() => {
            const btn = document.getElementById('qr-btn-copy');
            if (!btn) return;
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check mr-1"></i>Tersalin!';
            btn.classList.add('!bg-green-50', '!text-green-700', '!border-green-300');
            setTimeout(() => {
                btn.innerHTML = orig;
                btn.classList.remove('!bg-green-50', '!text-green-700', '!border-green-300');
            }, 2000);
        }).catch(() => {
            const ta = document.createElement('textarea');
            ta.value = url; document.body.appendChild(ta); ta.select();
            document.execCommand('copy'); document.body.removeChild(ta);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                const m = document.getElementById('qr-modal');
                if (m && m.style.display !== 'none') closeQRCodeModal();
            }
        });
    });
</script>

<!-- QR Code Modal -->
<div id="qr-modal" class="fixed z-[9999] hidden" style="display:none; inset:0; top:0; left:0; right:0; bottom:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:1rem;">
    <div class="w-full h-full absolute inset-0" onclick="closeQRCodeModal()"></div>
    <div id="qr-modal-content" class="relative z-10 bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-qrcode text-orange-600 text-lg"></i>
                </div>
                <div>
                    <h3 id="qr-name" class="text-lg font-semibold text-gray-900">QR Code Pelanggan</h3>
                    <p id="qr-name-sub" class="text-sm text-gray-500"></p>
                </div>
            </div>
            <button onclick="closeQRCodeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="flex flex-col items-center">
                <!-- QR code -->
                <div class="p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div id="qr-box" class="inline-block"></div>
                </div>
                <!-- link below QR -->
                <a id="qr-link" href="#" target="_blank" rel="noopener noreferrer"
                   class="mt-4 w-full flex items-center justify-center gap-2 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-4 py-2.5 rounded-lg break-all transition-colors no-underline">
                    <i class="fas fa-link text-xs text-gray-400 flex-shrink-0"></i>
                    <span id="qr-link-text" class="break-all text-xs"></span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl border-t border-gray-100">
            <button id="qr-btn-copy" onclick="copyQRLink()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                <i class="fas fa-copy text-xs"></i> Salin Link
            </button>
            <div class="flex gap-2">
                <button onclick="printQR()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <i class="fas fa-print text-xs"></i> Print
                </button>
                <button onclick="downloadQR()"
                        class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-download text-xs"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
