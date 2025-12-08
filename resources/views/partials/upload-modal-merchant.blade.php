<div id="uploadModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="uploadModalMerchantOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <div id="uploadModalMerchantPanel" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
        {{-- Header --}}
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-xl font-bold text-gray-800">
                Tambah Merchant Baru
            </h3>
            <button type="button"
                    onclick="closeUploadMerchant()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- FORM: Merchant form --}}
        <form id="formUploadMerchant"
      action="{{ route('merchants.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="flex-1 overflow-y-auto">

    @csrf
            <div class="p-6 space-y-6">
                {{-- Section 1: Informasi Dasar --}}
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama Merchant --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Merchant / Produk<span class="text-red-500">*</span>
                            </label>
                            <input type="text"
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
                                    class="hidden absolute left-0 mt-2 bg-white rounded-xl shadow-xl p-2 border border-gray-200 w-full z-50"
                                >
                                    <div class="py-1 text-sm">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all" onclick="selectMerchantKategori('kuliner')">
                                            Kuliner
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all" onclick="selectMerchantKategori('hiburan')">
                                            Hiburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all" onclick="selectMerchantKategori('liburan')">
                                            Liburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all" onclick="selectMerchantKategori('belanja')">
                                            Belanja
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all" onclick="selectMerchantKategori('kecantikan')">
                                            Kecantikan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all" onclick="selectMerchantKategori('telkomsel')">
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
                                       id="linkBlanjapoinCode"
                                       oninput="updateLinkBlanjapoin()"
                                       class="flex-1 px-4 py-3 h-12 border-0 focus:outline-none text-sm"
                                       placeholder="Code">
                                <input type="hidden" name="link_blanjapoin" id="linkBlanjapoinFull">
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
                                Nama PIC Merchant / Produk
                            </label>
                            <input type="text"
                                   name="nama_pic"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Masukkan nama PIC">
                        </div>

                        {{-- WA PIC --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                WhatsApp PIC
                            </label>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-orange-400 focus-within:border-transparent" id="waPicContainer">
                                <span class="px-4 py-3 bg-gray-50 text-sm text-gray-600 border-r border-gray-300 whitespace-nowrap">+62</span>
                                <input type="text"
                                       name="wa_pic_code"
                                       id="waPicCode"
                                       oninput="validateWaPic(); updateWaPic(); this.value = this.value.replace(/[^0-9]/g, '')"
                                       class="flex-1 px-4 py-3 h-12 border-0 focus:outline-none text-sm"
                                       placeholder="81234567890">
                                <input type="hidden" name="wa_pic" id="waPicFull">
                            </div>
                            <!-- <div id="waPicError" class="hidden mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Nomor WhatsApp harus menggunakan operator Indonesia yang valid (contoh: 812, 813, 821, 822, 823, 851, 852, 853, 814, 815, 816, 855, 856, 857, 858, 817, 818, 819, 859, 877, 878, 831, 832, 833, 838, 895, 896, 897, 898, 899, 881, 882, 883, 884, 885, 886, 887, 888, 889)</span>
                            </div> -->
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email PIC
                            </label>
                            <input type="email"
                                   id="emailPicInput"
                                   name="email_pic"
                                   oninput="toggleKtpUpload()"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Masukkan email PIC">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Upload KTP (Opsional)
                            </label>
                            <div class="relative">
                                <input type="file"
                                    id="uploadMerchantKtpInput"
                                    name="ktp_pic"
                                    accept="image/*"
                                    class="hidden"
                                    disabled
                                    onchange="previewUploadMerchantKtp(this)">
                                <button type="button"
                                        id="uploadMerchantKtpBtn"
                                        onclick="handleKtpUploadClick()"
                                    class="w-full min-h-[120px] px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg focus:outline-none flex flex-col items-center justify-center text-gray-400 transition-all cursor-not-allowed opacity-60">
                                <i class="fas fa-upload text-3xl mb-2"></i>
                                <span id="uploadMerchantKtpText" class="text-sm">
                                        Isi email PIC terlebih dahulu
                                    </span>
                                <span class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG maks 2MB</span>
                                </button>
                                <div id="uploadMerchantKtpPreview" class="mt-3 hidden"></div>
                        </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Lokasi --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Alamat</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Provinsi --}}
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Provinsi
                                </label>
                                <div class="relative">
                                    <input type="hidden" name="provinsi" id="provinsiValue">
                                    <input type="text"
                                           id="provinsiSearch"
                                           placeholder="Cari atau pilih provinsi..."
                                           autocomplete="off"
                                           class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                           onfocus="openSearchableDropdown('provinsi', event)"
                                           onclick="openSearchableDropdown('provinsi', event)"
                                           oninput="filterSearchableDropdown('provinsi', this.value)">
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                    <div id="provinsiDropdown" class="hidden absolute z-[100] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-hidden" style="top: 100%; left: 0;">
                                        <div class="max-h-60 overflow-y-auto">
                                            <div id="provinsiOptions" class="py-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kabupaten --}}
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kabupaten/Kota
                                </label>
                                <div class="relative">
                                    <input type="hidden" name="kabupaten" id="kabupatenValue">
                                    <input type="text"
                                           id="kabupatenSearch"
                                           placeholder="Cari atau pilih kabupaten..."
                                           autocomplete="off"
                                           class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                           onfocus="openSearchableDropdown('kabupaten', event)"
                                           onclick="openSearchableDropdown('kabupaten', event)"
                                           oninput="filterSearchableDropdown('kabupaten', this.value)">
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                    <div id="kabupatenDropdown" class="hidden absolute z-[100] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-hidden" style="top: 100%; left: 0;">
                                        <div class="max-h-60 overflow-y-auto">
                                            <div id="kabupatenOptions" class="py-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kecamatan --}}
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kecamatan
                                </label>
                                <div class="relative">
                                    <input type="hidden" name="kecamatan" id="kecamatanValue">
                                    <input type="text"
                                           id="kecamatanSearch"
                                           placeholder="Cari atau pilih kecamatan..."
                                           autocomplete="off"
                                           class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                           onfocus="openSearchableDropdown('kecamatan', event)"
                                           onclick="openSearchableDropdown('kecamatan', event)"
                                           oninput="filterSearchableDropdown('kecamatan', this.value)">
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                    <div id="kecamatanDropdown" class="hidden absolute z-[100] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-hidden" style="top: 100%; left: 0;">
                                        <div class="max-h-60 overflow-y-auto">
                                            <div id="kecamatanOptions" class="py-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Alamat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Detail Alamat
                            </label>
                            <textarea name="detail_alamat"
                                      rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm resize-none"
                                      placeholder="Masukkan detail alamat (jalan, nomor, RT/RW, dll)"></textarea>
                        </div>

                        {{-- Hidden field untuk menyimpan daerah (dikombinasikan) --}}
                        <input type="hidden" name="daerah" id="daerahCombined">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Link Google Maps --}}
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Link Google Maps
                                </label>
                                <div class="space-y-2 sm:space-y-0 sm:flex sm:gap-2">
                                    <input type="url"
                                           id="merchantLinkGmap"
                                           name="link_gmap"
                                           class="w-full sm:flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                           placeholder="Paste link Google Maps atau pilih lokasi"
                                           onpaste="setTimeout(() => validateGmapLink(this.value), 100)">
                                    <button type="button"
                                            onclick="openMapPicker('upload')"
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
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Logo Merchant</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Upload Logo
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
                                    class="w-full min-h-[120px] px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all">
                                <i class="fas fa-upload text-3xl mb-2"></i>
                                <span id="merchantImageText" class="text-sm">
                                        Click to upload Logo Merchant
                                    </span>
                                <span class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG maks 2MB</span>
                                </button>
                                <div id="merchantImagePreview" class="mt-3 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer dengan tombol --}}
            <div class="sticky bottom-0 z-10 flex justify-end items-center gap-3 px-6 py-4 border-t bg-white">
                <button type="button"
                        onclick="closeUploadMerchant()"
                        class="px-6 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all active:scale-95">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Peta Leaflet (OpenStreetMap) untuk Memilih Lokasi --}}
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<style>
    @media (min-width: 768px) {
        #mapContainer {
            min-height: 400px !important;
            height: 400px !important;
        }
        #map {
            height: 400px !important;
        }
    }
    @media (max-width: 767px) {
        #mapContainer {
            min-height: 300px !important;
            height: 300px !important;
        }
        #map {
            height: 300px !important;
        }
    }
    /* Leaflet map styling */
    .leaflet-container {
        font-family: 'Poppins', sans-serif;
    }
