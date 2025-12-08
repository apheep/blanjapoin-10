<!-- Approve Withdraw Validation Modal -->
<div id="approveWithdrawModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="approveWithdrawContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Konfirmasi Approve</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">Pastikan data sudah benar</p>
                </div>
            </div>
            <button onclick="closeApproveWithdrawModal()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors ml-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-4 py-3 sm:px-6 sm:py-4 overflow-y-auto max-h-[60vh]">
            <div class="text-center mb-3 sm:mb-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-3 sm:mb-4">
                    <i class="fas fa-check-circle text-green-600 text-xl sm:text-2xl"></i>
                </div>
                <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-1 sm:mb-2">Setujui Penarikan?</h4>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4" id="approveWithdrawMessage">
                    Apakah Anda yakin ingin menyetujui penarikan ini?
                </p>
            </div>
            
            <!-- Withdraw Details Table -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium w-1/3">Nama</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="approveWithdrawName">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Merchant</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="approveWithdrawMerchant">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Metode</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="approveWithdrawMethod">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">No. Rek/E-Wallet</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-mono font-semibold text-xs break-all" id="approveWithdrawAccount">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Jumlah</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="approveWithdrawAmount">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Tanggal</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="approveWithdrawDate">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-2 sm:gap-3 px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 rounded-b-2xl border-t border-gray-200 flex-shrink-0">
            <button onclick="closeApproveWithdrawModal()" class="px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="approveWithdrawForm" method="POST" action="" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-check-circle mr-1 sm:mr-2"></i>
                    <span class="hidden sm:inline">Ya, Approve</span>
                    <span class="sm:hidden">Approve</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reject Withdraw Validation Modal -->
<div id="rejectWithdrawModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full my-4 transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[90vh]" id="rejectWithdrawContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Tolak Withdraw Request</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">Masukkan alasan penolakan</p>
                </div>
            </div>
            <button onclick="closeRejectWithdrawModal()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors ml-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-4 py-3 sm:px-6 sm:py-4 overflow-y-auto flex-1 max-h-[60vh]">
            <div class="text-center mb-3 sm:mb-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-3 sm:mb-4">
                    <i class="fas fa-times text-red-600 text-xl sm:text-2xl"></i>
                </div>
                <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-1 sm:mb-2">Tolak Penarikan?</h4>
                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                    Apakah Anda yakin ingin menolak penarikan ini?
                </p>
            </div>
            
            <!-- Withdraw Details Table -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium w-1/3">Nama</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="rejectWithdrawName">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Merchant</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="rejectWithdrawMerchant">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Metode</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="rejectWithdrawMethod">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">No. Rek/E-Wallet</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-mono font-semibold text-xs break-all" id="rejectWithdrawAccount">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Jumlah</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="rejectWithdrawAmount">-</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-600 font-medium">Tanggal</td>
                                <td class="py-1.5 sm:py-2 px-2 sm:px-3 text-gray-900 font-semibold break-words" id="rejectWithdrawDate">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Rejection Reason Input -->
            <form id="rejectWithdrawForm" method="POST" action="">
                @csrf
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="dec_reject" id="rejectReason" rows="3" 
                              class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-xs sm:text-sm resize-none"
                              placeholder="Masukkan alasan penolakan..." required></textarea>
                    <p class="text-xs text-red-500 mt-1 hidden" id="rejectReasonError">Mohon masukkan alasan penolakan</p>
                </div>
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-2 sm:gap-3 px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 rounded-b-2xl border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeRejectWithdrawModal()" 
                    class="px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="submit" form="rejectWithdrawForm"
                    class="px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-times mr-1 sm:mr-2"></i>
                <span class="hidden sm:inline">Ya, Tolak</span>
                <span class="sm:hidden">Tolak</span>
            </button>
        </div>
    </div>
</div>

<script>
let currentWithdrawData = null;

// Approve Modal Functions
function openApproveWithdrawModal(withdrawId, withdrawData) {
    currentWithdrawData = { id: withdrawId, ...withdrawData };
    
    // Set form action
    const form = document.getElementById('approveWithdrawForm');
    form.action = '{{ route("withdraw.approve", ":id") }}'.replace(':id', withdrawId);
    
    // Set withdraw details in table
    document.getElementById('approveWithdrawName').textContent = withdrawData.nama || '-';
    document.getElementById('approveWithdrawMerchant').textContent = withdrawData.merchant || '-';
    document.getElementById('approveWithdrawMethod').textContent = withdrawData.method || '-';
    document.getElementById('approveWithdrawAccount').textContent = withdrawData.account || '-';
    document.getElementById('approveWithdrawAmount').textContent = withdrawData.amount || '-';
    document.getElementById('approveWithdrawDate').textContent = withdrawData.date || '-';
    
    // Show modal
    const modal = document.getElementById('approveWithdrawModal');
    const modalContent = document.getElementById('approveWithdrawContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeApproveWithdrawModal() {
    const modal = document.getElementById('approveWithdrawModal');
    const modalContent = document.getElementById('approveWithdrawContent');
    
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
        currentWithdrawData = null;
    }, 300);
}

