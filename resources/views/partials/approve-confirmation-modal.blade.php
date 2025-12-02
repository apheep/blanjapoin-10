<!-- Approve Verification Modal -->
<div id="approveVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="approveVerificationContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Approve</h3>
                    <p class="text-sm text-gray-500">Pastikan data sudah benar</p>
                </div>
            </div>
            <button onclick="closeApproveVerificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2" id="approveItemName">Item Name</h4>
                <p class="text-sm text-gray-600 mb-6" id="approveItemDescription">
                    Apakah Anda yakin ingin menyetujui item ini? Item yang sudah disetujui akan langsung aktif di sistem.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeApproveVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="confirmApprove()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check-circle mr-2"></i>
                Ya, Approve
            </button>
        </div>
    </div>
</div>

<!-- Approve Success Modal -->
<div id="approveSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="approveSuccessContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                    <p class="text-sm text-gray-500">Data berhasil disetujui</p>
                </div>
            </div>
            <button onclick="closeApproveSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Data Berhasil Disetujui!</h4>
                <p class="text-sm text-gray-600 mb-6">
                    Data <span id="approveSuccessItemName" class="font-semibold"></span> telah berhasil disetujui dan sekarang aktif di sistem.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeApproveSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-check mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<script>
let approveItemData = null;

function showApproveConfirmation(itemType, itemName, itemId, itemDescription = null) {
    approveItemData = {
        type: itemType,
        name: itemName,
        id: itemId
    };
    
    document.getElementById('approveItemName').textContent = itemName;
    document.getElementById('approveItemDescription').textContent = itemDescription || `Apakah Anda yakin ingin menyetujui ${itemType} "${itemName}"? Item yang sudah disetujui akan langsung aktif di sistem.`;
    
    const modal = document.getElementById('approveVerificationModal');
    const modalContent = document.getElementById('approveVerificationContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeApproveVerificationModal() {
    const modal = document.getElementById('approveVerificationModal');
    const modalContent = document.getElementById('approveVerificationContent');
    
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

function confirmApprove() {
    if (approveItemData) {
        // Close verification modal
        closeApproveVerificationModal();
        
        // Make AJAX call to backend
        const endpoint = approveItemData.type === 'Keyword' 
            ? `/keywords/${approveItemData.id}/approve`
            : `/merchants/${approveItemData.id}/approve`;
        
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                type: approveItemData.type,
                id: approveItemData.id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateKeywordDisplay(approveItemData.id, 'approve', approveItemData.name);
                showApproveSuccessModal(approveItemData.name);
            } else {
                alert('Error: ' + (data.message || 'Gagal menyetujui item'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyetujui item');
        });
    }
}

function showApproveSuccessModal(itemName) {
    document.getElementById('approveSuccessItemName').textContent = itemName;
    
    const modal = document.getElementById('approveSuccessModal');
    const modalContent = document.getElementById('approveSuccessContent');
    
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
        closeApproveSuccessModal();
    }, 3000);
}

