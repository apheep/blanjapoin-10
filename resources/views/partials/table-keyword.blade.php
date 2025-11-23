@php
    // gabungkan query yg sudah ada (misal search/filter) + paksa tab=keyword
    $keywordPaginator = $keywords->appends(array_merge(request()->query(), ['tab' => 'keyword']));
@endphp

<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    @if(Auth::check() && Auth::user()->can_approve == 1)
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Approval</th>
                    @endif
                    @if(Auth::check() && Auth::user()->can_approve == 0)
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    @endif
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merchant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA LINK</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKB</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="keyword-table-body">
                @forelse($keywordPaginator as $keyword)
                    <tr id="keyword-row-{{ $keyword->id }}" class="hover:bg-gray-50 transition-colors keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                            {{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex space-x-2">
                                <button type="button"
                                        onclick="openEditKeyword({{ $keyword->id }}, {{ json_encode($keyword) }})"
                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button"
                                        onclick="showDeleteConfirmation('Keyword', '{{ $keyword->nama_produk }}', {{ $keyword->id }})"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>

                        @if(Auth::check() && Auth::user()->can_approve == 1)
                            <td id="keyword-action-{{ $keyword->id }}" class="px-4 py-4">
                                @if($keyword->status === 'approve')
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-sm shadow-sm">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        <span>Approved</span>
                                    </div>
                                @elseif($keyword->status === 'reject')
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-red-100 to-rose-100 text-red-700 font-medium text-sm shadow-sm">
                                        <i class="fas fa-times text-red-600"></i>
                                        <span>Rejected</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <button onclick="showApproveConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Approve"><i class="fas fa-check-circle text-sm"></i></button>
                                        <button onclick="showRejectConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Reject"><i class="fas fa-times text-sm"></i></button>
                                    </div>
                                @endif
                            </td>
                        @endif
                        @if(Auth::check() && Auth::user()->can_approve == 0)
                            <td id="keyword-status-{{ $keyword->id }}" class="px-4 py-4">
                            <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full
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
                        @endif


                        <td class="px-4 py-4 text-sm text-gray-900">
                            <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900">
                            <div class="font-medium">{{ $keyword->nama_produk }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900">
                            <a href="{{ $keyword->cta_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $keyword->cta_link }}</a>
                        </td>   
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->redeem ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700">{{ $keyword->diskon ?? '-' }}</td>
                        <td class="px-4 py-4 text-xs text-gray-500">{{ $keyword->skb ?? '-' }}</td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
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
                        <td colspan="12" class="px-4 py-4 text-center text-sm text-gray-500">
                            Belum ada data keyword.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($keywordPaginator->hasPages())
        <div class="bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-semibold">{{ $keywordPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywordPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $keywordPaginator->total() }}</span> data
            </div>
            
            <div class="flex items-center space-x-2">
                {{-- Previous Page Link --}}
                @if ($keywordPaginator->onFirstPage())
                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $keywordPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($keywordPaginator->getUrlRange(1, $keywordPaginator->lastPage()) as $page => $url)
                    @if ($page == $keywordPaginator->currentPage())
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
                @if ($keywordPaginator->hasMorePages())
                    <a href="{{ $keywordPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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

{{-- MOBILE VERSION --}}
<div class="md:hidden space-y-2" id="keyword-cards-container">
    @forelse($keywordPaginator as $keyword)
        <div id="keyword-card-{{ $keyword->id }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
            {{-- Header dengan No, Status, dan Actions --}}
            <div class="flex items-start justify-between mb-2 pb-2 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-500">#{{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}</span>
                    <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded-full
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
                <div class="flex items-center gap-2">
                    <button type="button"
                            onclick="openEditKeyword({{ $keyword->id }}, {{ json_encode($keyword) }})"
                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                            title="Edit">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button type="button"
                            onclick="showDeleteConfirmation('Keyword', '{{ $keyword->nama_produk }}', {{ $keyword->id }})"
                            class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                            title="Hapus">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- Grid Layout untuk informasi utama --}}
            <div class="grid grid-cols-2 gap-2 mb-2">
                <div>
                    <p class="text-[10px] text-gray-500 font-medium mb-0.5">Merchant</p>
                    <p class="text-xs font-semibold text-gray-900 truncate" title="{{ $keyword->merchant->nama_merchant ?? '-' }}">{{ $keyword->merchant->nama_merchant ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-medium mb-0.5">Stock</p>
                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded-full bg-blue-100 text-blue-800">{{ $keyword->stock }}</span>
                </div>
            </div>

            <div class="mb-2">
                <p class="text-[10px] text-gray-500 font-medium mb-0.5">Produk</p>
                <p class="text-xs text-gray-900 line-clamp-2">{{ $keyword->nama_produk }}</p>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-2 text-xs">
                <div>
                    <span class="text-gray-500">Redeem:</span>
                    <span class="text-gray-900 font-medium ml-1">{{ $keyword->redeem ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Diskon:</span>
                    <span class="text-gray-900 font-medium ml-1">{{ $keyword->diskon ?? '-' }}</span>
                </div>
            </div>

            @if($keyword->start_date || $keyword->end_date)
            <div class="mb-2">
                <p class="text-[10px] text-gray-500 font-medium mb-0.5">Periode</p>
                <p class="text-[10px] text-gray-600">
                    {{ $keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('d/m/Y') : '-' }} - 
                    {{ $keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('d/m/Y') : '-' }}
                </p>
            </div>
            @endif

            @if($keyword->cta_link)
            <div class="mb-2">
                <a href="{{ $keyword->cta_link }}" target="_blank" class="text-[10px] text-blue-600 hover:underline truncate block" title="{{ $keyword->cta_link }}">{{ $keyword->cta_link }}</a>
            </div>
            @endif

            {{-- Approve Button (Mobile) --}}
            @if(Auth::check() && Auth::user()->can_approve == 1)
                <div id="keyword-action-mobile-{{ $keyword->id }}" class="mt-2 pt-2 border-t border-gray-200">
                    @if($keyword->status === 'approve')
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-xs">
                            <i class="fas fa-check-circle text-green-600 text-xs"></i>
                            <span>Approved</span>
                        </div>
                    @elseif($keyword->status === 'reject')
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gradient-to-r from-red-100 to-rose-100 text-red-700 font-medium text-xs">
                            <i class="fas fa-times text-red-600 text-xs"></i>
                            <span>Rejected</span>
                        </div>
                    @else
                        <div class="flex gap-1.5">
                            <button onclick="showApproveConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="flex-1 px-2 py-1.5 rounded-md bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 text-xs font-medium transition-all" title="Approve">
                                <i class="fas fa-check-circle text-xs"></i>
                            </button>
                            <button onclick="showRejectConfirmation('Keyword','{{ $keyword->nama_produk }}',{{ $keyword->id }})" class="flex-1 px-2 py-1.5 rounded-md bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 text-xs font-medium transition-all" title="Reject">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            @if($keyword->image)
            <div class="mt-2 pt-2 border-t border-gray-200">
                <button type="button" 
                        onclick="previewKeywordImage('{{ asset('storage/' . $keyword->image) }}', '{{ basename($keyword->image) }}')"
                        class="w-full h-20 rounded-md overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors">
                    <img src="{{ asset('storage/' . $keyword->image) }}" 
                         alt="{{ $keyword->nama_produk }}" 
                         class="h-full w-full object-cover">
                </button>
            </div>
            @endif
        </div>
    @empty
        <p class="text-sm text-center text-gray-500">Belum ada data keyword.</p>
    @endforelse
    
    @if($keywordPaginator->hasPages())
        <div class="bg-white px-4 py-4 border-t border-gray-200 flex flex-col items-center justify-center space-y-3 rounded-xl">
            <div class="text-sm text-gray-600 text-center">
                Menampilkan <span class="font-semibold">{{ $keywordPaginator->firstItem() }}</span> hingga <span class="font-semibold">{{ $keywordPaginator->lastItem() }}</span> dari <span class="font-semibold">{{ $keywordPaginator->total() }}</span> data
            </div>
            
            <div class="flex items-center space-x-2">
                {{-- Previous Page Link --}}
                @if ($keywordPaginator->onFirstPage())
                    <button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $keywordPaginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements (Simplified for Mobile) --}}
                @foreach ($keywordPaginator->getUrlRange(1, $keywordPaginator->lastPage()) as $page => $url)
                    @if ($page == $keywordPaginator->currentPage())
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
                @if ($keywordPaginator->hasMorePages())
                    <a href="{{ $keywordPaginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
