<!-- Validation/Confirmation Modal -->
<div id="validationReceiptModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 opacity-0 scale-95 flex flex-col max-h-[90vh]">
        <!-- Close Button -->
        <button onclick="closeValidationModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Header - Fixed -->
        <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center justify-center mb-4">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                    <i class="fas fa-question-circle text-3xl text-blue-600"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center">Konfirmasi Penarikan</h3>
            <p class="text-sm text-gray-600 text-center mt-2">Pastikan data penarikan sudah benar</p>
        </div>

        <!-- Content - Scrollable -->
        <div class="p-6 overflow-y-auto flex-1 min-h-0">
            <!-- Confirmation Details -->
            <div class="space-y-3">
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Nama</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4" id="validationName">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Jumlah Penarikan</span>
                    <span class="text-base font-bold text-gray-900 text-right" id="validationAmount">Rp 0</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Metode Penarikan</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4" id="validationMethod">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100" id="validationAccountContainer">
                    <span class="text-sm font-medium text-gray-600" id="validationAccountLabel">Nomor Rekening</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4 break-all" id="validationAccount">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Tanggal</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4" id="validationDate">-</span>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-lg mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800 mb-1">Perhatian!</p>
                            <p class="text-xs text-yellow-700">Pastikan nomor rekening/e-wallet sudah benar. Penarikan tidak dapat dibatalkan setelah dikonfirmasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer - Fixed -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex-shrink-0">
            <div class="flex gap-3">
                <button onclick="closeValidationModal()" class="flex-1 py-2.5 px-4 bg-gray-200 text-gray-700 font-medium rounded-full hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button onclick="confirmWithdraw()" class="flex-1 py-2.5 px-4 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium rounded-full hover:shadow-lg transition-all">
                    Setuju & Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