// Reject Modal Functions
function openRejectWithdrawModal(withdrawId, withdrawData) {
    currentWithdrawData = { id: withdrawId, ...withdrawData };
    
    // Set form action
    const form = document.getElementById('rejectWithdrawForm');
    form.action = '{{ route("withdraw.reject", ":id") }}'.replace(':id', withdrawId);
    
    // Reset textarea
    const textarea = document.getElementById('rejectReason');
    textarea.value = '';
    document.getElementById('rejectReasonError').classList.add('hidden');
    
    // Set withdraw details in table
    document.getElementById('rejectWithdrawName').textContent = withdrawData.nama || '-';
    document.getElementById('rejectWithdrawMerchant').textContent = withdrawData.merchant || '-';
    document.getElementById('rejectWithdrawMethod').textContent = withdrawData.method || '-';
    document.getElementById('rejectWithdrawAccount').textContent = withdrawData.account || '-';
    document.getElementById('rejectWithdrawAmount').textContent = withdrawData.amount || '-';
    document.getElementById('rejectWithdrawDate').textContent = withdrawData.date || '-';
    
    // Show modal
    const modal = document.getElementById('rejectWithdrawModal');
    const modalContent = document.getElementById('rejectWithdrawContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeRejectWithdrawModal() {
    const modal = document.getElementById('rejectWithdrawModal');
    const modalContent = document.getElementById('rejectWithdrawContent');
    
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
        currentWithdrawData = null;
    }, 300);
}

// Handle reject form submission
document.getElementById('rejectWithdrawForm')?.addEventListener('submit', function(e) {
    const textarea = document.getElementById('rejectReason');
    const errorMsg = document.getElementById('rejectReasonError');
    
    if (!textarea || !textarea.value.trim()) {
        e.preventDefault();
        errorMsg.classList.remove('hidden');
        textarea.focus();
        return false;
    }
    
    errorMsg.classList.add('hidden');
});

// Close modals when clicking outside
document.getElementById('approveWithdrawModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeApproveWithdrawModal();
    }
});

document.getElementById('rejectWithdrawModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectWithdrawModal();
    }
});
</script>

<!-- Success Message Modal -->
<div id="withdrawSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="withdrawSuccessContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                <div id="successHeaderIcon" class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i id="successHeaderIconI" class="fas fa-check text-green-600 text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 id="successHeaderTitle" class="text-base sm:text-lg font-semibold text-gray-900 truncate">Berhasil!</h3>
                    <p id="successHeaderDesc" class="text-xs sm:text-sm text-gray-500 truncate">Operasi berhasil dilakukan</p>
                </div>
            </div>
            <button onclick="closeWithdrawSuccessModal()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors ml-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-4 py-3 sm:px-6 sm:py-4">
            <div class="text-center">
                <div id="successBodyIcon" class="mx-auto flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-3 sm:mb-4">
                    <i id="successBodyIconI" class="fas fa-check-circle text-green-600 text-2xl sm:text-3xl"></i>
                </div>
                <h4 id="successBodyTitle" class="text-base sm:text-lg font-medium text-gray-900 mb-1 sm:mb-2">Berhasil!</h4>
                <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 break-words" id="withdrawSuccessMessage">
                    Operasi berhasil dilakukan.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 rounded-b-2xl border-t border-gray-200 flex-shrink-0">
            <button id="successOkButton" onclick="closeWithdrawSuccessModal()" class="px-4 py-1.5 sm:px-6 sm:py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i id="successOkButtonI" class="fas fa-check mr-1 sm:mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<!-- Error Message Modal -->
