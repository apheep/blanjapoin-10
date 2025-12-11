<!-- Merchant Edit Modal -->
<div id="editModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="editModalMerchantOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <div id="editModalMerchantPanel" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
        {{-- Header --}}
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-xl font-bold text-gray-800">
                Edit Merchant
            </h3>
            <button type="button"
                    onclick="closeEditMerchant()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- FORM: Merchant form --}}
        <form id="formEditMerchant"
      method="POST"
      enctype="multipart/form-data"
      class="flex-1 overflow-y-auto">

    @csrf
    @method('PUT')
            <div class="p-6 space-y-6">
                {{-- Section 1: Informasi Dasar --}}
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama Merchant --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Merchant / Program<span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="editMerchantNama"
                                   name="nama_merchant"
                                   required
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Masukkan nama merchant">
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="hidden" name="kategori" id="editMerchantKategoriValue">
                                <button
                                    type="button"
                                    id="editMerchantKategoriBtn"
                                    onclick="toggleEditMerchantKategoriDropdown()"
                                    class="w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400"
                                >
                                    <span id="editMerchantKategoriLabel">Pilih kategori</span>
                                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                                </button>
                                <div
                                    id="editMerchantKategoriDropdown"
                                    class="hidden absolute left-0 mt-2 bg-white rounded-xl shadow-xl p-2 border border-gray-200 w-full z-50"
                                >
                                    <div class="py-1 text-sm">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all" onclick="selectEditMerchantKategori('kuliner')">
                                            Kuliner
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all" onclick="selectEditMerchantKategori('hiburan')">
                                            Hiburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all" onclick="selectEditMerchantKategori('liburan')">
                                            Liburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all" onclick="selectEditMerchantKategori('belanja')">
                                            Belanja
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all" onclick="selectEditMerchantKategori('kecantikan')">
                                            Kecantikan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all" onclick="selectEditMerchantKategori('telkomsel')">
                                            Telkomsel Paket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Link Blanjapoin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Link Blanjapoin
                            </label>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-orange-400 focus-within:border-transparent">
                                <span class="px-4 py-3 bg-gray-50 text-sm text-gray-600 border-r border-gray-300 whitespace-nowrap">blanjapoin.id/dash/</span>
                                <input type="text"
                                       name="link_blanjapoin_code"
                                       id="editLinkBlanjapoinCode"
                                       oninput="updateEditLinkBlanjapoin()"
                                       class="flex-1 px-4 py-3 h-12 border-0 focus:outline-none text-sm"
                                       placeholder="Code">
                                <input type="hidden" name="link_blanjapoin" id="editLinkBlanjapoinFull">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Informasi PIC --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi PIC</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama PIC --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama PIC Merchant
                            </label>
                            <input type="text"
                                   id="editMerchantNamaPic"
                                   name="nama_pic"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Masukkan nama PIC">
                        </div>

                        {{-- WA PIC --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                WhatsApp PIC
                            </label>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-orange-400 focus-within:border-transparent" id="editWaPicContainer">
                                <span class="px-4 py-3 bg-gray-50 text-sm text-gray-600 border-r border-gray-300 whitespace-nowrap">+62</span>
                                <input type="text"
                                       name="wa_pic_code"
                                       id="editWaPicCode"
                                       oninput="validateEditWaPic(); updateEditWaPic(); this.value = this.value.replace(/[^0-9]/g, '')"
                                       class="flex-1 px-4 py-3 h-12 border-0 focus:outline-none text-sm"
                                       placeholder="81234567890">
                                <input type="hidden" name="wa_pic" id="editWaPicFull">
                            </div>
                            <!-- <div id="editWaPicError" class="hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Nomor WhatsApp harus menggunakan operator Indonesia yang valid (contoh: 812, 813, 821, 822, 823, 851, 852, 853, 814, 815, 816, 855, 856, 857, 858, 817, 818, 819, 859, 877, 878, 831, 832, 833, 838, 895, 896, 897, 898, 899, 881, 882, 883, 884, 885, 886, 887, 888, 889)</span>
                            </div> -->
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email PIC (Opsional)
                            </label>
                            <input type="email"
                                   id="editMerchantEmailPic"
                                   name="email_pic"
                                   oninput="toggleEditKtpUpload()"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Masukkan email PIC">
                        </div>
                        <div id="editKtpUploadSection">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Upload KTP (Opsional)
                            </label>
                            <div class="relative">
                                <input type="file"
                                    id="editMerchantKtpInput"
                                    name="ktp_pic"
                                    accept="image/*"
                                    class="hidden"
                                    disabled
                                    onchange="previewEditMerchantKtp(this)">
                                <button type="button"
                                        id="editMerchantKtpBtn"
                                        onclick="handleEditKtpUploadClick()"
                                    class="w-full min-h-[120px] px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg focus:outline-none flex flex-col items-center justify-center text-gray-400 transition-all cursor-not-allowed opacity-60">
                                <i class="fas fa-upload text-3xl mb-2"></i>
                                <span id="editMerchantKtpText" class="text-sm">
                                        Isi email PIC terlebih dahulu
                                    </span>
                                <span class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG maks 2MB</span>
                                </button>
                                <div id="editMerchantKtpPreview" class="mt-3 hidden"></div>
                        </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Lokasi --}}
                <div id="editLokasiSection">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Alamat</h4>
                    <div class="space-y-4">
                        {{-- City Dropdown --}}
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kota/Kabupaten
                                </label>
                                <div class="relative">
                                <input type="hidden" name="city" id="editCityValue">
                                    <input type="text"
                                       id="editCitySearch"
                                       placeholder="Cari atau pilih kota..."
                                           autocomplete="off"
                                           class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                       onfocus="openEditCityDropdown(event)"
                                       onclick="openEditCityDropdown(event)"
                                       oninput="filterEditCityDropdown(this.value)">
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <div id="editCityDropdown" class="hidden absolute z-[100] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-hidden" style="top: 100%; left: 0;">
                                        <div class="max-h-60 overflow-y-auto">
                                        <div id="editCityOptions" class="py-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Alamat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Detail Alamat
                            </label>
                            <textarea id="editMerchantDetailAlamat"
                                      name="detail_alamat"
                                      rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm resize-none"
                                      placeholder="Masukkan detail alamat (jalan, nomor, RT/RW, dll)"></textarea>
                        </div>

                        {{-- Hidden field untuk menyimpan daerah (city) --}}
                        <input type="hidden" name="daerah" id="editDaerahCombined">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Link Google Maps --}}
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Link Google Maps
                                </label>
                                <div class="space-y-2 sm:space-y-0 sm:flex sm:gap-2">
                                    <input type="url"
                                           id="editMerchantLinkGmap"
                                           name="link_gmap"
                                           class="w-full sm:flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                           placeholder="Paste link Google Maps atau pilih lokasi"
                                           onpaste="setTimeout(() => validateGmapLink(this.value), 100)">
                                    <button type="button"
                                            onclick="openMapPicker('edit')"
                                            class="w-full sm:w-auto sm:flex-shrink-0 px-4 sm:px-6 h-12 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white rounded-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap font-medium shadow-sm hover:shadow-md">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Pilih Lokasi</span>
                                    </button>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span class="hidden sm:inline">Klik "Pilih Lokasi" untuk membuka peta interaktif atau paste link Google Maps langsung</span>
                                    <span class="sm:hidden">Pilih lokasi di peta atau paste link Google Maps</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Logo --}}
                <div id="editLogoSection">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Logo Merchant</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Upload Logo
                            </label>
                            <div class="relative">
                                <input type="file"
                                    id="editMerchantImageInput"
                                    name="logo_merchant"
                                    accept="image/*"
                                    class="hidden"
                                    onchange="previewEditMerchantImage(this)">
                                <button type="button"
                                        onclick="document.getElementById('editMerchantImageInput').click()"
                                    class="w-full min-h-[120px] px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all">
                                <i class="fas fa-upload text-3xl mb-2"></i>
                                <span id="editMerchantImageText" class="text-sm">
                                        Click to upload Logo Merchant
                                    </span>
                                <span class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG maks 2MB</span>
                                </button>
                                <div id="editMerchantImagePreview" class="mt-3 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer dengan tombol --}}
            <div class="sticky bottom-0 z-10 flex justify-end items-center gap-3 px-6 py-4 border-t bg-white">
                <button type="button"
                        onclick="closeEditMerchant()"
                        class="px-6 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all active:scale-95">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Include edit verification modal --}}
