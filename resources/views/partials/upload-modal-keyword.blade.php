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
        
        <form id="formUploadKeyword" method="POST" action="{{ route('keywords.store') }}" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
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
                                <select name="merchant_key" id="merchantSelect" class="hidden" required>
                                    <option value="">-- Pilih Merchant --</option>
                                    @foreach($allMerchants as $merchant)
                                        <option value="{{ $merchant->id }}" data-name="{{ $merchant->nama_merchant }}">{{ $merchant->nama_merchant }}</option>
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
                                                    onclick="selectMerchant({{ $merchant->id }}, '{{ addslashes($merchant->nama_merchant) }}')">
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

                        <!-- Row 1.5: Nama Produk -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" id="productName" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Nama produk akan otomatis terisi" required> 
                        </div>

                        <!-- Row 1.6: Keyword ID -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keyword ID</label>
                            <input type="text" name="keyword_id" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter keyword ID">
                        </div>

                        <!-- Row 2: CTA -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA</label>
                            <input type="url" name="cta_link" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="https://example.com">
                        </div>

                        <!-- Row 3: Redeem Point -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Redeem Point</label>
                            <input type="text" name="redeem" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter redeem points">
                        </div>

                        <!-- Row 4: Diskon (Persen + Rupiah) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Diskon <span class="text-red-500">*</span> (Pilih salah satu)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-2">
                                    <input type="number" name="diskon_percent" id="diskonPercent" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" min="0" max="100" onchange="validateDiskon()">
                                    <span class="text-gray-600 font-medium">%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 font-medium">Rp</span>
                                    <input type="number" name="diskon_rupiah" id="diskonRupiah" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" onchange="validateDiskon()">
                                </div>
                            </div>
                            <p id="diskonError" class="text-red-500 text-xs mt-1 hidden">Silakan isi salah satu dari diskon (persen atau rupiah)</p>
                        </div>

                        <!-- Row 5: Stock -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Stock</label>
                            <input type="number" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter stock">
                        </div>

                        <!-- Row 6: SKB -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SKB</label>
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
    
    // Reset calendar state
    const today = new Date();
    uploadCalendarState = {
        currentMonth: today.getMonth(),
        currentYear: today.getFullYear(),
        activeType: 'start',
        startDate: null,
        endDate: null
    };
    
    // Clear date inputs
    const startInput = document.getElementById('startDateUpload');
    const endInput = document.getElementById('endDateUpload');
    if (startInput) startInput.value = '';
    if (endInput) endInput.value = '';
    
    const startHidden = document.getElementById('startDateHiddenUpload');
    const endHidden = document.getElementById('endDateHiddenUpload');
    if (startHidden) startHidden.value = '';
    if (endHidden) endHidden.value = '';
    
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

function selectMerchant(merchantId, merchantName) {
    const merchantSelect = document.getElementById('merchantSelect');
    merchantSelect.value = merchantId;
    
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
    
    toggleCustomMerchantDropdown();
    updateProductName();
    merchantSelect.dispatchEvent(new Event('change'));
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
    } else {
        productNameInput.value = '';
    }
}

// Fungsi untuk validasi diskon
function validateDiskon() {
    const diskonPercent = document.getElementById('diskonPercent').value;
    const diskonRupiah = document.getElementById('diskonRupiah').value;
    const errorMsg = document.getElementById('diskonError');
    
    if (!diskonPercent && !diskonRupiah) {
        errorMsg.classList.remove('hidden');
        return false;
    } else {
        errorMsg.classList.add('hidden');
        return true;
    }
}

// Fungsi untuk validasi date range
function validateDateRange() {
    const startHidden = document.getElementById('startDateHiddenUpload');
    const endHidden = document.getElementById('endDateHiddenUpload');
    const errorMsg = document.getElementById('dateError');
    
    if (!startHidden || !endHidden) {
        if (errorMsg) errorMsg.classList.add('hidden');
        return true;
    }
    
    const startDate = startHidden.value;
    const endDate = endHidden.value;
    
    if (startDate && endDate && startDate > endDate) {
        if (errorMsg) errorMsg.classList.remove('hidden');
        return false;
    } else {
        if (errorMsg) errorMsg.classList.add('hidden');
        return true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formUploadKeyword');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi diskon sebelum submit
            if (!validateDiskon()) {
                return false;
            }
            
            // Tampilkan verification modal
            const formData = new FormData(form);
            if (typeof showUploadVerification === 'function') {
                showUploadVerification(formData, 'Keyword');
            }
        });
    }
    const modal = document.getElementById('uploadModalKeyword');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal || event.target.id === 'uploadModalKeywordOverlay') {
                closeUploadKeyword();
            }
        });
    }
});
</script>
