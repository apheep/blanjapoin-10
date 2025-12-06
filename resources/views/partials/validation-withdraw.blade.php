<!-- Approve Withdraw Validation Modal -->
<div id="approveWithdrawModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="approveWithdrawContent">
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
            <button onclick="closeApproveWithdrawModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Setujui Penarikan?</h4>
                <p class="text-sm text-gray-600 mb-4" id="approveWithdrawMessage">
                    Apakah Anda yakin ingin menyetujui penarikan ini?
                </p>
            </div>
            
            <!-- Withdraw Details Table -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium w-1/3">Nama</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="approveWithdrawName">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Merchant</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="approveWithdrawMerchant">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Metode</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="approveWithdrawMethod">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">No. Rek/E-Wallet</td>
                                <td class="py-2 px-3 text-gray-900 font-mono font-semibold" id="approveWithdrawAccount">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Jumlah</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="approveWithdrawAmount">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Tanggal</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="approveWithdrawDate">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeApproveWithdrawModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="approveWithdrawForm" method="POST" action="" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-check-circle mr-2"></i>
                    Ya, Approve
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reject Withdraw Validation Modal -->
<div id="rejectWithdrawModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full my-4 transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[90vh]" id="rejectWithdrawContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tolak Withdraw Request</h3>
                    <p class="text-sm text-gray-500">Masukkan alasan penolakan</p>
                </div>
            </div>
            <button onclick="closeRejectWithdrawModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1">
            <div class="text-center mb-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-4">
                    <i class="fas fa-times text-red-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Tolak Penarikan?</h4>
                <p class="text-sm text-gray-600 mb-4">
                    Apakah Anda yakin ingin menolak penarikan ini?
                </p>
            </div>
            
            <!-- Withdraw Details Table -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium w-1/3">Nama</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="rejectWithdrawName">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Merchant</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="rejectWithdrawMerchant">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Metode</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="rejectWithdrawMethod">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">No. Rek/E-Wallet</td>
                                <td class="py-2 px-3 text-gray-900 font-mono font-semibold" id="rejectWithdrawAccount">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Jumlah</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="rejectWithdrawAmount">-</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 text-gray-600 font-medium">Tanggal</td>
                                <td class="py-2 px-3 text-gray-900 font-semibold" id="rejectWithdrawDate">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Rejection Reason Input -->
            <form id="rejectWithdrawForm" method="POST" action="">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="dec_reject" id="rejectReason" rows="4" 
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm resize-none"
                              placeholder="Masukkan alasan penolakan..." required></textarea>
                    <p class="text-xs text-red-500 mt-1 hidden" id="rejectReasonError">Mohon masukkan alasan penolakan</p>
                </div>
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 rounded-b-2xl border-t border-gray-200 flex-shrink-0">
            <button type="button" onclick="closeRejectWithdrawModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="submit" form="rejectWithdrawForm"
                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-times mr-2"></i>
                Ya, Tolak
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
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div id="successHeaderIcon" class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <i id="successHeaderIconI" class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 id="successHeaderTitle" class="text-lg font-semibold text-gray-900">Berhasil!</h3>
                    <p id="successHeaderDesc" class="text-sm text-gray-500">Operasi berhasil dilakukan</p>
                </div>
            </div>
            <button onclick="closeWithdrawSuccessModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div id="successBodyIcon" class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 mb-4">
                    <i id="successBodyIconI" class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
                <h4 id="successBodyTitle" class="text-lg font-medium text-gray-900 mb-2">Berhasil!</h4>
                <p class="text-sm text-gray-600 mb-6" id="withdrawSuccessMessage">
                    Operasi berhasil dilakukan.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button id="successOkButton" onclick="closeWithdrawSuccessModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i id="successOkButtonI" class="fas fa-check mr-2"></i>
                OK
            </button>
        </div>
    </div>
</div>

<!-- Error Message Modal -->
<div id="withdrawErrorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="withdrawErrorContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Error!</h3>
                    <p class="text-sm text-gray-500">Terjadi kesalahan</p>
                </div>
            </div>
            <button onclick="closeWithdrawErrorModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-red-100 to-rose-100 mb-4">
                    <i class="fas fa-exclamation-circle text-red-600 text-3xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Terjadi Kesalahan</h4>
                <p class="text-sm text-gray-600 mb-6" id="withdrawErrorMessage">
                    Terjadi kesalahan saat memproses permintaan.
                </p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-center px-6 py-4 bg-gray-50 rounded-b-2xl">
            <button onclick="closeWithdrawErrorModal()" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-rose-600 rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-times mr-2"></i>
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

