<!-- Merchant Edit Modal -->
<div id="editModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 py-3 md:px-6 md:py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                Edit Product Data
            </h3>
            <button
                type="button"
                onclick="closeEditMerchant()"
                class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0"
            >
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Scrollable Form Content -->
        <form id="formEditMerchant" class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 space-y-4">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- 1. Nama Merchant -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Nama Merchant
                            </label>
                            <input
                                type="text"
                                id="editMerchantNama"
                                name="nama"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                placeholder="Enter merchant name"
                            >
                        </div>

                        <!-- 2. CTA -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                CTA
                            </label>
                            <input
                                type="url"
                                id="editMerchantCta"
                                name="cta"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                placeholder="https://example.com"
                            >
                        </div>

                        <!-- 3. Redeem Point -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Redeem Point
                            </label>
                            <input
                                type="text"
                                id="editMerchantRedeemPoint"
                                name="redeem_point"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                placeholder="Enter redeem points"
                            >
                        </div>

                        <!-- 4. Diskon (Persen & Rupiah) -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Diskon
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Diskon Persen -->
                                <div>
                                    <div class="relative">
                                        <input
                                            type="number"
                                            id="editMerchantDiskon"
                                            name="diskon"
                                            min="0"
                                            max="100"
                                            class="w-full px-4 h-12 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                            placeholder="0"
                                        >
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 text-sm">%</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Diskon Rupiah -->
                                <div>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="editMerchantDiskonRupiah"
                                            name="diskon_rupiah"
                                            inputmode="numeric"
                                            class="w-full h-12 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                            placeholder="0"
                                            oninput="formatRupiahInput(this)"
                                        >
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <span class="text-gray-500 text-sm">Rp</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Stock -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Stock
                            </label>
                            <input
                                type="number"
                                id="editMerchantStock"
                                name="stock"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                placeholder="Enter stock"
                            >
                        </div>

                        <!-- 6. SKB -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                SKB
                            </label>
                            <textarea
                                id="editMerchantSkb"
                                name="skb"
                                rows="5"
                                class="w-full px-4 pt-3 h-[140px] border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0 resize-none"
                                placeholder="Enter SKB"
                            ></textarea>
                        </div>

                        <!-- 7. Start Date & End Date -->
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Start Date
                            </label>
                            <input
                                type="text"
                                id="editMerchantStartDate"
                                name="start_date"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                maxlength="10"
                                placeholder="DD/MM/YYYY"
                                onkeyup="formatDateInput(this)"
                                onkeypress="return isNumberKey(event)"
                            >
                        </div>
                        <div>
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                End Date
                            </label>
                            <input
                                type="text"
                                id="editMerchantEndDate"
                                name="end_date"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                maxlength="10"
                                placeholder="DD/MM/YYYY"
                                onkeyup="formatDateInput(this)"
                                onkeypress="return isNumberKey(event)"
                            >
                        </div>

                        <!-- 8. Images -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Images
                            </label>
                            <div class="relative">
                                <input
                                    type="file"
                                    id="editMerchantImagesInput"
                                    name="images[]"
                                    accept="image/*"
                                    multiple
                                    class="hidden"
                                    onchange="previewEditMerchantImages(this)"
                                >
                                <button
                                    type="button"
                                    onclick="document.getElementById('editMerchantImagesInput').click()"
                                    class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                >
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="editMerchantImagesText" class="text-[15px]">
                                        Click to change images
                                    </span>
                                </button>
                                <div
                                    id="editMerchantImagesPreview"
                                    class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"
                                ></div>
                            </div>
                        </div>

                        <!-- (Opsional) Logo & Kategori sudah TIDAK ditampilkan agar sama seperti modal upload.
                             Fungsi JS-nya tetap ada agar tidak merusak kode lain yang mungkin memanggilnya. -->
                    </div>
                </div>
            </div>
            
            <!-- Sticky Footer -->
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-4 py-3 md:px-6 md:py-4 border-t bg-white rounded-b-xl">
                <button
                    type="button"
                    onclick="closeEditMerchant()"
                    class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                >
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.edit-verification-modal')

<script>
let currentEditMerchantId = null;

