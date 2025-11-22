<div id="uploadModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>

    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 py-3 md:px-6 md:py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                Upload Data
            </h3>
            <button type="button"
                    onclick="closeUploadMerchant()"
                    class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- FORM: Merchant form yang langsung POST ke database --}}
        <form id="formUploadMerchant"
      action="{{ route('merchants.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="flex-1 overflow-y-auto">

    @csrf
            <div class="p-4 md:p-6 space-y-4">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        {{-- Nama Merchant --}}
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Nama Merchant
                            </label>
                            <input type="text"
                                   name="nama_merchant"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                   placeholder="Masukkan nama merchant">
                        </div>

                        {{-- Daerah --}}
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Daerah
                            </label>
                            <input type="text"
                                   name="daerah"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                   placeholder="Contoh: Surabaya">
                        </div>

                        {{-- Kategori (dropdown) --}}
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Kategori
                            </label>

                            <div class="relative transition-all duration-300 ease-out">
                                <input type="hidden" name="kategori" id="merchantKategoriValue">

                                <button
                                    type="button"
                                    id="merchantKategoriBtn"
                                    onclick="toggleMerchantKategoriDropdown()"
                                    class="w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400"
                                >
                                    <span id="merchantKategoriLabel">Pilih kategori</span>
                                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                                </button>

                                <div
                                    id="merchantKategoriDropdown"
                                    class="hidden absolute left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-full z-50"
                                >
                                    <div class="py-1 text-sm">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Makanan')">
                                            Makanan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Hiburan')">
                                            Hiburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Liburan')">
                                            Liburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Belanja')">
                                            Belanja
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Kecantikan')">
                                            Kecantikan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Telkomsel Packet')">
                                            Telkomsel Packet
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-red-800 rounded-lg transition-all duration-300" onclick="selectMerchantKategori('Merchandise')">
                                            Merchandise
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Image --}}
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Logo Merchant
                            </label>
                            <div class="relative">
                                <input type="file"
                                    id="merchantImageInput"
                                    name="logo_merchant"
                                    accept="image/*"
                                    class="hidden"
                                    onchange="previewMerchantImage(this)">
                                <button type="button"
                                        onclick="document.getElementById('merchantImageInput').click()"
                                        class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="merchantImageText" class="text-[15px]">
                                        Click to upload Logo Merchant
                                    </span>
                                </button>
                                <div id="merchantImagePreview" class="mt-3 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-4 py-3 md:px-6 md:py-4 border-t bg-white rounded-b-xl">
                <button type="button"
                        onclick="closeUploadMerchant()"
                        class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

{{-- kalau kamu juga pakai upload-verification-modal untuk merchant --}}
@include('partials.upload-verification-modal')

<script>
// ======================
// Preview & remove image
// ======================
function previewMerchantImage(input) {
    const preview = document.getElementById('merchantImagePreview');
    const text = document.getElementById('merchantImageText');
    if (!preview) return;

    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.classList.remove('hidden');
            if (text) text.textContent = file.name;
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                <button type="button" onclick="removeMerchantImage()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        if (text) text.textContent = 'Click to upload Logo Merchant';
    }
}

function removeMerchantImage() {
    const input = document.getElementById('merchantImageInput');
    if (!input) return;
    input.value = '';
    previewMerchantImage(input);
}

// ======================
// Open / Close modal
// ======================
function openUploadMerchant() {
    const modal = document.getElementById('uploadModalMerchant');
    if (!modal) return;

    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0.5'; }, 10);
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 50);

    if (modalContent) {
        const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea');
        formElements.forEach((el, index) => {
            el.style.transform = 'translateY(10px)';
            el.style.opacity = '0';
            setTimeout(() => {
                el.style.transform = 'translateY(0)';
                el.style.opacity = '1';
            }, 100 + (index * 30));
        });
    }
}

function closeUploadMerchant() {
    const modal = document.getElementById('uploadModalMerchant');
    if (!modal) return;

    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');

    if (modalContent) {
        const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea');
        formElements.forEach((el, index) => {
            setTimeout(() => {
                el.style.transform = 'translateY(10px)';
                el.style.opacity = '0';
            }, index * 20);
        });
    }

    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    }, 100);

    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0'; }, 150);

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';

        const form = document.getElementById('formUploadMerchant');
        if (form) form.reset();

        // reset kategori dropdown
        const kategoriInput = document.getElementById('merchantKategoriValue');
        const kategoriLabel = document.getElementById('merchantKategoriLabel');
        const kategoriBtn   = document.getElementById('merchantKategoriBtn');
        if (kategoriInput) kategoriInput.value = '';
        if (kategoriLabel) kategoriLabel.textContent = 'Pilih kategori';
        if (kategoriBtn) {
            kategoriBtn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400';
        }

        // reset preview image
        const preview = document.getElementById('merchantImagePreview');
        const text = document.getElementById('merchantImageText');
        if (preview) {
            preview.innerHTML = '';
            preview.classList.add('hidden');
        }
        if (text) text.textContent = 'Click to upload Logo Merchant';

        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}