@include('partials.edit-verification-modal')

<script>
// Cities data from backend
window.editMerchantCities = {!! json_encode($cities ?? []) !!};
let currentEditMerchantId = null;

// ======================
// Preview & remove image
// ======================
function previewEditMerchantImage(input) {
    const preview = document.getElementById('editMerchantImagePreview');
    const text = document.getElementById('editMerchantImageText');
    if (!preview) return;

    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.classList.remove('hidden');
            if (text) text.textContent = file.name;
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                <button type="button" onclick="removeEditMerchantImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
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

function removeEditMerchantImage() {
    const input = document.getElementById('editMerchantImageInput');
    if (!input) return;
    input.value = '';
    previewEditMerchantImage(input);
}

// ======================
// Preview & remove KTP
// ======================
function previewEditMerchantKtp(input) {
    const preview = document.getElementById('editMerchantKtpPreview');
    const text = document.getElementById('editMerchantKtpText');
    if (!preview) return;

    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.classList.remove('hidden');
            if (text) text.textContent = file.name;
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                <button type="button" onclick="removeEditMerchantKtp()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        if (text) text.textContent = 'Click to upload KTP';
    }
}

function removeEditMerchantKtp() {
    const input = document.getElementById('editMerchantKtpInput');
    if (!input) return;
    input.value = '';
    previewEditMerchantKtp(input);
}

