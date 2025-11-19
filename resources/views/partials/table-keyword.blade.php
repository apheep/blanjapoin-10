<!-- ======================= DESKTOP / TABLE VIEW (DINAMIS) ======================= -->
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    @if(Auth::check() && Auth::user()->can_approve == 1)
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">approve</th>

                    @endif
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="keyword-table-body">
                @forelse($keywords as $keyword)
                    <tr class="hover:bg-gray-50 transition-colors keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}">

                        {{-- No --}}
                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                            {{ ($keywords->currentPage() - 1) * $keywords->perPage() + $loop->iteration }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-4">
                            <div class="flex space-x-2">
                                <button type="button"
                                        onclick="showDeleteConfirmation('Keyword', '{{ $keyword->nama_produk }}', {{ $keyword->id }})"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>

                        @if(Auth::check() && Auth::user()->can_approve == 1)
                        <td class="px-4 py-4">
                            @if($keyword->status === 'approve')
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-sm shadow-sm">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span>Approved</span>
                                </div>
                            @else
                                <button onclick="showApproveConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Approve"><i class="fas fa-check-circle text-sm"></i></button>
                            @endif
                        </td>
                        @endif

                        {{-- Merchant --}}
                        <td class="px-4 py-4 text-sm text-gray-900">
                            <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                        </td>

                        {{-- Nama Produk --}}
                        <td class="px-4 py-4 text-sm text-gray-900">
                            <div class="font-medium">{{ $keyword->nama_produk }}</div>
                        </td>

                        {{-- Redeem --}}
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->redeem ?? '-' }}</td>

                        {{-- Diskon --}}
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->diskon ?? '-' }}</td>

                        {{-- SKB --}}
                        <td class="px-4 py-4 text-xs text-gray-500">{{ $keyword->skb ?? '-' }}</td>

                        {{-- Stock --}}
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
                        </td>

                        {{-- Periode --}}
                        <td class="px-4 py-4 text-xs text-gray-500">
                            @if($keyword->start_date || $keyword->end_date)
                                <div>{{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }}</div>
                                <div>{{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}</div>
                            @else
                                <div>-</div>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($keyword->status === 'approve')
                                    bg-green-100 text-green-800
                                @elseif($keyword->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($keyword->status === 'reject')
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                {{ ucfirst($keyword->status) }}
                            </span>
                        </td>

                        {{-- Image --}}
                        <td class="px-4 py-4">
                            @if($keyword->image)
                                <img src="{{ asset('storage/' . $keyword->image) }}" 
                                     alt="{{ $keyword->nama_produk }}" 
                                     class="h-10 w-16 object-cover rounded">
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-4 text-center text-sm text-gray-500">
                            Belum ada data keyword.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
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

<!-- ======================= MOBILE / CARD VIEW (DINAMIS) ======================= -->
<div class="md:hidden space-y-3" id="keyword-cards-container">
    @forelse($keywords as $keyword)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col space-y-3 keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}">
            {{-- Header dengan No dan Actions --}}
            <div class="flex items-start justify-between pb-3 border-b border-gray-200">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">No</p>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ ($keywords->currentPage() - 1) * $keywords->perPage() + $loop->iteration }}</p>
                </div>
                <div class="flex items-center">
                    <button type="button"
                            onclick="showDeleteConfirmation('Keyword', '{{ $keyword->nama_produk }}', {{ $keyword->id }})"
                            class="text-red-600 hover:text-red-900 transition-colors"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            {{-- Merchant --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Merchant</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $keyword->merchant->nama_merchant ?? '-' }}</p>
            </div>

            {{-- Nama Produk --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Nama Produk</p>
                <p class="text-sm text-gray-700 mt-1">{{ $keyword->nama_produk }}</p>
            </div>

            {{-- Redeem --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Redeem</p>
                <p class="text-sm text-gray-700 mt-1">{{ $keyword->redeem ?? '-' }}</p>
            </div>

            {{-- Diskon --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Diskon</p>
                <p class="text-sm text-gray-700 mt-1">{{ $keyword->diskon ?? '-' }}</p>
            </div>

            {{-- SKB --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">SKB</p>
                <p class="text-xs text-gray-500 mt-1">{{ $keyword->skb ?? '-' }}</p>
            </div>

            {{-- Stock --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Stock</p>
                <p class="text-sm text-gray-700 mt-1">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
                </p>
            </div>

            {{-- Periode --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Periode</p>
                <div class="text-xs text-gray-500 mt-1">
                    @if($keyword->start_date || $keyword->end_date)
                        <div>{{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }}</div>
                        <div>{{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}</div>
                    @else
                        <div>-</div>
                    @endif
                </div>
            </div>

            {{-- Status --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Status</p>
                <div class="mt-1">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($keyword->status === 'approve')
                            bg-green-100 text-green-800
                        @elseif($keyword->status === 'pending')
                            bg-yellow-100 text-yellow-800
                        @elseif($keyword->status === 'reject')
                            bg-red-100 text-red-800
                        @endif
                    ">
                        {{ ucfirst($keyword->status) }}
                    </span>
                </div>
            </div>

            {{-- Approve Button (Mobile) --}}
            @if(Auth::check() && Auth::user()->can_approve == 1)
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2">Action</p>
                @if($keyword->status === 'approve')
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-sm shadow-sm">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>Approved</span>
                    </div>
                @else
                    <button onclick="showApproveConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="justify-center w-full px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-medium text-sm" title="Approve">
                        <i class="fas fa-check-circle mr-2"></i>
                        Approve
                    </button>
                @endif
            </div>
            @endif

            {{-- Image --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Image</p>
                <div class="mt-2 flex items-center space-x-2">
                    @if($keyword->image)
                        <button type="button" 
                                onclick="previewKeywordImage('{{ asset('storage/' . $keyword->image) }}', '{{ basename($keyword->image) }}')"
                                class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors">
                            <img src="{{ asset('storage/' . $keyword->image) }}" 
                                 alt="{{ $keyword->nama_produk }}" 
                                 class="h-full w-full object-cover">
                        </button>
                        <span class="text-sm text-gray-700 font-medium">{{ $keyword->nama_produk }}</span>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-center text-gray-500">Belum ada data keyword.</p>
    @endforelse
    
    <!-- Mobile Pagination -->
    @if($keywords->hasPages())
    <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col items-center justify-center space-y-3 rounded-xl">
        <div class="text-sm text-gray-600 text-center">
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

            {{-- Pagination Elements (simplified for mobile) --}}
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
