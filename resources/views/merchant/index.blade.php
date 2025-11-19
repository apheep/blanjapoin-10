<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Merchant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-6xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Merchant</h1>
        <a href="{{ route('merchants.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            + Tambah Merchant
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Daerah</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nama Merchant</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Link KV Google</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Poin</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Promo</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @forelse($merchants as $merchant)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $merchant->daerah }}</td>
                    <td class="px-4 py-2 text-sm">{{ $merchant->nama_merchant }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($merchant->link_kv_google)
                            <a href="{{ $merchant->link_kv_google }}" target="_blank"
                               class="text-blue-600 underline text-xs break-all">
                                Link
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm">{{ $merchant->kategori }}</td>
                    <td class="px-4 py-2 text-sm">{{ $merchant->poin }}</td>
                    <td class="px-4 py-2 text-sm">{{ $merchant->promo }}</td>
                    <div class="flex space-x-2">
                    {{-- Edit --}}
                    <a href="{{ route('merchants.edit', $merchant->no) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2563eb" viewBox="0 0 24 24">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 
                        000-1.42l-2.34-2.34a1.003 1.003 0 00-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/>
                        </svg>
                    </a>

                    {{-- Delete --}}
                    <form action="{{ route('merchants.destroy', $merchant->no) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc2626" viewBox="0 0 24 24">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 
                            1H5v2h14V4z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 text-sm">
                        Belum ada data merchant.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $merchants->links() }}
    </div>
</div>

</body>
</html>