// ======================
// Toggle KTP Upload based on Email PIC
// ======================
function toggleEditKtpUpload() {
    const emailInput = document.getElementById('editMerchantEmailPic');
    const ktpInput = document.getElementById('editMerchantKtpInput');
    const ktpBtn = document.getElementById('editMerchantKtpBtn');
    const ktpText = document.getElementById('editMerchantKtpText');
    
    if (!emailInput || !ktpInput || !ktpBtn) return;
    
    const emailValue = emailInput.value.trim();
    // Basic email validation: must contain @ and . with at least one character before @ and after .
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isValidEmail = emailValue && emailRegex.test(emailValue);
    
    if (isValidEmail) {
        // Enable KTP upload
        ktpInput.disabled = false;
        ktpBtn.disabled = false;
        ktpBtn.classList.remove('cursor-not-allowed', 'opacity-60');
        ktpBtn.classList.add('hover:border-orange-400', 'focus:border-orange-500', 'hover:text-orange-600', 'text-gray-600', 'border-gray-300');
        if (ktpText) {
            ktpText.textContent = 'Click to upload KTP';
        }
    } else {
        // Disable KTP upload
        ktpInput.disabled = true;
        ktpBtn.disabled = true;
        ktpBtn.classList.add('cursor-not-allowed', 'opacity-60');
        ktpBtn.classList.remove('hover:border-orange-400', 'focus:border-orange-500', 'hover:text-orange-600', 'text-gray-600');
        if (ktpText) {
            ktpText.textContent = 'Isi email PIC terlebih dahulu';
        }
        
        // Clear KTP preview and input if email is cleared or invalid
        const ktpPreview = document.getElementById('editMerchantKtpPreview');
        if (ktpPreview && !ktpPreview.classList.contains('hidden')) {
            removeEditMerchantKtp();
        }
    }
}

