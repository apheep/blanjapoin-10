<!-- ======================= DESKTOP / TABLE VIEW (DINAMIS) ======================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 w-20 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 w-20 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"> Actions</th>
                    <th class="px-4 py-3 w-20 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Daerah</th>
                    <th class="px-4 py-3 w-20 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                    <th class="px-4 py-3 w-20 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>

                    <th class="px-4 py-3 w-20 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Logo Merchant</th>
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
                                        onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', '{{ $merchant->nama_merchant }}', {{ $merchant->id }})"
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
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">{{ $merchant->kategori ?? '-' }}</td>

                        {{-- logo --}}
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">
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
                        <td colspan="11" class="px-4 py-4 text-center text-sm text-gray-500">
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
                            onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', '{{ $merchant->nama_merchant }}', {{ $merchant->id }})"
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

