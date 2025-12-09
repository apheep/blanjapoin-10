<!-- Keyword Edit Modal -->
<div id="editModalKeyword" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="editModalKeywordOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    
    <div id="editModalKeywordPanel" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden opacity-0 scale-95 translate-y-4 transition-all duration-300">
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-xl font-bold text-gray-800">
                Edit Keyword Data
            </h3>
            <button type="button"
                    onclick="closeEditKeyword()"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="formEditKeyword" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
            @csrf
            @method('PUT')
            <input type="hidden" name="redirect_to" id="keywordRedirectEdit">
            <input type="hidden" name="stay_on_detail" id="keywordStayOnDetailEdit">
            <div class="p-6 space-y-6">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Row 1: Nama Merchant -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Merchant <span class="text-red-500">*</span></label>
                            
                            <!-- Custom Dropdown -->
                            <div class="relative">
                                <!-- Hidden select for form submission -->
                                <select name="merchant_key" id="editMerchantSelect" class="hidden" required>
                                <option value="">-- Pilih Merchant --</option>
                                @foreach($allMerchants as $merchant)
                                        <option value="{{ $merchant->id }}" data-name="{{ $merchant->nama_merchant }}" data-email="{{ $merchant->email_pic ?? '' }}">{{ $merchant->nama_merchant }}</option>
                                @endforeach
                            </select>
                                
                                <!-- Custom Dropdown Button -->
                                <button type="button" 
                                        id="editCustomMerchantDropdownBtn" 
                                        onclick="toggleEditCustomMerchantDropdown()"
                                        class="w-full px-4 h-12 border border-gray-300 rounded-lg bg-white text-left flex items-center justify-between hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-colors">
                                    <span id="editCustomMerchantSelectedText" class="text-sm text-gray-600">
                                        -- Pilih Merchant --
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="editCustomMerchantChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Custom Dropdown Menu -->
                                <div id="editCustomMerchantDropdown" 
                                     class="hidden absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden max-h-80 flex flex-col">
                                    <!-- Search Box -->
                                    <div class="p-2 border-b border-gray-100">
                                        <input type="text" 
                                               id="editMerchantSearchInput" 
                                               placeholder="Cari merchant..." 
                                               onkeyup="filterEditMerchantOptions(this.value)"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400">
                                    </div>
                                    
                                    <!-- Options Container -->
                                    <div id="editMerchantOptionsContainer" class="overflow-y-auto max-h-64">
                                        @foreach($allMerchants as $merchant)
                                            <button type="button" 
                                                    class="edit-merchant-option w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0"
                                                    data-value="{{ $merchant->id }}"
                                                    data-name="{{ $merchant->nama_merchant }}"
                                                    data-email="{{ $merchant->email_pic ?? '' }}"
                                                    onclick="selectEditMerchant({{ $merchant->id }}, '{{ addslashes($merchant->nama_merchant) }}', '{{ $merchant->email_pic ?? '' }}')">
                                                {{ $merchant->nama_merchant }}
                                            </button>
                                        @endforeach
                                        
                                        <!-- Empty State -->
                                        <div id="editMerchantEmptyState" class="hidden px-4 py-8 text-center">
                                            <p class="text-sm text-gray-500">Merchant tidak ditemukan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1.5: Nama Produk -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" id="editProductName" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Nama produk akan otomatis terisi" required> 
                        </div>

                        <!-- Row 1.6: Keyword ID -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keyword ID</label>
                            <input type="text" name="keyword_id" id="editKeywordId" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter keyword ID">
                        </div>

                        <!-- Row 2: CTA -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA</label>
                            <input type="url" name="cta_link" id="editCtaLink" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="https://example.com">
                        </div>

                        <!-- Row 3: Redeem Point -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Redeem Point</label>
                            <input type="text" name="redeem" id="editRedeem" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter redeem points">
                        </div>

                        <!-- Row 4: Diskon (Persen + Rupiah + Free) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Diskon <span class="text-red-500">*</span> (Pilih salah satu)</label>
                            
                            <!-- Radio buttons untuk memilih jenis diskon -->
                            <div class="flex items-center gap-4 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="edit_diskon_type" value="percent" id="editDiskonTypePercent" onchange="toggleEditDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Persen (%)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="edit_diskon_type" value="rupiah" id="editDiskonTypeRupiah" onchange="toggleEditDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Rupiah (Rp)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="edit_diskon_type" value="free" id="editDiskonTypeFree" onchange="toggleEditDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Free</span>
                                </label>
                            </div>
                            
                            <!-- Input fields untuk diskon -->
                            <div id="editDiskonPercentContainer" class="flex items-center gap-2">
                                <span class="w-12 text-center text-gray-600 font-medium shrink-0">%</span>
                                <input type="text" name="diskon_percent" id="editDiskonPercent" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" min="0" max="100" onchange="validateEditDiskon()">
                            </div>
                            <div id="editDiskonRupiahContainer" class="hidden flex items-center gap-2">
                                <span class="w-12 text-center text-gray-600 font-medium shrink-0">Rp</span>
                                <input type="text" name="diskon_rupiah" id="editDiskonRupiah" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" onchange="validateEditDiskon()">
                            </div>
                            <div id="editDiskonFreeContainer" class="hidden">
                                <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-700 font-medium flex items-center gap-2">
                                        <i class="fas fa-gift text-green-600"></i>
                                        Produk ini akan ditandai sebagai <strong>FREE</strong>
                                    </p>
                                </div>
                            </div>
                            <p id="editDiskonError" class="text-red-500 text-xs mt-1 hidden">Silakan pilih salah satu jenis diskon (persen, rupiah, atau free)</p>
                        </div>

                        <!-- Row 4.5: Subsidi Diskon -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Subsidi Diskon</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="subsidy_enabled" value="0" id="editSubsidyEnabledNo" checked onchange="toggleEditSubsidyAmount()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">No</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="subsidy_enabled" value="1" id="editSubsidyEnabledYes" onchange="toggleEditSubsidyAmount()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Yes</span>
                                </label>
                            </div>
                            <div id="editSubsidyAmountContainer" class="mt-3 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal Subsidi (Rupiah) <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <span class="w-12 text-center text-gray-600 font-medium shrink-0">Rp</span>
                                    <input type="text" name="subsidy_amount" id="editSubsidyAmount" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" inputmode="numeric" oninput="formatRupiahInput(this)">
                                </div>
                                <p id="editSubsidyAmountError" class="text-red-500 text-xs mt-1 hidden">Nominal subsidi wajib diisi jika Subsidi Diskon dipilih Yes</p>
                            </div>
                        </div>

                        <!-- Row 4.6: Diamond -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Diamond</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diamond_enabled" value="0" id="editDiamondEnabledNo" checked onchange="toggleEditDiamond()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">No</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diamond_enabled" value="1" id="editDiamondEnabledYes" onchange="toggleEditDiamond()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Yes</span>
                                </label>
                            </div>
                            <div id="editDiamondAmountContainer" class="mt-3 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Diamond <span class="text-red-500">*</span></label>
                                <input type="number" name="diamond_amount" id="editDiamondAmount" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Masukkan jumlah diamond" min="0">
                                <p id="editDiamondAmountError" class="text-red-500 text-xs mt-1 hidden">Jumlah diamond wajib diisi jika Diamond dipilih Yes</p>
                            </div>
                        </div>

                        <!-- Row 5: Stock -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Stock</label>
                            <input type="number" name="stock" id="editStock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="Enter stock">
                        </div>

                        <!-- Row 6: SKB -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">SKB</label>
                            <textarea name="skb" id="editSkb" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm resize-none" placeholder="Enter SKB"></textarea>
                        </div>

                        <!-- Row 7: Start Date | End Date -->
                        <div class="md:col-span-2">
                            @include('partials.date-filter-edit')
                            <p id="editDateError" class="text-red-500 text-xs mt-1 hidden">Tanggal mulai tidak boleh melebihi tanggal berakhir</p>
                        </div>
                        
                        <!-- Row 8: Images -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Images</label>
                            <div class="relative">
                                <input type="file" id="editKeywordImagesInput" name="image" accept="image/*" class="hidden" onchange="previewEditKeywordImages(this)">
                                <button type="button" onclick="document.getElementById('editKeywordImagesInput').click()" class="w-full min-h-[120px] px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all">
                                    <i class="fas fa-upload text-3xl mb-2"></i>
                                    <span id="editKeywordImagesText" class="text-sm">Click to upload image</span>
                                </button>
                                <div id="editKeywordImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                                <div id="editKeywordCurrentImage" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sticky bottom-0 z-10 flex justify-end items-center gap-3 px-6 py-4 border-t bg-white">
                <button type="button"
                        onclick="closeEditKeyword()"
                        class="px-6 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.edit-modal-validation')