function handleEditKtpUploadClick() {
    const emailInput = document.getElementById('editMerchantEmailPic');
    const ktpInput = document.getElementById('editMerchantKtpInput');
    
    if (!emailInput || !ktpInput) return;
    
    const emailValue = emailInput.value.trim();
    // Basic email validation: must contain @ and . with at least one character before @ and after .
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isValidEmail = emailValue && emailRegex.test(emailValue);
    
    if (isValidEmail && !ktpInput.disabled) {
        ktpInput.click();
    } else {
        alert('Mohon isi email PIC yang valid terlebih dahulu sebelum mengupload KTP');
        emailInput.focus();
    }
}

// ======================
// Open / Close modal
// ======================
function openEditMerchant(id, merchantData) {
    currentEditMerchantId = id;
    const modal = document.getElementById('editModalMerchant');
    const overlay = document.getElementById('editModalMerchantOverlay');
    const panel = document.getElementById('editModalMerchantPanel');
    const form = document.getElementById('formEditMerchant');
    
    if (!modal) return;

    // Debug: Log data merchant yang diterima
    console.log('Merchant Data:', merchantData);
    console.log('WA PIC Value:', merchantData.wa_pic);

    // Set form action
    form.action = `/merchants/${id}`;
    
    // Populate form with existing data
    const namaEl = document.getElementById('editMerchantNama');
    if (namaEl) namaEl.value = merchantData.nama_merchant || '';
    
    // Kategori
    if (merchantData.kategori) {
        selectEditMerchantKategori(merchantData.kategori);
    }
    
    // Link Blanjapoin - extract code from full link
    if (merchantData.link_blanjapoin) {
        const link = merchantData.link_blanjapoin;
        const match = link.match(/blanjapoin\.id\/dash\/(.+)/);
        if (match) {
            const linkCodeEl = document.getElementById('editLinkBlanjapoinCode');
            if (linkCodeEl) {
                linkCodeEl.value = match[1];
                updateEditLinkBlanjapoin();
            }
        }
    }
    
    // PIC - Pastikan semua field PIC terisi dengan benar dari data tabel
    const namaPicEl = document.getElementById('editMerchantNamaPic');
    const waPicCodeEl = document.getElementById('editWaPicCode');
    const emailPicEl = document.getElementById('editMerchantEmailPic');
    if (namaPicEl) {
        namaPicEl.value = merchantData.nama_pic || '';
    }
    if (waPicCodeEl) {
        // Extract nomor dari wa_pic (hilangkan +62 jika ada)
        const waPicValue = merchantData.wa_pic || '';
        let waPicCode = '';
        if (waPicValue) {
            // Jika dimulai dengan +62, hapus +62
            if (waPicValue.startsWith('+62')) {
                waPicCode = waPicValue.substring(3);
            } else if (waPicValue.startsWith('62')) {
                waPicCode = waPicValue.substring(2);
            } else {
                waPicCode = waPicValue;
            }
        }
        waPicCodeEl.value = waPicCode;
        updateEditWaPic();
    }
    if (emailPicEl) {
        emailPicEl.value = merchantData.email_pic || '';
    }
    
    // Lokasi - set city dari daerah
    if (merchantData.daerah) {
        const citySearch = document.getElementById('editCitySearch');
        const cityValue = document.getElementById('editCityValue');
        if (citySearch) {
            citySearch.value = merchantData.daerah || '';
        }
        if (cityValue) {
            cityValue.value = merchantData.daerah || '';
        }
    }
    
    document.getElementById('editMerchantDetailAlamat').value = merchantData.detail_daerah || '';
    document.getElementById('editMerchantLinkGmap').value = merchantData.link_gmap || '';
    
    // Update daerah combined
    updateEditDaerahCombined();
    
    // Logo preview jika ada
    if (merchantData.logo_merchant) {
        const preview = document.getElementById('editMerchantImagePreview');
        const text = document.getElementById('editMerchantImageText');
        if (preview) {
            preview.innerHTML = `
                <div class="relative">
                    <img src="/storage/${merchantData.logo_merchant}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                    <button type="button" onclick="removeEditMerchantImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `;
            preview.classList.remove('hidden');
        }
        if (text) text.textContent = 'Logo saat ini';
    }
    
    // KTP preview jika ada
    if (merchantData.ktp_pic) {
        const preview = document.getElementById('editMerchantKtpPreview');
        const text = document.getElementById('editMerchantKtpText');
        if (preview) {
            preview.innerHTML = `
                <div class="relative">
                    <img src="/storage/${merchantData.ktp_pic}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                    <button type="button" onclick="removeEditMerchantKtp()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `;
            preview.classList.remove('hidden');
        }
        if (text) text.textContent = 'KTP saat ini';
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Preload provinsi data saat modal dibuka
    if (typeof allProvinsiOptions === 'undefined' || allProvinsiOptions.length === 0) {
        if (typeof fetchProvinces === 'function') {
            fetchProvinces();
        }
    }
    
    requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        
        // Ensure KTP upload is disabled/enabled based on email
        toggleEditKtpUpload();
        
        // Check if kategori is already selected and toggle fields accordingly
        const kategoriInput = document.getElementById('editMerchantKategoriValue');
        if (kategoriInput && kategoriInput.value) {
            toggleEditFieldsByKategori(kategoriInput.value);
        }
        
        // Pastikan semua field terisi setelah modal terbuka
        setTimeout(() => {
            // Update WA PIC setelah modal terbuka
            updateEditWaPic();
        }, 50);
    });
}