</style>
<div id="mapPickerModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-2 md:p-4">
    <div id="mapPickerOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeMapPicker()"></div>
    
    <div id="mapPickerPanel" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] md:max-h-[80vh] flex flex-col overflow-hidden z-10 transform transition-all duration-300 scale-95 opacity-0 m-2 md:m-4">
        {{-- Header Modal --}}
        <div class="flex justify-between items-center px-5 py-3 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-base font-bold text-gray-800">
                <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                Pilih Lokasi di Peta
            </h3>
            <button type="button"
                    onclick="closeMapPicker()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Body Modal dengan Peta --}}
        <div class="flex-1 flex flex-col overflow-y-auto" style="min-height: 400px;">
            {{-- Search Box --}}
            <div class="p-3 border-b">
                <div class="flex gap-2">
                    <input type="text"
                           id="mapSearchInput"
                           placeholder="Cari lokasi (contoh: Jalan Sudirman, Jakarta)..."
                           class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                           onkeypress="if(event.key === 'Enter') { event.preventDefault(); searchLocation(); }">
                    <button type="button"
                            onclick="searchLocation()"
                            class="px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            {{-- Peta Container --}}
            <div id="mapContainer" class="flex-1 relative w-full" style="min-height: 300px; height: 300px;">
                <div id="map" class="w-full h-full" style="z-index: 1; height: 300px; width: 100%; position: relative;"></div>
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-white px-4 py-2 rounded-lg shadow-lg border border-gray-200 z-20 pointer-events-none">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-info-circle text-orange-500 mr-1"></i>
                        Klik di peta untuk memilih lokasi
                    </p>
                </div>
            </div>
            
            {{-- Info Lokasi yang Dipilih --}}
            <div id="selectedLocationInfo" class="hidden p-3 border-t bg-gray-50 sticky bottom-0 z-30">
                <div class="flex flex-col md:flex-row md:items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 mb-1">Lokasi yang Dipilih:</p>
                        <p id="selectedAddress" class="text-sm text-gray-600 break-words"></p>
                        <p id="selectedCoordinates" class="text-xs text-gray-500 mt-1 break-words"></p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                        <button type="button"
                                onclick="closeMapPicker()"
                                class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors whitespace-nowrap">
                            Batal
                        </button>
                        <button type="button"
                                onclick="confirmLocation()"
                                class="px-4 py-2 text-sm bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors whitespace-nowrap">
                            <i class="fas fa-check mr-1"></i>Gunakan Lokasi Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Include upload verification modal --}}
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
                <button type="button" onclick="removeMerchantImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
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
// Toggle KTP Upload based on Email PIC
// ======================
function toggleKtpUpload() {
    const emailInput = document.getElementById('emailPicInput');
    const ktpInput = document.getElementById('uploadMerchantKtpInput');
    const ktpBtn = document.getElementById('uploadMerchantKtpBtn');
    const ktpText = document.getElementById('uploadMerchantKtpText');
    
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
        const ktpPreview = document.getElementById('uploadMerchantKtpPreview');
        if (ktpPreview && !ktpPreview.classList.contains('hidden')) {
            removeUploadMerchantKtp();
        }
    }
}

