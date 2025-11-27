@php
    // gabungkan query yg sudah ada (misal search/filter) + paksa tab=keyword
    $keywordPaginator = $keywords->appends(array_merge(request()->query(), ['tab' => 'keyword']));
@endphp

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto keyword-table-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0 z-20 shadow-sm">
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

            <tbody class="bg-white divide-y divide-gray-200" id="keyword-table-body">
                @forelse($keywordPaginator as $keyword)
                    <tr id="keyword-row-{{ $keyword->id }}" class="hover:bg-gray-50 transition-colors keyword-row" data-category="{{ $keyword->merchant->kategori ?? 'All' }}" data-status="{{ $keyword->status }}" data-start="{{ ($keyword->start_date ? \Carbon\Carbon::parse($keyword->start_date)->format('Y-m-d') : '') }}" data-end="{{ ($keyword->end_date ? \Carbon\Carbon::parse($keyword->end_date)->format('Y-m-d') : '') }}">
                        <td class="px-4 py-4 text-sm font-medium text-gray-900">
                            {{ ($keywordPaginator->currentPage() - 1) * $keywordPaginator->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex space-x-2">
                                @if ($keyword->status === 'pending')
                                <button type="button"
                                        onclick="openEditKeyword({{ $keyword->id }}, {{ json_encode($keyword) }})"
                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif

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
                            <div class="font-medium">{{ $keyword->keyword_id ?? '-' }}</div>
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
                                   class="group block w-24 h-12 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition duration-150 hover:border-gray-300 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
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
                            Belum ada data keyword.
                        </td>
                    </tr>
                @endforelse

                <tr id="keyword-filter-empty-row" class="hidden">
                    <td colspan="15" class="px-4 py-6 text-center text-sm text-gray-500">
                        Tidak ada keyword pada rentang tanggal yang dipilih.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    @if($keywordPaginator->hasPages())
        <div class="keyword-pagination-container bg-white px-4 py-4 border-t border-gray-200 flex items-center justify-between">
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
                    <a href="{{ $keywordPaginator->previousPageUrl() }}" class="keyword-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
                        <a href="{{ $url }}" class="keyword-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($keywordPaginator->hasMorePages())
                    <a href="{{ $keywordPaginator->nextPageUrl() }}" class="keyword-pagination-link px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
