<!-- Keyword Upload Modal -->
<div id="uploadModalKeyword" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="uploadModalKeywordOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    
    <div id="uploadModalKeywordPanel" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-xl font-bold text-gray-800">
                Add Keyword Data
            </h3>
            <button type="button"
                    onclick="closeUploadKeyword()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="formUploadKeyword" method="POST" action="{{ route('keywords.store') }}" enctype="multipart/form-data" class="flex-1 overflow-y-auto" novalidate>
            @csrf
            <input type="hidden" name="redirect_to" id="keywordRedirectUpload">
            <input type="hidden" name="stay_on_detail" id="keywordStayOnDetailUpload">
            <div class="p-6 space-y-6">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Row 1: Nama Merchant -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Merchant <span class="text-red-500">*</span></label>
                            
                            <!-- Custom Dropdown -->
                            <div class="relative">
                                <!-- Hidden select for form submission -->
                                <select name="merchant_key" id="merchantSelect" class="hidden">
                                    <option value="">-- Pilih Merchant --</option>
                                    @foreach($allMerchants as $merchant)
                                        <option value="{{ $merchant->id }}" data-name="{{ $merchant->nama_merchant }}" data-email="{{ $merchant->email_pic ?? '' }}" data-start-date="{{ $merchant->start_date ?? '' }}" data-end-date="{{ $merchant->end_date ?? '' }}" data-kategori="{{ $merchant->kategori ?? '' }}">{{ $merchant->nama_merchant }}</option>
                                    @endforeach
                                </select>
                                
                                <!-- Custom Dropdown Button -->
                                <button type="button" 
                                        id="customMerchantDropdownBtn" 
                                        onclick="toggleCustomMerchantDropdown()"
                                        class="w-full px-4 h-12 border border-gray-300 rounded-lg bg-white text-left flex items-center justify-between hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-colors">
                                    <span id="customMerchantSelectedText" class="text-sm text-gray-600">
                                        -- Pilih Merchant --
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="customMerchantChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Custom Dropdown Menu -->
                                <div id="customMerchantDropdown" 
                                     class="hidden absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden max-h-80 flex flex-col">
                                    <!-- Search Box -->
                                    <div class="p-2 border-b border-gray-100">
                                        <input type="text" 
                                               id="merchantSearchInput" 
                                               placeholder="Cari merchant..." 
                                               onkeyup="filterMerchantOptions(this.value)"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400">
                                    </div>
                                    
                                    <!-- Options Container -->
                                    <div id="merchantOptionsContainer" class="overflow-y-auto max-h-64">
                                        @foreach($allMerchants as $merchant)
                                            <button type="button" 
                                                    class="merchant-option w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0"
                                                    data-value="{{ $merchant->id }}"
                                                    data-name="{{ $merchant->nama_merchant }}"
                                                    data-email="{{ $merchant->email_pic ?? '' }}"
                                                    data-start-date="{{ $merchant->start_date ?? '' }}"
                                                    data-end-date="{{ $merchant->end_date ?? '' }}"
                                                    data-kategori="{{ $merchant->kategori ?? '' }}"
                                                    onclick="selectMerchant({{ $merchant->id }}, '{{ addslashes($merchant->nama_merchant) }}', '{{ $merchant->email_pic ?? '' }}', '{{ $merchant->start_date ?? '' }}', '{{ $merchant->end_date ?? '' }}', '{{ addslashes($merchant->kategori ?? '') }}')">
                                                {{ $merchant->nama_merchant }}
                                            </button>
                                        @endforeach
                                        
                                        <!-- Empty State -->
                                        <div id="merchantEmptyState" class="hidden px-4 py-8 text-center">
                                            <p class="text-sm text-gray-500">Merchant tidak ditemukan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1.3: Kategori Keyword -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori Keyword</label>
                            <div class="relative">
                                <input type="hidden" name="kategori_keyword" id="keywordKategoriValue">
                                <button
                                    type="button"
                                    id="keywordKategoriBtn"
                                    onclick="toggleKeywordKategoriDropdown()"
                                    class="w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400"
                                >
                                    <span id="keywordKategoriLabel">Kategori akan otomatis terisi dari merchant</span>
                                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                                </button>
                                <div
                                    id="keywordKategoriDropdown"
                                    class="hidden absolute left-0 mt-2 bg-white rounded-xl shadow-xl p-2 border border-gray-200 w-full z-50"
                                >
                                    <div class="py-1 text-sm">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all" onclick="selectKeywordKategori('kuliner')">
                                            Kuliner
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all" onclick="selectKeywordKategori('hiburan')">
                                            Hiburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all" onclick="selectKeywordKategori('liburan')">
                                            Liburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all" onclick="selectKeywordKategori('belanja')">
                                            Belanja
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all" onclick="selectKeywordKategori('kecantikan')">
                                            Kecantikan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all" onclick="selectKeywordKategori('telkomsel')">
                                            Telkomsel Paket
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-amber-100 hover:to-yellow-100 hover:text-amber-800 rounded-lg transition-all" onclick="selectKeywordKategori('merchandise')">
                                            Merchandise
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1.5: Nama Produk -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" id="productName" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Nama produk akan otomatis terisi"> 
                        </div>

                        <!-- Row 1.6: Keyword ID -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keyword ID <span class="text-red-500">*</span></label>
                            <input type="text" name="keyword_id" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter keyword ID">
                        </div>

                        <!-- Row 2: CTA -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA <span class="text-red-500">*</span></label>
                            <input type="url" name="cta_link" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="https://example.com">
                        </div>

                        <!-- Row 3: Redeem Point -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Redeem Point <span class="text-red-500">*</span></label>
                            <input type="number" name="redeem" min="0" step="1" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter redeem points">
                        </div>

                        <!-- Row 4: Diskon (Persen + Rupiah + Free) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Diskon <span class="text-red-500">*</span> (Pilih salah satu)</label>
                            
                            <!-- Radio buttons untuk memilih jenis diskon -->
                            <div class="flex items-center gap-4 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diskon_type" value="percent" checked onchange="toggleDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Persen (%)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diskon_type" value="rupiah" onchange="toggleDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Rupiah (Rp)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diskon_type" value="free" onchange="toggleDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Free</span>
                                </label>
                            </div>
                            
                            <!-- Input fields untuk diskon -->
                            <div id="diskonPercentContainer" class="flex items-center gap-2">
                                <span class="w-12 text-center text-gray-600 font-medium shrink-0">%</span>
                                <input type="text" name="diskon_percent" id="diskonPercent" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" min="0" max="100" onchange="validateDiskon()">
                            </div>
                            <div id="diskonRupiahContainer" class="hidden flex items-center gap-2">
                                <span class="w-12 text-center text-gray-600 font-medium shrink-0">Rp</span>
                                <input type="text" name="diskon_rupiah" id="diskonRupiah" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" onchange="validateDiskon()">
                            </div>
                            <div id="diskonFreeContainer" class="hidden">
                                <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-700 font-medium flex items-center gap-2">
                                        <i class="fas fa-gift text-green-600"></i>
                                        Produk ini akan ditandai sebagai <strong>FREE</strong>
                                    </p>
                                </div>
                            </div>
                            <p id="diskonError" class="text-red-500 text-xs mt-1 hidden">Silakan pilih salah satu jenis diskon (persen, rupiah, atau free)</p>
                        </div>

                        <!-- Row 4.5: Subsidi Diskon -->
                        <div class="md:col-span-2 ">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Subsidi Diskon</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="subsidy_enabled" value="0" checked onchange="toggleSubsidyAmount()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">No</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="subsidy_enabled" value="1" onchange="toggleSubsidyAmount()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Yes</span>
                                </label>
                            </div>
                            <div id="subsidyAmountContainer" class="mt-3 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal Subsidi (Rupiah) <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <span class="w-12 text-center text-gray-600 font-medium shrink-0">Rp</span>
                                    <input type="text" name="subsidy_amount" id="subsidyAmount" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" inputmode="numeric" oninput="formatRupiahInput(this)">
                                </div>
                                <p id="subsidyAmountError" class="text-red-500 text-xs mt-1 hidden">Nominal subsidi wajib diisi jika Subsidi Diskon dipilih Yes</p>
                            </div>
                        </div>

                        <!-- Row 4.6: Diamond -->
                        <div class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Diamond</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diamond_enabled" value="0" checked onchange="toggleDiamond()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">No</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diamond_enabled" value="1" onchange="toggleDiamond()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Yes</span>
                                </label>
                            </div>
                            <div id="diamondAmountContainer" class="mt-3 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Diamond <span class="text-red-500">*</span></label>
                                <input type="number" name="diamond_amount" id="diamondAmount" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Masukkan jumlah diamond" min="0">
                                <p id="diamondAmountError" class="text-red-500 text-xs mt-1 hidden">Jumlah diamond wajib diisi jika Diamond dipilih Yes</p>
                            </div>
                        </div>

                        <!-- Row 5: Stock -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Stock <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter stock">
                        </div>

                        <!-- Row 6: SKB -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SKB <span class="text-red-500">*</span></label>
                            <textarea name="skb" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm resize-none" placeholder="Enter SKB"></textarea>
                        </div>

                        <!-- Row 7: Start Date | End Date -->
                        <div class="md:col-span-2">
                            @include('partials.date-filter-upload')
                            <p id="dateError" class="text-red-500 text-xs mt-1 hidden">Tanggal mulai tidak boleh melebihi tanggal berakhir</p>
                        </div>
                        
                        <!-- Row 8: Images -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Images</label>
                            <div class="relative">
                                <input type="file" id="keywordImagesInput" name="image" accept="image/*" class="hidden" onchange="previewKeywordImages(this)">
                                <button type="button" onclick="document.getElementById('keywordImagesInput').click()" class="w-full min-h-[120px] px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all">
                                    <i class="fas fa-upload text-3xl mb-2"></i>
                                    <span id="keywordImagesText" class="text-sm">Click to upload image</span>
                                </button>
                                <div id="keywordImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sticky bottom-0 z-10 flex justify-end items-center gap-3 px-6 py-4 border-t bg-white">
                <button type="button"
                        onclick="closeUploadKeyword()"
                        class="px-6 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.upload-verification-modal')

