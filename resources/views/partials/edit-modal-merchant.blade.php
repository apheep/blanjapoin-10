<!-- Merchant Edit Modal -->
<div id="editModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Edit Merchant Data</h3>
            <button type="button" onclick="closeEditMerchant()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Scrollable Form Content -->
        <form id="formEditMerchant" class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 space-y-4">
                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- Row 1: Nama Merchant + Kategori | SKB (kanan) -->
                        <div class="flex flex-col">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Merchant</label>
                            <input type="text" id="editMerchantNama" name="nama" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter merchant name"> 
                            <div class="mt-4">
                                <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Kategori</label>
                                <div class="relative">
                                    <button type="button" id="categoryDropdownBtnEdit" onclick="toggleCategoryDropdownEdit()" class="w-full px-4 py-2.5 text-left bg-white border-2 border-gray-300 rounded-xl hover:border-orange-400 focus:outline-none focus:border-orange-500 transition-all duration-300 flex items-center justify-between group">
                                        <span id="categorySelectedEdit" class="text-gray-700">Pilih Kategori</span>
                                        <i class="fas fa-chevron-down text-gray-400 group-hover:text-orange-500 transition-transform duration-300" id="categoryIconEdit"></i>
                                    </button>
                                    <input type="hidden" id="kategoriInputEdit" name="kategori" value="">
                                    <div id="categoryDropdownEdit" class="hidden absolute z-10 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
                                        <div class="py-2 max-h-64 overflow-y-auto">
                                            <button type="button" onclick="selectCategoryEdit('F&B', 'F&B')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-700 transition-all duration-200"><span>F&B</span></button>
                                            <button type="button" onclick="selectCategoryEdit('Entertain', 'Entertain')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 hover:text-purple-700 transition-all duration-200"><span>Entertain</span></button>
                                            <button type="button" onclick="selectCategoryEdit('Vacation', 'Vacation')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 hover:text-blue-700 transition-all duration-200"><span>Vacation</span></button>
                                            <button type="button" onclick="selectCategoryEdit('Shopping', 'Shopping')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-700 transition-all duration-200"><span>Shopping</span></button>
                                            <button type="button" onclick="selectCategoryEdit('Beauty & Care', 'Beauty & Care')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 hover:text-pink-700 transition-all duration-200"><span>Beauty & Care</span></button>
                                            <button type="button" onclick="selectCategoryEdit('Telkomsel Packet', 'Telkomsel Packet')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 hover:text-indigo-700 transition-all duration-200"><span>Telkomsel Packet</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">SKB</label>
                            <textarea id="editMerchantSkb" name="skb" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none" placeholder="Enter SKB"></textarea>
                        </div>
                        <!-- Row 2: Diskon -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Diskon</label>
                            <div class="relative">
                                <input type="number" id="editMerchantDiskon" name="diskon" min="0" max="100" class="w-full px-4 h-12 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="0">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"><span class="text-gray-500 text-sm">%</span></div>
                            </div>
                        </div>
                        <!-- Row 3: CTA -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">CTA</label>
                            <input type="url" id="editMerchantCta" name="cta" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="https://example.com">
                        </div>
                        <!-- Row 4: Start Date | End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Start Date</label>
                            <input type="text" id="editMerchantStartDate" name="start_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">End Date</label>
                            <input type="text" id="editMerchantEndDate" name="end_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <!-- Row 5: Stock | Redeem Point -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Stock</label>
                            <input type="number" id="editMerchantStock" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter stock">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Redeem Point</label>
                            <input type="text" id="editMerchantRedeemPoint" name="redeem_point" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter redeem points">
                        </div>
                        <!-- Row 6: Logo Merchant | Images -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Logo Merchant</label>
                            <div class="relative">
                                <input type="file" id="editLogoMerchantInput" name="logo_merchant" accept="image/*" class="hidden" onchange="previewEditLogoMerchant(this)">
                                <button type="button" onclick="document.getElementById('editLogoMerchantInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editLogoMerchantText" class="text-[15px]">Click to change logo</span>
                                </button>
                                <div id="editLogoMerchantPreview" class="mt-3 hidden">
                                    <img src="" alt="Logo Preview" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeEditLogoMerchant()" class="mt-2 text-sm text-red-600 hover:text-red-800"><i class="fas fa-times mr-1"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Images</label>
                            <div class="relative">
                                <input type="file" id="editMerchantImagesInput" name="images[]" accept="image/*" multiple class="hidden" onchange="previewEditMerchantImages(this)">
                                <button type="button" onclick="document.getElementById('editMerchantImagesInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editMerchantImagesText" class="text-[15px]">Click to change images</span>
                                </button>
                                <div id="editMerchantImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sticky Footer -->
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-6 py-4 border-t bg-white rounded-b-xl">
                <button type="button" onclick="closeEditMerchant()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">Update</button>
            </div>
        </form>
    </div>
