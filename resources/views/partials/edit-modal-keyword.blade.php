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
                                    <option value="{{ $merchant->id }}" data-name="{{ $merchant->nama_merchant }}" data-kategori="{{ $merchant->kategori ?? '' }}">{{ $merchant->nama_merchant }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Row 1.3: Kategori Keyword -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Kategori Keyword</label>
                            <div class="relative">
                                <input type="hidden" name="kategori_keyword" id="editKeywordKategoriValue">
                                <button
                                    type="button"
                                    id="editKeywordKategoriBtn"
                                    onclick="toggleEditKeywordKategoriDropdown()"
                                    class="w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400"
                                >
                                    <span id="editKeywordKategoriLabel">Kategori akan otomatis terisi dari merchant</span>
                                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                                </button>
                                <div
                                    id="editKeywordKategoriDropdown"
                                    class="hidden absolute left-0 mt-2 bg-white rounded-xl shadow-xl p-2 border border-gray-200 w-full z-50"
                                >
                                    <div class="py-1 text-sm">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('kuliner')">
                                            Kuliner
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('hiburan')">
                                            Hiburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('liburan')">
                                            Liburan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('belanja')">
                                            Belanja
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-pink-100 hover:to-rose-100 hover:text-pink-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('kecantikan')">
                                            Kecantikan
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-100 hover:text-indigo-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('telkomsel')">
                                            Telkomsel Paket
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-amber-100 hover:to-yellow-100 hover:text-amber-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('merchandise')">
                                            Merchandise
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-red-100 hover:to-pink-100 hover:text-red-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('paket_video')">
                                            Paket Video
                                        </button>
                                        <button type="button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gradient-to-r hover:from-violet-100 hover:to-purple-100 hover:text-violet-800 rounded-lg transition-all" onclick="selectEditKeywordKategori('paket_games')">
                                            Paket games
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1.5: Nama Produk -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" id="editProductName" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Nama produk akan otomatis terisi" required> 
                        </div>

                        <!-- Row 1.6: Keyword ID -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Keyword ID</label>
                            <input type="text" name="keyword_id" id="editKeywordId" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter keyword ID">
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

                        <!-- Row 4: Diskon (Persen + Rupiah + Free) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Diskon <span class="text-red-500">*</span> (Pilih salah satu)</label>
                            
                            <!-- Radio buttons untuk memilih jenis diskon -->
                            <div class="flex items-center gap-4 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diskon_type" value="percent" id="editDiskonTypePercent" checked onchange="toggleEditDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Persen (%)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diskon_type" value="rupiah" id="editDiskonTypeRupiah" onchange="toggleEditDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Rupiah (Rp)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="diskon_type" value="free" id="editDiskonTypeFree" onchange="toggleEditDiskonType()" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
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
                                    <input type="text" name="subsidy_amount" id="editSubsidyAmount" class="flex-1 px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm" placeholder="0" inputmode="numeric" oninput="formatEditRupiahInput(this)">
                                </div>
                                <p id="editSubsidyAmountError" class="text-red-500 text-xs mt-1 hidden">Nominal subsidi wajib diisi jika Subsidi Diskon dipilih Yes</p>
                            </div>
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

function openEditKeyword(id, keywordData, canEditFull = true, canEditStock = true) {
    try {
        currentEditKeywordId = id;
        const modal = document.getElementById('editModalKeyword');
        if (!modal) {
            console.error('Edit modal not found');
            return;
        }
        
        const modalContent = modal.querySelector('.relative');
        if (!modalContent) {
            console.error('Modal content not found');
            return;
        }
        
        const form = document.getElementById('formEditKeyword');
        if (!form) {
            console.error('Form not found');
            return;
        }
        
        const redirectInput = document.getElementById('keywordRedirectEdit');
        const stayFlagInput = document.getElementById('keywordStayOnDetailEdit');
        
        // Set form action
        form.action = `/keywords/${id}`;
        
        // Disable field selain stock jika hanya bisa edit stock
        if (!canEditFull && canEditStock) {
            // Disable semua field kecuali stock (editStock)
            const fieldsToDisable = [
                'editMerchantSelect',
                'editKeywordKategoriBtn',
                'editProductName',
                'editKeywordId',
                'editCtaLink',
                'editRedeem',
                'editDiskonTypePercent',
                'editDiskonTypeRupiah',
                'editDiskonTypeFree',
                'editDiskonPercent',
                'editDiskonRupiah',
                'editSubsidyEnabledNo',
                'editSubsidyEnabledYes',
                'editSubsidyAmount',
                'editSkb',
                'editStartDate',
                'editEndDate',
                'editKeywordImagesInput'
            ];
            
            fieldsToDisable.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.disabled = true;
                    field.style.opacity = '0.6';
                    field.style.cursor = 'not-allowed';
                    // Tambahkan class untuk styling
                    field.classList.add('bg-gray-100');
                }
            });
            
            // Disable button upload image
            const uploadButton = document.querySelector('button[onclick*="editKeywordImagesInput"]');
            if (uploadButton) {
                uploadButton.disabled = true;
                uploadButton.style.opacity = '0.6';
                uploadButton.style.cursor = 'not-allowed';
                uploadButton.classList.add('bg-gray-100');
            }
            
            // Pastikan field stock TIDAK di-disable
            const stockField = document.getElementById('editStock');
            if (stockField) {
                stockField.disabled = false;
                stockField.style.opacity = '1';
                stockField.style.cursor = '';
                stockField.classList.remove('bg-gray-100');
            }
            
            // Tampilkan pesan informasi
            const existingInfo = document.getElementById('editKeywordInfoMessage');
            if (existingInfo) {
                existingInfo.remove();
            }
            const infoMessage = document.createElement('div');
            infoMessage.id = 'editKeywordInfoMessage';
            infoMessage.className = 'mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
            infoMessage.innerHTML = '<p class="text-sm text-yellow-800"><i class="fas fa-info-circle mr-2"></i>Anda hanya dapat mengedit stock untuk keyword ini. Field lainnya tidak dapat diubah.</p>';
            const formContent = form.querySelector('.p-4, .p-6');
            if (formContent) {
                formContent.insertBefore(infoMessage, formContent.firstChild);
            }
        } else {
            // Enable semua field jika bisa edit full
            const allFields = form.querySelectorAll('input, select, textarea, button');
            allFields.forEach(field => {
                field.disabled = false;
                field.style.opacity = '1';
                field.style.cursor = '';
                field.classList.remove('bg-gray-100');
            });
            
            // Hapus pesan informasi jika ada
            const infoMessage = document.getElementById('editKeywordInfoMessage');
            if (infoMessage) {
                infoMessage.remove();
            }
        }
        
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
        const editMerchantSelect = document.getElementById('editMerchantSelect');
        const editProductName = document.getElementById('editProductName');
        const editKeywordId = document.getElementById('editKeywordId');
        const editCtaLink = document.getElementById('editCtaLink');
        const editRedeem = document.getElementById('editRedeem');
        const editStock = document.getElementById('editStock');
        const editSkb = document.getElementById('editSkb');
        const editStartDate = document.getElementById('editStartDate');
        const editEndDate = document.getElementById('editEndDate');
        
        if (editMerchantSelect) editMerchantSelect.value = keywordData.merchant_key || '';
        if (editProductName) editProductName.value = keywordData.nama_produk || '';
        if (editKeywordId) editKeywordId.value = keywordData.keyword_id || '';
        if (editCtaLink) editCtaLink.value = keywordData.cta_link || '';
        if (editRedeem) editRedeem.value = keywordData.redeem || '';
        if (editStock) editStock.value = keywordData.stock || '';
        if (editSkb) editSkb.value = keywordData.skb || '';
        if (editStartDate) editStartDate.value = keywordData.start_date || '';
        if (editEndDate) editEndDate.value = keywordData.end_date || '';
        
        // Set kategori_keyword jika ada
        if (keywordData.kategori_keyword) {
            selectEditKeywordKategori(keywordData.kategori_keyword);
        }
    
        // Parse diskon
        const diskonStr = keywordData.diskon || '';
        const diskonPercent = document.getElementById('editDiskonPercent');
        const diskonRupiah = document.getElementById('editDiskonRupiah');
        const diskonTypePercent = document.getElementById('editDiskonTypePercent');
        const diskonTypeRupiah = document.getElementById('editDiskonTypeRupiah');
        const diskonTypeFree = document.getElementById('editDiskonTypeFree');
        
        // Reset semua field diskon
        if (diskonPercent) diskonPercent.value = '';
        if (diskonRupiah) diskonRupiah.value = '';
        
        if (diskonStr.toLowerCase().includes('free') || diskonStr.toLowerCase() === 'gratis') {
            if (diskonTypeFree) diskonTypeFree.checked = true;
            toggleEditDiskonType();
        } else if (diskonStr.includes('%')) {
            const percent = diskonStr.replace('%', '').trim();
            if (diskonPercent) diskonPercent.value = percent;
            if (diskonTypePercent) diskonTypePercent.checked = true;
            toggleEditDiskonType();
        } else if (diskonStr.includes('Rp')) {
            const rupiah = diskonStr.replace('Rp', '').replace(/\./g, '').replace(/,/g, '').trim();
            if (diskonRupiah) diskonRupiah.value = rupiah;
            if (diskonTypeRupiah) diskonTypeRupiah.checked = true;
            toggleEditDiskonType();
        } else {
            // Default to percent if no valid diskon data
            if (diskonTypePercent) {
                diskonTypePercent.checked = true;
            }
            toggleEditDiskonType();
        }
        
        // Show current image if exists
        const currentImageDiv = document.getElementById('editKeywordCurrentImage');
        if (currentImageDiv) {
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
        }
        
        // Clear file input
        const editKeywordImagesInput = document.getElementById('editKeywordImagesInput');
        const editKeywordImagesPreview = document.getElementById('editKeywordImagesPreview');
        const editKeywordImagesText = document.getElementById('editKeywordImagesText');
        if (editKeywordImagesInput) editKeywordImagesInput.value = '';
        if (editKeywordImagesPreview) {
            editKeywordImagesPreview.innerHTML = '';
            editKeywordImagesPreview.classList.add('hidden');
        }
        if (editKeywordImagesText) editKeywordImagesText.textContent = 'Click to upload image';
        
        // Ensure diskon type is properly displayed
        setTimeout(() => {
            toggleEditDiskonType();
        }, 50);
        
        // Parse and populate subsidy data
        // Determine subsidy enabled based on subsidy_amount (not subsidy_enabled field)
        // If subsidy_amount exists and > 0, then subsidy is enabled
        setTimeout(() => {
            const subsidyAmount = keywordData.subsidy_amount;
            
            // Check if subsidy is enabled: if subsidy_amount is not null, not undefined, not empty string, and > 0
            // Convert to string first to handle various input types, then parse
            let subsidyEnabled = false;
            if (subsidyAmount !== null && subsidyAmount !== undefined && subsidyAmount !== '') {
                // Remove any formatting (dots, commas) and parse
                const cleanValue = subsidyAmount.toString().replace(/[^\d.]/g, '');
                const numericValue = parseFloat(cleanValue);
                subsidyEnabled = !isNaN(numericValue) && numericValue > 0;
            }
            
            if (subsidyEnabled) {
                const subsidyYesRadio = document.getElementById('editSubsidyEnabledYes');
                const subsidyNoRadio = document.getElementById('editSubsidyEnabledNo');
                if (subsidyYesRadio) {
                    subsidyYesRadio.checked = true;
                    if (subsidyNoRadio) {
                        subsidyNoRadio.checked = false;
                    }
                    
                    // Set nilai sebelum toggle agar nilai terlihat saat container muncul
                    const subsidyAmountInput = document.getElementById('editSubsidyAmount');
                    if (subsidyAmountInput) {
                        // Format nilai subsidi dengan titik sebagai pemisah ribuan
                        const cleanValue = subsidyAmount.toString().replace(/[^\d.]/g, '');
                        if (cleanValue) {
                            // Parse as float to handle decimals, then format
                            const floatValue = parseFloat(cleanValue);
                            if (!isNaN(floatValue) && floatValue > 0) {
                                subsidyAmountInput.value = floatValue.toLocaleString('id-ID');
                            } else {
                                subsidyAmountInput.value = '';
                            }
                        } else {
                            subsidyAmountInput.value = '';
                        }
                    }
                    
                    // Toggle untuk menampilkan container
                    toggleEditSubsidyAmount();
                }
            } else {
                const subsidyNoRadio = document.getElementById('editSubsidyEnabledNo');
                const subsidyYesRadio = document.getElementById('editSubsidyEnabledYes');
                if (subsidyNoRadio) {
                    subsidyNoRadio.checked = true;
                    if (subsidyYesRadio) {
                        subsidyYesRadio.checked = false;
                    }
                }
                // Clear subsidy amount input
                const subsidyAmountInput = document.getElementById('editSubsidyAmount');
                if (subsidyAmountInput) {
                    subsidyAmountInput.value = '';
                }
                toggleEditSubsidyAmount();
            }
        }, 60);
    
        // Show modal with animation
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        const formElements = modalContent.querySelectorAll('input, select, textarea, label, p, h3');
        const buttons = modalContent.querySelectorAll('button');
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        buttons.forEach(btn => { btn.style.transform = 'translateY(10px)'; btn.style.opacity = '0'; btn.style.pointerEvents = 'none'; });
        const backdrop = modal.querySelector('.fixed');
        if (backdrop) backdrop.style.opacity = '0';
        
        setTimeout(() => {
            if (backdrop) backdrop.style.opacity = '0.5';
            formElements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.transform = 'translateY(0)';
                    el.style.opacity = '1';
                }, index * 30);
            });
            buttons.forEach((btn, index) => {
                setTimeout(() => {
                    btn.style.transform = 'translateY(0)';
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                }, (formElements.length + index) * 30);
            });
            if (modalContent) {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }
        }, 10);
    } catch (error) {
        console.error('Error opening edit keyword modal:', error);
        alert('Terjadi kesalahan saat membuka form edit. Silakan refresh halaman dan coba lagi.');
    }
}

