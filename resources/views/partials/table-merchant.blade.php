<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Quick Access</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">WA PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Email PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Daerah</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Detail Alamat</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Lat/Long</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link GMaps</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Dashboard</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Pelanggan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link History</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Logo</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="merchant-table-body">
                @forelse($merchants as $merchant)
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
                            {{ ($merchants->currentPage() - 1) * $merchants->perPage() + $loop->iteration }}
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
                        <td class="px-4 py-4 w-20 text-center text-sm font-semibold text-gray-900">{{ $merchant->nama_merchant }}</td>

                        {{-- Status Toggle --}}
                        <td class="px-4 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle Status">
                                <input type="checkbox" 
                                       data-merchant-id="{{ $merchant->id }}" 
                                       class="sr-only peer toggle-merchant-status" 
                                       {{ $merchant->is_active ? 'checked' : '' }} />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            </label>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">{{ $merchant->kategori ?? '-' }}</td>

                        {{-- Nama PIC --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">{{ $merchant->nama_pic ?? '-' }}</td>

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
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">{{ $merchant->daerah }}</td>

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

                        {{-- Link Google Maps --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->link_gmap)
                                <a href="{{ $merchant->link_gmap }}" 
                                   onclick="event.stopPropagation();"
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                    <span class="truncate max-w-xs">Link</span>
                                </a>
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
                                <a href="{{ route('link.pelanggan', $codePelanggan) }}" 
                                   onclick="event.stopPropagation();"
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <i class="fas fa-link text-xs"></i>
                                    <span class="truncate max-w-xs">Link</span>
                                </a>
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
                        <td colspan="15" class="px-4 py-4 text-center text-sm text-gray-500">
                            Belum ada data merchant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($merchants->hasPages())
    <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan <span class="font-semibold">{{ $merchants->firstItem() }}</span> hingga <span class="font-semibold">{{ $merchants->lastItem() }}</span> dari <span class="font-semibold">{{ $merchants->total() }}</span> data
        </div>
        
        <div class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($merchants->onFirstPage())
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $merchants->previousPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                @if ($page == $merchants->currentPage())
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
            @if ($merchants->hasMorePages())
                <a href="{{ $merchants->nextPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
<div class="hidden space-y-4" id="merchant-cards-container">
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
                    <p class="text-base font-semibold text-gray-900">{{ ($merchants->currentPage() - 1) * $merchants->perPage() + $loop->iteration }}</p>
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
                            @if($merchant->link_gmap)
                                <a href="{{ $merchant->link_gmap }}"
                                   onclick="event.stopPropagation();"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-map-marker-alt text-[11px]"></i>
                                    Buka Peta
                                </a>
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
                                <a href="{{ route('link.pelanggan', $codePelanggan) }}"
                                   onclick="event.stopPropagation();"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-link text-[11px]"></i>
                                    Buka Link
                                </a>
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
    @if($merchants->hasPages())
    <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col items-center justify-center space-y-3 rounded-xl">
        <div class="text-sm text-gray-600 text-center">
            Menampilkan <span class="font-semibold">{{ $merchants->firstItem() }}</span> hingga <span class="font-semibold">{{ $merchants->lastItem() }}</span> dari <span class="font-semibold">{{ $merchants->total() }}</span> data
        </div>
        
        <div class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($merchants->onFirstPage())
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $merchants->previousPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements (simplified for mobile) --}}
            @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                @if ($page == $merchants->currentPage())
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
            @if ($merchants->hasMorePages())
                <a href="{{ $merchants->nextPageUrl() }}" class="merchant-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
</script>
