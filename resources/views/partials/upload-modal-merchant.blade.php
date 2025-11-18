<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
/* ================================ */
/* DATE PICKER ANIMATION SLOWER     */
/* ================================ */

/* Perlambat animasi buka/tutup flatpickr (default: 0.15s) */
.flatpickr-calendar {
    animation-duration: 0.35s !important;
    transition: opacity 0.35s ease !important;
}

/* Tombol navigasi bulan lebih smooth */
.flatpickr-prev-month, 
.flatpickr-next-month {
    transition: all 0.3s ease !important;
}
</style>

<!-- Merchant Upload Modal -->
<div id="uploadModalMerchant" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0">
        <!-- Sticky Header -->
        <div class="sticky top-0 z-10 flex justify-between items-center px-4 py-3 md:px-6 md:py-4 border-b bg-white rounded-t-xl">
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                Upload Merchant Data
            </h3>
            <button
                type="button"
                onclick="closeUploadMerchant()"
                class="text-gray-400 hover:text-gray-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0"
            >
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Scrollable Form Content -->
        <form id="formUploadMerchant" class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 space-y-4">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-x-6 md:gap-y-3">
                        <!-- 1. Nama Merchant -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Nama Produk 
                            </label>
                            <input
                                type="text"
                                name="nama"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                placeholder="Enter product name"
                            >
                        </div>

                        <!-- 2. CTA -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                CTA
                            </label>
                            <input
                                type="url"
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
                                name="end_date"
                                class="w-full px-4 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-400 text-[15px] transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                maxlength="10"
                                placeholder="DD/MM/YYYY"
                                onkeyup="formatDateInput(this)"
                                onkeypress="return isNumberKey(event)"
                            >
                        </div>

                        <!-- 8. Images (auto-resize on upload) -->
                        <div class="md:col-span-2">
                            <label class="block text-[15px] font-medium text-gray-700 mb-1 transition-all duration-300 ease-out transform translate-y-2 opacity-0">
                                Images
                            </label>
                            <div class="relative">
                                <input
                                    type="file"
                                    id="merchantImagesInput"
                                    name="images[]"
                                    accept="image/*"
                                    multiple
                                    class="hidden"
                                    onchange="previewMerchantImages(this)"
                                >
                                <button
                                    type="button"
                                    onclick="document.getElementById('merchantImagesInput').click()"
                                    class="w-full min-h-[92px] px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 focus:outline-none focus:border-orange-500 flex flex-col items-center justify-center text-gray-600 hover:text-orange-600 transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                                >
                                    <i class="fas fa-upload text-2xl mb-2"></i>
                                    <span id="merchantImagesText" class="text-[15px]">
                                        Click to upload images
                                    </span>
                                </button>
                                <div
                                    id="merchantImagesPreview"
                                    class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Footer -->
            <div class="sticky bottom-0 z-10 flex justify-end space-x-3 px-4 py-3 md:px-6 md:py-4 border-t bg-white rounded-b-xl">
                <button
                    type="button"
                    onclick="closeUploadMerchant()"
                    class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 ease-out transform translate-y-2 opacity-0"
                >
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.upload-verification-modal')

<!-- Flatpickr JS (bisa dipindah ke layout utama jika mau) -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Category dropdown functions (masih disimpan jika suatu saat dipakai)
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

// Date formatting functions (tetap dipakai untuk input manual)
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

// Logo preview functions (aman walaupun elemen logo tidak ada)
function previewLogoMerchant(input) {
    const preview = document.getElementById('logoMerchantPreview');
    const text = document.getElementById('logoMerchantText');
    
    if (!preview || !input.files || !input.files[0]) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        preview.querySelector('img').src = e.target.result;
        preview.classList.remove('hidden');
        if (text) text.textContent = input.files[0].name;
    };
    reader.readAsDataURL(input.files[0]);
}

function removeLogoMerchant() {
    const input = document.getElementById('logoMerchantInput');
    const preview = document.getElementById('logoMerchantPreview');
    const text = document.getElementById('logoMerchantText');
    
    if (input) input.value = '';
    if (preview) preview.classList.add('hidden');
    if (text) text.textContent = 'Click to upload logo';
}

// === Helper: Resize image file sebelum upload & preview ===
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
                    // fallback: pakai file asli
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

// Images preview functions (dengan auto-resize)
function previewMerchantImages(input) {
    const preview = document.getElementById('merchantImagesPreview');
    const text = document.getElementById('merchantImagesText');
    
    if (!preview || !input.files) return;

    preview.innerHTML = '';

    const files = Array.from(input.files);

    if (files.length === 0) {
        preview.classList.add('hidden');
        if (text) text.textContent = 'Click to upload images';
        return;
    }

    preview.classList.remove('hidden');
    if (text) text.textContent = `${files.length} file(s) selected`;

    const dt = new DataTransfer();
    const MAX_SIZE = 1000; // px (maksimal sisi terpanjang)

    files.forEach((file, index) => {
        resizeImageFile(file, MAX_SIZE, function(resizedFile, dataUrl) {
            dt.items.add(resizedFile);

            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${dataUrl}" class="w-full h-24 object-cover rounded-lg">
                <button type="button" onclick="removeMerchantImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            preview.appendChild(div);

            // setelah semua selesai, replace files input dengan versi resized
            if (dt.items.length === files.length) {
                input.files = dt.files;
            }
        });
    });
}

function removeMerchantImage(index) {
    const input = document.getElementById('merchantImagesInput');
    if (!input || !input.files) return;

    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) dt.items.add(files[i]);
    }
    
    input.files = dt.files;
    previewMerchantImages(input);
    
    if (input.files.length === 0) {
        const preview = document.getElementById('merchantImagesPreview');
        const text = document.getElementById('merchantImagesText');
        if (preview) preview.classList.add('hidden');
        if (text) text.textContent = 'Click to upload images';
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
        
        // Reset category selection (aman walaupun elemen kategori tidak ada)
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
        if (backdrop) {
            backdrop.style.opacity = '0';
        }

        // Reset images preview text & grid
        const preview = document.getElementById('merchantImagesPreview');
        const text = document.getElementById('merchantImagesText');
        if (preview) {
            preview.innerHTML = '';
            preview.classList.add('hidden');
        }
        if (text) text.textContent = 'Click to upload images';
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

    // === Tambahan: init date picker untuk Start Date & End Date ===
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');

    if (typeof flatpickr !== 'undefined') {
        if (startInput) {
            flatpickr(startInput, {
                dateFormat: "d/m/Y",
                allowInput: true
            });
        }
        if (endInput) {
            flatpickr(endInput, {
                dateFormat: "d/m/Y",
                allowInput: true
            });
        }
    }
});

// Formatter Rupiah untuk Diskon (Rupiah)
function formatRupiahInput(input) {
    let value = (input.value || '').replace(/[^0-9]/g, '');
    
    if (value === '') {
        input.value = '';
        return;
    }

    value = value.replace(/^0+/, '') || '0';

    try {
        input.value = new Intl.NumberFormat('id-ID').format(parseInt(value, 10));
    } catch (e) {
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}
</script>