<script>
let currentEditKeywordId = null;
let editCalendarState = {
    currentMonth: new Date().getMonth(),
    currentYear: new Date().getFullYear(),
    activeType: 'start',
    startDate: null,
    endDate: null
};

function previewEditKeywordImages(input) {
    const preview = document.getElementById('editKeywordImagesPreview');
    const text = document.getElementById('editKeywordImagesText');
    preview.innerHTML = '';
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        if (text) text.textContent = `${input.files.length} file(s) selected`;
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg"><button type="button" onclick="removeEditKeywordImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"><i class="fas fa-times text-xs"></i></button>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeEditKeywordImage(index) {
    const input = document.getElementById('editKeywordImagesInput');
    const dt = new DataTransfer();
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) dt.items.add(input.files[i]);
    }
    input.files = dt.files;
    previewEditKeywordImages(input);
    if (input.files.length === 0) {
        document.getElementById('editKeywordImagesPreview').classList.add('hidden');
        document.getElementById('editKeywordImagesText').textContent = 'Click to upload image';
    }
}

function openEditKeyword(id, keywordData) {
    currentEditKeywordId = id;
    const modal = document.getElementById('editModalKeyword');
    const overlay = document.getElementById('editModalKeywordOverlay');
    const panel = document.getElementById('editModalKeywordPanel');
    const form = document.getElementById('formEditKeyword');
    const redirectInput = document.getElementById('keywordRedirectEdit');
    const stayFlagInput = document.getElementById('keywordStayOnDetailEdit');
    
    // Set form action
    form.action = `/keywords/${id}`;
    
    // Set redirect URL
    if (redirectInput && window.detailRedirectUrl) {
        redirectInput.value = window.detailRedirectUrl;
        if (stayFlagInput) {
            stayFlagInput.value = '1';
        }
    } else if (redirectInput) {
        const currentUrl = new URL(window.location.href);
        const currentTab = currentUrl.searchParams.get('tab');
        
        if (currentTab === 'keyword') {
            const baseUrl = currentUrl.pathname;
            redirectInput.value = `${baseUrl}?tab=keyword`;
        } else {
            redirectInput.value = window.location.pathname + '?tab=keyword';
    }
    if (stayFlagInput) {
            stayFlagInput.value = '';
        }
    }
    
    // Populate form fields
    const merchantSelect = document.getElementById('editMerchantSelect');
    merchantSelect.value = keywordData.merchant_key;
    
    // Update custom dropdown
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    if (selectedOption && selectedOption.value) {
        const selectedText = document.getElementById('editCustomMerchantSelectedText');
        selectedText.textContent = selectedOption.text;
        selectedText.classList.remove('text-gray-600');
        selectedText.classList.add('text-gray-900', 'font-medium');
        
        const button = document.getElementById('editCustomMerchantDropdownBtn');
        button.classList.remove('border-gray-300');
        button.classList.add('border-gray-400');
    }
    
    document.getElementById('editProductName').value = keywordData.nama_produk;
    document.getElementById('editKeywordId').value = keywordData.keyword_id || '';
    document.getElementById('editCtaLink').value = keywordData.cta_link || '';
    document.getElementById('editRedeem').value = keywordData.redeem || '';
    document.getElementById('editStock').value = keywordData.stock || '';
    document.getElementById('editSkb').value = keywordData.skb || '';
    
    // Parse diskon
    const diskonStr = keywordData.diskon || '';
    if (diskonStr === 'FREE' || diskonStr === 'free') {
        document.getElementById('editDiskonTypeFree').checked = true;
        document.getElementById('editDiskonPercent').value = '';
        document.getElementById('editDiskonRupiah').value = '';
    } else if (diskonStr.includes('%')) {
        const percent = diskonStr.replace('%', '').trim();
        document.getElementById('editDiskonTypePercent').checked = true;
        document.getElementById('editDiskonPercent').value = percent;
        document.getElementById('editDiskonRupiah').value = '';
    } else if (diskonStr.includes('Rp')) {
        const rupiah = diskonStr.replace('Rp', '').replace(/\./g, '').replace(/,/g, '').trim();
        document.getElementById('editDiskonTypeRupiah').checked = true;
        document.getElementById('editDiskonRupiah').value = rupiah;
        document.getElementById('editDiskonPercent').value = '';
    }
    toggleEditDiskonType();
    
    // Parse subsidy amount
    if (keywordData.subsidy_amount) {
        document.getElementById('editSubsidyEnabledYes').checked = true;
        const subsidyAmount = parseFloat(keywordData.subsidy_amount);
        document.getElementById('editSubsidyAmount').value = subsidyAmount.toLocaleString('id-ID');
        toggleEditSubsidyAmount();
    } else {
        document.getElementById('editSubsidyEnabledNo').checked = true;
        toggleEditSubsidyAmount();
    }
    
    // Parse diamond amount
    if (keywordData.diamond_amount) {
        document.getElementById('editDiamondEnabledYes').checked = true;
        document.getElementById('editDiamondAmount').value = keywordData.diamond_amount;
        toggleEditDiamond();
    } else {
        document.getElementById('editDiamondEnabledNo').checked = true;
        toggleEditDiamond();
    }
    
    // Parse dates
    if (keywordData.start_date) {
        const startDate = new Date(keywordData.start_date);
        editCalendarState.startDate = startDate;
        document.getElementById('editStartDate').value = formatDateForDisplayEdit(startDate);
        let hiddenInput = document.getElementById('editStartDateHidden');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'editStartDateHidden';
            hiddenInput.name = 'start_date';
            document.getElementById('editStartDate').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = formatDateForInputEdit(startDate);
    }
    
    if (keywordData.end_date) {
        const endDate = new Date(keywordData.end_date);
        editCalendarState.endDate = endDate;
        document.getElementById('editEndDate').value = formatDateForDisplayEdit(endDate);
        let hiddenInput = document.getElementById('editEndDateHidden');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'editEndDateHidden';
            hiddenInput.name = 'end_date';
            document.getElementById('editEndDate').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = formatDateForInputEdit(endDate);
    }
    
    // Show current image if exists
    const currentImageDiv = document.getElementById('editKeywordCurrentImage');
    if (keywordData.image) {
        currentImageDiv.innerHTML = `
            <div class="relative group">
                <p class="text-xs text-gray-500 mb-2">Gambar saat ini:</p>
                <img src="/storage/${keywordData.image}" alt="Current Image" class="w-full h-24 object-cover rounded-lg">
            </div>
        `;
    } else {
        currentImageDiv.innerHTML = '';
    }
    
    // Clear file input
    document.getElementById('editKeywordImagesInput').value = '';
    document.getElementById('editKeywordImagesPreview').innerHTML = '';
    document.getElementById('editKeywordImagesPreview').classList.add('hidden');
    document.getElementById('editKeywordImagesText').textContent = 'Click to upload image';
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        overlay?.classList.remove('opacity-0');
        overlay?.classList.add('opacity-100');
        panel?.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        panel?.classList.add('opacity-100', 'scale-100', 'translate-y-0');
    });
}