/* ==========================
   CATEGORY (MASIH DISIMPAN)
   ========================== */

function toggleCategoryDropdownEdit() {
    const dropdown = document.getElementById('categoryDropdownEdit');
    const icon = document.getElementById('categoryIconEdit');
    if (dropdown) dropdown.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}

function selectCategoryEdit(value, label) {
    const kategoriInput = document.getElementById('kategoriInputEdit');
    const categorySelected = document.getElementById('categorySelectedEdit');
    const button = document.getElementById('categoryDropdownBtnEdit');

    if (kategoriInput) kategoriInput.value = value;
    if (categorySelected) categorySelected.textContent = label;

    if (value && button) {
        button.classList.add('border-orange-400', 'bg-orange-50');
        if (categorySelected) {
            categorySelected.classList.add('text-orange-700', 'font-medium');
        }
    }

    toggleCategoryDropdownEdit();
}

/* ==========================
   LOGO EDIT (KEEP, TAPI TIDAK DIPAKAI)
   ========================== */

function previewEditLogoMerchant(input) {
    const preview = document.getElementById('editLogoMerchantPreview');
    const text = document.getElementById('editLogoMerchantText');

    if (!preview || !input.files || !input.files[0]) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        preview.querySelector('img').src = e.target.result;
        preview.classList.remove('hidden');
        if (text) text.textContent = input.files[0].name;
    };
    reader.readAsDataURL(input.files[0]);
}

function removeEditLogoMerchant() {
    const input = document.getElementById('editLogoMerchantInput');
    const preview = document.getElementById('editLogoMerchantPreview');
    const text = document.getElementById('editLogoMerchantText');
    
    if (input) input.value = '';
    if (preview) preview.classList.add('hidden');
    if (text) text.textContent = 'Click to change logo';
}

/* ==========================
   IMAGE RESIZE HELPER
   (PAKAI YANG GLOBAL JIKA SUDAH ADA)
   ========================== */

if (typeof resizeImageFile === 'undefined') {
    // fallback jika belum didefinisikan di script lain (upload)
    function resizeImageFile(file, maxSize, callback) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                let width = img.width;
                let height = img.height;
    
                if (width > height) {
                    if (width > maxSize) {
                        height = Math.round(height * (maxSize / width));
                        width = maxSize;
                    }
                } else {
                    if (height > maxSize) {
                        width = Math.round(width * (maxSize / height));
                        height = maxSize;
                    }
                }
    
                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
    
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
    
                canvas.toBlob(function(blob) {
                    if (!blob) {
                        callback(file, e.target.result);
                        return;
                    }
                    const resizedFile = new File(
                        [blob],
                        file.name,
                        { type: file.type || 'image/jpeg', lastModified: Date.now() }
                    );
                    const dataUrl = canvas.toDataURL(file.type || 'image/jpeg', 0.9);
                    callback(resizedFile, dataUrl);
                }, file.type || 'image/jpeg', 0.9);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

/* ==========================
   IMAGES EDIT (AUTO-RESIZE)
   ========================== */

function previewEditMerchantImages(input) {
    const preview = document.getElementById('editMerchantImagesPreview');
    const text = document.getElementById('editMerchantImagesText');
    
    if (!preview || !input.files) return;

    preview.innerHTML = '';

    const files = Array.from(input.files);

    if (files.length === 0) {
        preview.classList.add('hidden');
        if (text) text.textContent = 'Click to change images';
        return;
    }

    preview.classList.remove('hidden');
    if (text) text.textContent = `${files.length} file(s) selected`;

    const dt = new DataTransfer();
    const MAX_SIZE = 1000; // px maksimal sisi terpanjang

    files.forEach((file, index) => {
        resizeImageFile(file, MAX_SIZE, function(resizedFile, dataUrl) {
            dt.items.add(resizedFile);

            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${dataUrl}" class="w-full h-24 object-cover rounded-lg">
                <button type="button" onclick="removeEditMerchantImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            preview.appendChild(div);

            if (dt.items.length === files.length) {
                input.files = dt.files;
            }
        });
    });
}

function removeEditMerchantImage(index) {
    const input = document.getElementById('editMerchantImagesInput');
    if (!input || !input.files) return;

    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) dt.items.add(files[i]);
    }
    
    input.files = dt.files;
    previewEditMerchantImages(input);
    
    if (input.files.length === 0) {
        const preview = document.getElementById('editMerchantImagesPreview');
        const text = document.getElementById('editMerchantImagesText');
        if (preview) preview.classList.add('hidden');
        if (text) text.textContent = 'Click to change images';
    }
}

