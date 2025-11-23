<!-- Keyword Edit Modal -->
<div id="editModalKeyword" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 py-3 md:px-6 md:py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Edit Keyword Data</h3>
            <button type="button" onclick="closeEditKeyword()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="formEditKeyword" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
            @csrf
            @method('PUT')
            <input type="hidden" name="redirect_to" id="keywordRedirectEdit">
            <input type="hidden" name="stay_on_detail" id="keywordStayOnDetailEdit">
            <div class="p-4 md:p-6 space-y-4">
                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- Row 1: Nama Merchant -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Merchant <span class="text-red-500">*</span></label>
                            <select name="merchant_key" id="editMerchantSelect" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" required onchange="updateEditProductName()">
                                <option value="">-- Pilih Merchant --</option>
                                @foreach($allMerchants as $merchant)
                                    <option value="{{ $merchant->id }}">{{ $merchant->nama_merchant }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Row 1.5: Nama Produk -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" id="editProductName" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Nama produk akan otomatis terisi" required> 
                        </div>

                        <!-- Row 2: CTA -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">CTA</label>
                            <input type="url" name="cta_link" id="editCtaLink" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="https://example.com">
                        </div>

                        <!-- Row 3: Redeem Point -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Redeem Point</label>
                            <input type="text" name="redeem" id="editRedeem" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter redeem points">
                        </div>

                        <!-- Row 4: Diskon (Persen + Rupiah) -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Diskon <span class="text-red-500">*</span> (Pilih salah satu)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-2">
                                    <input type="number" name="diskon_percent" id="editDiskonPercent" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="0" min="0" max="100" onchange="validateEditDiskon()">
                                    <span class="text-gray-600 font-medium">%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 font-medium">Rp</span>
                                    <input type="number" name="diskon_rupiah" id="editDiskonRupiah" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="0" onchange="validateEditDiskon()">
                                </div>
                            </div>
                            <p id="editDiskonError" class="text-red-500 text-xs mt-1 hidden">Silakan isi salah satu dari diskon (persen atau rupiah)</p>
                        </div>

                        <!-- Row 5: Stock -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Stock</label>
                            <input type="number" name="stock" id="editStock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter stock">
                        </div>

                        <!-- Row 6: SKB -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">SKB</label>
                            <textarea name="skb" id="editSkb" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none" placeholder="Enter SKB"></textarea>
                        </div>

                        <!-- Row 7: Start Date | End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Start Date</label>
                            <input type="date" id="editStartDate" name="start_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" onchange="validateEditDateRange()">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">End Date</label>
                            <input type="date" id="editEndDate" name="end_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" onchange="validateEditDateRange()">
                            <p id="editDateError" class="text-red-500 text-xs mt-1 hidden">Tanggal mulai tidak boleh melebihi tanggal berakhir</p>
                        </div>
                        
                        <!-- Row 8: Images -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Images</label>
                            <div class="relative">
                                <input type="file" id="editKeywordImagesInput" name="image" accept="image/*" class="hidden" onchange="previewEditKeywordImages(this)">
                                <button type="button" onclick="document.getElementById('editKeywordImagesInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editKeywordImagesText" class="text-[15px]">Click to upload image</span>
                                </button>
                                <div id="editKeywordImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                                <div id="editKeywordCurrentImage" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-4 py-3 md:px-6 md:py-4 border-t bg-white rounded-b-xl">
                <button type="button" onclick="closeEditKeyword()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">Update</button>
            </div>
        </form>
    </div>
</div>

@include('partials.edit-modal-validation')

<script>
let currentEditKeywordId = null;

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
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function openEditKeyword(id, keywordData) {
    currentEditKeywordId = id;
    const modal = document.getElementById('editModalKeyword');
    const modalContent = modal.querySelector('.relative');
    const form = document.getElementById('formEditKeyword');
    const redirectInput = document.getElementById('keywordRedirectEdit');
    const stayFlagInput = document.getElementById('keywordStayOnDetailEdit');
    
    // Set form action
    form.action = `/keywords/${id}`;
    
    // Set redirect URL - prioritize detail page, then keyword tab, then default
    if (redirectInput && window.detailRedirectUrl) {
        // Jika ada detailRedirectUrl, berarti dari halaman merchant detail
        redirectInput.value = window.detailRedirectUrl;
        if (stayFlagInput) {
            stayFlagInput.value = '1';
        }
    } else if (redirectInput) {
        // Cek apakah kita sedang di halaman keyword (tab keyword)
        const currentUrl = new URL(window.location.href);
        const currentTab = currentUrl.searchParams.get('tab');
        
        if (currentTab === 'keyword') {
            // Jika sedang di tab keyword, set redirect ke halaman yang sama dengan tab=keyword
            const baseUrl = currentUrl.pathname;
            redirectInput.value = `${baseUrl}?tab=keyword`;
        } else {
            // Default: redirect ke halaman admin dengan tab keyword
            redirectInput.value = window.location.pathname + '?tab=keyword';
        }
        if (stayFlagInput) {
            stayFlagInput.value = '';
        }
    }
    
    // Populate form fields
    document.getElementById('editMerchantSelect').value = keywordData.merchant_key;
    document.getElementById('editProductName').value = keywordData.nama_produk;
    document.getElementById('editCtaLink').value = keywordData.cta_link || '';
    document.getElementById('editRedeem').value = keywordData.redeem || '';
    document.getElementById('editStock').value = keywordData.stock || '';
    document.getElementById('editSkb').value = keywordData.skb || '';
    document.getElementById('editStartDate').value = keywordData.start_date || '';
    document.getElementById('editEndDate').value = keywordData.end_date || '';
    
    // Parse diskon
    const diskonStr = keywordData.diskon || '';
    if (diskonStr.includes('%')) {
        const percent = diskonStr.replace('%', '').trim();
        document.getElementById('editDiskonPercent').value = percent;
        document.getElementById('editDiskonRupiah').value = '';
    } else if (diskonStr.includes('Rp')) {
        const rupiah = diskonStr.replace('Rp', '').replace(/\./g, '').replace(/,/g, '').trim();
        document.getElementById('editDiskonRupiah').value = rupiah;
        document.getElementById('editDiskonPercent').value = '';
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
    
    // Show modal with animation
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    const formElements = modalContent.querySelectorAll('input, select, textarea, button, label, p, h3');
    formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
    const backdrop = modal.querySelector('.fixed');
    backdrop.style.opacity = '0';
    
    setTimeout(() => {
        backdrop.style.opacity = '0.5';
        formElements.forEach((el, index) => {
            setTimeout(() => {
                el.style.transform = 'translateY(0)';
                el.style.opacity = '1';
            }, index * 30);
        });
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeEditKeyword() {
    const modal = document.getElementById('editModalKeyword');
    const modalContent = modal.querySelector('.relative');
    const backdrop = modal.querySelector('.fixed');
    const formElements = modalContent.querySelectorAll('input, select, textarea, button, label, p, h3');
    
    backdrop.style.opacity = '0';
    formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
    if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        const form = document.getElementById('formEditKeyword');
        if (form) form.reset();
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}

function updateEditProductName() {
    const merchantSelect = document.getElementById('editMerchantSelect');
    const productNameInput = document.getElementById('editProductName');
    const selectedOption = merchantSelect.options[merchantSelect.selectedIndex];
    
    if (selectedOption.value) {
        productNameInput.value = selectedOption.text;
    } else {
        productNameInput.value = '';
    }
}

function validateEditDiskon() {
    const diskonPercent = document.getElementById('editDiskonPercent').value;
    const diskonRupiah = document.getElementById('editDiskonRupiah').value;
    const errorMsg = document.getElementById('editDiskonError');
    
    if (!diskonPercent && !diskonRupiah) {
        errorMsg.classList.remove('hidden');
        return false;
    } else {
        errorMsg.classList.add('hidden');
        return true;
    }
}

function validateEditDateRange() {
    const startDate = document.getElementById('editStartDate').value;
    const endDate = document.getElementById('editEndDate').value;
    const errorMsg = document.getElementById('editDateError');
    
    if (startDate && endDate && startDate > endDate) {
        errorMsg.classList.remove('hidden');
        return false;
    } else {
        errorMsg.classList.add('hidden');
        return true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditKeyword');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi diskon sebelum submit
            if (!validateEditDiskon()) {
                return false;
            }
            
            // Validasi date range sebelum submit
            if (!validateEditDateRange()) {
                return false;
            }
            
            // Kumpulkan data untuk ditampilkan di modal verifikasi (opsional)
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
                // Fallback jika modal verifikasi belum tersedia
                form.submit();
            }
        });
    }
    const modal = document.getElementById('editModalKeyword');
    if (modal) { modal.addEventListener('click', function(event) { if (event.target === this) closeEditKeyword(); }); }
});
</script>
