<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Merchant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Merchant</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('merchants.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf

        <!-- <div>
            <label class="block text-sm font-medium mb-1">No</label>
            <input type="number" name="no" value="{{ old('no') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div> -->

        <div>
            <label class="block text-sm font-medium mb-1">Daerah</label>
            <input type="text" name="daerah" value="{{ old('daerah') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nama Merchant</label>
            <input type="text" name="nama_merchant" value="{{ old('nama_merchant') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Link KV Google</label>
            <input type="url" name="link_kv_google" value="{{ old('link_kv_google') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <input type="text" name="kategori" value="{{ old('kategori') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Poin</label>
            <input type="number" name="poin" value="{{ old('poin') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Promo</label>
            <input type="text" name="promo" value="{{ old('promo') }}"
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin') }}"
               class="px-4 py-2 text-sm border rounded">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>

</body>
</html>
