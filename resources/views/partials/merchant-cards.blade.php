@forelse($merchants as $merchant)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col space-y-3 merchant-row" data-category="{{ $merchant->kategori ?? 'All' }}">
        {{-- Header dengan No dan Actions --}}
        <div class="flex items-start justify-between pb-3 border-b border-gray-200">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">No</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ ($merchants->currentPage() - 1) * $merchants->perPage() + $loop->iteration }}</p>
            </div>
            <div class="flex items-center">
                <button type="button"
                        onclick="showDeleteConfirmation('Merchant', '{{ $merchant->nama_merchant }}', {{ $merchant->id }})"
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