// ======================
// Dropdown kategori di modal
// ======================
function toggleMerchantKategoriDropdown() {
    const dropdown = document.getElementById('merchantKategoriDropdown');
    if (!dropdown) return;

    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-6px)';
        requestAnimationFrame(() => {
            dropdown.style.transition = 'opacity .25s ease, transform .25s ease';
            dropdown.style.opacity = '1';
            dropdown.style.transform = 'translateY(0)';
        });
    } else {
        dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-6px)';
        setTimeout(() => {
            dropdown.classList.add('hidden');
            dropdown.style.transition = '';
            dropdown.style.opacity = '';
            dropdown.style.transform = '';
        }, 200);
    }
}

function selectMerchantKategori(value) {
    const hiddenInput = document.getElementById('merchantKategoriValue');
    const labelSpan   = document.getElementById('merchantKategoriLabel');
    const btn         = document.getElementById('merchantKategoriBtn');
    const dropdown    = document.getElementById('merchantKategoriDropdown');

    if (hiddenInput) hiddenInput.value = value;
    if (labelSpan)   labelSpan.textContent = value;

    if (btn) {
        btn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border transition-all duration-300';

        if (value === 'Makanan') {
            btn.classList.add('border-orange-300', 'text-orange-800', 'bg-gradient-to-r', 'from-orange-100', 'to-red-100');
        } else if (value === 'Hiburan') {
            btn.classList.add('border-purple-300', 'text-purple-800', 'bg-gradient-to-r', 'from-purple-100', 'to-pink-100');
        } else if (value === 'Liburan') {
            btn.classList.add('border-blue-300', 'text-blue-800', 'bg-gradient-to-r', 'from-blue-100', 'to-cyan-100');
        } else if (value === 'Belanja') {
            btn.classList.add('border-green-300', 'text-green-800', 'bg-gradient-to-r', 'from-green-100', 'to-emerald-100');
        } else if (value === 'Kecantikan & Perawatan') {
            btn.classList.add('border-pink-300', 'text-pink-800', 'bg-gradient-to-r', 'from-pink-100', 'to-rose-100');
        } else if (value === 'Paket Telkomsel') {
            btn.classList.add('border-indigo-300', 'text-indigo-800', 'bg-gradient-to-r', 'from-indigo-100', 'to-blue-100');
        } else if (value === 'Merchandise') {
            btn.classList.add('border-indigo-300', 'text-red-800', 'bg-gradient-to-r', 'from-indigo-100', 'to-blue-100');
        } else {
            btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
        }
    }

    if (dropdown) {
        dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-6px)';
        setTimeout(() => {
            dropdown.classList.add('hidden');
            dropdown.style.transition = '';
            dropdown.style.opacity = '';
            dropdown.style.transform = '';
        }, 200);
    }
}

// klik di luar dropdown kategori → tutup
document.addEventListener('click', function (event) {
    const btn = document.getElementById('merchantKategoriBtn');
    const dropdown = document.getElementById('merchantKategoriDropdown');
    if (!btn || !dropdown) return;

    if (!btn.contains(event.target) && !dropdown.contains(event.target) && !dropdown.classList.contains('hidden')) {
        dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-6px)';
        setTimeout(() => {
            dropdown.classList.add('hidden');
            dropdown.style.transition = '';
            dropdown.style.opacity = '';
            dropdown.style.transform = '';
        }, 200);
    }
});

// ======================
// Submit & overlay click
// ======================
let pendingFormData = null;
let csrfToken = null;

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formUploadMerchant');
    if (form) {
        // Get CSRF token from form
        const csrfInput = form.querySelector('input[name="_token"]');
        if (csrfInput) {
            csrfToken = csrfInput.value;
        }
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate required fields
            const namaMerchant = form.querySelector('input[name="nama_merchant"]').value.trim();
            const daerah = form.querySelector('input[name="daerah"]').value.trim();
            const kategori = form.querySelector('input[name="kategori"]').value.trim();
            
            if (!namaMerchant || !daerah || !kategori) {
                alert('Mohon isi semua field yang diperlukan (Nama Merchant, Daerah, Kategori)');
                return;
            }
            
            // Store form data and show verification modal
            pendingFormData = new FormData(form);
            // Use the shared verification modal from upload-verification-modal.blade.php
            showUploadVerification(pendingFormData, 'Merchant');
        });
    }

    const modal = document.getElementById('uploadModalMerchant');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) closeUploadMerchant();
        });
    }
});

</script>