<script>
function previewKeywordImages(input) {
    const preview = document.getElementById('keywordImagesPreview');
    const text = document.getElementById('keywordImagesText');
    preview.innerHTML = '';
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        if (text) text.textContent = `${input.files.length} file(s) selected`;
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg"><button type="button" onclick="removeKeywordImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"><i class="fas fa-times text-xs"></i></button>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeKeywordImage(index) {
    const input = document.getElementById('keywordImagesInput');
    const dt = new DataTransfer();
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) dt.items.add(input.files[i]);
    }
    input.files = dt.files;
    previewKeywordImages(input);
    if (input.files.length === 0) {
        document.getElementById('keywordImagesPreview').classList.add('hidden');
        document.getElementById('keywordImagesText').textContent = 'Click to upload images';
    }
}


// Lock merchant dropdown when page is scoped to a specific merchant (e.g. from detail view)
function applyFixedMerchantContext() {
    const merchantSelect = document.getElementById('merchantSelect');
    const customButton = document.getElementById('customMerchantDropdownBtn');
    const productNameInput = document.getElementById('productName');
    const redirectInput = document.getElementById('keywordRedirectUpload');
    const stayFlagInput = document.getElementById('keywordStayOnDetailUpload');
    if (!merchantSelect) return;

    const hasFixedMerchant = Boolean(window.fixedMerchantId);
    const existingHidden = document.getElementById('lockedMerchantKey');
    if (hasFixedMerchant) {
        merchantSelect.value = window.fixedMerchantId;
        merchantSelect.disabled = true;

        // Update custom dropdown button
        if (customButton) {
            customButton.disabled = true;
            customButton.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-75');
            customButton.classList.remove('hover:border-gray-400');
            
            const selectedText = document.getElementById('customMerchantSelectedText');
            if (selectedText && window.fixedMerchantName) {
                selectedText.textContent = window.fixedMerchantName;
                selectedText.classList.remove('text-gray-600');
                selectedText.classList.add('text-gray-700');
            }
        }

        let hiddenInput = existingHidden;
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'lockedMerchantKey';
            hiddenInput.name = 'merchant_key';
            merchantSelect.parentNode.appendChild(hiddenInput);
        }
        hiddenInput.value = window.fixedMerchantId;

        const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
        if (productNameInput && !productNameInput.value) {
            productNameInput.value = (selectedOption && (selectedOption.dataset.name || selectedOption.text)) || window.fixedMerchantName || '';
        }
        if (redirectInput && window.detailRedirectUrl) {
            redirectInput.value = window.detailRedirectUrl;
        }
        if (stayFlagInput) {
            stayFlagInput.value = '1';
        }
        
        // Auto-fill start_date dan end_date dari merchant jika ada
        if (window.fixedMerchantStartDate || window.fixedMerchantEndDate) {
            fillKeywordDatesFromMerchant(window.fixedMerchantStartDate || '', window.fixedMerchantEndDate || '');
        }
    } else {
        merchantSelect.disabled = false;
        
        // Re-enable custom dropdown button
        if (customButton) {
            customButton.disabled = false;
            customButton.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-75');
            customButton.classList.add('hover:border-orange-400', 'hover:shadow-md');
        }
        
        if (existingHidden) {
            existingHidden.remove();
        }
        if (redirectInput) {
            redirectInput.value = '';
        }
        if (stayFlagInput) {
            stayFlagInput.value = '';
        }
    }
}