function closeEditKeyword() {
    const modal = document.getElementById('editModalKeyword');
    const overlay = document.getElementById('editModalKeywordOverlay');
    const panel = document.getElementById('editModalKeywordPanel');
    
    overlay?.classList.remove('opacity-100');
    overlay?.classList.add('opacity-0');
    panel?.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    panel?.classList.add('opacity-0', 'scale-95', 'translate-y-4');
    
    setTimeout(() => {
        modal?.classList.add('hidden');
        document.body.style.overflow = '';

        const form = document.getElementById('formEditKeyword');
        if (form) {
            form.reset();
        }
        
        // Reset custom dropdown
        const customDropdown = document.getElementById('editCustomMerchantDropdown');
        const chevron = document.getElementById('editCustomMerchantChevron');
        const customButton = document.getElementById('editCustomMerchantDropdownBtn');
        const selectedText = document.getElementById('editCustomMerchantSelectedText');
        const searchInput = document.getElementById('editMerchantSearchInput');
        
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
            filterEditMerchantOptions('');
        }
        
        // Reset all merchant options highlight
        const options = document.querySelectorAll('.edit-merchant-option');
        options.forEach(opt => {
            opt.classList.remove('bg-gray-100', 'font-medium');
            opt.style.display = '';
        });
        
        // Reset diskon type
        const diskonTypePercent = document.getElementById('editDiskonTypePercent');
        if (diskonTypePercent) {
            diskonTypePercent.checked = true;
            toggleEditDiskonType();
        }
        
        // Reset subsidy
        const subsidyNo = document.getElementById('editSubsidyEnabledNo');
        if (subsidyNo) {
            subsidyNo.checked = true;
            toggleEditSubsidyAmount();
        }
        
        // Reset diamond
        const diamondNo = document.getElementById('editDiamondEnabledNo');
        if (diamondNo) {
            diamondNo.checked = true;
            toggleEditDiamond();
        }
        
        // Reset calendar state
        const today = new Date();
        editCalendarState = {
            currentMonth: today.getMonth(),
            currentYear: today.getFullYear(),
            activeType: 'start',
            startDate: null,
            endDate: null
        };
        
        // Clear date inputs
        const startInput = document.getElementById('editStartDate');
        const endInput = document.getElementById('editEndDate');
        if (startInput) startInput.value = '';
        if (endInput) endInput.value = '';
        
        const startHidden = document.getElementById('editStartDateHidden');
        const endHidden = document.getElementById('editEndDateHidden');
        if (startHidden) startHidden.value = '';
        if (endHidden) endHidden.value = '';
    }, 300);
}

