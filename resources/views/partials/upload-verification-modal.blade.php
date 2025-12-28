<!-- Upload Verification Modal -->
<div id="uploadVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="uploadVerificationContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-blue-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Data</h3>
                    <p class="text-sm text-gray-500">Pastikan data sudah benar</p>
                </div>
            </div>
            <button onclick="closeUploadVerificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 mb-4">
                    <i class="fas fa-question-circle text-blue-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Upload</h4>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin data yang Anda masukkan sudah benar dan ingin melanjutkan upload <span id="uploadType" class="font-semibold"></span>?
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeUploadVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="confirmUpload()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check mr-2"></i>
                Ya, Upload
            </button>
        </div>
    </div>
</div>

<!-- Upload Success Modal -->
<div id="uploadSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="uploadSuccessContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                    <p class="text-sm text-gray-500">Data berhasil diupload</p>
                </div>
            </div>
            <button onclick="closeUploadSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Upload Berhasil!</h4>
                <p class="text-sm text-gray-600 mb-6">
                    Data <span id="successType" class="font-semibold"></span> berhasil diupload ke sistem.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeUploadSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<!-- Upload Error Modal -->
<div id="uploadErrorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="uploadErrorContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-red-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Gagal!</h3>
                    <p class="text-sm text-gray-500">Data gagal diupload</p>
                </div>
            </div>
            <button onclick="closeUploadErrorModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-4">
                    <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Upload Gagal!</h4>
                <div class="text-sm text-gray-600 mb-6">
                    <p id="errorMessage" class="mb-3">Terjadi kesalahan saat mengupload data.</p>
                    <div id="missingFieldsList" class="hidden mt-4">
                        <p class="font-semibold text-gray-800 mb-2">Field yang belum diisi:</p>
                        <ul id="missingFieldsItems" class="list-disc list-inside text-left max-h-40 overflow-y-auto bg-red-50 p-3 rounded-lg border border-red-200">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeUploadErrorModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-times mr-2"></i>
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
let uploadVerificationData = null;
let uploadDataType = null;

function showUploadVerification(formData, type) {
    uploadVerificationData = formData;
    uploadDataType = type;
    
    // Update type in modal
    const uploadTypeSpan = document.getElementById('uploadType');
    if (uploadTypeSpan) uploadTypeSpan.textContent = type;
    
    // Show modal with animation
    const modal = document.getElementById('uploadVerificationModal');
    const modalContent = document.getElementById('uploadVerificationContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeUploadVerificationModal() {
    const modal = document.getElementById('uploadVerificationModal');
    const modalContent = document.getElementById('uploadVerificationContent');
    
    // Animate out
    if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    }, 300);
}

