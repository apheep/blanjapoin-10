<!-- Merchant Upload Modal -->
<div id="uploadModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 py-3 md:px-6 md:py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Upload Merchant Data</h3>
            <button type="button" onclick="closeUploadMerchant()" class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Scrollable Form Content -->
        <form id="formUploadMerchant" class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 space-y-4">
                <div class="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- Row 1: Nama Merchant + Kategori | SKB (kanan) -->
                        <div class="flex flex-col">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Nama Merchant</label>
                            <input type="text" name="nama" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter merchant name"> 
                            <div class="mt-4">
                                <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Kategori</label>
                                <!-- Kategori dropdown & input field as is -->
                                <div class="relative">
                                    <button type="button" id="categoryDropdownBtn" onclick="toggleCategoryDropdown()" class="w-full px-4 py-2.5 text-left bg-white border-2 border-gray-300 rounded-xl hover:border-orange-400 focus:outline-none focus:border-orange-500 transition-all duration-300 flex items-center justify-between group">
                                        <span id="categorySelected" class="text-gray-700">Pilih Kategori</span>
                                        <i class="fas fa-chevron-down text-gray-400 group-hover:text-orange-500 transition-transform duration-300" id="categoryIcon"></i>
                                    </button>
                                    <input type="hidden" id="kategoriInput" name="kategori" value="">
                                    <div id="categoryDropdown" class="hidden absolute z-10 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
                                        <div class="py-2 max-h-64 overflow-y-auto">
                                            <button type="button" onclick="selectCategory('F&B', 'F&B')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-700 transition-all duration-200"><span>F&B</span></button>
                                            <button type="button" onclick="selectCategory('Entertain', 'Entertain')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 hover:text-purple-700 transition-all duration-200"><span>Entertain</span></button>
                                            <button type="button" onclick="selectCategory('Vacation', 'Vacation')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 hover:text-blue-700 transition-all duration-200"><span>Vacation</span></button>
                                            <button type="button" onclick="selectCategory('Shopping', 'Shopping')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-700 transition-all duration-200"><span>Shopping</span></button>
                                            <button type="button" onclick="selectCategory('Beauty & Care', 'Beauty & Care')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 hover:text-pink-700 transition-all duration-200"><span>Beauty & Care</span></button>
                                            <button type="button" onclick="selectCategory('Telkomsel Packet', 'Telkomsel Packet')" class="w-full px-4 py-2.5 text-left text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 hover:text-indigo-700 transition-all duration-200"><span>Telkomsel Packet</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">SKB</label>
                            <textarea name="skb" rows="5" class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none" placeholder="Enter SKB"></textarea>
                        </div>
                        <!-- Row 2: Diskon -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Diskon</label>
                            <div class="relative">
                                <input type="number" name="diskon" min="0" max="100" class="w-full px-4 h-12 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="0">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"><span class="text-gray-500 text-sm">%</span></div>
                            </div>
                        </div>
                        <!-- Row 3: CTA -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">CTA</label>
                            <input type="url" name="cta" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="https://example.com">
                        </div>
                        <!-- Row 4: Start Date | End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Start Date</label>
                            <input type="text" name="start_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">End Date</label>
                            <input type="text" name="end_date" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" maxlength="10" placeholder="DD/MM/YYYY" onkeyup="formatDateInput(this)" onkeypress="return isNumberKey(event)">
                        </div>
                        <!-- Row 5: Stock | Redeem Point (kanan) -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Stock</label>
                            <input type="number" name="stock" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter stock">
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Redeem Point</label>
                            <input type="text" name="redeem_point" class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0" placeholder="Enter redeem points">
                        </div>
                        <!-- Row 6:  Logo Merchant | Images -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Logo Merchant</label>
                            <div class="relative">
                                <input type="file" id="logoMerchantInput" name="logo_merchant" accept="image/*" class="hidden" onchange="previewLogoMerchant(this)">
                                <button type="button" onclick="document.getElementById('logoMerchantInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="logoMerchantText" class="text-[15px]">Click to upload logo</span>
                                </button>
                                <div id="logoMerchantPreview" class="mt-3 hidden">
                                    <img src="" alt="Logo Preview" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeLogoMerchant()" class="mt-2 text-sm text-red-600 hover:text-red-800"><i class="fas fa-times mr-1"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Images</label>
                            <div class="relative">
                                <input type="file" id="merchantImagesInput" name="images[]" accept="image/*" multiple class="hidden" onchange="previewMerchantImages(this)">
                                <button type="button" onclick="document.getElementById('merchantImagesInput').click()" class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="merchantImagesText" class="text-[15px]">Click to upload images</span>
                                </button>
                                <div id="merchantImagesPreview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sticky Footer -->
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-4 py-3 md:px-6 md:py-4 border-t bg-white rounded-b-xl">
                <button type="button" onclick="closeUploadMerchant()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0">Upload</button>
            </div>
        </form>
    </div>
</div>

@include('partials.upload-verification-modal')

