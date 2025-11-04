<!-- Telkom Package Edit Modal -->
<div id="editModalTelkom" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-6 py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Edit Telkom Package</h3>
            <button type="button" onclick="closeEditTelkom()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Scrollable Form Content -->
        <form id="formEditTelkom" class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 space-y-4">
                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- Row 1: Nama Paket + Redeem Point | SKB (kanan) -->
                        <div class="flex flex-col">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Package</label>
                            <input type="text" id="editTelkomNama" name="nama" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter package name">
                            <div class="mt-4">
                                <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Redeem Point</label>
                                <input type="text" id="editTelkomRedeemPoint" name="redeem_point" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter redeem points">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Deskripsi</label>
                            <textarea id="editTelkomDeskripsi" name="deskripsi" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none" placeholder="Enter description"></textarea>
                        </div>
                        <!-- Row 2: Stock -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Stock</label>
                            <input type="number" id="editTelkomStock" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter stock">
                        </div>
                        <!-- Row 3: CTA -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">CTA</label>
                            <input type="url" id="editTelkomCta" name="cta" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="https://example.com">
                        </div>
                        <!-- Row 4: Start Date | End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Start Date</label>
                            <input type="text" id="editTelkomStartDate" name="start_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">End Date</label>
                            <input type="text" id="editTelkomEndDate" name="end_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <!-- Row 5: Logo | Image -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Logo</label>
                            <div class="relative">
                                <input type="file" id="editLogoTelkomInput" name="logo" accept="image/*" class="hidden" onchange="previewEditLogoTelkom(this)">
                                <button type="button" onclick="document.getElementById('editLogoTelkomInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editLogoTelkomText" class="text-[15px]">Click to change logo</span>
                                </button>
                                <div id="editLogoTelkomPreview" class="mt-3 hidden">
                                    <img src="" alt="Logo Preview" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeEditLogoTelkom()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        <i class="fas fa-times mr-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Image</label>
                            <div class="relative">
                                <input type="file" id="editTelkomImageInput" name="image" accept="image/*" class="hidden" onchange="previewEditTelkomImage(this)">
                                <button type="button" onclick="document.getElementById('editTelkomImageInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editTelkomImageText" class="text-[15px]">Click to change image</span>
                                </button>
                                <div id="editTelkomImagePreview" class="mt-3 hidden">
                                    <img src="" alt="Image Preview" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeEditTelkomImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
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
                <button type="button" onclick="closeEditTelkom()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentEditTelkomId = null;

// Preview functions for edit
function previewEditLogoTelkom(input) {
    const preview = document.getElementById('editLogoTelkomPreview');
    const text = document.getElementById('editLogoTelkomText');
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

function removeEditLogoTelkom() {
    document.getElementById('editLogoTelkomInput').value = '';
    document.getElementById('editLogoTelkomPreview').classList.add('hidden');
    document.getElementById('editLogoTelkomText').textContent = 'Click to change logo';
}

function previewEditTelkomImage(input) {
    const preview = document.getElementById('editTelkomImagePreview');
    const text = document.getElementById('editTelkomImageText');
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

function removeEditTelkomImage() {
    document.getElementById('editTelkomImageInput').value = '';
    document.getElementById('editTelkomImagePreview').classList.add('hidden');
    document.getElementById('editTelkomImageText').textContent = 'Click to change image';
}

function openEditTelkom(id, data) {
    currentEditTelkomId = id;
    const modal = document.getElementById('editModalTelkom');
    if (!modal) return;

    // Pastikan data selalu berupa object agar aman saat akses properti
    data = data || {};

    // Populate form with existing data
    document.getElementById('editTelkomNama').value = data.nama || '';
    document.getElementById('editTelkomDeskripsi').value = data.deskripsi || '';
    document.getElementById('editTelkomRedeemPoint').value = data.redeem_point || '';
    document.getElementById('editTelkomStock').value = data.stock || '';
    document.getElementById('editTelkomStartDate').value = data.start_date || '';
    document.getElementById('editTelkomEndDate').value = data.end_date || '';
    document.getElementById('editTelkomCta').value = data.cta || '';
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0.5'; }, 10);
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(1)'; modalContent.style.opacity = '1'; } }, 50);
    
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea, button.px-3, span:not([class*="fa-"])');
    formElements.forEach((el, index) => { setTimeout(() => { el.style.transform = 'translateY(0)'; el.style.opacity = '1'; }, 100 + (index * 30)); });
}

function closeEditTelkom() {
    const modal = document.getElementById('editModalTelkom');
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
        document.getElementById('formEditTelkom').reset();
        formElements.forEach(el => { el.style.transform = 'translateY(10px)'; el.style.opacity = '0'; });
        if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditTelkom');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) { data[key] = value; }
            data.id = currentEditTelkomId;
            console.log('Telkom Package Update:', data);
            // Add your update logic here
            closeEditTelkom();
        });
    }
    
    const modal = document.getElementById('editModalTelkom');
    if (modal) { modal.addEventListener('click', function(event) { if (event.target === this) closeEditTelkom(); }); }
});
</script>