function closeEditKeyword() {
    const modal = document.getElementById('editModalKeyword');
    const modalContent = modal.querySelector('.relative');
    const backdrop = modal.querySelector('.fixed');
    const formElements = modalContent.querySelectorAll('input, select, textarea, label, p, h3');
    const buttons = modalContent.querySelectorAll('button');
    
    backdrop.style.opacity = '0';
    formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
    buttons.forEach(btn => { btn.style.transform = 'translateY(10px)'; btn.style.opacity = '0'; btn.style.pointerEvents = 'none'; });
    if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        const form = document.getElementById('formEditKeyword');
        if (form) {
            form.reset();
            // Re-enable semua field saat modal ditutup
            const allFields = form.querySelectorAll('input, select, textarea, button');
            allFields.forEach(field => {
                field.disabled = false;
                field.style.opacity = '1';
                field.style.cursor = '';
                field.classList.remove('bg-gray-100');
            });
            
            // Hapus pesan informasi jika ada
            const infoMessage = document.getElementById('editKeywordInfoMessage');
            if (infoMessage) {
                infoMessage.remove();
            }
        }
        
        // Reset diskon type to default (percent)
        const diskonTypePercent = document.getElementById('editDiskonTypePercent');
        if (diskonTypePercent) {
            diskonTypePercent.checked = true;
            toggleEditDiskonType();
        }
        const diskonError = document.getElementById('editDiskonError');
        if (diskonError) {
            diskonError.classList.add('hidden');
        }
        
        // Reset subsidy container
        const subsidyAmountContainer = document.getElementById('editSubsidyAmountContainer');
        if (subsidyAmountContainer) {
            subsidyAmountContainer.classList.add('hidden');
        }
        const subsidyError = document.getElementById('editSubsidyAmountError');
        if (subsidyError) {
            subsidyError.classList.add('hidden');
        }
        const subsidyEnabledNo = document.getElementById('editSubsidyEnabledNo');
        if (subsidyEnabledNo) {
            subsidyEnabledNo.checked = true;
        }
        toggleEditSubsidyAmount();
        
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        buttons.forEach(btn => { btn.style.transform = 'translateY(10px)'; btn.style.opacity = '0'; btn.style.pointerEvents = 'none'; });
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
        // Auto-fill kategori_keyword dari merchant jika ada
        if (selectedOption.dataset.kategori) {
            selectEditKeywordKategori(selectedOption.dataset.kategori);
        }
    } else {
        productNameInput.value = '';
        // Reset kategori dropdown
        const kategoriInput = document.getElementById('editKeywordKategoriValue');
        const kategoriLabel = document.getElementById('editKeywordKategoriLabel');
        const kategoriBtn = document.getElementById('editKeywordKategoriBtn');
        if (kategoriInput) kategoriInput.value = '';
        if (kategoriLabel) kategoriLabel.textContent = 'Kategori akan otomatis terisi dari merchant';
        if (kategoriBtn) {
            kategoriBtn.className = 'w-full flex items-center justify-between px-4 h-12 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-400';
        }
    }
}