function openUploadKeyword() {
    const modal = document.getElementById('uploadModalKeyword');
    const overlay = document.getElementById('uploadModalKeywordOverlay');
    const panel = document.getElementById('uploadModalKeywordPanel');
    
    if (!modal) return;
    
    // Reset custom dropdown
    const customDropdown = document.getElementById('customMerchantDropdown');
    const chevron = document.getElementById('customMerchantChevron');
    const searchInput = document.getElementById('merchantSearchInput');
    if (customDropdown) {
        customDropdown.classList.add('hidden');
    }
    if (chevron) {
        chevron.style.transform = 'rotate(0deg)';
    }
    if (searchInput) {
        searchInput.value = '';
        filterMerchantOptions('');
    }
    
    applyFixedMerchantContext();
    
    // Reset diskon type to default (percent)
    const diskonTypePercent = document.querySelector('input[name="diskon_type"][value="percent"]');
    if (diskonTypePercent) {
        diskonTypePercent.checked = true;
        toggleDiskonType();
    }
    
    // Reset calendar state
    const today = new Date();
    uploadCalendarState = {
        currentMonth: today.getMonth(),
        currentYear: today.getFullYear(),
        activeType: 'start',
        startDate: null,
        endDate: null
    };
    
    // Clear date inputs first (will be auto-filled if fixed merchant has dates)
    const startInput = document.getElementById('startDateUpload');
    const endInput = document.getElementById('endDateUpload');
    if (startInput) startInput.value = '';
    if (endInput) endInput.value = '';
    
    const startHidden = document.getElementById('startDateHiddenUpload');
    const endHidden = document.getElementById('endDateHiddenUpload');
    if (startHidden) startHidden.value = '';
    if (endHidden) endHidden.value = '';
    
    // After clearing, auto-fill dates from fixed merchant if available
    // This ensures dates are filled when opening modal from merchant detail page
    if (window.fixedMerchantId && (window.fixedMerchantStartDate || window.fixedMerchantEndDate)) {
        fillKeywordDatesFromMerchant(window.fixedMerchantStartDate || '', window.fixedMerchantEndDate || '');
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Date picker can be opened via calendar icon button
    // Input fields are now fully clickable and editable for normal typing
    
    requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
    });
}