<script>
// Category dropdown functions
function toggleCategoryDropdown() {
    const dropdown = document.getElementById('categoryDropdown');
    const icon = document.getElementById('categoryIcon');
    
    if (dropdown) dropdown.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}

function selectCategory(value, label) {
    const kategoriInput = document.getElementById('kategoriInput');
    const categorySelected = document.getElementById('categorySelected');
    
    if (kategoriInput) kategoriInput.value = value;
    if (categorySelected) categorySelected.textContent = label;
    
    // Update button styling based on selection
    const button = document.getElementById('categoryDropdownBtn');
    
    if (value && button) {
        button.classList.add('border-orange-400', 'bg-orange-50');
        if (categorySelected) {
            categorySelected.classList.add('text-orange-700', 'font-medium');
        }
    } else if (button) {
        button.classList.remove('border-orange-400', 'bg-orange-50');
        if (categorySelected) {
            categorySelected.classList.remove('text-orange-700', 'font-medium');
        }
    }
    
    toggleCategoryDropdown();
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('categoryDropdown');
    const button = document.getElementById('categoryDropdownBtn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.add('hidden');
        const icon = document.getElementById('categoryIcon');
        if (icon) icon.classList.remove('rotate-180');
    }
});

// Date formatting functions
function isNumberKey(evt) {
    const charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 47)
        return false;
    return true;
}

function formatDateInput(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    if (value.length >= 5) {
        value = value.substring(0, 5) + '/' + value.substring(5, 9);
    }
    input.value = value;
}

// Logo preview functions
function previewLogoMerchant(input) {
    const preview = document.getElementById('logoMerchantPreview');
    const text = document.getElementById('logoMerchantText');
    
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

function removeLogoMerchant() {
    const input = document.getElementById('logoMerchantInput');
    const preview = document.getElementById('logoMerchantPreview');
    const text = document.getElementById('logoMerchantText');
    
    input.value = '';
    preview.classList.add('hidden');
    if (text) text.textContent = 'Click to upload logo';
}

// Images preview functions
function previewMerchantImages(input) {
    const preview = document.getElementById('merchantImagesPreview');
    const text = document.getElementById('merchantImagesText');
    
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        if (text) text.textContent = `${input.files.length} file(s) selected`;
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" onclick="removeMerchantImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeMerchantImage(index) {
    const input = document.getElementById('merchantImagesInput');
    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) dt.items.add(files[i]);
    }
    
    input.files = dt.files;
    previewMerchantImages(input);
    
    if (input.files.length === 0) {
        document.getElementById('merchantImagesPreview').classList.add('hidden');
        document.getElementById('merchantImagesText').textContent = 'Click to upload images';
    }
}

function openUploadMerchant() {
    const modal = document.getElementById('uploadModalMerchant');
    if (!modal) return;
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate backdrop
    setTimeout(() => {
        if (backdrop) backdrop.style.opacity = '0.5';
    }, 10);
    
    // Animate modal content
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 50);
    
    // Animate form elements with staggered delays
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea');
    formElements.forEach((el, index) => {
        setTimeout(() => {
            el.style.transform = 'translateY(0)';
            el.style.opacity = '1';
        }, 100 + (index * 30));
    });
}

function closeUploadMerchant() {
    const modal = document.getElementById('uploadModalMerchant');
    if (!modal) return;
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    
    // Animate form elements out
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea');
    formElements.forEach((el, index) => {
        setTimeout(() => {
            el.style.transform = 'translateY(10px)';
            el.style.opacity = '0';
        }, index * 20);
    });
    
    // Animate modal content
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    }, 100);
    
    // Animate backdrop
    setTimeout(() => {
        if (backdrop) backdrop.style.opacity = '0';
    }, 150);
    
    // Hide modal completely after animations
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Reset form
        const form = document.getElementById('formUploadMerchant');
        if (form) form.reset();
        
        // Reset category selection
        const categorySelected = document.getElementById('categorySelected');
        if (categorySelected) categorySelected.textContent = 'Pilih Kategori';
        const kategoriInput = document.getElementById('kategoriInput');
        if (kategoriInput) kategoriInput.value = '';
        const button = document.getElementById('categoryDropdownBtn');
        if (button) {
            button.classList.remove('border-orange-400', 'bg-orange-50');
        }
        
        // Reset form element positions
        formElements.forEach(el => {
            el.style.transform = 'translateY(10px)';
            el.style.opacity = '0';
        });
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
        if (backdrop) backdrop.style.opacity = '0';
    }, 400);
}

// Form submit handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formUploadMerchant');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(form);
            const data = {};
            
            // Convert FormData to object
            for (const [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            // Show verification modal
            if (typeof showUploadVerification === 'function') {
                showUploadVerification(data, 'Merchant');
            }
        });
    }
    
    // Close modal when clicking outside
    const modal = document.getElementById('uploadModalMerchant');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeUploadMerchant();
            }
        });
    }
});
</script>
