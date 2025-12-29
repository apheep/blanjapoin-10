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
        <table class="min-w-full divide-y divide-gray-200 transition-opacity duration-300" id="merchant-table">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Quick Access</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('merchant-table-body', 3, 'text')" data-sortable="true" data-column-index="3">
                        <div class="flex items-center justify-center gap-1">
                            <span>Merchant</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    @if(Auth::check() && Auth::user()->can_approve == 1)
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Status</th>
                    @endif
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Start Periode</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">End Periode</th>
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
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link GMaps</th>
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
                                            onclick="event.stopPropagation(); openQRCodeModal('{{ route('link.pelanggan', $codePelanggan) }}', '{{ $merchant->nama_merchant }}')"
                                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold text-white bg-gradient-to-r from-red-500 to-yellow-600 rounded-lg hover:from-red-600 hover:to-yellow-500 transition-colors shadow-sm hover:shadow-md"
                                            title="Generate QR Code">
                                        <i class="fas fa-qrcode text-xs"></i>
                                        <span class="ml-1">QRCode</span>
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
                                <a href="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                   onclick="event.stopPropagation();"
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center h-10 w-10 rounded-lg overflow-hidden border border-gray-300 hover:border-blue-500 transition-colors hover:shadow-md">
                                    <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                         alt="{{ $merchant->nama_merchant }}" 
                                         class="h-full w-full object-cover">
                                </a>
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
                                            onclick="event.stopPropagation(); openQRCodeModal('{{ route('link.pelanggan', $codePelanggan) }}', '{{ $merchant->nama_merchant }}')"
                                            class="inline-flex items-center justify-center px-2 py-1 text-[10px] font-semibold text-white bg-gradient-to-r from-red-500 to-yellow-400 rounded-lg hover:from-red-600 hover:to-yellow-500 transition-colors shadow-sm">
                                        <i class="fas fa-qrcode text-[10px]"></i>
                                        <span class="ml-1">QRCode</span>
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
                                <a href="{{ asset('storage/' . $merchant->logo_merchant) }}"
                                   onclick="event.stopPropagation();"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="mt-1 inline-flex items-center justify-center h-12 w-12 rounded-xl overflow-hidden border border-gray-200 hover:border-blue-500 transition-colors">
                                    <img src="{{ asset('storage/' . $merchant->logo_merchant) }}"
                                         alt="{{ $merchant->nama_merchant }}"
                                         class="h-full w-full object-cover">
                                </a>
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

    // Merchant table uses the same global sortTable function defined in table-keyword.blade.php
    // No additional code needed here as the function handles both tables
    
    // Update sort icons for merchant table
    function updateMerchantSortIcons(column, order) {
        // Reset all sort icons (except loading spinners)
        document.querySelectorAll('.merchant-sort-header .sort-icon i:not(.sort-loading)').forEach(icon => {
            icon.className = 'fas fa-sort text-gray-400 transition-all duration-200';
        });
        
        // Update icon for active column
        const activeHeader = document.querySelector(`.merchant-sort-header[data-sort-column="${column}"]`);
        
        if (activeHeader && order) {
            const icon = activeHeader.querySelector('.sort-icon i:not(.sort-loading)');
            if (icon) {
                icon.className = `fas fa-sort-${order === 'asc' ? 'up' : 'down'} text-orange-500 transition-all duration-200`;
            }
        } else if (activeHeader && !order) {
            const icon = activeHeader.querySelector('.sort-icon i:not(.sort-loading)');
            if (icon) {
                icon.className = 'fas fa-sort text-gray-400 transition-all duration-200';
            }
        }
    }
    
    // Show loading state for sort column
    function showSortLoading(column) {
        const header = document.querySelector(`.merchant-sort-header[data-sort-column="${column}"]`);
        if (header) {
            const loadingIcon = header.querySelector('.sort-loading');
            const sortIcon = header.querySelector('.sort-icon i:not(.sort-loading)');
            if (loadingIcon && sortIcon) {
                sortIcon.classList.add('opacity-30');
                loadingIcon.classList.remove('hidden');
                header.classList.add('opacity-75', 'cursor-wait');
                header.style.pointerEvents = 'none';
            }
        }
        
        // Show table loading overlay (desktop)
        const loadingOverlay = document.getElementById('merchant-table-loading');
        const table = document.getElementById('merchant-table');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
        }
        if (table) {
            table.style.opacity = '0.6';
        }
        
        // Show cards loading overlay (mobile)
        const cardsLoadingOverlay = document.getElementById('merchant-cards-loading');
        const cardsContainer = document.getElementById('merchant-cards-container');
        if (cardsLoadingOverlay) {
            cardsLoadingOverlay.classList.remove('hidden');
        }
        if (cardsContainer) {
            cardsContainer.style.opacity = '0.6';
        }
    }
    
    // Hide loading state for sort column
    function hideSortLoading(column) {
        const header = document.querySelector(`.merchant-sort-header[data-sort-column="${column}"]`);
        if (header) {
            const loadingIcon = header.querySelector('.sort-loading');
            const sortIcon = header.querySelector('.sort-icon i:not(.sort-loading)');
            if (loadingIcon && sortIcon) {
                sortIcon.classList.remove('opacity-30');
                loadingIcon.classList.add('hidden');
                header.classList.remove('opacity-75', 'cursor-wait');
                header.style.pointerEvents = '';
            }
        }
        
        // Hide table loading overlay (desktop)
        const loadingOverlay = document.getElementById('merchant-table-loading');
        const table = document.getElementById('merchant-table');
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
        if (table) {
            table.style.opacity = '1';
        }
        
        // Hide cards loading overlay (mobile)
        const cardsLoadingOverlay = document.getElementById('merchant-cards-loading');
        const cardsContainer = document.getElementById('merchant-cards-container');
        if (cardsLoadingOverlay) {
            cardsLoadingOverlay.classList.add('hidden');
        }
        if (cardsContainer) {
            cardsContainer.style.opacity = '1';
        }
    }
    
    // AJAX sort function for merchant calculated columns (like best offer)
    function sortMerchantColumn(column, event) {
        // Prevent default behavior if event is provided
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Prevent multiple simultaneous sorts
        if (window.merchantSortInProgress) {
            return;
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort_merchant');
        const currentOrder = urlParams.get('sort_merchant_dir') || 'asc';
        
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
            urlParams.set('sort_merchant', newSort);
            urlParams.set('sort_merchant_dir', newOrder);
        } else {
            urlParams.delete('sort_merchant');
            urlParams.delete('sort_merchant_dir');
        }
        
        // Update icons immediately
        updateMerchantSortIcons(column, newOrder);
        
        // Update URL without reload
        window.history.pushState({}, '', '?' + urlParams.toString());
        
        // Show loading state
        showSortLoading(column);
        window.merchantSortInProgress = true;
        
        // Get container elements
        const container = document.getElementById('merchant-table-container');
        const tableBody = container?.querySelector('#merchant-table-body') || container?.querySelector('tbody');
        const cardsContainer = document.getElementById('merchant-cards-container');
        // Get the overflow-x-auto div that contains the table (for horizontal scroll)
        const tableScrollContainer = container?.querySelector('.overflow-x-auto');
        
        if (container) {
            // Store current height to prevent layout shift (like best offer)
            const currentHeight = container.offsetHeight;
            container.style.minHeight = currentHeight + 'px';
            
            // Store scroll positions BEFORE making request (to maintain after update)
            const scrollX = window.scrollX || window.pageXOffset;
            const scrollY = window.scrollY || window.pageYOffset;
            const tableScrollX = tableScrollContainer ? tableScrollContainer.scrollLeft : 0;
            
            // Build request URL (like spesial-promo-form)
            let requestUrl;
            if (typeof buildMerchantSearchRequestUrl === 'function') {
                // Temporarily store sort params in global scope
                window.currentMerchantSort = newSort;
                window.currentMerchantSortDir = newOrder;
                requestUrl = buildMerchantSearchRequestUrl();
                // Clean up after a short delay
                setTimeout(() => {
                    delete window.currentMerchantSort;
                    delete window.currentMerchantSortDir;
                }, 100);
            } else {
                // Fallback: build URL manually (like spesial-promo-form)
                const baseUrl = '{{ route("merchants.search") }}';
                requestUrl = baseUrl + '?' + urlParams.toString();
            }
            
            // Make AJAX request (like spesial-promo-form)
            fetch(requestUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse HTML response (like spesial-promo-form)
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.getElementById('merchant-table-body');
                const newCardsContainer = doc.getElementById('merchant-cards-container');
                // Find pagination container (like spesial-promo-form) - desktop pagination
                const newPaginationContainer = doc.querySelector('.bg-white.px-4.py-4.border-t.flex.items-center.justify-between');
                // Find mobile pagination container
                const newMobilePaginationContainer = doc.querySelector('.bg-white.px-4.py-4.border-t.flex.flex-col.items-center.justify-center.space-y-3.rounded-xl');
                
                if (newTableBody || newCardsContainer) {
                    // Smooth transition (like spesial-promo-form)
                    if (tableBody) {
                        tableBody.style.opacity = '0';
                        tableBody.style.transition = 'opacity 0.2s';
                    }
                    if (cardsContainer) {
                        cardsContainer.style.opacity = '0';
                        cardsContainer.style.transition = 'opacity 0.2s';
                    }
                    
                    setTimeout(() => {
                        // Replace table body content (like spesial-promo-form)
                        if (newTableBody && tableBody) {
                            tableBody.innerHTML = newTableBody.innerHTML;
                        }
                        
                        // Replace cards container if exists
                        if (newCardsContainer && cardsContainer) {
                            cardsContainer.innerHTML = newCardsContainer.innerHTML;
                        }
                        
                        // Replace pagination container if exists (like spesial-promo-form)
                        if (newPaginationContainer) {
                            const currentPaginationContainer = container.querySelector('.bg-white.px-4.py-4.border-t.flex.items-center.justify-between');
                            if (currentPaginationContainer) {
                                currentPaginationContainer.outerHTML = newPaginationContainer.outerHTML;
                            }
                        }
                        
                        // Also handle mobile pagination if exists
                        if (newMobilePaginationContainer && cardsContainer) {
                            const currentMobilePagination = cardsContainer.parentElement?.querySelector('.bg-white.px-4.py-4.border-t.flex.flex-col.items-center.justify-center.space-y-3.rounded-xl');
                            if (currentMobilePagination) {
                                currentMobilePagination.outerHTML = newMobilePaginationContainer.outerHTML;
                            }
                        }
                        
                        // Restore scroll positions immediately (BEFORE re-attaching listeners)
                        window.scrollTo(scrollX, scrollY);
                        
                        // Restore horizontal scroll position of table container
                        const newTableScrollContainer = container.querySelector('.overflow-x-auto');
                        if (newTableScrollContainer && tableScrollX > 0) {
                            newTableScrollContainer.scrollLeft = tableScrollX;
                        }
                        
                        // Re-attach event listeners (like spesial-promo-form)
                        if (typeof attachMerchantPaginationHandlers === 'function') {
                            attachMerchantPaginationHandlers();
                        }
                        if (typeof updateMerchantUrlState === 'function') {
                            updateMerchantUrlState();
                        }
                        if (typeof reapplyMerchantCategoryFilter === 'function') {
                            reapplyMerchantCategoryFilter();
                        }
                        
                        // Re-attach toggle listeners for status checkboxes
                        document.querySelectorAll('.toggle-merchant-status').forEach(toggle => {
                            toggle.addEventListener('change', (e) => {
                                const merchantId = e.target.dataset.merchantId;
                                if (!merchantId) return;
                                toggleMerchantStatus(merchantId);
                            });
                        });
                        
                        document.querySelectorAll('.toggle-merchant-status-mobile').forEach(toggle => {
                            toggle.addEventListener('change', (e) => {
                                const merchantId = e.target.dataset.merchantId;
                                if (!merchantId) return;
                                toggleMerchantStatus(merchantId);
                            });
                        });

                        // Re-attach toggle listeners for link status checkboxes
                        document.querySelectorAll('.toggle-link-status').forEach(toggle => {
                            toggle.addEventListener('change', (e) => {
                                const merchantId = e.target.dataset.merchantId;
                                if (!merchantId) return;
                                toggleLinkStatus(merchantId);
                            });
                        });
                        
                        document.querySelectorAll('.toggle-link-status-mobile').forEach(toggle => {
                            toggle.addEventListener('change', (e) => {
                                const merchantId = e.target.dataset.merchantId;
                                if (!merchantId) return;
                                toggleLinkStatus(merchantId);
                            });
                        });
                        
                        // Restore opacity
                        const updatedTableBody = container.querySelector('#merchant-table-body') || container.querySelector('tbody');
                        const updatedCardsContainer = document.getElementById('merchant-cards-container');
                        if (updatedTableBody) {
                            updatedTableBody.style.opacity = '1';
                        }
                        if (updatedCardsContainer) {
                            updatedCardsContainer.style.opacity = '1';
                        }
                        
                        // Remove min-height after transition (like spesial-promo-form)
                        setTimeout(() => {
                            container.style.minHeight = '';
                            // Ensure scroll positions are maintained after all updates
                            window.scrollTo(scrollX, scrollY);
                            if (newTableScrollContainer && tableScrollX > 0) {
                                newTableScrollContainer.scrollLeft = tableScrollX;
                            }
                        }, 300);
                        
                        // Hide loading and finalize icons
                        hideSortLoading(column);
                        window.merchantSortInProgress = false;
                        updateMerchantSortIcons(column, newOrder);
                    }, 200);
                } else {
                    // Fallback: reload if parsing fails (like spesial-promo-form)
                    hideSortLoading(column);
                    window.merchantSortInProgress = false;
                    container.style.minHeight = '';
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideSortLoading(column);
                window.merchantSortInProgress = false;
                if (container) {
                    container.style.minHeight = '';
                }
                // Fallback: reload on error (like spesial-promo-form)
                window.location.reload();
            });
        } else {
            hideSortLoading(column);
            window.merchantSortInProgress = false;
        }
    }

    // QR Code Modal Functions
    function openQRCodeModal(linkUrl, merchantName) {
        const modal = document.getElementById('qrcode-modal');
        const overlay = document.getElementById('qrcode-modal-overlay');
        const qrContainer = document.getElementById('qrcode-container');
        const merchantTitle = document.getElementById('qrcode-merchant-name');
        
        if (!modal || !overlay || !qrContainer) return;
        
        // Set merchant name
        if (merchantTitle) {
            merchantTitle.textContent = merchantName || 'Merchant';
        }
        
        // Clear previous QR code
        qrContainer.innerHTML = '';
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Generate QR code with responsive size
        setTimeout(() => {
            if (typeof QRCode !== 'undefined') {
                // Determine QR code size based on screen width
                const isMobile = window.innerWidth < 640; // sm breakpoint
                const qrSize = isMobile ? 150 : 200;
                
                new QRCode(qrContainer, {
                    text: linkUrl,
                    width: qrSize,
                    height: qrSize,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            } else {
                qrContainer.innerHTML = '<p class="text-red-500 text-sm">QR Code library tidak tersedia. Silakan refresh halaman.</p>';
            }
        }, 100);
        
        // Store link URL for download
        qrContainer.dataset.linkUrl = linkUrl;
        qrContainer.dataset.merchantName = merchantName || 'Merchant';
    }

    function closeQRCodeModal() {
        const modal = document.getElementById('qrcode-modal');
        const qrContainer = document.getElementById('qrcode-container');
        
        if (!modal) return;
        
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Clear QR code
        if (qrContainer) {
            qrContainer.innerHTML = '';
        }
    }

    function downloadQRCode() {
        const qrContainer = document.getElementById('qrcode-container');
        if (!qrContainer) return;
        
        const canvas = qrContainer.querySelector('canvas');
        if (!canvas) {
            alert('QR Code belum siap, silakan tunggu sebentar');
            return;
        }
        
        const link = document.createElement('a');
        const merchantName = qrContainer.dataset.merchantName || 'Merchant';
        const date = new Date().toISOString().split('T')[0].replace(/-/g, '');
        link.download = `qrcode-${merchantName.replace(/\s+/g, '-')}-${date}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    }

    function printQRCode() {
        const qrContainer = document.getElementById('qrcode-container');
        const merchantTitle = document.getElementById('qrcode-merchant-name');
        
        if (!qrContainer) return;
        
        const canvas = qrContainer.querySelector('canvas');
        if (!canvas) {
            alert('QR Code belum siap, silakan tunggu sebentar');
            return;
        }

        const dataUrl = canvas.toDataURL('image/png');
        const merchantName = merchantTitle ? merchantTitle.textContent : 'Merchant';
        const printWindow = window.open('', '_blank');
        
        if (!printWindow) {
            alert('Tidak dapat membuka jendela baru untuk cetak.');
            return;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Print QR Code - ${merchantName}</title>
                <style>
                    body {
                        margin: 0;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                        font-family: 'Poppins', sans-serif;
                    }
                    h1 {
                        margin-bottom: 20px;
                        color: #333;
                    }
                    img {
                        border: 3px solid #14b8a6;
                        border-radius: 10px;
                        padding: 10px;
                        background: white;
                    }
                </style>
            </head>
            <body>
                <h1>${merchantName}</h1>
                <img src="${dataUrl}" alt="QR Code" width="300" height="300" />
                <p style="margin-top: 20px; color: #666;">Scan untuk akses link pelanggan</p>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
        }, 250);
    }

    // Close modal when clicking overlay
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('qrcode-modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', closeQRCodeModal);
        }
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('qrcode-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeQRCodeModal();
                }
            }
        });
    });
</script>

<!-- QR Code Modal -->
<div id="qrcode-modal" class="fixed inset-0 z-[9999] hidden">
    <div id="qrcode-modal-overlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300"></div>
    
    <div class="absolute inset-0 flex items-center justify-center p-3 md:p-4">
        <div class="relative bg-white rounded-2xl md:rounded-3xl shadow-2xl max-w-md w-full p-4 md:p-6 lg:p-8 transform transition-all duration-300 max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="closeQRCodeModal()" class="absolute top-2 right-2 md:top-4 md:right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Header -->
            <div class="text-center mb-4 md:mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-red-500 to-yellow-400 rounded-xl md:rounded-2xl mb-3 md:mb-4">
                    <i class="fas fa-qrcode text-lg md:text-2xl text-white"></i>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 md:mb-2">QR Code Pelanggan</h3>
                <p id="qrcode-merchant-name" class="text-xs md:text-sm text-gray-600 font-semibold"></p>
            </div>
            
            <!-- QR Code Display -->
            <div class="flex justify-center mb-4 md:mb-6">
                <div class="p-2 md:p-4 bg-white rounded-xl md:rounded-2xl shadow-lg border-2 border-gray-100">
                    <div id="qrcode-container" class="inline-block"></div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-2 md:gap-3">
                <button onclick="downloadQRCode()" 
                        class="flex-1 bg-gradient-to-r from-red-500 to-yellow-400 text-white hover:from-red-600 hover:to-yellow-500 py-2.5 md:py-3 px-3 md:px-4 rounded-lg md:rounded-xl text-sm md:text-base font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-download text-sm md:text-base"></i>
                    <span>Download</span>
                </button>
                <button onclick="printQRCode()" 
                        class="flex-1 bg-gray-100 text-gray-700 hover:bg-gray-200 py-2.5 md:py-3 px-3 md:px-4 rounded-lg md:rounded-xl text-sm md:text-base font-semibold transition-all duration-200 flex items-center justify-center gap-2 border border-gray-300">
                    <i class="fas fa-print text-sm md:text-base"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- QRCode.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