/* ==========================
   OPEN / CLOSE MODAL EDIT
   ========================== */

function openEditMerchant(id, data) {
    currentEditMerchantId = id;
    const modal = document.getElementById('editModalMerchant');
    if (!modal) return;
    
    // Populate form with existing data
    document.getElementById('editMerchantNama').value           = data.nama || '';
    document.getElementById('editMerchantDiskon').value         = data.diskon || '';
    const diskonRupiahEl = document.getElementById('editMerchantDiskonRupiah');
    if (diskonRupiahEl) diskonRupiahEl.value = data.diskon_rupiah || '';
    document.getElementById('editMerchantSkb').value            = data.skb || '';
    document.getElementById('editMerchantRedeemPoint').value    = data.redeem_point || '';
    document.getElementById('editMerchantStock').value          = data.stock || '';
    document.getElementById('editMerchantStartDate').value      = data.start_date || '';
    document.getElementById('editMerchantEndDate').value        = data.end_date || '';
    document.getElementById('editMerchantCta').value            = data.cta || '';
    
    // kategori tetap di-handle kalau masih dipakai tempat lain
    if (data.kategori) {
        selectCategoryEdit(data.kategori, data.kategori);
    }
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0.5'; }, 10);
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 50);
    
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea, span:not([class*="fa-"])');
    formElements.forEach((el, index) => {
        setTimeout(() => {
            el.style.transform = 'translateY(0)';
            el.style.opacity = '1';
        }, 100 + (index * 30));
    });

    // Init flatpickr untuk edit date (kalau library tersedia)
    if (typeof flatpickr !== 'undefined') {
        const startInput = document.getElementById('editMerchantStartDate');
        const endInput   = document.getElementById('editMerchantEndDate');
        if (startInput && !startInput._flatpickr) {
            flatpickr(startInput, {
                dateFormat: "d/m/Y",
                allowInput: true
            });
        }
        if (endInput && !endInput._flatpickr) {
            flatpickr(endInput, {
                dateFormat: "d/m/Y",
                allowInput: true
            });
        }
    }
}

function closeEditMerchant() {
    const modal = document.getElementById('editModalMerchant');
    if (!modal) return;
    
    const modalContent = modal.querySelector('div.relative');
    const backdrop = modal.querySelector('div.fixed');
    const formElements = modalContent.querySelectorAll('h3, button, label, input, select, textarea, span:not([class*="fa-"])');
    
    formElements.forEach((el, index) => {
        setTimeout(() => {
            el.style.transform = 'translateY(10px)';
            el.style.opacity = '0';
        }, index * 20);
    });
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    }, 100);
    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0'; }, 150);
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        const form = document.getElementById('formEditMerchant');
        if (form) form.reset();

        // Reset category (aman walaupun elemen sudah tidak ada di DOM)
        const categorySelected = document.getElementById('categorySelectedEdit');
        if (categorySelected) categorySelected.textContent = 'Pilih Kategori';
        const kategoriInput = document.getElementById('kategoriInputEdit');
        if (kategoriInput) kategoriInput.value = '';
        
        // Reset images preview
        const preview = document.getElementById('editMerchantImagesPreview');
        const text = document.getElementById('editMerchantImagesText');
        if (preview) {
            preview.innerHTML = '';
            preview.classList.add('hidden');
        }
        if (text) text.textContent = 'Click to change images';
        
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

/* ==========================
   SUBMIT HANDLER
   ========================== */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditMerchant');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) {
                data[key] = value;
            }
            data.id = currentEditMerchantId;
            if (typeof showEditVerification === 'function') {
                showEditVerification(data, 'Merchant');
            }
        });
    }
    
    const modal = document.getElementById('editModalMerchant');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) closeEditMerchant();
        });
    }
});
</script>