function handleKtpUploadClick() {
    const emailInput = document.getElementById('emailPicInput');
    const ktpInput = document.getElementById('uploadMerchantKtpInput');
    
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
// Preview & remove KTP
// ======================
function previewUploadMerchantKtp(input) {
    const preview = document.getElementById('uploadMerchantKtpPreview');
    const text = document.getElementById('uploadMerchantKtpText');
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
                <button type="button" onclick="removeUploadMerchantKtp()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
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

function removeUploadMerchantKtp() {
    const input = document.getElementById('uploadMerchantKtpInput');
    if (!input) return;
    input.value = '';
    previewUploadMerchantKtp(input);
}

// ======================
// Open / Close modal
// ======================
function openUploadMerchant() {
    const modal = document.getElementById('uploadModalMerchant');
    const overlay = document.getElementById('uploadModalMerchantOverlay');
    const panel = document.getElementById('uploadModalMerchantPanel');
    
    if (!modal) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Preload provinsi data saat modal dibuka
    if (allProvinsiOptions.length === 0) {
        fetchProvinces();
    }
    
    requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        
        // Ensure KTP upload is disabled on modal open
        toggleKtpUpload();
    });
}

function closeUploadMerchant() {
    const modal = document.getElementById('uploadModalMerchant');
    const overlay = document.getElementById('uploadModalMerchantOverlay');
    const panel = document.getElementById('uploadModalMerchantPanel');
    
    overlay?.classList.remove('opacity-100');
    overlay?.classList.add('opacity-0');
    panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');

    setTimeout(() => {
        modal?.classList.add('hidden');
        document.body.style.overflow = '';

        const form = document.getElementById('formUploadMerchant');
        if (form) {
            form.reset();
            // Reset kategori dropdown
        const kategoriInput = document.getElementById('merchantKategoriValue');
        const kategoriLabel = document.getElementById('merchantKategoriLabel');
            const kategoriBtn = document.getElementById('merchantKategoriBtn');
        if (kategoriInput) kategoriInput.value = '';
        if (kategoriLabel) kategoriLabel.textContent = 'Pilih kategori';
        if (kategoriBtn) {
            kategoriBtn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400';
        }

            // Reset lokasi dropdowns
            document.getElementById('provinsiSearch').value = '';
            document.getElementById('provinsiValue').value = '';
            document.getElementById('provinsiOptions').innerHTML = '';
            document.getElementById('kabupatenSearch').value = '';
            document.getElementById('kabupatenValue').value = '';
            document.getElementById('kabupatenOptions').innerHTML = '';
            document.getElementById('kecamatanSearch').value = '';
            document.getElementById('kecamatanValue').value = '';
            document.getElementById('kecamatanOptions').innerHTML = '';
            document.getElementById('daerahCombined').value = '';
            allKabupatenOptions = [];
            allKecamatanOptions = [];
            selectedProvinceCode = null;
            selectedRegencyCode = null;
            closeSearchableDropdown('provinsi');
            closeSearchableDropdown('kabupaten');
            closeSearchableDropdown('kecamatan');
            
            // Reset link blanjapoin
            document.getElementById('linkBlanjapoinCode').value = '';
            document.getElementById('linkBlanjapoinFull').value = '';
            
            // Reset WA PIC
            document.getElementById('waPicCode').value = '';
            document.getElementById('waPicFull').value = '';
            
            // Reset Email PIC and disable KTP upload
            const emailPicInput = document.getElementById('emailPicInput');
            if (emailPicInput) {
                emailPicInput.value = '';
                toggleKtpUpload(); // Disable KTP upload after reset
            }
            
            // Reset preview image
        const preview = document.getElementById('merchantImagePreview');
        const text = document.getElementById('merchantImageText');
        if (preview) {
            preview.innerHTML = '';
            preview.classList.add('hidden');
        }
        if (text) text.textContent = 'Click to upload Logo Merchant';
        
        // Reset preview KTP
        const ktpPreview = document.getElementById('uploadMerchantKtpPreview');
        const ktpText = document.getElementById('uploadMerchantKtpText');
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
        }, 200);
    }
}

function selectMerchantKategori(value) {
    const hiddenInput = document.getElementById('merchantKategoriValue');
    const labelSpan = document.getElementById('merchantKategoriLabel');
    const btn = document.getElementById('merchantKategoriBtn');
    const dropdown = document.getElementById('merchantKategoriDropdown');

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
}

// Klik di luar dropdown kategori → tutup
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
        }, 200);
    }
});

// ======================
// Update Link Blanjapoin
// ======================
function updateLinkBlanjapoin() {
    const code = document.getElementById('linkBlanjapoinCode').value.trim();
    // Format sesuai dengan yang diharapkan controller: blanjapoin.id/dash/{code}
    const fullLink = code ? `blanjapoin.id/dash/${code}` : '';
    document.getElementById('linkBlanjapoinFull').value = fullLink;
}

