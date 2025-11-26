@extends('layouts.app')


@section('content')
@include('partials.navbar-admin')
<div id="iklanPage" class="min-h-screen bg-white pt-28 md:pt-32 pb-12 opacity-0 transition-opacity duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="text-sm text-neutral-500 font-semibold tracking-wide uppercase mb-1">Landing Page</p>
                <h1 class="text-2xl md:text-3xl font-bold text-neutral-800">Manajemen Iklan</h1>
                <p class="text-sm text-neutral-500">Atur banner yang tampil pada halaman utama pengguna.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-rose-500 text-white font-semibold shadow-md hover:shadow-lg transition">
                Lihat Landing Page
            </a>
        </div>

        @if (session('success'))
            <div id="successAlert" class="rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm text-green-700 shadow-sm opacity-0 translate-y-2 transition-all duration-500">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-100 px-4 py-3 text-sm text-rose-600 shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Tambah Iklan Baru</h2>
                <p class="text-sm text-neutral-500 mb-5">Unggah file gambar (JPG, PNG, maksimal 2 MB).</p>
                <form id="uploadForm" action="{{ route('iklan.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Gambar</span>
                        <input id="imageInput" type="file" name="image" accept="image/*"
                               class="mt-2 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                        <span id="fileError" class="text-xs text-rose-500 mt-2 hidden">Silakan pilih gambar terlebih dahulu.</span>
                    </label>
                    <button type="button" id="openConfirmModal" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-neutral-900 text-white font-semibold hover:bg-neutral-800 transition">
                        Simpan Iklan
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Preview Banner</h2>
                <p class="text-sm text-neutral-500 mb-4">Gambar pertama pada daftar akan menjadi banner utama.</p>
                <div class="relative h-60 rounded-2xl overflow-hidden bg-neutral-100">
                    @if ($iklans->isNotEmpty())
                        <img src="{{ asset('storage/' . $iklans->first()->image_path) }}" alt="Preview Iklan" class="w-full h-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-neutral-500 text-sm font-medium">Belum ada iklan</div>
                    @endif
                </div>
                <p class="text-xs text-neutral-400 mt-3 leading-relaxed">
                    Rekomendasi ukuran: 1920x800 piksel dengan format landscape. Simpan file ke storage publik agar dapat ditampilkan pada landing page.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800">Daftar Iklan</h2>
                    <p class="text-sm text-neutral-500">Total {{ $iklans->count() }} banner.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100 text-sm">
                    <thead class="text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="py-3">Preview</th>
                            <th class="py-3">Nama File</th>
                            <th class="py-3">Diupload</th>
                            <th class="py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse ($iklans as $iklan)
                            <tr>
                                <td class="py-3">
                                    <div class="w-28 h-16 rounded-lg overflow-hidden bg-neutral-100">
                                        <img src="{{ asset('storage/' . $iklan->image_path) }}" alt="Iklan {{ $loop->iteration }}" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="py-3 font-semibold text-neutral-700">{{ basename($iklan->image_path) }}</td>
                                <td class="py-3 text-neutral-500">{{ $iklan->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="py-3 text-right">
                                    <form id="deleteForm-{{ $iklan->id }}" action="{{ route('iklan.destroy', $iklan) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-delete-form="deleteForm-{{ $iklan->id }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-rose-200 text-rose-600 font-semibold hover:bg-rose-50 transition text-xs deleteTrigger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-neutral-500 font-medium">
                                    Belum ada data iklan. Tambahkan gambar melalui form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="uploadConfirmationModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div id="uploadModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-4 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center">
                    <i class="fas fa-cloud-upload-alt text-orange-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Konfirmasi Upload</h3>
                    <p class="text-sm text-neutral-500">Pastikan banner yang dipilih sudah benar.</p>
                </div>
            </div>
            <button type="button" class="text-neutral-400 hover:text-neutral-600 transition" data-close-upload>
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center mb-4">
                <i class="fas fa-image text-2xl text-orange-500"></i>
            </div>
            <h4 class="text-lg font-semibold text-neutral-900 mb-2">Upload Banner Sekarang?</h4>
            <p class="text-sm text-neutral-600">Banner akan langsung tersimpan dan tampil di landing page setelah proses berhasil.</p>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-neutral-50 rounded-b-2xl">
            <button type="button" data-close-upload class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">Batal</button>
            <button type="button" id="confirmUploadBtn" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-rose-500 rounded-lg hover:shadow-lg transition">Upload</button>
        </div>
    </div>
</div>

<div id="deleteConfirmationModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div id="deleteModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-4 rounded-full bg-gradient-to-r from-rose-100 to-red-100 flex items-center justify-center">
                    <i class="fas fa-trash-alt text-rose-500"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-900">Hapus Iklan</h3>
                    <p class="text-sm text-neutral-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <button type="button" class="text-neutral-400 hover:text-neutral-600 transition" data-close-delete>
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-gradient-to-r from-rose-100 to-red-100 flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-rose-500"></i>
            </div>
            <h4 class="text-lg font-semibold text-neutral-900 mb-2">Yakin hapus banner ini?</h4>
            <p class="text-sm text-neutral-600">Banner akan dihapus dari daftar dan tidak lagi tampil di landing page.</p>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-neutral-50 rounded-b-2xl">
            <button type="button" data-close-delete class="px-4 py-2 text-sm font-semibold text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-100 transition">Batal</button>
            <button type="button" id="confirmDeleteBtn" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-rose-500 to-red-500 rounded-lg hover:shadow-lg transition">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.getElementById('iklanPage');
    if (page) {
        requestAnimationFrame(() => {
            page.classList.remove('opacity-0');
        });
    }

    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.remove('opacity-0', 'translate-y-2');
            setTimeout(() => {
                successAlert.classList.add('opacity-0', 'translate-y-2');
            }, 4000);
        }, 100);
    }

    const imageInput = document.getElementById('imageInput');
    const fileError = document.getElementById('fileError');
    const uploadForm = document.getElementById('uploadForm');
    const uploadModal = document.getElementById('uploadConfirmationModal');
    const uploadModalContent = document.getElementById('uploadModalContent');
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const openConfirmBtn = document.getElementById('openConfirmModal');
    const confirmUploadBtn = document.getElementById('confirmUploadBtn');
    const closeUploadButtons = document.querySelectorAll('[data-close-upload]');
    const deleteButtons = document.querySelectorAll('.deleteTrigger');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const closeDeleteButtons = document.querySelectorAll('[data-close-delete]');
    let pendingDeleteForm = null;

    function openModal(modal, content) {
        if (!modal || !content) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal(modal, content) {
        if (!modal || !content) return;
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 250);
    }

    if (openConfirmBtn) {
        openConfirmBtn.addEventListener('click', () => {
            if (!imageInput || !imageInput.files.length) {
                fileError.classList.remove('hidden');
                return;
            }
            fileError.classList.add('hidden');
            openModal(uploadModal, uploadModalContent);
        });
    }

    closeUploadButtons.forEach(btn => {
        btn.addEventListener('click', () => closeModal(uploadModal, uploadModalContent));
    });

    if (uploadModal) {
        uploadModal.addEventListener('click', (event) => {
            if (event.target === uploadModal) {
                closeModal(uploadModal, uploadModalContent);
            }
        });
    }

    if (confirmUploadBtn) {
        confirmUploadBtn.addEventListener('click', () => {
            closeModal(uploadModal, uploadModalContent);
            uploadForm.submit();
        });
    }

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const formId = button.getAttribute('data-delete-form');
            pendingDeleteForm = document.getElementById(formId);
            openModal(deleteModal, deleteModalContent);
        });
    });

    confirmDeleteBtn?.addEventListener('click', () => {
        if (pendingDeleteForm) {
            closeModal(deleteModal, deleteModalContent);
            pendingDeleteForm.submit();
        }
    });

    closeDeleteButtons.forEach(btn => {
        btn.addEventListener('click', () => closeModal(deleteModal, deleteModalContent));
    });

    if (deleteModal) {
        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeModal(deleteModal, deleteModalContent);
            }
        });
    }
});
</script>
@endsection