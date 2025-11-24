<!-- Edit Validation Modal -->
<div id="editValidationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black opacity-0 transition-opacity duration-300 ease-out"></div>
    <div id="editValidationContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out scale-95 opacity-0 flex flex-col">
        <div class="flex items-start justify-between px-6 py-4 border-b">
            <div>
                <p class="text-sm text-gray-500">Verifikasi Data</p>
                <h3 class="text-lg font-semibold text-gray-800">Pastikan data sudah benar</h3>
            </div>
            <button type="button" onclick="closeEditValidationModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="px-6 py-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-question text-blue-500"></i>
                </div>
            </div>
            <h4 class="text-center text-base font-semibold mb-2">Konfirmasi Update</h4>
            <p id="editValidationMessage" class="text-center text-sm text-gray-600">
                Apakah Anda yakin data yang Anda masukkan sudah benar dan ingin melanjutkan update <span id="editValidationEntity">Keyword</span>?
            </p>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t">
            <button type="button" onclick="closeEditValidationModal()" class="px-5 py-2.5 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
            <button type="button" onclick="confirmEditValidation()" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg">Ya, Update</button>
        </div>
    </div>
</div>

<script>
let pendingEditData = null;
let pendingEditEntity = 'Keyword';

function showEditValidation(data, entityLabel = 'Keyword') {
    pendingEditData = data || null;
    pendingEditEntity = entityLabel || 'Keyword';
    const modal = document.getElementById('editValidationModal');
    const content = document.getElementById('editValidationContent');
    const backdrop = modal ? modal.querySelector('.fixed') : null;
    const entitySpan = document.getElementById('editValidationEntity');
    const message = document.getElementById('editValidationMessage');

    if (!modal || !content) return;
    if (entitySpan) entitySpan.textContent = pendingEditEntity;
    if (message) message.innerHTML = `Apakah Anda yakin data yang Anda masukkan sudah benar dan ingin melanjutkan update <span class=\"font-semibold\">${pendingEditEntity}</span>?`;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => { if (backdrop) backdrop.style.opacity = '0.5'; }, 10);
    setTimeout(() => { content.style.transform = 'scale(1)'; content.style.opacity = '1'; }, 50);
}

function closeEditValidationModal() {
    const modal = document.getElementById('editValidationModal');
    const content = document.getElementById('editValidationContent');
    const backdrop = modal ? modal.querySelector('.fixed') : null;
    if (!modal || !content) return;

    content.style.transform = 'scale(0.95)';
    content.style.opacity = '0';
    if (backdrop) backdrop.style.opacity = '0';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        pendingEditData = null;
    }, 300);
}

function confirmEditValidation() {
    // Default behaviour: submit the Keyword edit form
    const form = document.getElementById('formEditKeyword');
    if (form) {
        closeEditValidationModal();
        form.submit();
        return;
    }
    // Fallback: just close
    closeEditValidationModal();
}

// Close when clicking backdrop
(function() {
    const modal = document.getElementById('editValidationModal');
    if (!modal) return;
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeEditValidationModal();
        }
    });
})();
</script>