function closeApproveSuccessModal() {
    const modal = document.getElementById('approveSuccessModal');
    const modalContent = document.getElementById('approveSuccessContent');
    
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
    const successData = sessionStorage.getItem('approveSuccess');
    if (successData) {
        const data = JSON.parse(successData);
        // Show success modal
        showApproveSuccessModal(data.name);
        // Clear the stored message
        sessionStorage.removeItem('approveSuccess');
        
        // Auto close after 3 seconds
        setTimeout(() => {
            closeApproveSuccessModal();
        }, 3000);
    }
});
</script>
<script>
function updateKeywordDisplay(id, status, name) {
    var actionCell = document.getElementById('keyword-action-' + id);
    var statusCell = document.getElementById('keyword-status-' + id);
    var actionMobile = document.getElementById('keyword-action-mobile-' + id);
    var statusMobile = document.getElementById('keyword-status-mobile-' + id);
    // Tombol edit (desktop) di kolom "Actions"
    var editButton = document.querySelector('[data-keyword-edit-id="' + id + '"]');

    if (status === 'approve') {
        var approvedBadge = '<div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium text-sm shadow-sm"><i class="fas fa-check-circle text-green-600"></i><span>Approved</span></div>';
        if (actionCell) actionCell.innerHTML = approvedBadge;
        if (actionMobile) actionMobile.innerHTML = '<p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2">Action</p>' + approvedBadge;
        var statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>';
        if (statusCell) statusCell.innerHTML = statusBadge;
        if (statusMobile) statusMobile.innerHTML = '<p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Status</p><div class="mt-1">' + statusBadge + '</div>';
    } else if (status === 'reject') {
        var rejectedBadge = '<div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-red-100 to-rose-100 text-red-700 font-medium text-sm shadow-sm"><i class="fas fa-times text-red-600"></i><span>Rejected</span></div>';
        if (actionCell) actionCell.innerHTML = rejectedBadge;
        if (actionMobile) actionMobile.innerHTML = '<p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2">Action</p>' + rejectedBadge;
        var statusBadgeR = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>';
        if (statusCell) statusCell.innerHTML = statusBadgeR;
        if (statusMobile) statusMobile.innerHTML = '<p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Status</p><div class="mt-1">' + statusBadgeR + '</div>';
    }

    // Setelah status bukan lagi "pending", hilangkan tombol Edit tanpa perlu reload page
    if (editButton) {
        editButton.remove();
    }
}
</script>
<script>
let rejectItemData = null;
function showRejectConfirmation(itemType, itemName, itemId, itemDescription = null) {
    rejectItemData = { type: itemType, name: itemName, id: itemId };
    document.getElementById('rejectItemName').textContent = itemName;
    document.getElementById('rejectItemDescription').textContent = itemDescription || `Apakah Anda yakin ingin menolak ${itemType} "${itemName}"?`;
    const modal = document.getElementById('rejectVerificationModal');
    const modalContent = document.getElementById('rejectVerificationContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(1)'; modalContent.style.opacity = '1'; } }, 10);
}
function closeRejectVerificationModal() {
    const modal = document.getElementById('rejectVerificationModal');
    const modalContent = document.getElementById('rejectVerificationContent');
    if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; } }, 300);
}
function confirmReject() {
    if (rejectItemData) {
        closeRejectVerificationModal();
        const endpoint = rejectItemData.type === 'Keyword' ? `/keywords/${rejectItemData.id}/reject` : `/merchants/${rejectItemData.id}/reject`;
        fetch(endpoint, { 
            method: 'POST', 
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }, 
            body: JSON.stringify({ type: rejectItemData.type, id: rejectItemData.id }) 
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => { 
            if (data.success) { 
                updateKeywordDisplay(rejectItemData.id, 'reject', rejectItemData.name); 
                showRejectSuccessModal(rejectItemData.name); 
            } else { 
                alert('Error: ' + (data.message || 'Gagal menolak item')); 
            } 
        })
        .catch(error => { 
            console.error('Error:', error); 
            const errorMessage = error.message || error.error || 'Terjadi kesalahan saat menolak item';
            alert('Error: ' + errorMessage); 
        });
    }
}
function showRejectSuccessModal(itemName) {
    document.getElementById('rejectSuccessItemName').textContent = itemName;
    const modal = document.getElementById('rejectSuccessModal');
    const modalContent = document.getElementById('rejectSuccessContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => { if (modalContent) { modalContent.style.transform = 'scale(1)'; modalContent.style.opacity = '1'; } }, 10);
    setTimeout(() => { closeRejectSuccessModal(); }, 3000);
}
function closeRejectSuccessModal() {
    const modal = document.getElementById('rejectSuccessModal');
    const modalContent = document.getElementById('rejectSuccessContent');
    if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; }
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; if (modalContent) { modalContent.style.transform = 'scale(0.95)'; modalContent.style.opacity = '0'; } }, 300);
}
document.addEventListener('DOMContentLoaded', function() {
    const successData = sessionStorage.getItem('rejectSuccess');
    if (successData) {
        const data = JSON.parse(successData);
        showRejectSuccessModal(data.name);
        sessionStorage.removeItem('rejectSuccess');
        setTimeout(() => { closeRejectSuccessModal(); }, 3000);
    }
});
</script>

<div id="rejectVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="rejectVerificationContent">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Reject</h3>
                    <p class="text-sm text-gray-500">Pastikan data sudah benar</p>
                </div>
            </div>
            <button onclick="closeRejectVerificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-4">
                    <i class="fas fa-times text-red-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2" id="rejectItemName">Item Name</h4>
                <p class="text-sm text-gray-600 mb-6" id="rejectItemDescription">Apakah Anda yakin ingin menolak item ini?</p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeRejectVerificationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
            <button onclick="confirmReject()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300"><i class="fas fa-times mr-2"></i>Ya, Reject</button>
        </div>
    </div>
    </div>

<div id="rejectSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="rejectSuccessContent">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                    <p class="text-sm text-gray-500">Data berhasil ditolak</p>
                </div>
            </div>
            <button onclick="closeRejectSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-4">
                    <i class="fas fa-times text-red-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Data Berhasil Ditolak!</h4>
                <p class="text-sm text-gray-600 mb-6">Data <span id="rejectSuccessItemName" class="font-semibold"></span> telah berhasil ditolak.</p>
            </div>
        </div>
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeRejectSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg hover:shadow-lg transition-all duration-300"><i class="fas fa-check mr-2"></i>OK</button>
        </div>
    </div>
    </div>

