<!-- Receipt Modal - Success -->
<div id="receiptSuccessModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 opacity-0 scale-95 flex flex-col max-h-[90vh]">
        <!-- Close Button -->
        <button onclick="closeReceiptModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Header - Fixed -->
        <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center justify-center mb-4">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center">Pengajuan Berhasil</h3>
            <p class="text-sm text-gray-600 text-center mt-2">Pengajuan penarikan saldo telah berhasil diajukan</p>
        </div>

        <!-- Content - Scrollable -->
        <div class="p-6 overflow-y-auto flex-1 min-h-0">
            <!-- Receipt Details -->
            <div class="space-y-3">
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Nama</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4" id="receiptNameSuccess">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Jumlah Withdraw</span>
                    <span class="text-base font-bold text-gray-900 text-right" id="receiptAmountSuccess">Rp 0</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Metode Pembayaran</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4" id="receiptMethodSuccess">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100" id="receiptAccountSuccessContainer">
                    <span class="text-sm font-medium text-gray-600" id="receiptAccountLabelSuccess">Nomor Rekening</span>
                    <span class="text-sm font-semibold text-gray-900 text-right ml-4 break-all" id="receiptAccountSuccess">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Status</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold whitespace-nowrap">
                        <i class="fas fa-check-circle mr-1"></i>
                        Berhasil Diajukan
                    </span>
                </div>
                
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Waktu Pengajuan</span>
                    <span class="text-sm text-gray-700 text-right ml-4" id="receiptTimeSuccess">-</span>
                </div>
                
                <div class="flex justify-between items-start py-2">
                    <span class="text-sm font-medium text-gray-600">No. Transaksi</span>
                    <span class="text-sm font-mono text-gray-700 text-right ml-4 break-all" id="receiptTransactionId">-</span>
                </div>
            </div>

            <!-- Success Message -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                    <p class="text-sm font-medium text-green-800">Pengajuan penarikan saldo telah berhasil diajukan</p>
                    <p class="text-xs text-green-700 mt-1">Tim kami akan memproses pengajuan Anda segera</p>
                </div>
            </div>
        </div>

        <!-- Footer - Fixed -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex-shrink-0">
            <button onclick="closeReceiptModal()" class="w-full py-2.5 px-4 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium rounded-full hover:shadow-lg transition-all">
                Selesai
            </button>
        </div>
    </div>
</div>