function confirmUpload() {
    if (uploadVerificationData && uploadDataType) {
        // Close verification modal
        closeUploadVerificationModal();
        
        // Handle Merchant upload - submit form via AJAX
        if (uploadDataType === 'Merchant') {
            setTimeout(() => {
                const form = document.getElementById('formUploadMerchant');
                if (!form) {
                    alert('Form tidak ditemukan');
                    return;
                }
                
                // Pastikan kategori terisi
                const kategoriInput = document.getElementById('merchantKategoriValue');
                if (!kategoriInput || !kategoriInput.value) {
                    alert('Mohon pilih Kategori');
                    return;
                }
                
                // Update semua field sebelum membuat FormData baru
                // Update link blanjapoin
                if (typeof updateLinkBlanjapoin === 'function') {
                    updateLinkBlanjapoin();
                }
                
                // Update WA PIC
                if (typeof updateWaPic === 'function') {
                    updateWaPic();
                }
                
                // Update daerah
                if (typeof updateDaerahCombined === 'function') {
                    updateDaerahCombined();
                }
                
                // Tunggu sebentar untuk memastikan semua update selesai
                setTimeout(() => {
                    // Buat FormData baru dari form untuk memastikan semua nilai terbaru terkirim
                    const freshFormData = new FormData(form);
                    
                    // Get CSRF token
                    const csrfInput = form.querySelector('input[name="_token"]');
                    const csrfToken = csrfInput ? csrfInput.value : null;
                    
                    if (!csrfToken) {
                        alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                        return;
                    }
                    
                    // Ensure CSRF token is in the FormData
                    if (!freshFormData.has('_token')) {
                        freshFormData.append('_token', csrfToken);
                    }
                    
                    // Pastikan kategori ada di FormData (force update)
                    if (kategoriInput.value) {
                        freshFormData.set('kategori', kategoriInput.value);
                    }
                    
                    // Pastikan link_blanjapoin terisi jika ada code
                    const linkCode = document.getElementById('linkBlanjapoinCode');
                    const linkFull = document.getElementById('linkBlanjapoinFull');
                    if (linkCode && linkCode.value && linkFull) {
                        if (typeof updateLinkBlanjapoin === 'function') {
                            updateLinkBlanjapoin();
                        }
                        if (linkFull.value) {
                            freshFormData.set('link_blanjapoin', linkFull.value);
                        }
                    }
                    
                    // Pastikan wa_pic terisi jika ada code
                    const waCode = document.getElementById('waPicCode');
                    const waFull = document.getElementById('waPicFull');
                    if (waCode && waCode.value && waFull) {
                        if (typeof updateWaPic === 'function') {
                            updateWaPic();
                        }
                        if (waFull.value) {
                            freshFormData.set('wa_pic', waFull.value);
                        }
                    }
                    
                    // Pastikan daerah terisi
                    const daerahCombined = document.getElementById('daerahCombined');
                    if (daerahCombined && daerahCombined.value) {
                        freshFormData.set('daerah', daerahCombined.value);
                    }
                    
                    // Debug: Log form data yang akan dikirim
                    console.log('=== SENDING FORM DATA ===');
                    for (let [key, value] of freshFormData.entries()) {
                        if (key !== 'logo_merchant' && key !== 'ktp_pic') {
                            console.log(key + ':', value);
                        } else {
                            console.log(key + ':', value instanceof File ? `File: ${value.name} (${value.size} bytes)` : 'File');
                        }
                    }
                    console.log('========================');
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: freshFormData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        // Cek status code terlebih dahulu
                        if (!response.ok) {
                            // Cek content type terlebih dahulu sebelum membaca body
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json().then(data => {
                                    // Jika berhasil parse JSON, throw dengan data
                                    throw { response: response, data: data };
                                });
                            } else {
                                // Jika bukan JSON, baca sebagai text
                                return response.text().then(text => {
                                    throw { response: response, text: text };
                                });
                            }
                        }
                        
                        // Response OK - cek content type
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Jika bukan JSON, baca sebagai text dan coba parse
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Non-JSON response:', text);
                                    throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}`);
                                }
                            });
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            // Store success message in sessionStorage
                            sessionStorage.setItem('uploadSuccess', 'Merchant');
                            
                            // Close upload modal
                            if (typeof closeUploadMerchant === 'function') {
                                closeUploadMerchant();
                            }
                            
                            // Reload page
                            setTimeout(() => {
                                location.reload();
                            }, 300);
                        } else {
                            // Handle validation errors
                            let errorMessage = 'Gagal menyimpan data';
                            if (data.message) {
                                errorMessage += ': ' + data.message;
                            }
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join('\n');
                                errorMessage += '\n\n' + errorList;
                            }
                            alert(errorMessage);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        // Handle different error types
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                        
                        if (error.data) {
                            // Error dari server dengan data JSON
                            if (error.data.message) {
                                errorMessage = error.data.message;
                            }
                            if (error.data.errors) {
                                const errorList = Object.values(error.data.errors).flat().join('\n');
                                errorMessage += '\n\n' + errorList;
                            }
                        } else if (error.text) {
                            // Error dengan text response
                            errorMessage += '\n\n' + error.text.substring(0, 500);
                        } else if (error.message) {
                            // Standard error
                            errorMessage += '\n\n' + error.message;
                        }
                        
                        alert(errorMessage);
                    });
                }, 100); // Delay 100ms untuk memastikan semua update selesai
            }, 300);
        } else if (uploadDataType === 'Keyword') {
            // Handle Keyword upload - submit form via AJAX
            setTimeout(() => {
                const form = document.getElementById('formUploadKeyword');
                if (form) {
                    // Get CSRF token
                    const csrfInput = form.querySelector('input[name="_token"]');
                    const csrfToken = csrfInput ? csrfInput.value : null;
                    
                    // Buat FormData baru dari form untuk memastikan semua nilai terbaru terkirim
                    const freshFormData = new FormData(form);
                    
                    // Ensure CSRF token is in the FormData
                    if (csrfToken) {
                        freshFormData.set('_token', csrfToken);
                    }
                    
                    // Pastikan diskon type dan nilai terbaru terkirim
                    const diskonType = form.querySelector('input[name="diskon_type"]:checked');
                    if (diskonType) {
                        if (diskonType.value === 'free') {
                            // Set diskon_percent = 100 untuk free
                            freshFormData.set('diskon_percent', '100');
                            freshFormData.delete('diskon_rupiah'); // Hapus rupiah jika ada
                        } else if (diskonType.value === 'percent') {
                            // Pastikan diskon_percent ada
                            const diskonPercent = form.querySelector('input[name="diskon_percent"]');
                            if (diskonPercent && diskonPercent.value) {
                                // Konversi ke angka (hapus format jika ada)
                                const percentValue = diskonPercent.value.toString().replace(/[^\d.]/g, '');
                                freshFormData.set('diskon_percent', percentValue);
                            }
                            freshFormData.delete('diskon_rupiah'); // Hapus rupiah
                        } else if (diskonType.value === 'rupiah') {
                            // Pastikan diskon_rupiah ada
                            const diskonRupiah = form.querySelector('input[name="diskon_rupiah"]');
                            if (diskonRupiah && diskonRupiah.value) {
                                // Konversi ke angka (hapus format jika ada)
                                const rupiahValue = diskonRupiah.value.toString().replace(/[^\d.]/g, '');
                                freshFormData.set('diskon_rupiah', rupiahValue);
                            }
                            freshFormData.delete('diskon_percent'); // Hapus percent
                        }
                    }
                    
                    // Pastikan subsidy_enabled dikirim dengan benar
                    const subsidyEnabledRadio = form.querySelector('input[name="subsidy_enabled"]:checked');
                    if (subsidyEnabledRadio) {
                        freshFormData.set('subsidy_enabled', subsidyEnabledRadio.value);
                    } else {
                        // Default ke '0' jika tidak ada yang dipilih
                        freshFormData.set('subsidy_enabled', '0');
                    }
                    
                    // Pastikan subsidy_amount dikonversi ke angka (hapus format titik sebagai pemisah ribuan)
                    const subsidyEnabled = form.querySelector('input[name="subsidy_enabled"]:checked');
                    if (subsidyEnabled && subsidyEnabled.value === '1') {
                        const subsidyAmountInput = form.querySelector('input[name="subsidy_amount"]');
                        if (subsidyAmountInput && subsidyAmountInput.value) {
                            // Hapus semua karakter non-numerik (titik sebagai pemisah ribuan, bukan desimal)
                            // Format Indonesia: 1.000.000 (titik = pemisah ribuan, koma = desimal)
                            // Kita hapus semua titik dan koma, lalu konversi ke angka
                            let subsidyValue = subsidyAmountInput.value.toString().replace(/\./g, ''); // Hapus titik (pemisah ribuan)
                            subsidyValue = subsidyValue.replace(/,/g, '.'); // Ganti koma dengan titik untuk desimal
                            subsidyValue = subsidyValue.replace(/[^\d.]/g, ''); // Hapus karakter lain
                            
                            // Pastikan hanya ada satu titik desimal
                            const parts = subsidyValue.split('.');
                            if (parts.length > 2) {
                                subsidyValue = parts[0] + '.' + parts.slice(1).join('');
                            }
                            
                            if (subsidyValue && !isNaN(parseFloat(subsidyValue))) {
                                freshFormData.set('subsidy_amount', subsidyValue);
                            } else {
                                freshFormData.delete('subsidy_amount');
                            }
                        } else {
                            freshFormData.delete('subsidy_amount');
                        }
                    } else {
                        // Jika subsidy disabled, hapus dari FormData
                        freshFormData.delete('subsidy_amount');
                    }
                    
                    // Pastikan diamond_enabled dikirim dengan benar
                    const diamondEnabledRadio = form.querySelector('input[name="diamond_enabled"]:checked');
                    if (diamondEnabledRadio) {
                        freshFormData.set('diamond_enabled', diamondEnabledRadio.value);
                    } else {
                        // Default ke '0' jika tidak ada yang dipilih
                        freshFormData.set('diamond_enabled', '0');
                    }
                    
                    // Pastikan diamond_amount dikonversi ke angka
                    const diamondEnabled = form.querySelector('input[name="diamond_enabled"]:checked');
                    if (diamondEnabled && diamondEnabled.value === '1') {
                        const diamondAmountInput = form.querySelector('input[name="diamond_amount"]');
                        if (diamondAmountInput && diamondAmountInput.value) {
                            // Konversi ke angka (hapus format jika ada)
                            const diamondValue = diamondAmountInput.value.toString().replace(/[^\d]/g, '');
                            if (diamondValue) {
                                freshFormData.set('diamond_amount', diamondValue);
                            } else {
                                freshFormData.delete('diamond_amount');
                            }
                        } else {
                            freshFormData.delete('diamond_amount');
                        }
                    } else {
                        // Jika diamond disabled, hapus dari FormData
                        freshFormData.delete('diamond_amount');
                    }
                    
                    // Pastikan redeem point dikonversi ke angka jika ada
                    const redeemInput = form.querySelector('input[name="redeem"]');
                    if (redeemInput && redeemInput.value) {
                        const redeemValue = redeemInput.value.toString().replace(/[^\d]/g, '');
                        if (redeemValue) {
                            freshFormData.set('redeem', redeemValue);
                        }
                    }
                    
                    // Pastikan stock dikonversi ke angka jika ada
                    const stockInput = form.querySelector('input[name="stock"]');
                    if (stockInput && stockInput.value) {
                        const stockValue = stockInput.value.toString().replace(/[^\d]/g, '');
                        if (stockValue) {
                            freshFormData.set('stock', stockValue);
                        }
                    }
                    
                    // Debug: Log form data yang akan dikirim
                    console.log('=== SENDING KEYWORD FORM DATA ===');
                    for (let [key, value] of freshFormData.entries()) {
                        if (key !== 'image') {
                            console.log(key + ':', value);
                        } else {
                            console.log(key + ':', value instanceof File ? `File: ${value.name} (${value.size} bytes)` : 'File');
                        }
                    }
                    console.log('==================================');
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: freshFormData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        // Clone response untuk bisa dibaca beberapa kali jika perlu
                        const responseClone = response.clone();
                        
                        if (!response.ok) {
                            // Cek content type terlebih dahulu
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json().then(data => {
                                    // Create error object with data for better handling
                                    const error = new Error(data.message || data.error || `HTTP ${response.status}`);
                                    error.data = data;
                                    throw error;
                                });
                            } else {
                                // Jika bukan JSON, baca sebagai text
                                return response.text().then(text => {
                                    throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                                });
                            }
                        }
                        
                        // Response OK - parse sebagai JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Jika bukan JSON, baca sebagai text dan coba parse
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Non-JSON response:', text);
                                    throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}`);
                                }
                            });
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            // Store success message in sessionStorage
                            sessionStorage.setItem('uploadSuccess', 'Keyword');
                            
                            // Close upload modal
                            if (typeof closeUploadKeyword === 'function') {
                                closeUploadKeyword();
                            }
                            
                            // Reload page with keyword tab active
                            setTimeout(() => {
                                const currentUrl = new URL(window.location.href);
                                currentUrl.searchParams.set('tab', 'keyword');
                                window.location.href = currentUrl.toString();
                            }, 300);
                        } else {
                            // Show error with missing fields if available
                            let errorMessage = data.message || 'Gagal menyimpan data';
                            let missingFields = data.missing_fields || [];
                            
                            showUploadErrorModal(errorMessage, missingFields);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                        let missingFields = [];
                        
                        // Check if error has data object (from JSON response)
                        if (error.data) {
                            if (error.data.message) {
                                errorMessage = error.data.message;
                            }
                            if (error.data.missing_fields && Array.isArray(error.data.missing_fields)) {
                                missingFields = error.data.missing_fields;
                            }
                        } else if (error.message) {
                            errorMessage = error.message;
                            
                            // Try to extract missing fields from error message
                            const fieldMatch = errorMessage.match(/Field yang belum diisi: ([^.]+)/);
                            if (fieldMatch) {
                                const fieldsStr = fieldMatch[1];
                                missingFields = fieldsStr.split(',').map(f => f.trim());
                                // Remove field list from main message
                                errorMessage = errorMessage.replace(/Field yang belum diisi:.*?\./, '').trim();
                            }
                        }
                        
                        showUploadErrorModal(errorMessage, missingFields);
                    });
                }
            }, 300);
        } else {
            // Handle other types (Merchandise, Telkom Package)
            setTimeout(() => {
                if (uploadDataType === 'Merchandise' && typeof closeUploadMerchandise === 'function') {
                    closeUploadMerchandise();
                } else if (uploadDataType === 'Telkom Package' && typeof closeUploadTelkom === 'function') {
                    closeUploadTelkom();
                }
                
                // Show success modal after a short delay
                setTimeout(() => {
                    showUploadSuccessModal(uploadDataType);
                }, 500);
            }, 300);
        }
    }
}

