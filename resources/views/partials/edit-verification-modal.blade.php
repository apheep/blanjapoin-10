<!-- Edit Verification Modal -->
<div id="editVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="editVerificationContent">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-edit text-blue-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Edit</h3>
                    <p class="text-sm text-gray-500">Pastikan perubahan sudah benar</p>
                </div>
            </div>
            <button onclick="closeEditVerificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 mb-4">
                    <i class="fas fa-question-circle text-blue-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Perubahan</h4>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin perubahan data <span id="editType" class="font-semibold"></span> sudah benar dan ingin menyimpannya?
                </p>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeEditVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="confirmEdit()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check mr-2"></i>
                Ya, Update
            </button>
        </div>
    </div>
</div>

<!-- Edit Success Modal -->
<div id="editSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="editSuccessContent">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                    <p class="text-sm text-gray-500">Data berhasil diupdate</p>
                </div>
            </div>
            <button onclick="closeEditSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Update Berhasil!</h4>
                <p class="text-sm text-gray-600 mb-6">
                    Data <span id="successEditType" class="font-semibold"></span> berhasil diupdate.
                </p>
            </div>
        </div>
        
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeEditSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<script>
let editVerificationData = null;
let editDataType = null;

function showEditVerification(formData, type) {
    editVerificationData = formData;
    editDataType = type;
    
    document.getElementById('editType').textContent = type;
    
    const modal = document.getElementById('editVerificationModal');
    const modalContent = document.getElementById('editVerificationContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeEditVerificationModal() {
    const modal = document.getElementById('editVerificationModal');
    const modalContent = document.getElementById('editVerificationContent');
    
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

function confirmEdit() {
    if (editVerificationData && editDataType) {
        closeEditVerificationModal();
        
        setTimeout(() => {
            // Handle Merchant edit - submit form via AJAX
            if (editDataType === 'Merchant') {
                const form = document.getElementById('formEditMerchant');
                if (form) {
                    // Sync banner_keyword_ids dari checkbox yang ter-check sebelum kirim form
                    const kwList  = document.getElementById('editBannerKeywordList');
                    const kwIds   = document.getElementById('editBannerKeywordIds');
                    if (kwList && kwIds) {
                        const checked = Array.from(kwList.querySelectorAll('.banner-kw-check:checked'));
                        kwIds.value = checked.map(c => c.value).join(',');
                    }

                    // Update link blanjapoin dan daerah sebelum membuat FormData baru
                    if (typeof updateEditLinkBlanjapoin === 'function') {
                        updateEditLinkBlanjapoin();
                    }
                    if (typeof updateEditDaerahCombined === 'function') {
                        updateEditDaerahCombined();
                    }
                    
                    // Buat FormData baru dari form untuk memastikan semua nilai terbaru terkirim
                    const freshFormData = new FormData(form);
                    
                    // Get CSRF token
                    const csrfInput = form.querySelector('input[name="_token"]');
                    const csrfToken = csrfInput ? csrfInput.value : null;
                    
                    // Ensure CSRF token is in the FormData
                    if (csrfToken && !freshFormData.has('_token')) {
                        freshFormData.append('_token', csrfToken);
                    }
                    
                    // _method PUT sudah ada dari @method('PUT') di form, tidak perlu append lagi
                    
                    // Debug: Log form data yang akan dikirim
                    console.log('Sending edit merchant form data:');
                    for (let [key, value] of freshFormData.entries()) {
                        if (key !== 'logo_merchant') {
                            console.log(key + ':', value);
                        } else {
                            console.log(key + ':', value instanceof File ? value.name : 'File');
                        }
                    }
                    
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
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Store success message in sessionStorage
                            sessionStorage.setItem('editSuccess', 'Merchant');
                            
                            // Close edit modal
                            if (typeof closeEditMerchant === 'function') {
                                closeEditMerchant();
                            }
                            
                            // Show success modal
                            setTimeout(() => {
                                showEditSuccessModal(editDataType);
                            }, 300);
                            
                            // Reload page after success modal
                            setTimeout(() => {
                                location.reload();
                            }, 3500);
                        } else {
                            alert('Gagal mengupdate data: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupdate data.\n\n' + error.message);
                    });
                }
            } else if (editDataType === 'Merchandise' && typeof closeEditMerchandise === 'function') {
                closeEditMerchandise();
                setTimeout(() => {
                    showEditSuccessModal(editDataType);
                }, 500);
            } else if (editDataType === 'Telkom Package' && typeof closeEditTelkom === 'function') {
                closeEditTelkom();
                setTimeout(() => {
                    showEditSuccessModal(editDataType);
                }, 500);
            }
        }, 300);
    }
}

function showEditSuccessModal(type) {
    document.getElementById('successEditType').textContent = type;
    
    const modal = document.getElementById('editSuccessModal');
    const modalContent = document.getElementById('editSuccessContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
    
    setTimeout(() => {
        closeEditSuccessModal();
    }, 3000);
}

function closeEditSuccessModal() {
    const modal = document.getElementById('editSuccessModal');
    const modalContent = document.getElementById('editSuccessContent');
    
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
</script>
