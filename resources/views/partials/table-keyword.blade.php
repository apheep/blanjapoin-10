@php
    // Pastikan $keywords terdefinisi
    if (!isset($keywords) || !$keywords) {
        $keywords = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
    }
    
    // gabungkan query yg sudah ada (misal search/filter) + paksa tab=keyword
    // PENTING: Pertahankan merchant_page untuk mempertahankan pagination merchant saat pindah tab
    $queryParams = request()->query();
    // Pastikan tab=keyword dan pertahankan merchant_page jika ada
    $queryParams['tab'] = 'keyword';
    // Hapus parameter page generik jika ada, karena kita sudah menggunakan keyword_page
    unset($queryParams['page']);
    // Pastikan merchant_page tetap ada jika sebelumnya ada di URL
    if (request()->has('merchant_page')) {
        $queryParams['merchant_page'] = request()->get('merchant_page');
    }
    $keywordPaginator = $keywords->appends($queryParams);
@endphp

<div class="bg-white rounded-xl shadow overflow-hidden mt-4">
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
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table-body', 4, 'text')" data-sortable="true" data-column-index="4">
                        <div class="flex items-center gap-1">
                            <span>Merchant</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table-body', 5, 'text')" data-sortable="true" data-column-index="5">
                        <div class="flex items-center gap-1">
                            <span>Kategori Keyword</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table-body', 6, 'text')" data-sortable="true" data-column-index="6">
                        <div class="flex items-center gap-1">
                            <span>Nama Produk</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors select-none" onclick="sortTable('keyword-table-body', 7, 'text')" data-sortable="true" data-column-index="7">
                        <div class="flex items-center gap-1">
                            <span>Keyword ID</span>
                            <span class="sort-icon text-gray-400 text-[10px]"><i class="fas fa-sort"></i></span>
                        </div>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">CTA LINK</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Redeem</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diskon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-48">SKB</th>
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
                            <div class="flex justify-center items-center space-x-2">
                                <button type="button"
                                        id="keyword-edit-btn-{{ $keyword->id }}"
                                        data-keyword-edit-id="{{ $keyword->id }}"
                                        data-keyword-data="{{ json_encode($keyword, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                        onclick="openEditKeyword({{ $keyword->id }}, JSON.parse(this.getAttribute('data-keyword-data')))"
                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button"
                                        onclick="showDeleteConfirmation('Keyword', {{ json_encode($keyword->nama_produk) }}, {{ $keyword->id }})"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>

                        @if(Auth::check() && Auth::user()->can_approve == 1)
                            <td id="keyword-action-{{ $keyword->id }}" class="px-4 py-4">
                                {{-- Cek status terlebih dahulu, bukan subsidy_amount --}}
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
                                    {{-- Tampilkan approve/reject jika status pending --}}
                                    <div class="flex items-center gap-2">
                                        <button onclick="showApproveConfirmation('Keyword',{{ json_encode($keyword->nama_produk) }},{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Approve"><i class="fas fa-check-circle text-sm"></i></button>
                                        <button onclick="showRejectConfirmation('Keyword',{{ json_encode($keyword->nama_produk) }},{{ $keyword->id }})" class="p-2.5 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200" title="Reject"><i class="fas fa-times text-sm"></i></button>
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

                        {{-- Status Toggle --}}
                        <td class="px-4 py-4">
                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle Status">
                                <input type="checkbox" 
                                       data-keyword-id="{{ $keyword->id }}" 
                                       class="sr-only peer toggle-keyword-status" 
                                       {{ $keyword->is_active ? 'checked' : '' }} />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                            </label>
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-900" data-sort-value="{{ strtolower($keyword->merchant->nama_merchant ?? '-') }}">
                            <div class="font-medium">{{ $keyword->merchant->nama_merchant ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900" data-sort-value="{{ strtolower($keyword->kategori_keyword ?? '-') }}">
                            @if($keyword->kategori_keyword)
                                @php
                                    $kategoriColors = [
                                        'kuliner' => 'bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 border-orange-300',
                                        'hiburan' => 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 border-purple-300',
                                        'liburan' => 'bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border-blue-300',
                                        'belanja' => 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-green-300',
                                        'kecantikan' => 'bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800 border-pink-300',
                                        'telkomsel' => 'bg-gradient-to-r from-indigo-100 to-blue-100 text-indigo-800 border-indigo-300',
                                        'merchandise' => 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-amber-300'
                                    ];
                                    $kategoriLabel = [
                                        'kuliner' => 'Kuliner',
                                        'hiburan' => 'Hiburan',
                                        'liburan' => 'Liburan',
                                        'belanja' => 'Belanja',
                                        'kecantikan' => 'Kecantikan',
                                        'telkomsel' => 'Telkomsel Paket',
                                        'merchandise' => 'Merchandise'
                                    ];
                                    $color = $kategoriColors[strtolower($keyword->kategori_keyword)] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                    $label = $kategoriLabel[strtolower($keyword->kategori_keyword)] ?? ucfirst($keyword->kategori_keyword);
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-lg border text-xs font-medium {{ $color }}">
                                    {{ $label }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900" data-sort-value="{{ strtolower($keyword->nama_produk) }}">
                            <div class="font-medium">{{ $keyword->nama_produk }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900" data-sort-value="{{ strtolower($keyword->keyword_id ?? '-') }}">
                            <div class="font-medium">{{ $keyword->keyword_id ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900">
                            <a href="{{ $keyword->cta_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $keyword->cta_link }}</a>
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
                        <td colspan="17" class="px-4 py-4 text-center text-sm text-gray-500">
                            Belum ada data keyword.
                        </td>
                    </tr>
                @endforelse

                <tr id="keyword-filter-empty-row" class="hidden">
                    <td colspan="17" class="px-4 py-6 text-center text-sm text-gray-500">
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

<script>
    // Function to toggle keyword status - defined in global scope so it's available after AJAX reload
    function toggleKeywordStatus(keywordId) {
        const checkbox = document.querySelector(`.toggle-keyword-status[data-keyword-id="${keywordId}"]`);
        if (!checkbox) return;

        fetch(`/api/keywords/${keywordId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Gagal memperbarui status');
                });
            }
            return response.json();
        })
        .then(data => {
            // Update checkbox to match database value
            if (checkbox) {
                checkbox.checked = data.is_active;
            }
            console.log('Status keyword berhasil diperbarui', data);
        })
        .catch(error => {
            console.error('Error toggling keyword status:', error);
            // Revert checkbox on error
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            alert('Gagal memperbarui status: ' + error.message);
        });
    }

    // Make function available globally
    window.toggleKeywordStatus = toggleKeywordStatus;

    // Use event delegation for toggle keyword status - works even after AJAX reload
    if (!window.keywordToggleHandlerAttached) {
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('toggle-keyword-status')) {
                const keywordId = e.target.getAttribute('data-keyword-id');
                if (keywordId) {
                    toggleKeywordStatus(keywordId);
                }
            }
        });
        window.keywordToggleHandlerAttached = true;
    }

    // Also attach directly for initial load (backup)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-keyword-status').forEach(toggle => {
            toggle.addEventListener('change', function(e) {
                const keywordId = e.target.getAttribute('data-keyword-id');
                if (keywordId) {
                    toggleKeywordStatus(keywordId);
                }
            });
        });
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
                    <p class="text-sm font-semibold text-gray-700 mb-0">SKB:</p>
                    <div class="bg-gray-50 rounded-lg p-2 border border-gray-200 mt-0">
                        <p class="text-sm text-gray-700 leading-none whitespace-pre-wrap break-words" style="line-height: 1.2;">${skbText}</p>
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

    // Client-side table sorting functionality
    // Store original order and sort state for each table
    const tableSortData = {
        'keyword-table-body': {
            originalOrder: null,
            sortState: {}
        },
        'merchant-table-body': {
            originalOrder: null,
            sortState: {}
        }
    };

    function sortTable(tableBodyId, columnIndex, dataType) {
        const tbody = document.getElementById(tableBodyId);
        if (!tbody) return;

        const tableData = tableSortData[tableBodyId];
        if (!tableData) return;

        // Store original order on first sort
        if (!tableData.originalOrder) {
            tableData.originalOrder = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.cloneNode(true));
        }

        const rows = Array.from(tbody.querySelectorAll('tr:not(.hidden)'));
        if (rows.length === 0) return;

        // Get current sort state for this column
        const currentState = tableData.sortState[columnIndex] || null;
        let newState;

        // Toggle: null -> 'asc' -> 'desc' -> null (reset)
        if (currentState === null) {
            newState = 'asc';
        } else if (currentState === 'asc') {
            newState = 'desc';
        } else {
            newState = null; // Reset
        }

        // Update sort state
        tableData.sortState[columnIndex] = newState;

        // Reset all sort icons for this column in this table
        const table = tbody.closest('table');
        if (table) {
            const headers = table.querySelectorAll(`thead [data-column-index="${columnIndex}"] .sort-icon`);
            headers.forEach(icon => {
                icon.innerHTML = '<i class="fas fa-sort"></i>';
                icon.className = 'sort-icon text-gray-400 text-[10px]';
            });
        }

        if (newState === null) {
            // Reset to original order
            tbody.innerHTML = '';
            tableData.originalOrder.forEach(tr => tbody.appendChild(tr.cloneNode(true)));
            tableData.originalOrder = null;
            tableData.sortState = {};
            return;
        }

        // Update icon for current column
        const tableForHeader = tbody.closest('table');
        if (tableForHeader) {
            const header = tableForHeader.querySelector(`thead [data-column-index="${columnIndex}"]`);
            if (header) {
                const icon = header.querySelector('.sort-icon');
                if (icon) {
                    if (newState === 'asc') {
                        icon.innerHTML = '<i class="fas fa-sort-up"></i>';
                        icon.className = 'sort-icon text-blue-600 text-[10px]';
                    } else {
                        icon.innerHTML = '<i class="fas fa-sort-down"></i>';
                        icon.className = 'sort-icon text-blue-600 text-[10px]';
                    }
                }
            }
        }

        // Sort rows
        rows.sort((a, b) => {
            const cellA = a.querySelector(`td:nth-child(${columnIndex + 1})`);
            const cellB = b.querySelector(`td:nth-child(${columnIndex + 1})`);
            
            if (!cellA || !cellB) return 0;

            let valueA, valueB;

            if (dataType === 'text') {
                // Use data-sort-value if available, otherwise get text content
                valueA = (cellA.getAttribute('data-sort-value') || cellA.textContent.trim().toLowerCase()) || '';
                valueB = (cellB.getAttribute('data-sort-value') || cellB.textContent.trim().toLowerCase()) || '';
            } else {
                valueA = parseFloat(cellA.textContent.replace(/[^\d.-]/g, '')) || 0;
                valueB = parseFloat(cellB.textContent.replace(/[^\d.-]/g, '')) || 0;
            }

            let comparison = 0;
            if (valueA < valueB) {
                comparison = -1;
            } else if (valueA > valueB) {
                comparison = 1;
            }

            return newState === 'asc' ? comparison : -comparison;
        });

        // Reorder rows in DOM
        rows.forEach(row => tbody.appendChild(row));
    }

    // Make function globally available
    window.sortTable = sortTable;
</script>