// Custom Dropdown Functions
function toggleEditCustomMerchantDropdown() {
    const dropdown = document.getElementById('editCustomMerchantDropdown');
    const chevron = document.getElementById('editCustomMerchantChevron');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
        setTimeout(() => {
            document.getElementById('editMerchantSearchInput')?.focus();
        }, 100);
    } else {
        dropdown.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

function selectEditMerchant(merchantId, merchantName, merchantEmail = '') {
    const merchantSelect = document.getElementById('editMerchantSelect');
    merchantSelect.value = merchantId;
    
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    if (selectedOption) {
        selectedOption.dataset.email = merchantEmail;
    }
    
    const selectedText = document.getElementById('editCustomMerchantSelectedText');
    selectedText.textContent = merchantName;
    selectedText.classList.remove('text-gray-600');
    selectedText.classList.add('text-gray-900', 'font-medium');
    
    const button = document.getElementById('editCustomMerchantDropdownBtn');
    button.classList.remove('border-gray-300');
    button.classList.add('border-gray-400');
    
    const options = document.querySelectorAll('.edit-merchant-option');
    options.forEach(opt => {
        opt.classList.remove('bg-gray-100', 'font-medium');
        if (opt.dataset.value == merchantId) {
            opt.classList.add('bg-gray-100', 'font-medium');
        }
    });
    
    toggleEditCustomMerchantDropdown();
    updateEditProductName();
    merchantSelect.dispatchEvent(new Event('change'));
}

function filterEditMerchantOptions(searchTerm) {
    const options = document.querySelectorAll('.edit-merchant-option');
    const emptyState = document.getElementById('editMerchantEmptyState');
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

function updateEditProductName() {
    const merchantSelect = document.getElementById('editMerchantSelect');
    const productNameInput = document.getElementById('editProductName');
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        productNameInput.value = selectedOption.dataset.name || selectedOption.text;
    } else {
        productNameInput.value = '';
    }
}

// Fungsi untuk toggle jenis diskon
function toggleEditDiskonType() {
    const diskonType = document.querySelector('input[name="edit_diskon_type"]:checked');
    const percentContainer = document.getElementById('editDiskonPercentContainer');
    const rupiahContainer = document.getElementById('editDiskonRupiahContainer');
    const freeContainer = document.getElementById('editDiskonFreeContainer');
    const errorMsg = document.getElementById('editDiskonError');
    
    if (!diskonType) return;
    
    // Hide all containers first
    if (percentContainer) percentContainer.classList.add('hidden');
    if (rupiahContainer) rupiahContainer.classList.add('hidden');
    if (freeContainer) freeContainer.classList.add('hidden');
    
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
    validateEditDiskon();
}

// Fungsi untuk validasi diskon
function validateEditDiskon() {
    const diskonType = document.querySelector('input[name="edit_diskon_type"]:checked');
    const diskonPercent = document.getElementById('editDiskonPercent');
    const diskonRupiah = document.getElementById('editDiskonRupiah');
    const errorMsg = document.getElementById('editDiskonError');
    
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

// Fungsi untuk toggle subsidy amount
function toggleEditSubsidyAmount() {
    const subsidyEnabled = document.querySelector('input[name="subsidy_enabled"]:checked');
    const subsidyAmountContainer = document.getElementById('editSubsidyAmountContainer');
    const subsidyAmount = document.getElementById('editSubsidyAmount');
    const subsidyError = document.getElementById('editSubsidyAmountError');
    
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

// Fungsi untuk toggle diamond amount
function toggleEditDiamond() {
    const diamondEnabled = document.querySelector('input[name="diamond_enabled"]:checked');
    const diamondAmountContainer = document.getElementById('editDiamondAmountContainer');
    const diamondAmount = document.getElementById('editDiamondAmount');
    const diamondError = document.getElementById('editDiamondAmountError');
    
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

// Date picker functions for edit modal
function openDatePickerEdit(type) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    editCalendarState.activeType = type;
    
    if (type === 'end' && editCalendarState.startDate) {
        editCalendarState.currentMonth = editCalendarState.startDate.getMonth();
        editCalendarState.currentYear = editCalendarState.startDate.getFullYear();
    }
    
    const modal = document.getElementById('datePickerModalEdit');
    const content = document.getElementById('datePickerContentEdit');
    
    if (!modal || !content) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        content.style.opacity = '1';
        content.style.transform = 'scale(1)';
    });
    
    renderCalendarEdit();
}

window.openDatePickerEdit = openDatePickerEdit;

function closeDatePickerEdit() {
    const modal = document.getElementById('datePickerModalEdit');
    const content = document.getElementById('datePickerContentEdit');
    
    if (!modal || !content) return;
    
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function renderCalendarEdit() {
    const container = document.getElementById('calendarEdit');
    if (!container) return;
    
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const daysOfWeek = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
    
    const firstDay = new Date(editCalendarState.currentYear, editCalendarState.currentMonth, 1);
    const lastDay = new Date(editCalendarState.currentYear, editCalendarState.currentMonth + 1, 0);
    const startDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
    const daysInMonth = lastDay.getDate();
    
    let html = `
        <div class="flex items-center justify-between mb-3">
            <button type="button" onclick="changeMonthEdit(-1); return false;" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <div class="text-base font-semibold text-gray-800">${months[editCalendarState.currentMonth]} ${editCalendarState.currentYear}</div>
            <button type="button" onclick="changeMonthEdit(1); return false;" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-chevron-right text-gray-600"></i>
            </button>
        </div>
        <div class="grid grid-cols-7 gap-1 mb-2">
    `;
    
    daysOfWeek.forEach(day => {
        html += `<div class="text-center text-xs font-semibold text-gray-500 py-1">${day}</div>`;
    });
    
    html += `</div><div class="grid grid-cols-7 gap-1">`;
    
    for (let i = 0; i < startDayOfWeek; i++) {
        html += `<div></div>`;
    }
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const isSelectingEndDate = editCalendarState.activeType === 'end';
    const minDate = isSelectingEndDate && editCalendarState.startDate ? editCalendarState.startDate : today;
    
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = new Date(editCalendarState.currentYear, editCalendarState.currentMonth, day);
        currentDate.setHours(0, 0, 0, 0);
        
        const isToday = currentDate.getTime() === today.getTime();
        const isPast = currentDate.getTime() < minDate.getTime();
        const isSelected = isDateSelectedEdit(currentDate);
        const isStartDate = isSelectingEndDate && editCalendarState.startDate && 
                           formatDateForInputEdit(currentDate) === formatDateForInputEdit(editCalendarState.startDate);
        
        let dayClass = 'text-center text-sm py-2 rounded-lg transition-all aspect-square flex items-center justify-center ';
        
        if (isPast) {
            dayClass += 'text-gray-300 cursor-not-allowed bg-gray-50';
            html += `<div class="${dayClass}">${day}</div>`;
        } else if (isSelected) {
            dayClass += 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold cursor-pointer';
            html += `<div class="${dayClass}" onclick="selectDateEdit(${day}); return false;">${day}</div>`;
        } else if (isStartDate) {
            dayClass += 'bg-blue-100 text-blue-700 font-semibold cursor-pointer border-2 border-blue-400';
            html += `<div class="${dayClass}" onclick="selectDateEdit(${day}); return false;">${day}</div>`;
        } else if (isToday) {
            dayClass += 'bg-orange-100 text-orange-700 font-medium cursor-pointer hover:bg-orange-200';
            html += `<div class="${dayClass}" onclick="selectDateEdit(${day}); return false;">${day}</div>`;
        } else {
            dayClass += 'text-gray-700 hover:bg-orange-50 hover:text-orange-600 cursor-pointer';
            html += `<div class="${dayClass}" onclick="selectDateEdit(${day}); return false;">${day}</div>`;
        }
    }
    
    html += `</div>`;
    container.innerHTML = html;
}

function changeMonthEdit(delta) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    editCalendarState.currentMonth += delta;
    if (editCalendarState.currentMonth > 11) {
        editCalendarState.currentMonth = 0;
        editCalendarState.currentYear++;
    }
    if (editCalendarState.currentMonth < 0) {
        editCalendarState.currentMonth = 11;
        editCalendarState.currentYear--;
    }
    renderCalendarEdit();
}