function closeEditMerchant() {
    const modal = document.getElementById('editModalMerchant');
    const overlay = document.getElementById('editModalMerchantOverlay');
    const panel = document.getElementById('editModalMerchantPanel');
    
    overlay?.classList.remove('opacity-100');
    overlay?.classList.add('opacity-0');
    panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');

    setTimeout(() => {
        modal?.classList.add('hidden');
        document.body.style.overflow = '';

        const form = document.getElementById('formEditMerchant');
        if (form) {
            form.reset();
            // Reset kategori dropdown
            const kategoriInput = document.getElementById('editMerchantKategoriValue');
            const kategoriLabel = document.getElementById('editMerchantKategoriLabel');
            const kategoriBtn = document.getElementById('editMerchantKategoriBtn');
            if (kategoriInput) kategoriInput.value = '';
            if (kategoriLabel) kategoriLabel.textContent = 'Pilih kategori';
            if (kategoriBtn) {
                kategoriBtn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400';
            }

            // Reset city dropdown
            const citySearch = document.getElementById('editCitySearch');
            const cityValue = document.getElementById('editCityValue');
            if (citySearch) citySearch.value = '';
            if (cityValue) cityValue.value = '';
            document.getElementById('editCityOptions').innerHTML = '';
            document.getElementById('editDaerahCombined').value = '';
            closeEditCityDropdown();
            
            // Reset link blanjapoin
            document.getElementById('editLinkBlanjapoinCode').value = '';
            document.getElementById('editLinkBlanjapoinFull').value = '';
            
            // Reset WA PIC
            document.getElementById('editWaPicCode').value = '';
            document.getElementById('editWaPicFull').value = '';
            
            // Reset preview image
            const preview = document.getElementById('editMerchantImagePreview');
            const text = document.getElementById('editMerchantImageText');
            if (preview) {
                preview.innerHTML = '';
                preview.classList.add('hidden');
            }
            if (text) text.textContent = 'Click to upload Logo Merchant';
            
            // Reset preview KTP
            const ktpPreview = document.getElementById('editMerchantKtpPreview');
            const ktpText = document.getElementById('editMerchantKtpText');
            if (ktpPreview) {
                ktpPreview.innerHTML = '';
                ktpPreview.classList.add('hidden');
            }
            if (ktpText) ktpText.textContent = 'Click to upload KTP';
        }
    }, 300);
}

// ======================
// Dropdown kategori
// ======================
function toggleEditMerchantKategoriDropdown() {
    const dropdown = document.getElementById('editMerchantKategoriDropdown');
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
        }, 200);
    }
}