function closeUploadKeyword() {
    const modal = document.getElementById('uploadModalKeyword');
    const overlay = document.getElementById('uploadModalKeywordOverlay');
    const panel = document.getElementById('uploadModalKeywordPanel');
    
    overlay?.classList.remove('opacity-100');
    overlay?.classList.add('opacity-0');
    panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');

    setTimeout(() => {
        modal?.classList.add('hidden');
        document.body.style.overflow = '';

        const form = document.getElementById('formUploadKeyword');
        if (form) {
            form.reset();
        }
        
        // Reset diamond container
        const diamondAmountContainer = document.getElementById('diamondAmountContainer');
        if (diamondAmountContainer) {
            diamondAmountContainer.classList.add('hidden');
        }
        const diamondError = document.getElementById('diamondAmountError');
        if (diamondError) {
            diamondError.classList.add('hidden');
        }
        
        // Reset subsidy container
        const subsidyAmountContainer = document.getElementById('subsidyAmountContainer');
        if (subsidyAmountContainer) {
            subsidyAmountContainer.classList.add('hidden');
        }
        const subsidyError = document.getElementById('subsidyAmountError');
        if (subsidyError) {
            subsidyError.classList.add('hidden');
        }
        
        // Reset diskon type to default (percent)
        const diskonTypePercent = document.querySelector('input[name="diskon_type"][value="percent"]');
        if (diskonTypePercent) {
            diskonTypePercent.checked = true;
            toggleDiskonType();
        }
        const diskonError = document.getElementById('diskonError');
        if (diskonError) {
            diskonError.classList.add('hidden');
        }
        
        // Reset custom dropdown
        const customDropdown = document.getElementById('customMerchantDropdown');
        const chevron = document.getElementById('customMerchantChevron');
        const customButton = document.getElementById('customMerchantDropdownBtn');
        const selectedText = document.getElementById('customMerchantSelectedText');
        const searchInput = document.getElementById('merchantSearchInput');
        
        if (customDropdown) {
            customDropdown.classList.add('hidden');
        }
        if (chevron) {
            chevron.style.transform = 'rotate(0deg)';
        }
        if (customButton) {
            customButton.classList.remove('border-gray-400');
            customButton.classList.add('border-gray-300');
        }
        if (selectedText) {
            selectedText.textContent = '-- Pilih Merchant --';
            selectedText.classList.remove('text-gray-900', 'font-medium');
            selectedText.classList.add('text-gray-600');
        }
        if (searchInput) {
            searchInput.value = '';
            filterMerchantOptions('');
        }
        
        // Reset all merchant options highlight
        const options = document.querySelectorAll('.merchant-option');
        options.forEach(opt => {
            opt.classList.remove('bg-gray-100', 'font-medium');
            opt.style.display = '';
        });
        
        // Reset kategori dropdown
        const kategoriInput = document.getElementById('keywordKategoriValue');
        const kategoriLabel = document.getElementById('keywordKategoriLabel');
        const kategoriBtn = document.getElementById('keywordKategoriBtn');
        if (kategoriInput) kategoriInput.value = '';
        if (kategoriLabel) kategoriLabel.textContent = 'Kategori akan otomatis terisi dari merchant';
        if (kategoriBtn) {
            kategoriBtn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400';
        }
        
        applyFixedMerchantContext();
    }, 300);
}