function selectDateEdit(day) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const selectedDate = new Date(editCalendarState.currentYear, editCalendarState.currentMonth, day);
    selectedDate.setHours(0, 0, 0, 0);
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate.getTime() < today.getTime()) {
        return;
    }
    
    const formattedDate = formatDateForDisplayEdit(selectedDate);
    const dateValue = formatDateForInputEdit(selectedDate);
    
    if (editCalendarState.activeType === 'start') {
        editCalendarState.startDate = selectedDate;
        document.getElementById('editStartDate').value = formattedDate;
        
        let hiddenInput = document.getElementById('editStartDateHidden');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'editStartDateHidden';
            hiddenInput.name = 'start_date';
            document.getElementById('editStartDate').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = dateValue;
        
        if (editCalendarState.endDate && selectedDate > editCalendarState.endDate) {
            editCalendarState.endDate = null;
            document.getElementById('editEndDate').value = '';
            const endHidden = document.getElementById('editEndDateHidden');
            if (endHidden) endHidden.value = '';
        }
    } else {
        if (editCalendarState.startDate && selectedDate < editCalendarState.startDate) {
            alert('Tanggal akhir tidak boleh sebelum tanggal mulai');
            return;
        }
        if (selectedDate.getTime() < today.getTime()) {
            return;
        }
        editCalendarState.endDate = selectedDate;
        document.getElementById('editEndDate').value = formattedDate;
        
        let hiddenInput = document.getElementById('editEndDateHidden');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'editEndDateHidden';
            hiddenInput.name = 'end_date';
            document.getElementById('editEndDate').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = dateValue;
    }
    
    renderCalendarEdit();
    validateEditDateRange();
    closeDatePickerEdit();
}