// ======================
// Validate WA PIC (Indonesian Mobile Prefixes)
// ======================
function validateWaPic() {
    const code = document.getElementById('waPicCode').value.trim();
    const errorDiv = document.getElementById('waPicError');
    const container = document.getElementById('waPicContainer');
    
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
function updateWaPic() {
    const code = document.getElementById('waPicCode').value.trim();
    // Format: +62{code} (tanpa spasi)
    const fullWa = code ? `+62${code}` : '';
    document.getElementById('waPicFull').value = fullWa;
}

// ======================
// Leaflet Map Functions (OpenStreetMap)
// ======================
let mapPickerMap = null;
let mapPickerMarker = null;
let selectedLocationData = null;
let mapPickerMode = 'upload'; // 'upload' or 'edit'

// Validate URL (accepts any URL now, not just Google Maps)
function validateGmapLink(url) {
    if (!url || url.trim() === '') {
        return true;
    }
    
    // Basic URL validation
    try {
        new URL(url);
        return true;
    } catch (e) {
        return false;
    }
}

// Open map picker modal
function openMapPicker(mode) {
    mapPickerMode = mode || 'upload';
    const modal = document.getElementById('mapPickerModal');
    const overlay = document.getElementById('mapPickerOverlay');
    const panel = document.getElementById('mapPickerPanel');
    
    if (!modal) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95');
        panel?.classList.add('opacity-100', 'scale-100');
    });
    
    // Initialize map after modal is shown
    setTimeout(() => {
        initMapPicker();
    }, 300);
}

// Close map picker modal
function closeMapPicker() {
    const modal = document.getElementById('mapPickerModal');
    const overlay = document.getElementById('mapPickerOverlay');
    const panel = document.getElementById('mapPickerPanel');
    
    overlay?.classList.remove('opacity-100');
    overlay?.classList.add('opacity-0');
    panel?.classList.remove('opacity-100', 'scale-100');
    panel?.classList.add('opacity-0', 'scale-95');
    
    setTimeout(() => {
        modal?.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Clean up map
        if (mapPickerMap) {
            mapPickerMap.remove();
            mapPickerMap = null;
            mapPickerMarker = null;
            selectedLocationData = null;
        }
        
        // Reset UI
        const selectedInfo = document.getElementById('selectedLocationInfo');
        if (selectedInfo) selectedInfo.classList.add('hidden');
        const searchInput = document.getElementById('mapSearchInput');
        if (searchInput) searchInput.value = '';
    }, 300);
}

// Initialize Leaflet map
function initMapPicker() {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;
    
    // Remove existing map if any
    if (mapPickerMap) {
        mapPickerMap.remove();
    }
    
    // Default center: Jakarta, Indonesia
    const defaultCenter = [-6.2088, 106.8456];
    const defaultZoom = 13;
    
    // Initialize map
    mapPickerMap = L.map('map').setView(defaultCenter, defaultZoom);
    
    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(mapPickerMap);
    
    // Add click handler to map
    mapPickerMap.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Immediately update link field for instant feedback
        updateLinkGmapField(lat, lng);
        
        // Reverse geocode to get address (will also update link again as backup)
        reverseGeocode(lat, lng);
        
        // Add/update marker
        if (mapPickerMarker) {
            mapPickerMarker.setLatLng([lat, lng]);
        } else {
            mapPickerMarker = L.marker([lat, lng], {
                draggable: true,
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(mapPickerMap);
            
            // Handle marker drag
            mapPickerMarker.on('dragend', function() {
                const position = mapPickerMarker.getLatLng();
                // Immediately update link field
                updateLinkGmapField(position.lat, position.lng);
                // Then reverse geocode for address
                reverseGeocode(position.lat, position.lng);
            });
        }
        
        // Show selected location info
        showSelectedLocationInfo(lat, lng);
    });
    
    // Try to use geolocation if available
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            mapPickerMap.setView([lat, lng], 15);
        }, function(error) {
            console.log('Geolocation error:', error);
        });
    }
}

// Reverse geocode coordinates to address
async function reverseGeocode(lat, lng) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`,
            {
                headers: {
                    'User-Agent': 'BlanjaPoin-LocationPicker/1.0'
                }
            }
        );
        
        if (!response.ok) {
            throw new Error('Reverse geocoding failed');
        }
        
        const data = await response.json();
        const address = data.display_name || `${lat}, ${lng}`;
        
        selectedLocationData = {
            lat: lat,
            lng: lng,
            address: address,
            fullData: data
        };
        
        updateSelectedLocationDisplay(lat, lng, address);
        // Auto-update link to input field
        updateLinkGmapField(lat, lng);
    } catch (error) {
        console.error('Reverse geocoding error:', error);
        const address = `${lat}, ${lng}`;
        selectedLocationData = {
            lat: lat,
            lng: lng,
            address: address
        };
        updateSelectedLocationDisplay(lat, lng, address);
        // Auto-update link to input field even if geocoding fails
        updateLinkGmapField(lat, lng);
    }
}

// Search location using Nominatim geocoding
async function searchLocation() {
    const searchInput = document.getElementById('mapSearchInput');
    if (!searchInput) return;
    
    const query = searchInput.value.trim();
    if (!query) {
        alert('Masukkan lokasi yang ingin dicari');
        return;
    }
    
    try {
        // Show loading state
        searchInput.disabled = true;
        
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=1&accept-language=id`,
            {
                headers: {
                    'User-Agent': 'BlanjaPoin-LocationPicker/1.0'
                }
            }
        );
        
        if (!response.ok) {
            throw new Error('Geocoding failed');
        }
        
        const data = await response.json();
        
        if (data && data.length > 0) {
            const result = data[0];
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            const address = result.display_name || query;
            
            // Move map to location
            mapPickerMap.setView([lat, lng], 15);
            
            // Add/update marker
            if (mapPickerMarker) {
                mapPickerMarker.setLatLng([lat, lng]);
            } else {
                mapPickerMarker = L.marker([lat, lng], {
                    draggable: true,
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(mapPickerMap);
                
                // Handle marker drag
                mapPickerMarker.on('dragend', function() {
                    const position = mapPickerMarker.getLatLng();
                    // Immediately update link field
                    updateLinkGmapField(position.lat, position.lng);
                    // Then reverse geocode for address
                    reverseGeocode(position.lat, position.lng);
                });
            }
            
            selectedLocationData = {
                lat: lat,
                lng: lng,
                address: address,
                fullData: result
            };
            
            updateSelectedLocationDisplay(lat, lng, address);
            // Auto-update link to input field
            updateLinkGmapField(lat, lng);
        } else {
            alert('Lokasi tidak ditemukan. Silakan coba dengan kata kunci lain.');
        }
    } catch (error) {
        console.error('Geocoding error:', error);
        alert('Terjadi kesalahan saat mencari lokasi. Silakan coba lagi.');
    } finally {
        searchInput.disabled = false;
    }
}

