<!-- Merchandise Edit Modal -->
<div id="editModalMerchandise" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Edit Merchandise</h3>
            <button type="button" onclick="closeEditMerchandise()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Scrollable Form Content -->
        <form id="formEditMerchandise" class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 space-y-4">
                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- Row 1: Nama Merchandise + Redeem Point | SKB (kanan) -->
                        <div class="flex flex-col">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Merchandise</label>
                            <input type="text" id="editMerchandiseNama" name="nama" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter merchandise name">
                            <div class="mt-4">
                                <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Redeem Point</label>
                                <input type="text" id="editMerchandiseRedeemPoint" name="redeem_point" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter redeem points">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">SKB</label>
                            <textarea id="editMerchandiseDeskripsi" name="deskripsi" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none" placeholder="Enter SKB"></textarea>
                        </div>
                        <!-- Row 2: Stock -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Stock</label>
                            <input type="number" id="editMerchandiseStock" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter stock">
                        </div>
                        <!-- Row 3: CTA -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">CTA</label>
                            <input type="url" id="editMerchandiseCta" name="cta" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="https://example.com">
                        </div>
                        <!-- Row 4: Start Date | End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Start Date</label>
                            <input type="text" id="editMerchandiseStartDate" name="start_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">End Date</label>
                            <input type="text" id="editMerchandiseEndDate" name="end_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <!-- Row 5: Images | Gambar Merchandise -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Images</label>
                            <div class="relative">
                                <input type="file" id="editMerchandiseImagesInput" name="images[]" accept="image/*" multiple class="hidden" onchange="previewEditMerchandiseImages(this)">
                                <button type="button" onclick="document.getElementById('editMerchandiseImagesInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editMerchandiseImagesText" class="text-[15px]">Click to upload images</span>
                                </button>
                                <div id="editMerchandiseImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Gambar Merchandise</label>
                            <div class="relative">
                                <input type="file" id="editMerchandiseImageInput" name="image" accept="image/*" class="hidden" onchange="previewEditMerchandiseImage(this)">
                                <button type="button" onclick="document.getElementById('editMerchandiseImageInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editMerchandiseImageText" class="text-[15px]">Click to change image</span>
                                </button>
                                <div id="editMerchandiseImagePreview" class="mt-3 hidden">
                                    <img src="" alt="Image Preview" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeEditMerchandiseImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        <i class="fas fa-times mr-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sticky Footer -->
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-6 py-4 border-t bg-white rounded-b-xl">
                <button type="button" onclick="closeEditMerchandise()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentEditMerchandiseId = null;

// Category dropdown functions for edit
function toggleCategoryDropdownEditMerchandise() {
    const dropdown = document.getElementById('categoryDropdownEditMerchandise');
    const icon = document.getElementById('categoryIconEditMerchandise');
    if (dropdown) dropdown.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}

function selectCategoryEditMerchandise(value, label) {
    document.getElementById('kategoriInputEditMerchandise').value = value;
    document.getElementById('categorySelectedEditMerchandise').textContent = label;
    const button = document.getElementById('categoryDropdownBtnEditMerchandise');
    if (value && button) {
        button.classList.add('border-orange-400', 'bg-orange-50');
        document.getElementById('categorySelectedEditMerchandise').classList.add('text-orange-700', 'font-medium');
    }
    toggleCategoryDropdownEditMerchandise();
}

// Preview functions for edit
function previewEditMerchandiseImage(input) {
    const preview = document.getElementById('editMerchandiseImagePreview');
    const text = document.getElementById('editMerchandiseImageText');
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

function removeEditMerchandiseImage() {
    document.getElementById('editMerchandiseImageInput').value = '';
    document.getElementById('editMerchandiseImagePreview').classList.add('hidden');
    document.getElementById('editMerchandiseImageText').textContent = 'Click to change image';
}

function previewEditMerchandiseImages(input) {
    const preview = document.getElementById('editMerchandiseImagesPreview');
    const text = document.getElementById('editMerchandiseImagesText');
    preview.innerHTML = '';
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        if (text) text.textContent = `${input.files.length} file(s) selected`;
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg"><button type=\"button\" onclick=\"removeEditMerchandiseImageAt(${index})\" class=\"absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600\"><i class=\"fas fa-times text-xs\"></i></button>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeEditMerchandiseImageAt(index) {
    const input = document.getElementById('editMerchandiseImagesInput');
    const dt = new DataTransfer();
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) dt.items.add(input.files[i]);
    }
    input.files = dt.files;
    previewEditMerchandiseImages(input);
    if (input.files.length === 0) {
        document.getElementById('editMerchandiseImagesPreview').classList.add('hidden');
        document.getElementById('editMerchandiseImagesText').textContent = 'Click to upload images';
    }
}

function openEditMerchandise(id, data) {
    currentEditMerchandiseId = id;
    const modal = document.getElementById('editModalMerchandise');
    if (!modal) return;
    
    // Populate form with existing data
    document.getElementById('editMerchandiseNama').value = data.nama || '';
    document.getElementById('editMerchandiseDeskripsi').value = data.deskripsi || '';
    document.getElementById('editMerchandiseRedeemPoint').value = data.redeem_point || '';
    document.getElementById('editMerchandiseStock').value = data.stock || '';
    document.getElementById('editMerchandiseStartDate').value = data.start_date || '';
    document.getElementById('editMerchandiseEndDate').value = data.end_date || '';
    document.getElementById('editMerchandiseCta').value = data.cta || '';
    
    if (data.kategori) {
        selectCategoryEditMerchandise(data.kategori, data.kategori);
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

function closeEditMerchandise() {
    const modal = document.getElementById('editModalMerchandise');
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
        document.getElementById('formEditMerchandise').reset();
        document.getElementById('categorySelectedEditMerchandise').textContent = 'Pilih Kategori';
        document.getElementById('kategoriInputEditMerchandise').value = '';
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditMerchandise');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) { data[key] = value; }
            data.id = currentEditMerchandiseId;
            console.log('Merchandise Update:', data);
            // Add your update logic here
            closeEditMerchandise();
        });
    }
    
    const modal = document.getElementById('editModalMerchandise');
    if (modal) { modal.addEventListener('click', function(event) { if (event.target === this) closeEditMerchandise(); }); }
});
</script>