// Fungsi untuk toggle jenis diskon
function toggleEditDiskonType() {
    const form = document.getElementById('formEditKeyword');
    const diskonType = form ? form.querySelector('input[name="diskon_type"]:checked') : document.querySelector('input[name="diskon_type"]:checked');
    const percentContainer = document.getElementById('editDiskonPercentContainer');
    const rupiahContainer = document.getElementById('editDiskonRupiahContainer');
    const freeContainer = document.getElementById('editDiskonFreeContainer');
    const diskonPercent = document.getElementById('editDiskonPercent');
    const diskonRupiah = document.getElementById('editDiskonRupiah');
    const errorMsg = document.getElementById('editDiskonError');
    
    if (!diskonType) {
        // Default to percent if no selection
        const defaultPercent = document.getElementById('editDiskonTypePercent');
        if (defaultPercent && percentContainer) {
            defaultPercent.checked = true;
            percentContainer.classList.remove('hidden');
            if (rupiahContainer) rupiahContainer.classList.add('hidden');
            if (freeContainer) freeContainer.classList.add('hidden');
        }
        return;
    }
    
    // Hide all containers first
    if (percentContainer) percentContainer.classList.add('hidden');
    if (rupiahContainer) rupiahContainer.classList.add('hidden');
    if (freeContainer) freeContainer.classList.add('hidden');
    
    // Clear inputs when switching types
    if (diskonType.value === 'percent') {
        if (diskonRupiah) diskonRupiah.value = '';
    } else if (diskonType.value === 'rupiah') {
        if (diskonPercent) diskonPercent.value = '';
    } else if (diskonType.value === 'free') {
        if (diskonPercent) diskonPercent.value = '';
        if (diskonRupiah) diskonRupiah.value = '';
    }
    
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
    const form = document.getElementById('formEditKeyword');
    const diskonType = form ? form.querySelector('input[name="diskon_type"]:checked') : document.querySelector('input[name="diskon_type"]:checked');
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

// Fungsi untuk toggle subsidy amount
function toggleEditSubsidyAmount() {
    const form = document.getElementById('formEditKeyword');
    const subsidyEnabled = form ? form.querySelector('input[name="subsidy_enabled"]:checked') : document.querySelector('input[name="subsidy_enabled"]:checked');
    const subsidyAmountContainer = document.getElementById('editSubsidyAmountContainer');
    const subsidyAmount = document.getElementById('editSubsidyAmount');
    const subsidyError = document.getElementById('editSubsidyAmountError');
    
    if (!subsidyAmountContainer) return;
    
    if (subsidyEnabled && subsidyEnabled.value === '1') {
        subsidyAmountContainer.classList.remove('hidden');
        if (subsidyAmount) {
            subsidyAmount.required = true;
            // Pastikan name attribute ada
            if (!subsidyAmount.hasAttribute('name')) {
                subsidyAmount.setAttribute('name', 'subsidy_amount');
            }
            // Set default value "0" jika kosong
            const currentValue = subsidyAmount.value ? subsidyAmount.value.replace(/[^\d]/g, '') : '';
            if (!currentValue || currentValue === '' || currentValue === '0') {
                subsidyAmount.value = '0';
            } else {
                // Format nilai yang sudah ada dengan pemisah ribuan
                subsidyAmount.value = parseInt(currentValue, 10).toLocaleString('id-ID');
            }
        }
    } else {
        subsidyAmountContainer.classList.add('hidden');
        if (subsidyAmount) {
            subsidyAmount.required = false;
            subsidyAmount.value = '';
            // Hapus name attribute agar tidak dikirim ke server saat "No" dipilih
            subsidyAmount.removeAttribute('name');
        }
        if (subsidyError) subsidyError.classList.add('hidden');
    }
}

// Fungsi untuk format rupiah input
function formatEditRupiahInput(input) {
    // Hapus semua karakter selain angka
    let value = input.value.replace(/[^\d]/g, '');
    
    // Format dengan titik sebagai pemisah ribuan
    if (value) {
        value = parseInt(value, 10).toLocaleString('id-ID');
    }
    
    input.value = value;
}

// Fungsi untuk mendapatkan nilai numerik dari input rupiah yang sudah diformat
function getEditNumericValue(input) {
    return input.value.replace(/[^\d]/g, '');
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize diskon type display
    const diskonTypePercent = document.getElementById('editDiskonTypePercent');
    if (diskonTypePercent) {
        // Ensure percent is checked by default if no other is selected
        const form = document.getElementById('formEditKeyword');
        const checkedType = form ? form.querySelector('input[name="diskon_type"]:checked') : null;
        if (!checkedType && diskonTypePercent) {
            diskonTypePercent.checked = true;
        }
        toggleEditDiskonType();
    }
    
    // Initialize subsidy display
    toggleEditSubsidyAmount();
    
    const form = document.getElementById('formEditKeyword');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi diskon sebelum submit
            if (!validateEditDiskon()) {
                return false;
            }
            
            // Handle free diskon: set diskon_percent = 100 jika free dipilih
            const diskonType = form.querySelector('input[name="diskon_type"]:checked');
            if (diskonType && diskonType.value === 'free') {
                const diskonPercent = document.getElementById('editDiskonPercent');
                const diskonRupiah = document.getElementById('editDiskonRupiah');
                if (diskonPercent) {
                    diskonPercent.value = '100'; // 100% diskon = free
                }
                if (diskonRupiah) {
                    diskonRupiah.value = ''; // Clear rupiah value
                }
            } else {
                // Clear nilai yang tidak dipilih
                if (diskonType && diskonType.value === 'percent') {
                    const diskonRupiah = document.getElementById('editDiskonRupiah');
                    if (diskonRupiah) diskonRupiah.value = '';
                } else if (diskonType && diskonType.value === 'rupiah') {
                    const diskonPercent = document.getElementById('editDiskonPercent');
                    if (diskonPercent) diskonPercent.value = '';
                }
            }
            
            // Validasi date range sebelum submit
            if (!validateEditDateRange()) {
                return false;
            }
            
            // Validasi subsidy amount jika subsidy enabled
            const subsidyEnabled = form.querySelector('input[name="subsidy_enabled"]:checked');
            const subsidyAmountInput = document.getElementById('editSubsidyAmount');
            
            if (subsidyEnabled && subsidyEnabled.value === '1') {
                // Pastikan name attribute ada
                if (subsidyAmountInput && !subsidyAmountInput.hasAttribute('name')) {
                    subsidyAmountInput.setAttribute('name', 'subsidy_amount');
                }
                
                const subsidyAmount = getEditNumericValue(subsidyAmountInput);
                const subsidyError = document.getElementById('editSubsidyAmountError');
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
                // Jika subsidy disabled, pastikan input kosong dan hapus name attribute
                if (subsidyAmountInput) {
                    subsidyAmountInput.value = '';
                    // Hapus name attribute agar tidak dikirim ke server
                    subsidyAmountInput.removeAttribute('name');
                }
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

// ======================
// Dropdown kategori keyword (Edit)
// ======================
function toggleEditKeywordKategoriDropdown() {
    const dropdown = document.getElementById('editKeywordKategoriDropdown');
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

function selectEditKeywordKategori(value) {
    const hiddenInput = document.getElementById('editKeywordKategoriValue');
    const labelSpan = document.getElementById('editKeywordKategoriLabel');
    const btn = document.getElementById('editKeywordKategoriBtn');
    const dropdown = document.getElementById('editKeywordKategoriDropdown');

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
            'merchandise': 'Merchandise',
            'paket_video': 'Paket Video',
            'paket_games': 'Paket games'
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
            'merchandise': ['border-amber-300', 'text-amber-800', 'from-amber-100', 'to-yellow-100'],
            'paket_video': ['border-red-300', 'text-red-800', 'from-red-100', 'to-pink-100'],
            'paket_games': ['border-violet-300', 'text-violet-800', 'from-violet-100', 'to-purple-100']
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

// Close dropdown when clicking outside (Edit)
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('editKeywordKategoriDropdown');
    const button = document.getElementById('editKeywordKategoriBtn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        if (!dropdown.classList.contains('hidden')) {
            toggleEditKeywordKategoriDropdown();
        }
    }
});
</script>
