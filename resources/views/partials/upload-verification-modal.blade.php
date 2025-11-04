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
        
        // Close upload modal based on type
        setTimeout(() => {
            if (uploadDataType === 'Merchant' && typeof closeUploadMerchant === 'function') {
                closeUploadMerchant();
            } else if (uploadDataType === 'Merchandise' && typeof closeUploadMerchandise === 'function') {
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
</script>