// Custom Dropdown Functions
function toggleCustomMerchantDropdown() {
    const dropdown = document.getElementById('customMerchantDropdown');
    const chevron = document.getElementById('customMerchantChevron');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
        setTimeout(() => {
            document.getElementById('merchantSearchInput')?.focus();
        }, 100);
    } else {
        dropdown.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

function selectMerchant(merchantId, merchantName, merchantEmail = '', merchantStartDate = '', merchantEndDate = '', merchantKategori = '') {
    const merchantSelect = document.getElementById('merchantSelect');
    merchantSelect.value = merchantId;
    
    // Update option dengan email dan kategori
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    if (selectedOption) {
        selectedOption.dataset.email = merchantEmail;
        selectedOption.dataset.kategori = merchantKategori;
    }
    
    const selectedText = document.getElementById('customMerchantSelectedText');
    selectedText.textContent = merchantName;
    selectedText.classList.remove('text-gray-600');
    selectedText.classList.add('text-gray-900', 'font-medium');
    
    const button = document.getElementById('customMerchantDropdownBtn');
    button.classList.remove('border-gray-300');
    button.classList.add('border-gray-400');
    
    const options = document.querySelectorAll('.merchant-option');
    options.forEach(opt => {
        opt.classList.remove('bg-gray-100', 'font-medium');
        if (opt.dataset.value == merchantId) {
            opt.classList.add('bg-gray-100', 'font-medium');
        }
    });
    
    // Auto-fill kategori_keyword dari merchant jika ada
    if (merchantKategori) {
        selectKeywordKategori(merchantKategori);
    }
    
    // Auto-fill start_date dan end_date dari merchant jika ada
    if (merchantStartDate || merchantEndDate) {
        fillKeywordDatesFromMerchant(merchantStartDate, merchantEndDate);
    }
    
    toggleCustomMerchantDropdown();
    updateProductName();
    merchantSelect.dispatchEvent(new Event('change'));
}

// Function to fill keyword dates from merchant dates
function fillKeywordDatesFromMerchant(merchantStartDate, merchantEndDate) {
    // Format: merchant dates are in YYYY-MM-DD format
    // Need to convert to DD/MM/YYYY for display and set calendar state
    
    if (merchantStartDate) {
        // Parse YYYY-MM-DD to Date object
        const startParts = merchantStartDate.split('-');
        if (startParts.length === 3) {
            const startDate = new Date(parseInt(startParts[0]), parseInt(startParts[1]) - 1, parseInt(startParts[2]));
            
            // Format for display (DD/MM/YYYY)
            const day = String(startDate.getDate()).padStart(2, '0');
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const year = startDate.getFullYear();
            const formattedStart = `${day}/${month}/${year}`;
            
            // Update display input
            const startInput = document.getElementById('startDateUpload');
            if (startInput) {
                startInput.value = formattedStart;
            }
            
            // Update hidden input (YYYY-MM-DD format)
            let startHidden = document.getElementById('startDateHiddenUpload');
            if (!startHidden) {
                startHidden = document.createElement('input');
                startHidden.type = 'hidden';
                startHidden.id = 'startDateHiddenUpload';
                startHidden.name = 'start_date';
                if (startInput && startInput.parentElement) {
                    startInput.parentElement.appendChild(startHidden);
                }
            }
            startHidden.value = merchantStartDate;
            
            // Update calendar state
            if (typeof uploadCalendarState !== 'undefined') {
                uploadCalendarState.startDate = startDate;
            }
        }
    }
    
    if (merchantEndDate) {
        // Parse YYYY-MM-DD to Date object
        const endParts = merchantEndDate.split('-');
        if (endParts.length === 3) {
            const endDate = new Date(parseInt(endParts[0]), parseInt(endParts[1]) - 1, parseInt(endParts[2]));
            
            // Format for display (DD/MM/YYYY)
            const day = String(endDate.getDate()).padStart(2, '0');
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const year = endDate.getFullYear();
            const formattedEnd = `${day}/${month}/${year}`;
            
            // Update display input
            const endInput = document.getElementById('endDateUpload');
            if (endInput) {
                endInput.value = formattedEnd;
            }
            
            // Update hidden input (YYYY-MM-DD format)
            let endHidden = document.getElementById('endDateHiddenUpload');
            if (!endHidden) {
                endHidden = document.createElement('input');
                endHidden.type = 'hidden';
                endHidden.id = 'endDateHiddenUpload';
                endHidden.name = 'end_date';
                if (endInput && endInput.parentElement) {
                    endInput.parentElement.appendChild(endHidden);
                }
            }
            endHidden.value = merchantEndDate;
            
            // Update calendar state
            if (typeof uploadCalendarState !== 'undefined') {
                uploadCalendarState.endDate = endDate;
            }
        }
    }
}

function filterMerchantOptions(searchTerm) {
    const options = document.querySelectorAll('.merchant-option');
    const emptyState = document.getElementById('merchantEmptyState');
    let visibleCount = 0;
    
    const term = searchTerm.toLowerCase().trim();
    
    options.forEach(option => {
        const merchantName = option.dataset.name.toLowerCase();
        if (merchantName.includes(term)) {
            option.style.display = '';
            visibleCount++;
        } else {
            option.style.display = 'none';
        }
    });
    
    if (visibleCount === 0 && term !== '') {
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('customMerchantDropdown');
    const button = document.getElementById('customMerchantDropdownBtn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        if (!dropdown.classList.contains('hidden')) {
            toggleCustomMerchantDropdown();
        }
    }
});