</div>

@include('partials.edit-verification-modal')

<script>
let currentEditMerchantId = null;

// Category dropdown functions for edit
function toggleCategoryDropdownEdit() {
    const dropdown = document.getElementById('categoryDropdownEdit');
    const icon = document.getElementById('categoryIconEdit');
    if (dropdown) dropdown.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}

function selectCategoryEdit(value, label) {
    document.getElementById('kategoriInputEdit').value = value;
    document.getElementById('categorySelectedEdit').textContent = label;
    const button = document.getElementById('categoryDropdownBtnEdit');
    if (value && button) {
        button.classList.add('border-orange-400', 'bg-orange-50');
        document.getElementById('categorySelectedEdit').classList.add('text-orange-700', 'font-medium');
    }
    toggleCategoryDropdownEdit();
}

// Preview functions for edit
function previewEditLogoMerchant(input) {
    const preview = document.getElementById('editLogoMerchantPreview');
    const text = document.getElementById('editLogoMerchantText');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            if (text) text.textContent = input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeEditLogoMerchant() {
    document.getElementById('editLogoMerchantInput').value = '';
    document.getElementById('editLogoMerchantPreview').classList.add('hidden');
    document.getElementById('editLogoMerchantText').textContent = 'Click to change logo';
}

function previewEditMerchantImages(input) {
    const preview = document.getElementById('editMerchantImagesPreview');
    const text = document.getElementById('editMerchantImagesText');
    preview.innerHTML = '';
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        if (text) text.textContent = `${input.files.length} file(s) selected`;
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg"><button type="button" onclick="removeEditMerchantImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"><i class="fas fa-times text-xs"></i></button>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeEditMerchantImage(index) {
    const input = document.getElementById('editMerchantImagesInput');
    const dt = new DataTransfer();
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) dt.items.add(input.files[i]);
    }
    input.files = dt.files;
    previewEditMerchantImages(input);
    if (input.files.length === 0) {
        document.getElementById('editMerchantImagesPreview').classList.add('hidden');
        document.getElementById('editMerchantImagesText').textContent = 'Click to change images';
    }
}

function openEditMerchant(id, data) {
    currentEditMerchantId = id;
    const modal = document.getElementById('editModalMerchant');
    if (!modal) return;
    
    // Populate form with existing data
    document.getElementById('editMerchantNama').value = data.nama || '';
    document.getElementById('editMerchantDiskon').value = data.diskon || '';
    document.getElementById('editMerchantSkb').value = data.skb || '';
    document.getElementById('editMerchantRedeemPoint').value = data.redeem_point || '';
    document.getElementById('editMerchantStock').value = data.stock || '';
    document.getElementById('editMerchantStartDate').value = data.start_date || '';
    document.getElementById('editMerchantEndDate').value = data.end_date || '';
    document.getElementById('editMerchantCta').value = data.cta || '';
    
    if (data.kategori) {
        selectCategoryEdit(data.kategori, data.kategori);
    }
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0.5'; }, 10);
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(1)'; modalContent.style.opacity = '1'; } }, 50);
    
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea, button.px-3, span:not([class*="fa-"])');
    formElements.forEach((el, index) => { setTimeout(() => { el.style.transform = 'translateY(0)'; el.style.opacity = '1'; }, 100 + (index * 30)); });
}

function closeEditMerchant() {
    const modal = document.getElementById('editModalMerchant');
    if (!modal) return;
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea, button.px-3, span:not([class*="fa-"])');
    
    formElements.forEach((el, index) => { setTimeout(() => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; }, index * 20); });
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; } }, 100);
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0'; }, 150);
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('formEditMerchant').reset();
        document.getElementById('categorySelectedEdit').textContent = 'Pilih Kategori';
        document.getElementById('kategoriInputEdit').value = '';
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditMerchant');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) { data[key] = value; }
            data.id = currentEditMerchantId;
            if (typeof showEditVerification === 'function') { showEditVerification(data, 'Merchant'); }
        });
    }
    
    const modal = document.getElementById('editModalMerchant');
    if (modal) { modal.addEventListener('click', function(event) { if (event.target === this) closeEditMerchant(); }); }
});
</script>
