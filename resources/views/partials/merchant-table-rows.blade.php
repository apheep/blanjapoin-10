@forelse($merchants as $merchant)
    <tr class="hover:bg-gray-50 transition-colors merchant-row" data-category="{{ $merchant->kategori ?? 'All' }}">

        {{-- No --}}
        <td class="px-4 py-4 w-20 text-center text-sm font-medium text-gray-900">
            {{ ($merchants->currentPage() - 1) * $merchants->perPage() + $loop->iteration }}
        </td>

        {{-- Actions (ikon delete center) --}}
        <td class="px-4 py-4 w-20 text-center">
            <div class="flex items-center justify-center h-full">
                <button type="button"
                        onclick="showDeleteConfirmation('Merchant', '{{ $merchant->nama_merchant }}', {{ $merchant->id }})"
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