// Fungsi untuk update nama produk berdasarkan merchant yang dipilih
function updateProductName() {
    const merchantSelect = document.getElementById('merchantSelect');
    const productNameInput = document.getElementById('productName');
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        productNameInput.value = selectedOption.dataset.name || selectedOption.text;
        // Auto-fill kategori_keyword dari merchant
        if (selectedOption.dataset.kategori) {
            selectKeywordKategori(selectedOption.dataset.kategori);
        }
    } else {
        productNameInput.value = '';
        // Reset kategori dropdown
        const kategoriInput = document.getElementById('keywordKategoriValue');
        const kategoriLabel = document.getElementById('keywordKategoriLabel');
        const kategoriBtn = document.getElementById('keywordKategoriBtn');
        if (kategoriInput) kategoriInput.value = '';
        if (kategoriLabel) kategoriLabel.textContent = 'Kategori akan otomatis terisi dari merchant';
        if (kategoriBtn) {
            kategoriBtn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400';
        }
    }
}

// Fungsi untuk toggle jenis diskon
function toggleDiskonType() {
    const diskonType = document.querySelector('input[name="diskon_type"]:checked');
    const percentContainer = document.getElementById('diskonPercentContainer');
    const rupiahContainer = document.getElementById('diskonRupiahContainer');
    const freeContainer = document.getElementById('diskonFreeContainer');
    const diskonPercent = document.getElementById('diskonPercent');
    const diskonRupiah = document.getElementById('diskonRupiah');
    const errorMsg = document.getElementById('diskonError');
    
    if (!diskonType) return;
    
    // Hide all containers first
    if (percentContainer) percentContainer.classList.add('hidden');
    if (rupiahContainer) rupiahContainer.classList.add('hidden');
    if (freeContainer) freeContainer.classList.add('hidden');
    
    // Clear inputs
    if (diskonPercent) diskonPercent.value = '';
    if (diskonRupiah) diskonRupiah.value = '';
    
    // Show selected container
    if (diskonType.value === 'percent') {
        if (percentContainer) percentContainer.classList.remove('hidden');
    } else if (diskonType.value === 'rupiah') {
        if (rupiahContainer) rupiahContainer.classList.remove('hidden');
    } else if (diskonType.value === 'free') {
        if (freeContainer) freeContainer.classList.remove('hidden');
    }
    
    // Hide error message when switching types
    if (errorMsg) errorMsg.classList.add('hidden');
    
    // Validate after toggle
    validateDiskon();
}

// Fungsi untuk validasi diskon
function validateDiskon() {
    const diskonType = document.querySelector('input[name="diskon_type"]:checked');
    const diskonPercent = document.getElementById('diskonPercent');
    const diskonRupiah = document.getElementById('diskonRupiah');
    const errorMsg = document.getElementById('diskonError');
    
    if (!diskonType) {
        if (errorMsg) errorMsg.classList.remove('hidden');
        return false;
    }
    
    if (diskonType.value === 'free') {
        // Free is always valid
        if (errorMsg) errorMsg.classList.add('hidden');
        return true;
    } else if (diskonType.value === 'percent') {
        const percentValue = diskonPercent ? diskonPercent.value : '';
        if (!percentValue || percentValue <= 0) {
            if (errorMsg) errorMsg.classList.remove('hidden');
            return false;
        }
    } else if (diskonType.value === 'rupiah') {
        const rupiahValue = diskonRupiah ? diskonRupiah.value : '';
        if (!rupiahValue || rupiahValue <= 0) {
            if (errorMsg) errorMsg.classList.remove('hidden');
            return false;
        }
    }
    
    if (errorMsg) errorMsg.classList.add('hidden');
    return true;
}