function selectEditMerchantKategori(value) {
    const hiddenInput = document.getElementById('editMerchantKategoriValue');
    const labelSpan = document.getElementById('editMerchantKategoriLabel');
    const btn = document.getElementById('editMerchantKategoriBtn');
    const dropdown = document.getElementById('editMerchantKategoriDropdown');

    if (hiddenInput) hiddenInput.value = value;
    if (labelSpan) labelSpan.textContent = value.charAt(0).toUpperCase() + value.slice(1);

    if (btn) {
        btn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border transition-all duration-300';

        const colorMap = {
            'kuliner': ['border-orange-300', 'text-orange-800', 'from-orange-100', 'to-red-100'],
            'hiburan': ['border-purple-300', 'text-purple-800', 'from-purple-100', 'to-pink-100'],
            'liburan': ['border-blue-300', 'text-blue-800', 'from-blue-100', 'to-cyan-100'],
            'belanja': ['border-green-300', 'text-green-800', 'from-green-100', 'to-emerald-100'],
            'kecantikan': ['border-pink-300', 'text-pink-800', 'from-pink-100', 'to-rose-100'],
            'telkomsel': ['border-indigo-300', 'text-indigo-800', 'from-indigo-100', 'to-blue-100']
        };
        
        const colors = colorMap[value] || ['border-gray-300', 'text-gray-700'];
        btn.classList.add(...colors);
        if (colors.length > 2) {
            btn.classList.add('bg-gradient-to-r', colors[2], colors[3]);
        } else {
            btn.classList.add('hover:bg-gray-50');
        }
    }

    if (dropdown) {
        dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-6px)';
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
    
    // Toggle visibility berdasarkan kategori
    toggleEditFieldsByKategori(value);
}

// Function to toggle field visibility based on kategori
function toggleEditFieldsByKategori(kategori) {
    const lokasiSection = document.getElementById('editLokasiSection');
    const ktpUploadSection = document.getElementById('editKtpUploadSection');
    const logoSection = document.getElementById('editLogoSection');
    
    const isTelkomsel = kategori === 'telkomsel';
    
    // Toggle Lokasi Section
    if (lokasiSection) {
        if (isTelkomsel) {
            lokasiSection.style.display = 'none';
            // Clear values when hiding
            const citySearch = document.getElementById('editCitySearch');
            const cityValue = document.getElementById('editCityValue');
            if (citySearch) citySearch.value = '';
            if (cityValue) cityValue.value = '';
            document.querySelector('textarea[name="detail_alamat"]').value = '';
            document.getElementById('editMerchantLinkGmap').value = '';
            document.getElementById('editDaerahCombined').value = '';
        } else {
            lokasiSection.style.display = 'block';
        }
    }
    
    // Toggle KTP Upload Section
    if (ktpUploadSection) {
        if (isTelkomsel) {
            ktpUploadSection.style.display = 'none';
            // Clear KTP upload if exists
            const ktpInput = document.getElementById('editMerchantKtpInput');
            if (ktpInput) {
                ktpInput.value = '';
                removeEditMerchantKtp();
            }
        } else {
            ktpUploadSection.style.display = 'block';
        }
    }
    
    // Logo Section tetap visible untuk semua kategori termasuk telkomsel
    // Tidak perlu di-hide
}

// Klik di luar dropdown kategori → tutup
document.addEventListener('click', function (event) {
    const btn = document.getElementById('editMerchantKategoriBtn');
    const dropdown = document.getElementById('editMerchantKategoriDropdown');
    if (!btn || !dropdown) return;

    if (!btn.contains(event.target) && !dropdown.contains(event.target) && !dropdown.classList.contains('hidden')) {
        dropdown.style.transition = 'opacity .2s ease, transform .2s ease';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-6px)';
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
});

// ======================
// Update Link Blanjapoin
// ======================
function updateEditLinkBlanjapoin() {
    const code = document.getElementById('editLinkBlanjapoinCode').value.trim();
    // Format sesuai dengan yang diharapkan controller: blanjapoin.id/dash/{code}
    const fullLink = code ? `blanjapoin.id/dash/${code}` : '';
    document.getElementById('editLinkBlanjapoinFull').value = fullLink;
}