function showUploadSuccessModal(type) {
    // Update type in success modal
    const successTypeSpan = document.getElementById('successType');
    if (successTypeSpan) successTypeSpan.textContent = type;
    
    // Show modal with animation
    const modal = document.getElementById('uploadSuccessModal');
    const modalContent = document.getElementById('uploadSuccessContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        closeUploadSuccessModal();
    }, 3000);
}

function closeUploadSuccessModal() {
    const modal = document.getElementById('uploadSuccessModal');
    const modalContent = document.getElementById('uploadSuccessContent');
    
    // Animate out
    if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    }, 300);
}

function showUploadErrorModal(message, missingFields = []) {
    // Update error message
    const errorMessageSpan = document.getElementById('errorMessage');
    if (errorMessageSpan) {
        // Clean up message - remove duplicate "Field yang belum diisi" if present
        let cleanMessage = message || 'Terjadi kesalahan saat mengupload data.';
        if (missingFields && missingFields.length > 0) {
            // Remove the field list from message if it's already there
            const fieldListPattern = /Field yang belum diisi:.*?\./g;
            cleanMessage = cleanMessage.replace(fieldListPattern, '').trim();
        }
        errorMessageSpan.textContent = cleanMessage;
    }
    
    // Show missing fields if available
    const missingFieldsList = document.getElementById('missingFieldsList');
    const missingFieldsItems = document.getElementById('missingFieldsItems');
    
    if (missingFields && missingFields.length > 0 && missingFieldsList && missingFieldsItems) {
        missingFieldsItems.innerHTML = '';
        missingFields.forEach(field => {
            const li = document.createElement('li');
            li.className = 'text-red-700 mb-1';
            li.textContent = field;
            missingFieldsItems.appendChild(li);
        });
        missingFieldsList.classList.remove('hidden');
    } else {
        if (missingFieldsList) {
            missingFieldsList.classList.add('hidden');
        }
    }
    
    // Show modal with animation
    const modal = document.getElementById('uploadErrorModal');
    const modalContent = document.getElementById('uploadErrorContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeUploadErrorModal() {
    const modal = document.getElementById('uploadErrorModal');
    const modalContent = document.getElementById('uploadErrorContent');
    
    // Animate out
    if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    }, 300);
}

// Check for success message after page reload
document.addEventListener('DOMContentLoaded', function() {
    const successType = sessionStorage.getItem('uploadSuccess');
    if (successType) {
        // Clear the stored message
        sessionStorage.removeItem('uploadSuccess');
        
        // Show success modal
        setTimeout(() => {
            showUploadSuccessModal(successType);
        }, 500);
    }
    
    // Check for flash message from Laravel (for Keyword and other POST submissions)
    const flashMessage = document.querySelector('[data-flash-message]');
    if (flashMessage) {
        const message = flashMessage.getAttribute('data-flash-message');
        const type = flashMessage.getAttribute('data-flash-type') || 'success';
        
        if (message) {
            setTimeout(() => {
                if (type === 'success') {
                    // Extract type from message or use default
                    let messageType = 'Keyword';
                    if (message.includes('Merchant')) messageType = 'Merchant';
                    else if (message.includes('Merchandise')) messageType = 'Merchandise';
                    
                    showUploadSuccessModal(messageType);
                } else if (type === 'error') {
                    showUploadErrorModal(message);
                }
            }, 500);
        }
    }
});
</script>