<div id="withdrawErrorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="withdrawErrorContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">Error!</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">Terjadi kesalahan</p>
                </div>
            </div>
            <button onclick="closeWithdrawErrorModal()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors ml-2">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-4 py-3 sm:px-6 sm:py-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-3 sm:mb-4">
                    <i class="fas fa-exclamation-circle text-red-600 text-2xl sm:text-3xl"></i>
                </div>
                <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-1 sm:mb-2">Terjadi Kesalahan</h4>
                <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 break-words" id="withdrawErrorMessage">
                    Terjadi kesalahan saat memproses permintaan.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 rounded-b-2xl border-t border-gray-200 flex-shrink-0">
            <button onclick="closeWithdrawErrorModal()" class="px-4 py-1.5 sm:px-6 sm:py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-times mr-1 sm:mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<script>
// Success Modal Functions
function showWithdrawSuccessModal(message) {
    const modal = document.getElementById('withdrawSuccessModal');
    const modalContent = document.getElementById('withdrawSuccessContent');
    const messageElement = document.getElementById('withdrawSuccessMessage');
    
    // Check if message is about rejection
    const isReject = message && (message.toLowerCase().includes('ditolak') || message.toLowerCase().includes('reject'));
    
    if (messageElement) {
        messageElement.textContent = message || 'Operasi berhasil dilakukan.';
    }
    
    // Get elements by ID
    const headerIcon = document.getElementById('successHeaderIcon');
    const headerIconI = document.getElementById('successHeaderIconI');
    const headerTitle = document.getElementById('successHeaderTitle');
    const headerDesc = document.getElementById('successHeaderDesc');
    const bodyIcon = document.getElementById('successBodyIcon');
    const bodyIconI = document.getElementById('successBodyIconI');
    const bodyTitle = document.getElementById('successBodyTitle');
    const okButton = document.getElementById('successOkButton');
    const okButtonI = document.getElementById('successOkButtonI');
    
    // Update colors based on message type
    if (isReject) {
        // Change to red theme for reject success
        if (headerIcon) {
            headerIcon.className = 'flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center';
        }
        if (headerIconI) {
            headerIconI.className = 'fas fa-times text-red-600 text-lg';
        }
        if (bodyIcon) {
            bodyIcon.className = 'mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-4';
        }
        if (bodyIconI) {
            bodyIconI.className = 'fas fa-times text-red-600 text-3xl';
        }
        if (headerTitle) {
            headerTitle.textContent = 'Ditolak!';
        }
        if (headerDesc) {
            headerDesc.textContent = 'Penarikan berhasil ditolak';
        }
        if (bodyTitle) {
            bodyTitle.textContent = 'Ditolak!';
        }
        if (okButton) {
            okButton.className = 'px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 rounded-lg hover:shadow-lg transition-all duration-300';
        }
        if (okButtonI) {
            okButtonI.className = 'fas fa-times mr-2';
        }
    } else {
        // Reset to green theme for approve success
        if (headerIcon) {
            headerIcon.className = 'flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center';
        }
        if (headerIconI) {
            headerIconI.className = 'fas fa-check text-green-600 text-lg';
        }
        if (bodyIcon) {
            bodyIcon.className = 'mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4';
        }
        if (bodyIconI) {
            bodyIconI.className = 'fas fa-check-circle text-green-600 text-3xl';
        }
        if (headerTitle) {
            headerTitle.textContent = 'Berhasil!';
        }
        if (headerDesc) {
            headerDesc.textContent = 'Operasi berhasil dilakukan';
        }
        if (bodyTitle) {
            bodyTitle.textContent = 'Berhasil!';
        }
        if (okButton) {
            okButton.className = 'px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:shadow-lg transition-all duration-300';
        }
        if (okButtonI) {
            okButtonI.className = 'fas fa-check mr-2';
        }
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeWithdrawSuccessModal() {
    const modal = document.getElementById('withdrawSuccessModal');
    const modalContent = document.getElementById('withdrawSuccessContent');
    
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

// Error Modal Functions
function showWithdrawErrorModal(message) {
    const modal = document.getElementById('withdrawErrorModal');
    const modalContent = document.getElementById('withdrawErrorContent');
    const messageElement = document.getElementById('withdrawErrorMessage');
    
    if (messageElement) {
        messageElement.textContent = message || 'Terjadi kesalahan saat memproses permintaan.';
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }
    }, 10);
}

function closeWithdrawErrorModal() {
    const modal = document.getElementById('withdrawErrorModal');
    const modalContent = document.getElementById('withdrawErrorContent');
    
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

// Close modals when clicking outside
document.getElementById('withdrawSuccessModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeWithdrawSuccessModal();
    }
});

document.getElementById('withdrawErrorModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeWithdrawErrorModal();
    }
});
</script>