// ======================
// Validate WA PIC (Indonesian Mobile Prefixes)
// ======================
function validateEditWaPic() {
    const code = document.getElementById('editWaPicCode').value.trim();
    const errorDiv = document.getElementById('editWaPicError');
    const container = document.getElementById('editWaPicContainer');
    
    // Valid Indonesian mobile prefixes (without leading 0, since +62 is already there)
    const validPrefixes = [
        '811', '812', '813',
        '821', '822', '823',
        '851', '852', '853',
        '814', '815', '816',
        '855', '856', '857', '858',
        '817', '818', '819',
        '859',
        '877', '878',
        '831', '832', '833', '838',
        '895', '896', '897', '898', '899',
        '881', '882', '883', '884', '885', '886', '887', '888', '889'
    ];
    
    // If empty, hide error
    if (!code) {
        if (errorDiv) errorDiv.classList.add('hidden');
        if (container) {
            container.classList.remove('border-red-500');
            container.classList.add('border-gray-300');
        }
        return true;
    }
    
    // Check if number starts with valid prefix (minimum 3 digits for prefix)
    if (code.length >= 3) {
        const prefix = code.substring(0, 3);
        if (validPrefixes.includes(prefix)) {
            if (errorDiv) errorDiv.classList.add('hidden');
            if (container) {
                container.classList.remove('border-red-500');
                container.classList.add('border-gray-300');
            }
            return true;
        }
    }
    
    // Show error if prefix is invalid
    if (code.length >= 3) {
        if (errorDiv) errorDiv.classList.remove('hidden');
        if (container) {
            container.classList.add('border-red-500');
            container.classList.remove('border-gray-300');
        }
        return false;
    }
    
    // Hide error while typing (less than 3 digits)
    if (errorDiv) errorDiv.classList.add('hidden');
    if (container) {
        container.classList.remove('border-red-500');
        container.classList.add('border-gray-300');
    }
    return true;
}

// ======================
// Update WA PIC
// ======================
function updateEditWaPic() {
    const code = document.getElementById('editWaPicCode').value.trim();
    // Format: +62{code} (tanpa spasi)
    const fullWa = code ? `+62${code}` : '';
    document.getElementById('editWaPicFull').value = fullWa;
}

// ======================
// City Dropdown Functions
// ======================

// Store cities from backend
const editCities = window.editMerchantCities || [];

// Open city dropdown
function openEditCityDropdown(event) {
    if (event) {
        event.stopPropagation();
    }
    
    const dropdown = document.getElementById('editCityDropdown');
    const optionsContainer = document.getElementById('editCityOptions');
    
    if (!dropdown || !optionsContainer) {
        console.error('City dropdown elements not found');
        return;
    }
    
    // Open dropdown
    dropdown.classList.remove('hidden');
    dropdown.style.display = 'block';
    dropdown.style.opacity = '1';
    dropdown.style.visibility = 'visible';
    
    // Render cities
    renderEditCityOptions(editCities);
}

// Close city dropdown
function closeEditCityDropdown() {
    const dropdown = document.getElementById('editCityDropdown');
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.style.display = 'none';
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
    }
}

// Filter city dropdown
function filterEditCityDropdown(searchTerm) {
    const filtered = searchTerm 
        ? editCities.filter(city => city.toLowerCase().includes(searchTerm.toLowerCase()))
        : editCities;
    
    // Open dropdown if not already open
    const dropdown = document.getElementById('editCityDropdown');
    if (dropdown) {
        dropdown.classList.remove('hidden');
    }
    
    renderEditCityOptions(filtered);
}

// Render city options
function renderEditCityOptions(cityList) {
    const container = document.getElementById('editCityOptions');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!cityList || cityList.length === 0) {
        container.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Tidak ada data kota</div>';
        return;
    }
    
    cityList.forEach(city => {
        const div = document.createElement('div');
        div.className = 'px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 cursor-pointer transition-colors';
        div.textContent = city;
        div.onclick = () => selectEditCity(city);
        container.appendChild(div);
    });
}