function isDateSelectedEdit(date) {
    const dateStr = formatDateForInputEdit(date);
    if (editCalendarState.activeType === 'start' && editCalendarState.startDate) {
        return formatDateForInputEdit(editCalendarState.startDate) === dateStr;
    }
    if (editCalendarState.activeType === 'end' && editCalendarState.endDate) {
        return formatDateForInputEdit(editCalendarState.endDate) === dateStr;
    }
    return false;
}

function formatDateForDisplayEdit(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function formatDateForInputEdit(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function validateEditDateRange() {
    const startHidden = document.getElementById('editStartDateHidden');
    const endHidden = document.getElementById('editEndDateHidden');
    const errorMsg = document.getElementById('editDateError');
    
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

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('editCustomMerchantDropdown');
    const button = document.getElementById('editCustomMerchantDropdownBtn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        if (!dropdown.classList.contains('hidden')) {
            toggleEditCustomMerchantDropdown();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditKeyword');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi diskon sebelum submit
            if (!validateEditDiskon()) {
                return false;
            }
            
            // Handle free diskon: set diskon_percent = 100 jika free dipilih
            const diskonType = document.querySelector('input[name="edit_diskon_type"]:checked');
            if (diskonType && diskonType.value === 'free') {
                const diskonPercent = document.getElementById('editDiskonPercent');
                const diskonRupiah = document.getElementById('editDiskonRupiah');
                if (diskonPercent) {
                    diskonPercent.value = '100';
                }
                if (diskonRupiah) {
                    diskonRupiah.value = '';
                }
            } else {
                if (diskonType && diskonType.value === 'percent') {
                    const diskonRupiah = document.getElementById('editDiskonRupiah');
                    if (diskonRupiah) diskonRupiah.value = '';
                } else if (diskonType && diskonType.value === 'rupiah') {
                    const diskonPercent = document.getElementById('editDiskonPercent');
                    if (diskonPercent) diskonPercent.value = '';
                }
            }
            
            // Validasi subsidy amount jika subsidy enabled
            const subsidyEnabled = document.querySelector('input[name="subsidy_enabled"]:checked');
            if (subsidyEnabled && subsidyEnabled.value === '1') {
                const subsidyAmountInput = document.getElementById('editSubsidyAmount');
                const subsidyAmount = getNumericValue(subsidyAmountInput);
                const subsidyError = document.getElementById('editSubsidyAmountError');
                if (!subsidyAmount || subsidyAmount <= 0) {
                    if (subsidyError) subsidyError.classList.remove('hidden');
                    return false;
                } else {
                    if (subsidyError) subsidyError.classList.add('hidden');
                    let cleanValue = subsidyAmountInput.value.toString().replace(/\./g, '');
                    cleanValue = cleanValue.replace(/,/g, '.');
                    cleanValue = cleanValue.replace(/[^\d.]/g, '');
                    
                    const parts = cleanValue.split('.');
                    if (parts.length > 2) {
                        cleanValue = parts[0] + '.' + parts.slice(1).join('');
                    }
                    
                    subsidyAmountInput.value = cleanValue;
                }
            } else {
                const subsidyAmountInput = document.getElementById('editSubsidyAmount');
                if (subsidyAmountInput) {
                    subsidyAmountInput.value = '';
                }
            }
            
            // Validasi diamond amount jika diamond enabled
            const diamondEnabled = document.querySelector('input[name="diamond_enabled"]:checked');
            if (diamondEnabled && diamondEnabled.value === '1') {
                const diamondAmount = document.getElementById('editDiamondAmount').value;
                const diamondError = document.getElementById('editDiamondAmountError');
                if (!diamondAmount || diamondAmount <= 0) {
                    if (diamondError) diamondError.classList.remove('hidden');
                    return false;
                } else {
                    if (diamondError) diamondError.classList.add('hidden');
                }
            }
            
            // Validasi date range sebelum submit
            if (!validateEditDateRange()) {
                return false;
            }
            
            // Kumpulkan data untuk ditampilkan di modal verifikasi
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) {
                data[key] = value;
            }
            data.id = currentEditKeywordId;
            
            // Tampilkan modal verifikasi edit sebelum submit
            if (typeof showEditValidation === 'function') {
                showEditValidation(data, 'Keyword');
            } else {
                form.submit();
            }
        });
    }
    
    const modal = document.getElementById('editModalKeyword');
    const panel = document.getElementById('editModalKeywordPanel');
    const overlay = document.getElementById('editModalKeywordOverlay');
    
    if (overlay) {
        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                closeEditKeyword();
            }
        });
    }
    
    if (panel) {
        panel.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }
});
</script>
