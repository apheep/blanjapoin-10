<!-- Delete Verification Modal -->
<div id="deleteVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="deleteVerificationContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-orange-100 to-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <button onclick="closeDeleteVerificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-orange-100 to-red-100 mb-4">
                    <i class="fas fa-trash text-orange-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2" id="deleteItemName">Item Name</h4>
                <p class="text-sm text-gray-600 mb-6" id="deleteItemDescription">
                    Apakah Anda yakin ingin menghapus item ini? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeDeleteVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="confirmDelete()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-trash mr-2"></i>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- Delete Success Modal -->
<div id="deleteSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="deleteSuccessContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                    <p class="text-sm text-gray-500">Data berhasil dihapus</p>
                </div>
            </div>
            <button onclick="closeDeleteSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Data Berhasil Dihapus!</h4>
                <p class="text-sm text-gray-600 mb-6">
                    Data <span id="deleteSuccessItemName" class="font-semibold"></span> telah berhasil dihapus dari sistem.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeDeleteSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<script>
let deleteItemData = null;

function showDeleteConfirmation(itemType, itemName, itemId, itemDescription = null) {
    deleteItemData = {
        type: itemType,
        name: itemName,
        id: itemId
    };
    
    document.getElementById('deleteItemName').textContent = itemName;
    document.getElementById('deleteItemDescription').textContent = itemDescription || `Apakah Anda yakin ingin menghapus ${itemType} "${itemName}"?`;
    
    const modal = document.getElementById('deleteVerificationModal');
    const modalContent = document.getElementById('deleteVerificationContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeDeleteVerificationModal() {
    const modal = document.getElementById('deleteVerificationModal');
    const modalContent = document.getElementById('deleteVerificationContent');
    
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

function confirmDelete() {
    if (deleteItemData) {
        // Close verification modal
        closeDeleteVerificationModal();
        
        // Show success modal after a short delay
        setTimeout(() => {
            showDeleteSuccessModal(deleteItemData.name);
        }, 500);
        
        // Here you would implement actual delete logic
        console.log('Deleting:', deleteItemData);
    }
}

function showDeleteSuccessModal(itemName) {
    document.getElementById('deleteSuccessItemName').textContent = itemName;
    
    const modal = document.getElementById('deleteSuccessModal');
    const modalContent = document.getElementById('deleteSuccessContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        closeDeleteSuccessModal();
    }, 3000);
}

function closeDeleteSuccessModal() {
    const modal = document.getElementById('deleteSuccessModal');
    const modalContent = document.getElementById('deleteSuccessContent');
    
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