// Select city
function selectEditCity(city) {
    const searchInput = document.getElementById('editCitySearch');
    const hiddenInput = document.getElementById('editCityValue');
    
    if (searchInput) searchInput.value = city;
    if (hiddenInput) hiddenInput.value = city;
    
    closeEditCityDropdown();
        updateEditDaerahCombined();
}

// Update daerah combined (only city now)
function updateEditDaerahCombined() {
    const citySearch = document.getElementById('editCitySearch');
    
    const daerahCombined = citySearch && citySearch.value ? citySearch.value.trim() : '';
    document.getElementById('editDaerahCombined').value = daerahCombined;
    
    console.log('Edit Daerah combined updated:', daerahCombined);
    return daerahCombined;
}

// ======================
// Handle form submit - tampilkan modal verifikasi
// ======================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditMerchant');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate required fields
            const namaMerchant = form.querySelector('input[name="nama_merchant"]').value.trim();
            const kategori = form.querySelector('input[name="kategori"]').value.trim();
            
            if (!namaMerchant || !kategori) {
                alert('Mohon isi field yang diperlukan (Nama Merchant, Kategori)');
                return false;
            }
            
            // Update link blanjapoin sebelum submit
            updateEditLinkBlanjapoin();
            
            // Validate WA PIC
            const waPicCode = document.getElementById('editWaPicCode').value.trim();
            if (waPicCode && !validateEditWaPic()) {
                alert('Nomor WhatsApp harus menggunakan operator Indonesia yang valid');
                document.getElementById('editWaPicCode').focus();
                return false;
            }
            
            // Update WA PIC sebelum submit
            updateEditWaPic();
            
            // Update daerah (city) sebelum submit
            updateEditDaerahCombined();
            
            // Debug: Log form data sebelum submit
            const formData = new FormData(form);
            console.log('=== EDIT MERCHANT FORM DATA SEBELUM SUBMIT ===');
            for (let [key, value] of formData.entries()) {
                if (key !== 'logo_merchant') {
                    console.log(`${key}:`, value);
                } else {
                    console.log(`${key}:`, value instanceof File ? `File: ${value.name} (${value.size} bytes)` : value);
                }
            }
            console.log('================================');
            
            // Tampilkan modal verifikasi
            if (typeof showEditVerification === 'function') {
                showEditVerification(formData, 'Merchant');
            } else {
                // Fallback: submit langsung jika modal verifikasi tidak tersedia
                form.submit();
            }
        });
    }
    
    // Close modal when clicking overlay
    const modal = document.getElementById('editModalMerchant');
    const overlay = document.getElementById('editModalMerchantOverlay');
    if (modal && overlay) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal || event.target === overlay) {
                closeEditMerchant();
            }
        });
    }
    
    // Close dropdown saat klik di luar
    let clickTimeout;
    document.addEventListener('click', function(event) {
        const target = event.target;
        
        // Skip jika klik di input atau dropdown option
        if (target.id === 'editCitySearch' || target.closest('#editCityOptions')) {
            clearTimeout(clickTimeout);
            return;
        }
        
        // Clear previous timeout
        clearTimeout(clickTimeout);
        
        // Delay untuk memastikan open event sudah selesai
        clickTimeout = setTimeout(() => {
            const cityInput = document.getElementById('editCitySearch');
            const cityDropdown = document.getElementById('editCityDropdown');
            
            // Check if click is inside dropdown container
            const cityContainer = cityInput?.closest('.relative');
            
            // Close city dropdown if click is outside
            if (cityDropdown && !cityDropdown.classList.contains('hidden')) {
                if (!cityContainer?.contains(target) && !cityDropdown?.contains(target)) {
                    closeEditCityDropdown();
                }
            }
        }, 150);
    });
    
    // Close dropdown saat tekan ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditCityDropdown();
        }
    });
    
    // Initialize KTP upload state (disabled by default)
    toggleEditKtpUpload();
});

</script>