// Fungsi untuk validasi date range
function validateDateRange() {
    const startHidden = document.getElementById('startDateHiddenUpload');
    const endHidden = document.getElementById('endDateHiddenUpload');
    const errorMsg = document.getElementById('dateError');
    
    if (!errorMsg) return true;
    
    let errorMessage = '';
    
    if (startHidden && endHidden) {
        const startDate = startHidden.value;
        const endDate = endHidden.value;
        
        // Validasi: start_date tidak boleh lebih besar dari end_date
        if (startDate && endDate && startDate > endDate) {
            errorMessage = 'Tanggal mulai tidak boleh melebihi tanggal berakhir';
        }
        
        // Validasi: start_date tidak boleh lebih awal dari merchant start_date
        if (startDate && (window.fixedMerchantStartDate || (startHidden.dataset && startHidden.dataset.merchantStartDate))) {
            const merchantStartDate = window.fixedMerchantStartDate || (startHidden.dataset && startHidden.dataset.merchantStartDate);
            if (merchantStartDate && startDate < merchantStartDate) {
                errorMessage = 'Tanggal mulai keyword tidak boleh lebih awal dari tanggal mulai periode merchant (' + formatDateDisplay(merchantStartDate) + ')';
            }
        }
        
        // Validasi: end_date tidak boleh melebihi merchant end_date
        if (endDate && (window.fixedMerchantEndDate || (endHidden.dataset && endHidden.dataset.merchantEndDate))) {
            const merchantEndDate = window.fixedMerchantEndDate || (endHidden.dataset && endHidden.dataset.merchantEndDate);
            if (merchantEndDate && endDate > merchantEndDate) {
                errorMessage = 'Tanggal akhir keyword tidak boleh melebihi tanggal akhir periode merchant (' + formatDateDisplay(merchantEndDate) + ')';
            }
        }
    }
    
    if (errorMessage) {
        errorMsg.textContent = errorMessage;
        errorMsg.classList.remove('hidden');
        return false;
    } else {
        errorMsg.classList.add('hidden');
        return true;
    }
}

