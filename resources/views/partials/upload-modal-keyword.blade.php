<!-- Keyword Upload Modal -->
<div id="uploadModalKeyword" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 py-3 md:px-6 md:py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Add Keyword Data</h3>
            <button type="button" onclick="closeUploadKeyword()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="formUploadKeyword" method="POST" action="{{ route('keywords.store') }}" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
            @csrf
            <input type="hidden" name="redirect_to" id="keywordRedirectUpload">
            <input type="hidden" name="stay_on_detail" id="keywordStayOnDetailUpload">
            <div class="p-4 md:p-6 space-y-4">
                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- Row 1: Nama Merchant -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Merchant <span class="text-red-500">*</span></label>
                            <select name="merchant_key" id="merchantSelect" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" required onchange="updateProductName()">
                                <option value="">-- Pilih Merchant --</option>
                                @foreach($allMerchants as $merchant)
                                    <option value="{{ $merchant->id }}">{{ $merchant->nama_merchant }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Row 1.5: Nama Produk -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" id="productName" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Nama produk akan otomatis terisi" required> 
                        </div>

                        <!-- Row 1.6: Keyword ID -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Keyword ID</label>
                            <input type="text" name="keyword_id" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter keyword ID">
                        </div>

                        <!-- Row 2: CTA -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">CTA</label>
                            <input type="url" name="cta_link" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="https://example.com">
                        </div>

                        <!-- Row 3: Redeem Point -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Redeem Point</label>
                            <input type="text" name="redeem" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter redeem points">
                        </div>

                        <!-- Row 4: Diskon (Persen + Rupiah) -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Diskon <span class="text-red-500">*</span> (Pilih salah satu)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-2">
                                    <input type="number" name="diskon_percent" id="diskonPercent" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="0" min="0" max="100" onchange="validateDiskon()">
                                    <span class="text-gray-600 font-medium">%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 font-medium">Rp</span>
                                    <input type="number" name="diskon_rupiah" id="diskonRupiah" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="0" onchange="validateDiskon()">
                                </div>
                            </div>
                            <p id="diskonError" class="text-red-500 text-xs mt-1 hidden">Silakan isi salah satu dari diskon (persen atau rupiah)</p>
                        </div>

                        <!-- Row 5: Stock -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Stock</label>
                            <input type="number" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter stock">
                        </div>

                        <!-- Row 6: SKB -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">SKB</label>
                            <textarea name="skb" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none" placeholder="Enter SKB"></textarea>
                        </div>

                        <!-- Row 7: Start Date | End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Start Date</label>
                            <input type="date" id="startDate" name="start_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" onchange="validateDateRange()">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">End Date</label>
                            <input type="date" id="endDate" name="end_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" onchange="validateDateRange()">
                            <p id="dateError" class="text-red-500 text-xs mt-1 hidden">Tanggal mulai tidak boleh melebihi tanggal berakhir</p>
                        </div>
                        
                        <!-- Row 8: Images -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Images</label>
                            <div class="relative">
                                <input type="file" id="keywordImagesInput" name="image" accept="image/*" class="hidden" onchange="previewKeywordImages(this)">
                                <button type="button" onclick="document.getElementById('keywordImagesInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="keywordImagesText" class="text-[15px]">Click to upload image</span>
                                </button>
                                <div id="keywordImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-4 py-3 md:px-6 md:py-4 border-t bg-white rounded-b-xl">
                <button type="button" onclick="closeUploadKeyword()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">Simpan</button>
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
    const productNameInput = document.getElementById('productName');
    const redirectInput = document.getElementById('keywordRedirectUpload');
    const stayFlagInput = document.getElementById('keywordStayOnDetailUpload');
    if (!merchantSelect) return;

    const hasFixedMerchant = Boolean(window.fixedMerchantId);
    const existingHidden = document.getElementById('lockedMerchantKey');
    if (hasFixedMerchant) {
        merchantSelect.value = window.fixedMerchantId;
        merchantSelect.disabled = true;
        merchantSelect.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-600');

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
            productNameInput.value = (selectedOption && selectedOption.text) || window.fixedMerchantName || '';
        }
        if (redirectInput && window.detailRedirectUrl) {
            redirectInput.value = window.detailRedirectUrl;
        }
        if (stayFlagInput) {
            stayFlagInput.value = '1';
        }
    } else {
        merchantSelect.disabled = false;
        merchantSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-600');
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
    if (!modal) return;
    applyFixedMerchantContext();
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0.5'; }, 10);
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(1)'; modalContent.style.opacity = '1'; } }, 50);
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea');
    formElements.forEach((el, index) => { setTimeout(() => { el.style.transform = 'translateY(0)'; el.style.opacity = '1'; }, 100 + (index * 30)); });
}

function closeUploadKeyword() {
    const modal = document.getElementById('uploadModalKeyword');
    if (!modal) return;
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea');
    formElements.forEach((el, index) => { setTimeout(() => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; }, index * 20); });
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; } }, 100);
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0'; }, 150);
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        const form = document.getElementById('formUploadKeyword');
        if (form) form.reset();
        applyFixedMerchantContext();
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}


// Fungsi untuk update nama produk berdasarkan merchant yang dipilih
function updateProductName() {
    const merchantSelect = document.getElementById('merchantSelect');
    const productNameInput = document.getElementById('productName');
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    
    if (selectedOption.value) {
        productNameInput.value = selectedOption.text;
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
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const errorMsg = document.getElementById('dateError');
    
    if (startDate && endDate && startDate > endDate) {
        errorMsg.classList.remove('hidden');
        return false;
    } else {
        errorMsg.classList.add('hidden');
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
    if (modal) { modal.addEventListener('click', function(event) { if (event.target === this) closeUploadKeyword(); }); }
});
</script>
