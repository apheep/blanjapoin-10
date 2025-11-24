<!-- ======================= DESKTOP / TABLE VIEW (DINAMIS) ======================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Daerah</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">WA PIC</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Lat/Long</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link GMap</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Blanjapoin</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Pelanggan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Link History</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Logo</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="merchant-table-body">
                @forelse($merchants as $merchant)
                    <tr class="hover:bg-gray-50 transition-colors merchant-row cursor-pointer" data-category="{{ $merchant->kategori ?? 'All' }}"
                        onclick="window.location='{{ route('merchants.show', $merchant->id) }}'">

                        {{-- No --}}
                        <td class="px-4 py-4 w-20 text-center text-sm font-medium text-gray-900">
                            {{ ($merchants->currentPage() - 1) * $merchants->perPage() + $loop->iteration }}
                        </td>

                        {{-- Actions (ikon delete center) --}}
                        <td class="px-4 py-4 w-20 text-center">
                            <div class="flex items-center justify-center h-full">
                                <button type="button"
                                        onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', {{ json_encode($merchant->nama_merchant) }}, {{ $merchant->id }})"
                                        class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                        title="Hapus">
                                    <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                                </button>
                            </div>
                        </td>

                        {{-- Daerah --}}
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">{{ $merchant->daerah }}</td>

                        {{-- Merchant --}}
                        <td class="px-4 py-4 w-20 text-center text-sm font-semibold text-gray-900">{{ $merchant->nama_merchant }}</td>

                        {{-- Kategori --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">{{ $merchant->kategori ?? '-' }}</td>

                        {{-- Nama PIC --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">{{ $merchant->nama_pic ?? '-' }}</td>

                        {{-- WA PIC --}}
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            @if($merchant->wa_pic)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $merchant->wa_pic) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $merchant->wa_pic }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

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
                            @php
                                $codeDashboard = null;
                                if($merchant->link_blanjapoin) {
                                    // Remove http:// or https:// if present
                                    $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                                    // Extract code from link_blanjapoin (e.g., "blanjapoin.id/dash/unsur" -> "unsur")
                                    $parts = explode('/', $link);
                                    if(count($parts) >= 3 && $parts[1] === 'dash') {
                                        $codeDashboard = end($parts);
                                    }
                                }
                            @endphp
                            @if($codeDashboard)
                                <a href="{{ route('link.dashboard', $codeDashboard) }}" 
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
                            @php
                                $codePelanggan = null;
                                if($merchant->link_blanjapoin) {
                                    // Remove http:// or https:// if present
                                    $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                                    // Extract code from link_blanjapoin (e.g., "blanjapoin.id/dash/unsur" -> "unsur")
                                    $parts = explode('/', $link);
                                    if(count($parts) >= 3 && $parts[1] === 'dash') {
                                        $codePelanggan = end($parts);
                                    }
                                }
                            @endphp
                            @if($codePelanggan)
                                <a href="{{ route('link.pelanggan', $codePelanggan) }}" 
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
                            @php
                                $linkHistory = null;
                                if($merchant->link_blanjapoin) {
                                    // Remove http:// or https:// if present
                                    $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                                    // Extract code from link_blanjapoin (e.g., "blanjapoin.id/dash/unsur" -> "unsur")
                                    $parts = explode('/', $link);
                                    if(count($parts) >= 3 && $parts[1] === 'dash') {
                                        $code = end($parts);
                                        $linkHistory = $parts[0] . '/history/' . $code;
                                    }
                                }
                            @endphp
                            @if($linkHistory)
                                <a href="https://{{ $linkHistory }}" 
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
                        <td colspan="14" class="px-4 py-4 text-center text-sm text-gray-500">
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
                <a href="{{ $merchants->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($merchants->hasMorePages())
                <a href="{{ $merchants->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
<!-- ======================= MOBILE / CARD VIEW (DINAMIS) ======================= -->
<div class="md:hidden space-y-3" id="merchant-cards-container">
    @forelse($merchants as $merchant)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col space-y-3 merchant-row cursor-pointer" data-category="{{ $merchant->kategori ?? 'All' }}"
             onclick="window.location='{{ route('merchants.show', $merchant->id) }}'">
            {{-- Header dengan No dan Actions --}}
            <div class="flex items-start justify-between pb-3 border-b border-gray-200">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">No</p>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ ($merchants->currentPage() - 1) * $merchants->perPage() + $loop->iteration }}</p>
                </div>
                <div class="flex items-center">
                    <button type="button"
                            onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', {{ json_encode($merchant->nama_merchant) }}, {{ $merchant->id }})"
                            class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                            title="Hapus">
                        <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                    </button>
                </div>
            </div>

            {{-- Daerah --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Daerah</p>
                <p class="text-sm text-gray-700 mt-1">{{ $merchant->daerah }}</p>
            </div>

            {{-- Merchant --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Merchant</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</p>
            </div>

            {{-- Kategori --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Kategori</p>
                <p class="text-sm text-gray-700 mt-1">{{ $merchant->kategori ?? '-' }}</p>
            </div>

            {{-- Nama PIC --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Nama PIC</p>
                <p class="text-sm text-gray-700 mt-1">{{ $merchant->nama_pic ?? '-' }}</p>
            </div>

            {{-- WA PIC --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">WA PIC</p>
                @if($merchant->wa_pic)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $merchant->wa_pic) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline mt-1 inline-block">
                        {{ $merchant->wa_pic }}
                    </a>
                @else
                    <p class="text-sm text-gray-400 mt-1">-</p>
                @endif
            </div>

            {{-- Detail Daerah --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Detail Daerah</p>
                <p class="text-sm text-gray-700 mt-1">{{ $merchant->detail_daerah ?? '-' }}</p>
            </div>

            {{-- Lat/Long --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Koordinat</p>
                @if($merchant->lat && $merchant->long)
                    <p class="text-sm text-gray-700 mt-1">{{ $merchant->lat }}, {{ $merchant->long }}</p>
                @else
                    <p class="text-sm text-gray-400 mt-1">-</p>
                @endif
            </div>

            {{-- Link Google Maps --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Link Google Maps</p>
                @if($merchant->link_gmap)
                    <a href="{{ $merchant->link_gmap }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline mt-1 inline-flex items-center gap-1">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                        <span class="truncate max-w-full">Buka Peta</span>
                    </a>
                @else
                    <p class="text-sm text-gray-400 mt-1">-</p>
                @endif
            </div>

            {{-- Link Blanjapoin (Dashboard) --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Link Blanjapoin</p>
                @php
                    $codeDashboardMobile = null;
                    if($merchant->link_blanjapoin) {
                        // Remove http:// or https:// if present
                        $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                        // Extract code from link_blanjapoin (e.g., "blanjapoin.id/dash/unsur" -> "unsur")
                        $parts = explode('/', $link);
                        if(count($parts) >= 3 && $parts[1] === 'dash') {
                            $codeDashboardMobile = end($parts);
                        }
                    }
                @endphp
                @if($codeDashboardMobile)
                    <a href="{{ route('link.dashboard', $codeDashboardMobile) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-sm text-orange-600 hover:text-orange-800 hover:underline mt-1 inline-flex items-center gap-1">
                        <i class="fas fa-link text-xs"></i>
                        <span class="truncate max-w-full">Buka Link</span>
                    </a>
                @else
                    <p class="text-sm text-gray-400 mt-1">-</p>
                @endif
            </div>

            {{-- Link Pelanggan --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Link Pelanggan</p>
                @php
                    $codePelanggan = null;
                    if($merchant->link_blanjapoin) {
                        // Remove http:// or https:// if present
                        $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                        // Extract code from link_blanjapoin (e.g., "blanjapoin.id/dash/unsur" -> "unsur")
                        $parts = explode('/', $link);
                        if(count($parts) >= 3 && $parts[1] === 'dash') {
                            $codePelanggan = end($parts);
                        }
                    }
                @endphp
                @if($codePelanggan)
                    <a href="{{ route('link.pelanggan', $codePelanggan) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline mt-1 inline-flex items-center gap-1">
                        <i class="fas fa-link text-xs"></i>
                        <span class="truncate max-w-full">Buka Link</span>
                    </a>
                @else
                    <p class="text-sm text-gray-400 mt-1">-</p>
                @endif
            </div>

            {{-- Link History --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Link History</p>
                @php
                    $linkHistory = null;
                    if($merchant->link_blanjapoin) {
                        // Remove http:// or https:// if present
                        $link = preg_replace('#^https?://#', '', trim($merchant->link_blanjapoin));
                        // Extract code from link_blanjapoin (e.g., "blanjapoin.id/dash/unsur" -> "unsur")
                        $parts = explode('/', $link);
                        if(count($parts) >= 3 && $parts[1] === 'dash') {
                            $code = end($parts);
                            $linkHistory = $parts[0] . '/history/' . $code;
                        }
                    }
                @endphp
                @if($linkHistory)
                    <a href="https://{{ $linkHistory }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-sm text-purple-600 hover:text-purple-800 hover:underline mt-1 inline-flex items-center gap-1">
                        <i class="fas fa-link text-xs"></i>
                        <span class="truncate max-w-full">Buka Link</span>
                    </a>
                @else
                    <p class="text-sm text-gray-400 mt-1">-</p>
                @endif
            </div>

            {{-- Logo Merchant --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Logo Merchant</p>
                <div class="mt-2 flex items-center space-x-2">
                    @if($merchant->logo_merchant)
                        <button type="button" 
                                onclick="previewMerchantLogo('{{ asset('storage/' . $merchant->logo_merchant) }}', '{{ basename($merchant->logo_merchant) }}')"
                                class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors">
                            <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" 
                                 alt="{{ $merchant->nama_merchant }}" 
                                 class="h-full w-full object-cover">
                        </button>
                        <span class="text-sm text-gray-700 font-medium">{{ $merchant->nama_merchant }}</span>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
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
                <a href="{{ $merchants->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($merchants->hasMorePages())
                <a href="{{ $merchants->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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