// Update selected location display
function updateSelectedLocationDisplay(lat, lng, address) {
    const addressEl = document.getElementById('selectedAddress');
    const coordsEl = document.getElementById('selectedCoordinates');
    const infoEl = document.getElementById('selectedLocationInfo');
    
    if (addressEl) addressEl.textContent = address;
    if (coordsEl) coordsEl.textContent = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    if (infoEl) infoEl.classList.remove('hidden');
}

// Show selected location info
function showSelectedLocationInfo(lat, lng) {
    const coordsEl = document.getElementById('selectedCoordinates');
    if (coordsEl) {
        coordsEl.textContent = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
    const infoEl = document.getElementById('selectedLocationInfo');
    if (infoEl) {
        infoEl.classList.remove('hidden');
    }
}

// Auto-update link_gmap field when location is selected
function updateLinkGmapField(lat, lng) {
    // Generate Google Maps link
    const googleMapsLink = `https://www.google.com/maps?q=${lat},${lng}`;
    
    // Update input field based on mode
    const inputId = mapPickerMode === 'edit' ? 'editMerchantLinkGmap' : 'merchantLinkGmap';
    const inputField = document.getElementById(inputId);
    
    if (inputField) {
        inputField.value = googleMapsLink;
        // Trigger input event to notify any listeners
        inputField.dispatchEvent(new Event('input', { bubbles: true }));
    }
}

// Confirm location and close modal (link already auto-updated)
function confirmLocation() {
    if (!selectedLocationData) {
        alert('Silakan pilih lokasi di peta terlebih dahulu');
        return;
    }
    
    // Link sudah otomatis ter-update via updateLinkGmapField()
    // Tinggal tutup modal
    closeMapPicker();
}

// ======================
// Lokasi Dropdowns (Provinsi, Kabupaten, Kecamatan) - Data dari API wilayah.id
// ======================
// Data akan diambil dari API wilayah.id secara dinamis

// Store all options for searchable dropdowns
let allProvinsiOptions = [];
let allKabupatenOptions = [];
let allKecamatanOptions = [];

// Cache untuk menyimpan kode yang dipilih
let selectedProvinceCode = null;
let selectedRegencyCode = null;

// ======================
// API Functions - wilayah.id
// ======================

// Load provinsi dari API
async function fetchProvinces() {
    const container = document.getElementById('provinsiOptions');
    const dropdown = document.getElementById('provinsiDropdown');
    
    // Show loading state
    if (container) {
        container.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Memuat data provinsi...</div>';
    }
    if (dropdown) {
        dropdown.classList.remove('hidden');
        dropdown.style.display = 'block';
    }
    
    try {
        // Gunakan backend proxy untuk menghindari CORS
        const response = await fetch('{{ route("api.wilayah.provinces") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('API Response:', data); // Debug log
        
        // Handle different response formats
        const provincesData = data?.data || data?.provinces || data;
        
        if (Array.isArray(provincesData) && provincesData.length > 0) {
            allProvinsiOptions = provincesData.map(prov => ({
                value: prov.code || prov.id,
                label: prov.name,
                code: prov.code || prov.id
            }));
            
            console.log('Loaded provinces:', allProvinsiOptions.length, allProvinsiOptions.slice(0, 3)); // Debug log
            
            // Render options
            if (container) {
                renderSearchableOptions('provinsi', allProvinsiOptions);
            }
            
            // Ensure dropdown is visible
            if (dropdown) {
                dropdown.classList.remove('hidden');
                dropdown.style.display = 'block';
            }
        } else {
            throw new Error('Invalid data format or empty data');
        }
    } catch (error) {
        console.error('Error fetching provinces:', error);
        
        // Show error message with retry option
        if (container) {
            container.innerHTML = `
                <div class="px-4 py-2 text-sm text-red-500">
                    <div>Gagal memuat data provinsi</div>
                    <button onclick="fetchProvinces()" class="mt-2 text-xs text-blue-600 hover:text-blue-800 underline">Coba lagi</button>
                </div>
            `;
        }
        
        // Keep dropdown visible to show error
        if (dropdown) {
            dropdown.classList.remove('hidden');
            dropdown.style.display = 'block';
        }
    }
}

// Load kabupaten dari API berdasarkan kode provinsi
async function fetchRegencies(provinceCode) {
    if (!provinceCode) return;
    
    try {
        allKabupatenOptions = [];
        const kabupatenSearch = document.getElementById('kabupatenSearch');
        const kabupatenValue = document.getElementById('kabupatenValue');
        const kecamatanSearch = document.getElementById('kecamatanSearch');
        const kecamatanValue = document.getElementById('kecamatanValue');
        
        // Reset kabupaten dan kecamatan
        if (kabupatenSearch) kabupatenSearch.value = '';
        if (kabupatenValue) kabupatenValue.value = '';
        if (kecamatanSearch) kecamatanSearch.value = '';
        if (kecamatanValue) kecamatanValue.value = '';
        document.getElementById('kabupatenOptions').innerHTML = '';
        document.getElementById('kecamatanOptions').innerHTML = '';
        allKecamatanOptions = [];
        
        // Show loading state
        const container = document.getElementById('kabupatenOptions');
        if (container) {
            container.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Memuat data...</div>';
        }
        
        // Gunakan backend proxy untuk menghindari CORS
        const response = await fetch(`{{ url('/api/wilayah/regencies') }}/${provinceCode}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Regencies response:', data); // Debug log
        
        // Handle different response formats
        const regenciesData = data?.data || data?.regencies || data;
        
        if (Array.isArray(regenciesData) && regenciesData.length > 0) {
            allKabupatenOptions = regenciesData.map(kab => {
                // Pastikan kode disimpan sebagai string dan tanpa karakter aneh
                const code = String(kab.code || kab.id || '').trim();
                return {
                    value: code,
                    label: kab.name || kab.nama,
                    code: code
                };
            });
            
            console.log('Loaded regencies:', allKabupatenOptions.length); // Debug log
            
            const dropdown = document.getElementById('kabupatenDropdown');
            renderSearchableOptions('kabupaten', allKabupatenOptions);
            if (dropdown) {
                dropdown.classList.remove('hidden');
                dropdown.style.display = 'block';
            }
        } else {
            throw new Error('Invalid data format or empty data');
        }
    } catch (error) {
        console.error('Error fetching regencies:', error);
        const container = document.getElementById('kabupatenOptions');
        if (container) {
            container.innerHTML = `
                <div class="px-4 py-2 text-sm text-red-500">
                    <div>Gagal memuat data kabupaten</div>
                    <button onclick="fetchRegencies('${provinceCode}')" class="mt-2 text-xs text-blue-600 hover:text-blue-800 underline">Coba lagi</button>
                </div>
            `;
        }
    }
}

// Load kecamatan dari API berdasarkan kode kabupaten
async function fetchDistricts(regencyCode) {
    if (!regencyCode) return;
    
    const container = document.getElementById('kecamatanOptions');
    const dropdown = document.getElementById('kecamatanDropdown');
    
    try {
        allKecamatanOptions = [];
        const kecamatanSearch = document.getElementById('kecamatanSearch');
        const kecamatanValue = document.getElementById('kecamatanValue');
        
        // Reset kecamatan
        if (kecamatanSearch) kecamatanSearch.value = '';
        if (kecamatanValue) kecamatanValue.value = '';
        if (container) container.innerHTML = '';
        
        // Show loading state
        if (container) {
            container.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Memuat data kecamatan...</div>';
        }
        if (dropdown) {
            dropdown.classList.remove('hidden');
            dropdown.style.display = 'block';
        }
        
        // Log untuk debugging
        console.log('Fetching districts for regency code:', regencyCode);
        
        // Gunakan backend proxy untuk menghindari CORS
        // Gunakan query parameter untuk menghindari masalah dengan titik di route
        const url = `{{ url('/api/wilayah/districts-by-code') }}?code=${encodeURIComponent(regencyCode)}`;
        console.log('Fetching from URL:', url, 'Original code:', regencyCode);
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Response status:', response.status, response.statusText);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
        }
        
        const data = await response.json();
        console.log('Districts response data:', data); // Debug log
        
        // Check if response has error
        if (data.error) {
            throw new Error(data.error + (data.message ? ': ' + data.message : ''));
        }
        
        // Handle different response formats
        let districtsData = null;
        if (data?.data) {
            districtsData = Array.isArray(data.data) ? data.data : (data.data.data || null);
        } else if (data?.districts) {
            districtsData = Array.isArray(data.districts) ? data.districts : null;
        } else if (Array.isArray(data)) {
            districtsData = data;
        }
        
        console.log('Parsed districts data:', districtsData);
        
        if (districtsData && Array.isArray(districtsData) && districtsData.length > 0) {
            allKecamatanOptions = districtsData.map(kec => {
                // Pastikan kode disimpan sebagai string dan tanpa karakter aneh
                const code = String(kec.code || kec.id || kec.kode || '').trim();
                return {
                    value: code,
                    label: kec.name || kec.nama,
                    code: code
                };
            });
            
            console.log('Loaded districts:', allKecamatanOptions.length, 'items'); // Debug log
            
            // Render options
            if (container) {
                renderSearchableOptions('kecamatan', allKecamatanOptions);
            }
            
            // Ensure dropdown is visible
            if (dropdown) {
                dropdown.classList.remove('hidden');
                dropdown.style.display = 'block';
            }
        } else {
            console.warn('No districts data found or empty array');
            throw new Error('Data kecamatan kosong atau format tidak valid');
        }
    } catch (error) {
        console.error('Error fetching districts:', error);
        console.error('Error details:', {
            message: error.message,
            stack: error.stack,
            regencyCode: regencyCode
        });
        
        // Show error message with retry option and more details
        if (container) {
            container.innerHTML = `
                <div class="px-4 py-2 text-sm text-red-500">
                    <div>Gagal memuat data kecamatan</div>
                    <div class="text-xs text-gray-500 mt-1">${error.message}</div>
                    <button onclick="fetchDistricts('${regencyCode}')" class="mt-2 text-xs text-blue-600 hover:text-blue-800 underline">Coba lagi</button>
                </div>
            `;
        }
        
        // Keep dropdown visible to show error
        if (dropdown) {
            dropdown.classList.remove('hidden');
            dropdown.style.display = 'block';
        }
    }
}

// Load provinsi saat DOM ready atau saat modal dibuka
document.addEventListener('DOMContentLoaded', function() {
    // Preload provinsi data
    fetchProvinces();
    
    // Initialize KTP upload state (disabled by default)
    toggleKtpUpload();
    
    // Close dropdowns saat klik di luar (dengan delay untuk menghindari konflik dengan open event)
    let clickTimeout;
    document.addEventListener('click', function(event) {
        const target = event.target;
        
        // Skip jika klik di input atau dropdown option
        if (target.id === 'provinsiSearch' || target.id === 'kabupatenSearch' || target.id === 'kecamatanSearch' ||
            target.closest('#provinsiOptions') || target.closest('#kabupatenOptions') || target.closest('#kecamatanOptions')) {
            clearTimeout(clickTimeout);
            return;
        }
        
        // Clear previous timeout
        clearTimeout(clickTimeout);
        
        // Delay untuk memastikan open event sudah selesai
        clickTimeout = setTimeout(() => {
            const provinsiInput = document.getElementById('provinsiSearch');
            const kabupatenInput = document.getElementById('kabupatenSearch');
            const kecamatanInput = document.getElementById('kecamatanSearch');
            
            const provinsiDropdown = document.getElementById('provinsiDropdown');
            const kabupatenDropdown = document.getElementById('kabupatenDropdown');
            const kecamatanDropdown = document.getElementById('kecamatanDropdown');
            
            // Check if click is inside dropdown container
            const provinsiContainer = provinsiInput?.closest('.relative');
            const kabupatenContainer = kabupatenInput?.closest('.relative');
            const kecamatanContainer = kecamatanInput?.closest('.relative');
            
            // Close provinsi dropdown if click is outside
            if (provinsiDropdown && !provinsiDropdown.classList.contains('hidden')) {
                if (!provinsiContainer?.contains(target) && !provinsiDropdown?.contains(target)) {
                    closeSearchableDropdown('provinsi');
                }
            }
            
            // Close kabupaten dropdown if click is outside
            if (kabupatenDropdown && !kabupatenDropdown.classList.contains('hidden')) {
                if (!kabupatenContainer?.contains(target) && !kabupatenDropdown?.contains(target)) {
                    closeSearchableDropdown('kabupaten');
                }
            }
            
            // Close kecamatan dropdown if click is outside
            if (kecamatanDropdown && !kecamatanDropdown.classList.contains('hidden')) {
                if (!kecamatanContainer?.contains(target) && !kecamatanDropdown?.contains(target)) {
                    closeSearchableDropdown('kecamatan');
                }
            }
        }, 150); // Delay 150ms untuk memberikan waktu open event selesai
    });
    
    // Close dropdowns saat tekan ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAllDropdowns();
        }
    });
});

// ======================
// Searchable Dropdown Functions
// ======================
async function openSearchableDropdown(type, event) {
    // Stop event propagation jika event tersedia
    if (event) {
        event.stopPropagation();
    }
    
    const dropdown = document.getElementById(`${type}Dropdown`);
    const optionsContainer = document.getElementById(`${type}Options`);
    
    if (!dropdown || !optionsContainer) {
        console.error(`Element not found for type: ${type}`, { dropdown: !!dropdown, optionsContainer: !!optionsContainer });
        return;
    }
    
    // Close other dropdowns first
    if (type !== 'provinsi') closeSearchableDropdown('provinsi');
    if (type !== 'kabupaten') closeSearchableDropdown('kabupaten');
    if (type !== 'kecamatan') closeSearchableDropdown('kecamatan');
    
    // Open dropdown
    dropdown.classList.remove('hidden');
    dropdown.style.display = 'block';
    dropdown.style.opacity = '1';
    dropdown.style.visibility = 'visible';
    
    // Load data jika belum ada
    if (type === 'provinsi') {
        if (allProvinsiOptions.length === 0) {
            // Show loading
            optionsContainer.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Memuat data provinsi...</div>';
            await fetchProvinces();
        } else {
            // Render existing options
            renderSearchableOptions(type, allProvinsiOptions);
        }
        return;
    }
    
    if (type === 'kabupaten') {
        if (allKabupatenOptions.length === 0) {
            // Show message if no province selected
            if (!selectedProvinceCode) {
                optionsContainer.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Pilih provinsi terlebih dahulu</div>';
                return;
            }
            await fetchRegencies(selectedProvinceCode);
        } else {
            // Render existing options
            renderSearchableOptions(type, allKabupatenOptions);
        }
        return;
    }
    
    if (type === 'kecamatan') {
        // Check if regency code is available
        if (!selectedRegencyCode) {
            // Try to get from hidden input
            const kabupatenValue = document.getElementById('kabupatenValue');
            if (kabupatenValue && kabupatenValue.value) {
                selectedRegencyCode = kabupatenValue.value;
                console.log('Got regency code from input:', selectedRegencyCode);
            } else {
                optionsContainer.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Pilih kabupaten terlebih dahulu</div>';
                return;
            }
        }
        
        // Always fetch if no data or if regency code changed
        if (allKecamatanOptions.length === 0) {
            await fetchDistricts(selectedRegencyCode);
        } else {
            // Render existing options
            renderSearchableOptions(type, allKecamatanOptions);
        }
        return;
    }
}

function closeSearchableDropdown(type) {
    const dropdown = document.getElementById(`${type}Dropdown`);
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.style.display = 'none';
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
    }
}

// Close all dropdowns
function closeAllDropdowns() {
    closeSearchableDropdown('provinsi');
    closeSearchableDropdown('kabupaten');
    closeSearchableDropdown('kecamatan');
}

function filterSearchableDropdown(type, searchTerm) {
    let options = [];
    if (type === 'provinsi') {
        options = allProvinsiOptions;
    } else if (type === 'kabupaten') {
        options = allKabupatenOptions;
    } else if (type === 'kecamatan') {
        options = allKecamatanOptions;
    }
    
    const filtered = searchTerm 
        ? options.filter(opt => opt.label.toLowerCase().includes(searchTerm.toLowerCase()))
        : options;
    
    // Open dropdown if not already open
    const dropdown = document.getElementById(`${type}Dropdown`);
    if (dropdown) {
        dropdown.classList.remove('hidden');
    }
    
    renderSearchableOptions(type, filtered);
}

function renderSearchableOptions(type, options) {
    const container = document.getElementById(`${type}Options`);
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!options || options.length === 0) {
        container.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Tidak ada data</div>';
        return;
    }
    
    options.forEach(option => {
        const div = document.createElement('div');
        div.className = 'px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 cursor-pointer transition-colors';
        div.textContent = option.label || option.name || option;
        const value = option.value || option.code || option;
        const code = option.code || option.value || option;
        const label = option.label || option.name || option;
        div.onclick = () => selectSearchableOption(type, value, label, code);
        container.appendChild(div);
    });
}

function selectSearchableOption(type, value, label, code) {
    const searchInput = document.getElementById(`${type}Search`);
    const hiddenInput = document.getElementById(`${type}Value`);
    
    if (searchInput) searchInput.value = label;
    if (hiddenInput) hiddenInput.value = value;
    
    closeSearchableDropdown(type);
    
    if (type === 'provinsi') {
        selectedProvinceCode = code || value;
        fetchRegencies(selectedProvinceCode);
        updateDaerahCombined();
    } else if (type === 'kabupaten') {
        // Use code first, then value as fallback, ensure it's a clean string
        const regencyCode = String(code || value || '').trim();
        selectedRegencyCode = regencyCode || null;
        console.log('Kabupaten selected:', {
            label: label,
            value: value,
            code: code,
            selectedRegencyCode: selectedRegencyCode,
            originalCode: code,
            originalValue: value
        });
        
        // Validate code format (should be numeric, possibly with dots)
        if (!selectedRegencyCode || (!/^[0-9.]+$/.test(selectedRegencyCode))) {
            console.error('Invalid regency code format:', selectedRegencyCode);
        }
        
        // Reset kecamatan data when kabupaten changes
        allKecamatanOptions = [];
        const kecamatanSearch = document.getElementById('kecamatanSearch');
        const kecamatanValue = document.getElementById('kecamatanValue');
        if (kecamatanSearch) kecamatanSearch.value = '';
        if (kecamatanValue) kecamatanValue.value = '';
        
        if (selectedRegencyCode) {
            // Fetch districts immediately
            fetchDistricts(selectedRegencyCode);
        } else {
            console.error('Regency code is empty or invalid. Code:', code, 'Value:', value);
            alert('Kode kabupaten tidak valid. Silakan pilih ulang.');
        }
        updateDaerahCombined();
    } else if (type === 'kecamatan') {
        updateDaerahCombined();
    }
}

// Event listener untuk close dropdown sudah di-handle di DOMContentLoaded

// Functions ini sudah tidak diperlukan karena menggunakan API langsung
// updateKabupatenOptions() dan updateKecamatanOptions() sudah diganti dengan fetchRegencies() dan fetchDistricts()

function updateDaerahCombined() {
    const provinsiSearch = document.getElementById('provinsiSearch');
    const kabupatenSearch = document.getElementById('kabupatenSearch');
    const kecamatanSearch = document.getElementById('kecamatanSearch');
    
    const parts = [];
    
    // Gunakan nilai dari input search (yang sudah terisi label)
    if (kecamatanSearch && kecamatanSearch.value && kecamatanSearch.value.trim() !== '') {
        parts.push(kecamatanSearch.value.trim());
    }
    if (kabupatenSearch && kabupatenSearch.value && kabupatenSearch.value.trim() !== '') {
        parts.push(kabupatenSearch.value.trim());
    }
    if (provinsiSearch && provinsiSearch.value && provinsiSearch.value.trim() !== '') {
        parts.push(provinsiSearch.value.trim());
    }
    
    const daerahCombined = parts.join(', ');
    document.getElementById('daerahCombined').value = daerahCombined;
    
    console.log('Daerah combined updated:', daerahCombined);
    return daerahCombined;
}

// ======================
// Handle form submit - tampilkan modal verifikasi
// ======================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formUploadMerchant');
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
            updateLinkBlanjapoin();
            
            // Validate WA PIC
            const waPicCode = document.getElementById('waPicCode').value.trim();
            if (waPicCode && !validateWaPic()) {
                alert('Nomor WhatsApp harus menggunakan operator Indonesia yang valid');
                document.getElementById('waPicCode').focus();
                return false;
            }
            
            // Update WA PIC sebelum submit
            updateWaPic();
            
            // Update daerah sebelum submit
            updateDaerahCombined();
            
            // Debug: Log form data sebelum submit
            const formData = new FormData(form);
            console.log('=== FORM DATA SEBELUM SUBMIT ===');
            for (let [key, value] of formData.entries()) {
                if (key !== 'logo_merchant') {
                    console.log(`${key}:`, value);
                } else {
                    console.log(`${key}:`, value instanceof File ? `File: ${value.name} (${value.size} bytes)` : value);
                }
            }
            console.log('================================');
            
            // Tampilkan modal verifikasi
            if (typeof showUploadVerification === 'function') {
                showUploadVerification(formData, 'Merchant');
            } else {
                // Fallback: submit langsung jika modal verifikasi tidak tersedia
                form.submit();
            }
        });
    }
    
    // Close modal when clicking overlay
    const modal = document.getElementById('uploadModalMerchant');
    const overlay = document.getElementById('uploadModalMerchantOverlay');
    if (modal && overlay) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal || event.target === overlay) {
                closeUploadMerchant();
            }
        });
    }
});

</script>