// Helper function untuk format date display
function formatDateDisplay(dateString) {
    if (!dateString) return '';
    // Format: YYYY-MM-DD to DD/MM/YYYY
    const parts = dateString.split('-');
    if (parts.length === 3) {
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
    return dateString;
}

// Fungsi untuk toggle subsidy amount
function toggleSubsidyAmount() {
    const subsidyEnabled = document.querySelector('input[name="subsidy_enabled"]:checked');
    const subsidyAmountContainer = document.getElementById('subsidyAmountContainer');
    const subsidyAmount = document.getElementById('subsidyAmount');
    const subsidyError = document.getElementById('subsidyAmountError');
    
    if (subsidyEnabled && subsidyEnabled.value === '1') {
        subsidyAmountContainer.classList.remove('hidden');
        if (subsidyAmount) subsidyAmount.required = true;
    } else {
        subsidyAmountContainer.classList.add('hidden');
        if (subsidyAmount) {
            subsidyAmount.required = false;
            subsidyAmount.value = '';
        }
        if (subsidyError) subsidyError.classList.add('hidden');
    }
}

// Fungsi untuk format rupiah input
function formatRupiahInput(input) {
    // Hapus semua karakter selain angka
    let value = input.value.replace(/[^\d]/g, '');
    
    // Format dengan titik sebagai pemisah ribuan
    if (value) {
        value = parseInt(value, 10).toLocaleString('id-ID');
    }
    
    input.value = value;
}

// Fungsi untuk mendapatkan nilai numerik dari input rupiah yang sudah diformat
function getNumericValue(input) {
    return input.value.replace(/[^\d]/g, '');
}

// Fungsi untuk toggle diamond amount
function toggleDiamond() {
    const diamondEnabled = document.querySelector('input[name="diamond_enabled"]:checked');
    const diamondAmountContainer = document.getElementById('diamondAmountContainer');
    const diamondAmount = document.getElementById('diamondAmount');
    const diamondError = document.getElementById('diamondAmountError');
    
    if (diamondEnabled && diamondEnabled.value === '1') {
        diamondAmountContainer.classList.remove('hidden');
        if (diamondAmount) diamondAmount.required = true;
    } else {
        diamondAmountContainer.classList.add('hidden');
        if (diamondAmount) {
            diamondAmount.required = false;
            diamondAmount.value = '';
        }
        if (diamondError) diamondError.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize diskon type display
    const diskonTypePercent = document.querySelector('input[name="diskon_type"][value="percent"]');
    if (diskonTypePercent && diskonTypePercent.checked) {
        toggleDiskonType();
    }
    
    const form = document.getElementById('formUploadKeyword');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi diskon sebelum submit
            if (!validateDiskon()) {
                return false;
            }
            
            // Handle free diskon: set diskon_percent = 100 jika free dipilih
            const diskonType = document.querySelector('input[name="diskon_type"]:checked');
            if (diskonType && diskonType.value === 'free') {
                const diskonPercent = document.getElementById('diskonPercent');
                const diskonRupiah = document.getElementById('diskonRupiah');
                if (diskonPercent) {
                    diskonPercent.value = '100'; // 100% diskon = free
                }
                if (diskonRupiah) {
                    diskonRupiah.value = ''; // Clear rupiah value
                }
            } else {
                // Clear nilai yang tidak dipilih
                if (diskonType && diskonType.value === 'percent') {
                    const diskonRupiah = document.getElementById('diskonRupiah');
                    if (diskonRupiah) diskonRupiah.value = '';
                } else if (diskonType && diskonType.value === 'rupiah') {
                    const diskonPercent = document.getElementById('diskonPercent');
                    if (diskonPercent) diskonPercent.value = '';
                }
            }
            
            // Validasi subsidy amount jika subsidy enabled
            const subsidyEnabled = document.querySelector('input[name="subsidy_enabled"]:checked');
            if (subsidyEnabled && subsidyEnabled.value === '1') {
                const subsidyAmountInput = document.getElementById('subsidyAmount');
                const subsidyAmount = getNumericValue(subsidyAmountInput);
                const subsidyError = document.getElementById('subsidyAmountError');
                if (!subsidyAmount || subsidyAmount <= 0) {
                    if (subsidyError) subsidyError.classList.remove('hidden');
                    return false;
                } else {
                    if (subsidyError) subsidyError.classList.add('hidden');
                    // Set nilai numerik murni (tanpa format) ke input sebelum submit
                    // Format Indonesia: hapus titik (pemisah ribuan), ganti koma dengan titik (desimal)
                    let cleanValue = subsidyAmountInput.value.toString().replace(/\./g, ''); // Hapus titik
                    cleanValue = cleanValue.replace(/,/g, '.'); // Ganti koma dengan titik
                    cleanValue = cleanValue.replace(/[^\d.]/g, ''); // Hapus karakter lain
                    
                    // Pastikan hanya ada satu titik desimal
                    const parts = cleanValue.split('.');
                    if (parts.length > 2) {
                        cleanValue = parts[0] + '.' + parts.slice(1).join('');
                    }
                    
                    subsidyAmountInput.value = cleanValue;
                }
            } else {
                // Jika subsidy disabled, pastikan input kosong
                const subsidyAmountInput = document.getElementById('subsidyAmount');
                if (subsidyAmountInput) {
                    subsidyAmountInput.value = '';
                }
            }
            
            // Validasi diamond amount jika diamond enabled
            const diamondEnabled = document.querySelector('input[name="diamond_enabled"]:checked');
            if (diamondEnabled && diamondEnabled.value === '1') {
                const diamondAmount = document.getElementById('diamondAmount').value;
                const diamondError = document.getElementById('diamondAmountError');
                if (!diamondAmount || diamondAmount <= 0) {
                    if (diamondError) diamondError.classList.remove('hidden');
                    return false;
                } else {
                    if (diamondError) diamondError.classList.add('hidden');
                }
            }
            
            // Tampilkan verification modal
            const formData = new FormData(form);
            if (typeof showUploadVerification === 'function') {
                showUploadVerification(formData, 'Keyword');
            }
        });
    }
    const modal = document.getElementById('uploadModalKeyword');
    const panel = document.getElementById('uploadModalKeywordPanel');
    const overlay = document.getElementById('uploadModalKeywordOverlay');
    
    // Event listener untuk overlay - hanya tutup ketika klik di background hitam
    if (overlay) {
        overlay.addEventListener('click', function(event) {
            // Hanya tutup jika klik langsung di overlay, bukan di child element
            if (event.target === overlay) {
                closeUploadKeyword();
            }
        });
    }
    
    // Prevent event bubbling dari panel ke modal
    if (panel) {
        panel.addEventListener('click', function(event) {
            // Stop propagation untuk mencegah event bubble ke modal/overlay
            event.stopPropagation();
        });
    }
});

// ======================
// Dropdown kategori keyword
// ======================
function toggleKeywordKategoriDropdown() {
    const dropdown = document.getElementById('keywordKategoriDropdown');
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

function selectKeywordKategori(value) {
    const hiddenInput = document.getElementById('keywordKategoriValue');
    const labelSpan = document.getElementById('keywordKategoriLabel');
    const btn = document.getElementById('keywordKategoriBtn');
    const dropdown = document.getElementById('keywordKategoriDropdown');

    if (hiddenInput) hiddenInput.value = value;
    if (labelSpan) {
        // Format label dengan capitalize
        const labelMap = {
            'kuliner': 'Kuliner',
            'hiburan': 'Hiburan',
            'liburan': 'Liburan',
            'belanja': 'Belanja',
            'kecantikan': 'Kecantikan',
            'telkomsel': 'Telkomsel Paket',
            'merchandise': 'Merchandise'
        };
        labelSpan.textContent = labelMap[value] || value.charAt(0).toUpperCase() + value.slice(1);
    }

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
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('keywordKategoriDropdown');
    const button = document.getElementById('keywordKategoriBtn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        if (!dropdown.classList.contains('hidden')) {
            toggleKeywordKategoriDropdown();
        }
    }
});
</script>
