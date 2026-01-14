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
                                Nama Merchant / Program<span class="text-red-500">*</span>
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
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-amber-100 hover:to-yellow-100 hover:text-amber-800 rounded-lg transition-all" onclick="selectMerchantKategori('merchandise')">
                                            Merchandise
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

                        {{-- Status Toggle --}}
                        @if(Auth::check() && Auth::user()->can_approve == 1)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                General Link
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle Status">
                                <input type="checkbox" 
                                       id="uploadMerchantStatusToggle"
                                       class="sr-only peer" />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 hover:peer-checked:bg-green-700"></div>
                                <span class="ml-3 text-sm text-gray-700" id="uploadMerchantStatusText">Tidak Aktif</span>
                            </label>
                            <input type="hidden" name="is_active" id="uploadMerchantStatusHidden" value="0">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Section 2: Informasi PIC --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi PIC</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama PIC --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama PIC Merchant / Program
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
                                Email PIC (Opsional)
                            </label>
                            <input type="email"
                                   id="emailPicInput"
                                   name="email_pic"
                                   oninput="toggleKtpUpload()"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Masukkan email PIC">
                        </div>
                        <div id="ktpUploadSection">
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
                <div id="lokasiSection">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Alamat</h4>
                    <div class="space-y-4">
                        {{-- City Dropdown --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kota/Kabupaten
                            </label>
                            <div class="relative">
                                <input type="hidden" name="city" id="cityValue">
                                <input type="text"
                                       id="citySearch"
                                       placeholder="Cari atau pilih kota..."
                                       autocomplete="off"
                                       class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                       onfocus="openCityDropdown(event)"
                                       onclick="openCityDropdown(event)"
                                       oninput="filterCityDropdown(this.value)">
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <div id="cityDropdown" class="hidden absolute z-[100] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-hidden" style="top: 100%; left: 0;">
                                    <div class="max-h-60 overflow-y-auto">
                                        <div id="cityOptions" class="py-1"></div>
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

                        {{-- Hidden field untuk menyimpan daerah (city) --}}
                        <input type="hidden" name="daerah" id="daerahCombined">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Link Google Maps (Multiple) --}}
                            <div class="md:col-span-3">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Link Google Maps
                                    </label>
                                    <button type="button"
                                            onclick="addGmapField()"
                                            class="text-sm px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all flex items-center gap-1.5 shadow-sm hover:shadow-md">
                                        <i class="fas fa-plus"></i>
                                        <span>Tambah Titik</span>
                                    </button>
                                </div>
                                
                                {{-- Container untuk multiple gmap fields --}}
                                <div id="gmapFieldsContainer" class="space-y-3">
                                    {{-- Field pertama (default) --}}
                                    <div class="gmap-field-group border border-gray-200 rounded-lg p-3 bg-gray-50" data-index="0">
                                        <div class="flex items-start gap-2">
                                            <div class="flex-1 space-y-2">
                                                <div class="space-y-2 sm:space-y-0 sm:flex sm:gap-2">
                                                    <input type="url"
                                                           name="link_gmaps[0][link]"
                                                           class="gmap-link-input w-full sm:flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                                           placeholder="Paste link Google Maps atau pilih lokasi"
                                                           data-index="0"
                                                           onpaste="setTimeout(() => validateUploadGmapLink(this), 100)">
                                                    <button type="button"
                                                            onclick="openMapPicker('upload', 0)"
                                                            class="w-full sm:w-auto sm:flex-shrink-0 px-4 sm:px-6 h-12 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white rounded-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap font-medium shadow-sm hover:shadow-md">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span>Pilih Lokasi</span>
                                                    </button>
                                                </div>
                                                
                                                {{-- Lock Radius Toggle untuk titik ini --}}
                                                <div class="flex items-center gap-3">
                                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                                        <i class="fas fa-lock text-orange-500"></i>
                                                        <span class="font-medium">Lock Radius</span>
                                                    </label>
                                                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle Lock Radius">
                                                        <input type="checkbox"
                                                               class="lock-radius-toggle sr-only peer"
                                                               data-index="0"
                                                               onchange="toggleLockRadiusField(0)" />
                                                        <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-orange-500 peer-checked:to-red-500 hover:peer-checked:from-orange-600 hover:peer-checked:to-red-600"></div>
                                                        <span class="ml-2 text-sm text-gray-600 lock-radius-text" data-index="0">Tidak Aktif</span>
                                                    </label>
                                                </div>
                                                
                                                {{-- Radius Validasi Lokasi untuk titik ini --}}
                                                <div class="lock-radius-field-container hidden" data-index="0">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                                        <i class="fas fa-map-marked-alt mr-1 text-orange-500"></i>
                                                        Radius Validasi Lokasi (meter)
                                                    </label>
                                                    <input type="number"
                                                           name="link_gmaps[0][lock_radius]"
                                                           class="lock-radius-input w-full px-4 h-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                                           placeholder="Contoh: 100"
                                                           min="1"
                                                           max="100000"
                                                           data-index="0">
                                                    <p class="mt-1 text-xs text-gray-500">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Radius area validasi untuk redeem di titik ini
                                                    </p>
                                                </div>
                                            </div>
                                            <button type="button"
                                                    onclick="removeGmapField(0)"
                                                    class="remove-gmap-btn hidden mt-1 px-3 h-12 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span class="hidden sm:inline">Klik "Pilih Lokasi" untuk membuka peta interaktif atau paste link Google Maps langsung. Klik "Tambah Titik" untuk menambah lokasi lainnya.</span>
                                    <span class="sm:hidden">Pilih lokasi di peta atau paste link Google Maps. Klik "+" untuk menambah lokasi.</span>
                                </p>
                                <p class="mt-1 text-xs text-orange-600 font-medium">
                                    <i class="fas fa-magic mr-1"></i>
                                    *Auto konvert koordinat maps
                                </p>
                            </div>
                        </div>

                        {{-- Lock Radius Toggle --}}
                        <!-- <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-lock mr-1.5 text-orange-500"></i>
                                Lock Radius LongLat
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer" title="Toggle Lock Radius">
                                <input type="checkbox"
                                       id="lockRadiusCheckbox"
                                       class="sr-only peer" />
                                <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-orange-500 peer-checked:to-red-500 hover:peer-checked:from-orange-600 hover:peer-checked:to-red-600"></div>
                                <span class="ml-3 text-sm text-gray-700" id="lockRadiusText">Tidak Aktif</span>
                            </label>
                            <p class="mt-1.5 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span>Aktifkan untuk menampilkan field radius validasi lokasi</span>
                            </p>
                        </div> -->

                        {{-- Radius untuk Validasi Lokasi --}}
                        <div id="radiusFieldContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-map-marked-alt mr-1.5 text-orange-500"></i>
                                Radius Validasi Lokasi (meter)
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="radius"
                                   id="merchantRadius"
                                   min="0"
                                   max="100000"
                                   step="1"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                   placeholder="Contoh: 300 (meter)">
                            <p class="mt-1.5 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span>Atur radius dalam meter untuk validasi lokasi saat user redeem</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Periode Merchant --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Periode Merchant</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Start Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Start Date Periode
                            </label>
                            <input type="date"
                                   name="start_date"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm">
                        </div>
                        {{-- End Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                End Date Periode
                            </label>
                            <input type="date"
                                   name="end_date"
                                   class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm">
                        </div>
                    </div>
                </div>

                {{-- Section 5: Logo --}}
                <div id="logoSection">
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
// Cities data from backend
window.uploadMerchantCities = {!! json_encode($cities ?? []) !!};
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
    
    requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        
        // Ensure KTP upload is disabled on modal open
        toggleKtpUpload();
        
        // Check if kategori is already selected and toggle fields accordingly
        const kategoriInput = document.getElementById('merchantKategoriValue');
        if (kategoriInput && kategoriInput.value) {
            toggleFieldsByKategori(kategoriInput.value);
        }
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
        
        // Reset visibility of all sections to default (visible)
        const lokasiSection = document.getElementById('lokasiSection');
        const ktpUploadSection = document.getElementById('ktpUploadSection');
        const logoSection = document.getElementById('logoSection');
        if (lokasiSection) lokasiSection.style.display = 'block';
        if (ktpUploadSection) ktpUploadSection.style.display = 'block';
        if (logoSection) logoSection.style.display = 'block';

            // Reset city dropdown
            const citySearch = document.getElementById('citySearch');
            const cityValue = document.getElementById('cityValue');
            if (citySearch) citySearch.value = '';
            if (cityValue) cityValue.value = '';
            document.getElementById('cityOptions').innerHTML = '';
            document.getElementById('daerahCombined').value = '';
            closeCityDropdown();
            
            // Reset gmap fields to single field
            resetGmapFields();
            
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
            
            // Reset status toggle
            const statusToggle = document.getElementById('uploadMerchantStatusToggle');
            const statusHidden = document.getElementById('uploadMerchantStatusHidden');
            const statusText = document.getElementById('uploadMerchantStatusText');
            if (statusToggle) {
                statusToggle.checked = false; // Default to inactive
            }
            if (statusHidden) {
                statusHidden.value = '0';
            }
            if (statusText) {
                statusText.textContent = 'Tidak Aktif';
            }

            // Reset lock radius toggle
            const lockRadiusCheckbox = document.getElementById('lockRadiusCheckbox');
            const lockRadiusText = document.getElementById('lockRadiusText');
            if (lockRadiusCheckbox) {
                lockRadiusCheckbox.checked = false;
                // Trigger change event to hide radius field and reset text
                lockRadiusCheckbox.dispatchEvent(new Event('change'));
            }
            if (lockRadiusText) {
                lockRadiusText.textContent = 'Tidak Aktif';
            }
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
            'telkomsel': ['border-indigo-300', 'text-indigo-800', 'from-indigo-100', 'to-blue-100'],
            'merchandise': ['border-amber-300', 'text-amber-800', 'from-amber-100', 'to-yellow-100']
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
    toggleFieldsByKategori(value);
}

// Function to toggle field visibility based on kategori
function toggleFieldsByKategori(kategori) {
    const lokasiSection = document.getElementById('lokasiSection');
    const ktpUploadSection = document.getElementById('ktpUploadSection');
    const logoSection = document.getElementById('logoSection');
    
    const isTelkomsel = kategori === 'telkomsel';
    
    // Toggle Lokasi Section (Provinsi, Kabupaten, Kecamatan, Detail Alamat, Link Google Maps)
    if (lokasiSection) {
        if (isTelkomsel) {
            lokasiSection.style.display = 'none';
            // Clear values when hiding
            const citySearch = document.getElementById('citySearch');
            const cityValue = document.getElementById('cityValue');
            if (citySearch) citySearch.value = '';
            if (cityValue) cityValue.value = '';
            document.querySelector('textarea[name="detail_alamat"]').value = '';
            document.getElementById('merchantLinkGmap').value = '';
            document.getElementById('daerahCombined').value = '';
        } else {
            lokasiSection.style.display = 'block';
        }
    }
    
    // Toggle KTP Upload Section
    if (ktpUploadSection) {
        if (isTelkomsel) {
            ktpUploadSection.style.display = 'none';
            // Clear KTP upload if exists
            const ktpInput = document.getElementById('uploadMerchantKtpInput');
            if (ktpInput) {
                ktpInput.value = '';
                removeUploadMerchantKtp();
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
// Toggle Status (is_active)
// ======================
document.addEventListener('DOMContentLoaded', function() {
    const statusToggle = document.getElementById('uploadMerchantStatusToggle');
    const statusHidden = document.getElementById('uploadMerchantStatusHidden');
    const statusText = document.getElementById('uploadMerchantStatusText');

    if (statusToggle && statusHidden) {
        statusToggle.addEventListener('change', function() {
            const isActive = this.checked ? 1 : 0;
            statusHidden.value = isActive;
            if (statusText) {
                statusText.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
            }
        });
    }
});

// ======================
// Toggle Lock Radius
// ======================
document.addEventListener('DOMContentLoaded', function() {
    const lockRadiusCheckbox = document.getElementById('lockRadiusCheckbox');
    const lockRadiusText = document.getElementById('lockRadiusText');
    const radiusFieldContainer = document.getElementById('radiusFieldContainer');
    const radiusInput = document.getElementById('merchantRadius');

    function toggleLockRadius() {
        const isLocked = lockRadiusCheckbox.checked;

        // Update toggle text
        if (lockRadiusText) {
            lockRadiusText.textContent = isLocked ? 'Aktif' : 'Tidak Aktif';
        }

        // Toggle visibility of radius field
        if (radiusFieldContainer) {
            if (isLocked) {
                radiusFieldContainer.classList.remove('hidden');
                // Make radius required when visible
                if (radiusInput) {
                    radiusInput.setAttribute('required', 'required');
                }
            } else {
                radiusFieldContainer.classList.add('hidden');
                // Remove required when hidden
                if (radiusInput) {
                    radiusInput.removeAttribute('required');
                    radiusInput.value = ''; // Clear value when hidden
                }
            }
        }
    }

    if (lockRadiusCheckbox) {
        lockRadiusCheckbox.addEventListener('change', toggleLockRadius);
        // Initialize state (hidden by default)
        toggleLockRadius();
    }
});

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
async function validateUploadGmapLink(inputElement) {
    const url = inputElement.value;
    if (!url || url.trim() === '') {
        return true;
    }

    // Basic URL validation
    try {
        new URL(url);
    } catch (e) {
        return false;
    }

    // If it's a Google Maps URL, try to convert it to coordinate format
    if (url.includes('goo.gl') || url.includes('maps.app.goo.gl') ||
        url.includes('google.com/maps')) {
        try {
            const convertedUrl = await convertGmapUrl(url);
            if (convertedUrl && convertedUrl !== url) {
                // Update the input field with the converted URL
                inputElement.value = convertedUrl;
                // Silently update the field without showing message
            }
        } catch (error) {
            console.warn('Failed to convert Google Maps URL:', error);
        }
    }

    return true;
}

// Convert Google Maps URL to coordinate format using Google Maps Embed API
async function convertGmapUrl(url) {
    try {
        // For goo.gl URLs, we need to resolve them first
        let finalUrl = url;
        if (url.includes('goo.gl') || url.includes('maps.app.goo.gl')) {
            // Try to resolve the short URL
            const response = await fetch('/api/resolve-gmap-url?url=' + encodeURIComponent(url));
            if (response.ok) {
                const data = await response.json();
                finalUrl = data.final_url || url;
            }
        }

        // Try to extract coordinates from the URL
        const coords = extractCoordinatesFromUrl(finalUrl);
        if (coords) {
            return `https://www.google.com/maps?q=${coords.lat},${coords.lng}`;
        }

        // If no coordinates found, try to geocode the place using Google Maps Geocoding API
        // This requires API key to be configured
        const geocodedCoords = await geocodeGmapUrl(finalUrl);
        if (geocodedCoords) {
            return `https://www.google.com/maps?q=${geocodedCoords.lat},${geocodedCoords.lng}`;
        }

        return url; // Return original if conversion fails
    } catch (error) {
        console.warn('Error converting URL:', error);
        return url;
    }
}

// Extract coordinates from Google Maps URL
function extractCoordinatesFromUrl(url) {
    // Pattern 1: maps?q=lat,lng
    const qMatch = url.match(/maps\?q=([-+]?\d+\.?\d*),([-+]?\d+\.?\d*)/);
    if (qMatch) {
        return { lat: qMatch[1], lng: qMatch[2] };
    }

    // Pattern 2: maps/@lat,lng
    const atMatch = url.match(/maps\/@([-+]?\d+\.?\d*),([-+]?\d+\.?\d*)/);
    if (atMatch) {
        return { lat: atMatch[1], lng: atMatch[2] };
    }

    // Pattern 3: !3d(lat)!4d(lng) in data parameter
    const dataMatch = url.match(/!3d([-+]?\d+\.?\d*)!4d([-+]?\d+\.?\d*)/);
    if (dataMatch) {
        return { lat: dataMatch[1], lng: dataMatch[2] };
    }

    return null;
}

// Geocode Google Maps URL using Google Maps API
async function geocodeGmapUrl(url) {
    try {
        // Extract address from place URL
        const addressMatch = url.match(/\/place\/([^\/]+)\//);
        if (addressMatch) {
            const address = decodeURIComponent(addressMatch[1].replace(/\+/g, ' '));

            // Use Google Maps Geocoding API
            const response = await fetch(`/api/geocode?address=${encodeURIComponent(address)}`);
            if (response.ok) {
                const data = await response.json();
                if (data.status === 'OK' && data.results && data.results.length > 0) {
                    const location = data.results[0].geometry.location;
                    return { lat: location.lat, lng: location.lng };
                }
            }
        }

        // Extract place ID and use Places API
        const placeIdMatch = url.match(/!1s([^!]+)/);
        if (placeIdMatch) {
            const placeId = placeIdMatch[1];

            const response = await fetch(`/api/place-details?place_id=${placeId}`);
            if (response.ok) {
                const data = await response.json();
                if (data.status === 'OK' && data.result && data.result.geometry) {
                    const location = data.result.geometry.location;
                    return { lat: location.lat, lng: location.lng };
                }
            }
        }
    } catch (error) {
        console.warn('Geocoding failed:', error);
    }

    return null;
}


// ======================
// Multiple Google Maps Fields Management
// ======================
let gmapFieldCounter = 1;
let currentGmapFieldIndex = 0; // Track which field is being edited via map picker

function addGmapField() {
    const container = document.getElementById('gmapFieldsContainer');
    if (!container) return;
    
    const newField = document.createElement('div');
    newField.className = 'gmap-field-group border border-gray-200 rounded-lg p-3 bg-gray-50';
    newField.setAttribute('data-index', gmapFieldCounter);
    
    newField.innerHTML = `
        <div class="flex items-start gap-2">
            <div class="flex-1 space-y-2">
                <div class="space-y-2 sm:space-y-0 sm:flex sm:gap-2">
                    <input type="url"
                           name="link_gmaps[${gmapFieldCounter}][link]"
                           class="gmap-link-input w-full sm:flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                           placeholder="Paste link Google Maps atau pilih lokasi"
                           data-index="${gmapFieldCounter}"
                           onpaste="setTimeout(() => validateUploadGmapLink(this), 100)">
                    <button type="button"
                            onclick="openMapPicker('upload', ${gmapFieldCounter})"
                            class="w-full sm:w-auto sm:flex-shrink-0 px-4 sm:px-6 h-12 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white rounded-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap font-medium shadow-sm hover:shadow-md">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Pilih Lokasi</span>
                    </button>
                </div>
                
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <i class="fas fa-lock text-orange-500"></i>
                        <span class="font-medium">Lock Radius</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer" title="Toggle Lock Radius">
                        <input type="checkbox"
                               class="lock-radius-toggle sr-only peer"
                               data-index="${gmapFieldCounter}"
                               onchange="toggleLockRadiusField(${gmapFieldCounter})" />
                        <div class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-orange-500 peer-checked:to-red-500 hover:peer-checked:from-orange-600 hover:peer-checked:to-red-600"></div>
                        <span class="ml-2 text-sm text-gray-600 lock-radius-text" data-index="${gmapFieldCounter}">Tidak Aktif</span>
                    </label>
                </div>
                
                <div class="lock-radius-field-container hidden" data-index="${gmapFieldCounter}">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-map-marked-alt mr-1 text-orange-500"></i>
                        Radius Validasi Lokasi (meter)
                    </label>
                    <input type="number"
                           name="link_gmaps[${gmapFieldCounter}][lock_radius]"
                           class="lock-radius-input w-full px-4 h-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                           placeholder="Contoh: 100"
                           min="1"
                           max="100000"
                           data-index="${gmapFieldCounter}">
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Radius area validasi untuk redeem di titik ini
                    </p>
                </div>
            </div>
            <button type="button"
                    onclick="removeGmapField(${gmapFieldCounter})"
                    class="remove-gmap-btn mt-1 px-3 h-12 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newField);
    gmapFieldCounter++;
    
    // Show remove buttons on all fields if there's more than 1
    updateRemoveButtons();
}

function removeGmapField(index) {
    const field = document.querySelector(`.gmap-field-group[data-index="${index}"]`);
    if (!field) return;
    
    // Don't allow removing if it's the last field
    const totalFields = document.querySelectorAll('.gmap-field-group').length;
    if (totalFields <= 1) {
        alert('Minimal harus ada 1 lokasi');
        return;
    }
    
    field.remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const fields = document.querySelectorAll('.gmap-field-group');
    const showRemoveButtons = fields.length > 1;
    
    fields.forEach(field => {
        const removeBtn = field.querySelector('.remove-gmap-btn');
        if (removeBtn) {
            if (showRemoveButtons) {
                removeBtn.classList.remove('hidden');
            } else {
                removeBtn.classList.add('hidden');
            }
        }
    });
}

function toggleRadiusField(index) {
    const checkbox = document.querySelector(`.radius-toggle[data-index="${index}"]`);
    const radiusInput = document.querySelector(`.radius-input[data-index="${index}"]`);
    
    if (!checkbox || !radiusInput) return;
    
    if (checkbox.checked) {
        radiusInput.classList.remove('hidden');
        radiusInput.focus();
    } else {
        radiusInput.classList.add('hidden');
        radiusInput.value = '';
    }
}

function toggleLockRadiusField(index) {
    const checkbox = document.querySelector(`.lock-radius-toggle[data-index="${index}"]`);
    const container = document.querySelector(`.lock-radius-field-container[data-index="${index}"]`);
    const statusText = document.querySelector(`.lock-radius-text[data-index="${index}"]`);
    const radiusInput = document.querySelector(`.lock-radius-input[data-index="${index}"]`);
    
    if (!checkbox || !container) return;
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
        if (statusText) {
            statusText.textContent = 'Aktif';
        }
        if (radiusInput) {
            radiusInput.focus();
        }
    } else {
        container.classList.add('hidden');
        if (statusText) {
            statusText.textContent = 'Tidak Aktif';
        }
        if (radiusInput) {
            radiusInput.value = '';
        }
    }
}

// Open map picker modal
function openMapPicker(mode, gmapIndex) {
    mapPickerMode = mode || 'upload';
    currentGmapFieldIndex = gmapIndex !== undefined ? gmapIndex : 0;
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
    if (mapPickerMode === 'edit') {
        const inputField = document.getElementById('editMerchantLinkGmap');
        if (inputField) {
            inputField.value = googleMapsLink;
            inputField.dispatchEvent(new Event('input', { bubbles: true }));
        }
    } else {
        // Upload mode - update specific field based on currentGmapFieldIndex
        const inputField = document.querySelector(`.gmap-link-input[data-index="${currentGmapFieldIndex}"]`);
        if (inputField) {
            inputField.value = googleMapsLink;
            inputField.dispatchEvent(new Event('input', { bubbles: true }));
        }
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
// City Dropdown Functions
// ======================

// Store cities from backend
const cities = window.uploadMerchantCities || [];

// Initialize saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize KTP upload state (disabled by default)
    toggleKtpUpload();
    
    // Close dropdown saat klik di luar
    let clickTimeout;
    document.addEventListener('click', function(event) {
        const target = event.target;
        
        // Skip jika klik di input atau dropdown option
        if (target.id === 'citySearch' || target.closest('#cityOptions')) {
            clearTimeout(clickTimeout);
            return;
        }
        
        // Clear previous timeout
        clearTimeout(clickTimeout);
        
        // Delay untuk memastikan open event sudah selesai
        clickTimeout = setTimeout(() => {
            const cityInput = document.getElementById('citySearch');
            const cityDropdown = document.getElementById('cityDropdown');
            
            // Check if click is inside dropdown container
            const cityContainer = cityInput?.closest('.relative');
            
            // Close city dropdown if click is outside
            if (cityDropdown && !cityDropdown.classList.contains('hidden')) {
                if (!cityContainer?.contains(target) && !cityDropdown?.contains(target)) {
                    closeCityDropdown();
                }
            }
        }, 150);
    });
    
    // Close dropdown saat tekan ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCityDropdown();
        }
    });
});

// Open city dropdown
function openCityDropdown(event) {
    if (event) {
        event.stopPropagation();
    }
    
    const dropdown = document.getElementById('cityDropdown');
    const optionsContainer = document.getElementById('cityOptions');
    
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
    renderCityOptions(cities);
}

// Close city dropdown
function closeCityDropdown() {
    const dropdown = document.getElementById('cityDropdown');
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.style.display = 'none';
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
    }
}

// Filter city dropdown
function filterCityDropdown(searchTerm) {
    const filtered = searchTerm 
        ? cities.filter(city => city.toLowerCase().includes(searchTerm.toLowerCase()))
        : cities;
    
    // Open dropdown if not already open
    const dropdown = document.getElementById('cityDropdown');
    if (dropdown) {
        dropdown.classList.remove('hidden');
    }
    
    renderCityOptions(filtered);
}

// Render city options
function renderCityOptions(cityList) {
    const container = document.getElementById('cityOptions');
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
        div.onclick = () => selectCity(city);
        container.appendChild(div);
    });
}

// Select city
function selectCity(city) {
    const searchInput = document.getElementById('citySearch');
    const hiddenInput = document.getElementById('cityValue');
    
    if (searchInput) searchInput.value = city;
    if (hiddenInput) hiddenInput.value = city;
    
    closeCityDropdown();
    updateDaerahCombined();
}

// Update daerah combined (only city now)
function updateDaerahCombined() {
    const citySearch = document.getElementById('citySearch');
    
    const daerahCombined = citySearch && citySearch.value ? citySearch.value.trim() : '';
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

            // Validate lock radius
            const lockRadiusCheckbox = document.getElementById('lockRadiusCheckbox');
            const isLockRadiusChecked = lockRadiusCheckbox && lockRadiusCheckbox.checked;
            const radius = form.querySelector('input[name="radius"]').value.trim();

            if (isLockRadiusChecked && !radius) {
                alert('Radius wajib diisi karena fitur Lock Radius aktif');
                document.getElementById('merchantRadius').focus();
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
            
            // Update daerah (city) sebelum submit
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