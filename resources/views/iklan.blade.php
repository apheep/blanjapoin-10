@extends('layouts.app')


@include('partials.head')


@section('content')
@include('partials.navbar-admin')
<div id="iklanPage" class="min-h-screen bg-white pt-20 md:pt-32 pb-12 opacity-0 transition-opacity duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-row items-center justify-between gap-3 pl-2">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-neutral-800">Landing Page</h1>
                <p class="text-sm text-neutral-500">Atur banner yang tampil pada halaman utama pengguna.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-r from-orange-500 to-rose-500 text-white shadow-md hover:shadow-lg transition flex-shrink-0" title="Lihat Landing Page">
                <i class="fas fa-external-link-alt"></i>
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
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-neutral-100">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Tambah Iklan Baru</h2>
                <p class="text-sm text-neutral-500 mb-5">Unggah file gambar dengan format 5:1 aspect ratio (JPG, PNG, maksimal 2 MB). </p>
                <form id="uploadForm" action="{{ route('iklan.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">Pilih Gambar</span>
                        <input id="imageInput" type="file" name="image" accept="image/*"
                               class="mt-2 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                        <span id="fileError" class="text-xs text-rose-500 mt-2 hidden">Silakan pilih gambar terlebih dahulu.</span>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">CTA Link </span>
                        <input id="linkInput" type="url" name="link_iklan" value="{{ old('link_iklan') }}"
                               placeholder="https://contoh.com/promo"
                               class="mt-2 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100">
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-neutral-700">Teritorial <span class="text-xs text-neutral-400 font-normal">(Opsional)</span></span>
                        <select id="territorialInput" name="territorial" 
                                class="mt-2 block w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100">
                            <option value="">Semua Teritorial (Tampil di semua halaman)</option>
                            @foreach($territories as $territory)
                                <option value="{{ $territory['slug'] }}" {{ old('territorial') === $territory['slug'] ? 'selected' : '' }}>
                                    {{ $territory['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-neutral-500 mt-1">Jika dipilih, iklan hanya akan tampil di halaman teritorial yang dipilih.</p>
                    </label>
                    <button type="button" id="openConfirmModal" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-neutral-900 text-white font-semibold hover:bg-neutral-800 transition">
                        Simpan Iklan
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-neutral-100">
                <h2 class="text-xl font-semibold text-neutral-800 mb-1">Preview Banner</h2>
                @php
                    $primaryBanner = $iklans->first();
                @endphp
                <div class="relative h-60 rounded-2xl overflow-hidden bg-neutral-100">
                    @if ($iklans->isNotEmpty())
                        <img src="{{ asset('storage/' . $iklans->first()->image_path) }}" alt="Preview Iklan" class="w-full h-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-neutral-500 text-sm font-medium">Belum ada iklan</div>
                    @endif
                </div>
                @if ($primaryBanner)
                    <p class="text-xs text-neutral-500 mt-3">
                        Link banner utama:
                        @if ($primaryBanner->link_iklan)
                            <a href="{{ $primaryBanner->link_iklan }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-orange-600 hover:text-orange-500 break-all">
                                {{ $primaryBanner->link_iklan }}
                            </a>
                        @else
                            <span class="text-neutral-400 font-medium">Belum ditentukan</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-800">Daftar Iklan</h2>
                    <p class="text-sm text-neutral-500">
                        Total <span id="totalCount">{{ $iklans->count() }}</span> banner.
                        <span id="filteredCount" class="hidden">Menampilkan <span id="filteredNumber">0</span> banner.</span>
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <label class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-neutral-700 whitespace-nowrap">Filter Teritorial:</span>
                        <select id="territorialFilter" 
                                class="block w-full md:w-48 rounded-xl border border-neutral-200 px-3 py-2 text-sm text-neutral-600 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100">
                            <option value="">Semua Teritorial</option>
                            <option value="null">Tanpa Teritorial</option>
                            @foreach($territories as $territory)
                                <option value="{{ $territory['slug'] }}">{{ $territory['name'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="button" id="resetFilter" class="hidden px-3 py-2 text-sm font-semibold text-neutral-600 bg-neutral-100 border border-neutral-200 rounded-xl hover:bg-neutral-200 transition">
                        <i class="fas fa-times mr-1"></i>Reset
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100 text-sm">
                    <thead class="text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                        <th class="py-3 text-center w-12 md:w-12 pr-3 md:pr-0"></th>
                        <th class="py-3 text-left pl-3 md:pl-0">No</th>
                        <th class="py-3 text-center">Preview</th>
                        <th class="py-3 text-center">Link</th>
                        <th class="py-3 text-center">Teritorial</th>
                        <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="iklanTableBody" class="divide-y divide-neutral-100">
                        @forelse ($iklans as $iklan)
                            <tr data-iklan-id="{{ $iklan->id }}" 
                                data-territorial="{{ $iklan->territorial ?? 'null' }}"
                                class="cursor-move hover:bg-neutral-50 transition-colors draggable-row iklan-row">
                                <td class="py-3 text-center pr-3 md:pr-0">
                                    <div class="flex items-center justify-center cursor-grab active:cursor-grabbing">
                                        <i class="fas fa-grip-vertical text-neutral-400 hover:text-neutral-600 transition-colors"></i>
                                    </div>
                                </td>
                                <td class="py-3 text-left  pl-3 md:pl-0">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3 text-center flex items-center justify-center">
                                    <div class="w-28 h-16 rounded-lg overflow-hidden bg-neutral-100 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $iklan->image_path) }}" alt="Iklan {{ $loop->iteration }}" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="py-3 text-center text-xs text-neutral-500 max-w-[220px]">
                                    @if ($iklan->link_iklan)
                                        <a href="{{ $iklan->link_iklan }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-orange-600 hover:text-orange-500 break-words">
                                            {{ $iklan->link_iklan }}
                                        </a>
                                    @else
                                        <span class="text-neutral-400 font-medium">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center text-xs">
                                    @if ($iklan->territorial)
                                        <a href="{{ route('city.show', $iklan->territorial) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 transition font-medium">
                                            {{ territorialName($iklan->territorial) }}
                                            <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                    @else
                                        <span class="text-neutral-400 font-medium">Semua Teritorial</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <form id="deleteForm-{{ $iklan->id }}" action="{{ route('iklan.destroy', $iklan) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-delete-form="deleteForm-{{ $iklan->id }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-rose-600 font-semibold hover:bg-rose-50 transition text-xs deleteTrigger" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="6" class="py-6 text-center text-neutral-500 font-medium">
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
// Script khusus halaman iklan
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

    // Drag and Drop functionality untuk reorder iklan
    const tableBody = document.getElementById('iklanTableBody');
    if (tableBody) {
        let draggedRow = null;
        let draggedOverRow = null;

        const rows = tableBody.querySelectorAll('.draggable-row');
        
        rows.forEach(row => {
            // Make row draggable
            row.setAttribute('draggable', 'true');
            
            row.addEventListener('dragstart', function(e) {
                draggedRow = this;
                this.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            });

            row.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                rows.forEach(r => {
                    r.classList.remove('border-t-2', 'border-orange-500');
                });
            });

            row.addEventListener('dragover', function(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                
                if (draggedRow && this !== draggedRow) {
                    rows.forEach(r => {
                        r.classList.remove('border-t-2', 'border-orange-500');
                    });
                    this.classList.add('border-t-2', 'border-orange-500');
                    draggedOverRow = this;
                }
                return false;
            });

            row.addEventListener('dragleave', function(e) {
                this.classList.remove('border-t-2', 'border-orange-500');
            });

            row.addEventListener('drop', function(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }

                if (draggedRow && this !== draggedRow) {
                    const allRows = Array.from(tableBody.querySelectorAll('.draggable-row'));
                    const draggedIndex = allRows.indexOf(draggedRow);
                    const targetIndex = allRows.indexOf(this);

                    if (draggedIndex < targetIndex) {
                        tableBody.insertBefore(draggedRow, this.nextSibling);
                    } else {
                        tableBody.insertBefore(draggedRow, this);
                    }

                    // Update nomor urut
                    updateRowNumbers();
                    
                    // Save order to server
                    saveOrder();
                }

                rows.forEach(r => {
                    r.classList.remove('border-t-2', 'border-orange-500');
                });

                return false;
            });
        });

        function updateRowNumbers() {
            const rows = Array.from(tableBody.querySelectorAll('.draggable-row')).filter(row => {
                return row.style.display !== 'none';
            });
            rows.forEach((row, index) => {
                const noCell = row.querySelector('td:nth-child(2)');
                if (noCell) {
                    noCell.textContent = index + 1;
                }
            });
        }

        function saveOrder() {
            // Get all rows in current DOM order (including hidden ones)
            // This ensures order is saved globally, not just for visible rows
            const rows = tableBody.querySelectorAll('.draggable-row');
            const orders = Array.from(rows).map(row => {
                return parseInt(row.getAttribute('data-iklan-id'));
            });

            fetch('{{ route("iklan.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optional: Show success message
                    console.log('Urutan berhasil diperbarui');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert on error
                location.reload();
            });
        }
    }

    // Filter functionality untuk teritorial
    const territorialFilter = document.getElementById('territorialFilter');
    const resetFilterBtn = document.getElementById('resetFilter');
    const totalCount = document.getElementById('totalCount');
    const filteredCount = document.getElementById('filteredCount');
    const filteredNumber = document.getElementById('filteredNumber');
    const iklanRows = document.querySelectorAll('.iklan-row');

    function filterByTerritorial(territorialValue) {
        let visibleCount = 0;
        
        iklanRows.forEach(row => {
            const rowTerritorial = row.getAttribute('data-territorial');
            const shouldShow = territorialValue === '' || rowTerritorial === territorialValue;
            
            if (shouldShow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update counter
        if (territorialValue === '') {
            totalCount.textContent = iklanRows.length;
            filteredCount.classList.add('hidden');
            resetFilterBtn.classList.add('hidden');
        } else {
            filteredNumber.textContent = visibleCount;
            filteredCount.classList.remove('hidden');
            resetFilterBtn.classList.remove('hidden');
        }

        // Update row numbers after filtering
        if (tableBody) {
            updateRowNumbers();
        }
    }

    if (territorialFilter) {
        territorialFilter.addEventListener('change', function() {
            filterByTerritorial(this.value);
        });
    }

    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            if (territorialFilter) {
                territorialFilter.value = '';
                filterByTerritorial('');
            }
        });
    }
});

// Dropdown user (desktop) – sama seperti di halaman admin
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const arrow = document.getElementById('userDropdownArrow');
    if (!dropdown) return;

    if (dropdown.classList.contains('opacity-0')) {
        dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.add('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
        dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(event) {
    const btn = document.getElementById('userDropdownBtn');
    const dropdown = document.getElementById('userDropdown');
    if (!btn || !dropdown) return;

    if (!btn.contains(event.target) && !dropdown.contains(event.target) && dropdown.classList.contains('opacity-100')) {
        toggleUserDropdown();
    }
});
</script>
@endsection