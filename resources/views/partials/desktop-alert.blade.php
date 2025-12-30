  <!-- Desktop Alert Modal -->
  <div id="desktopAlertModal" class="fixed inset-0 z-[10000] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closeDesktopModal()"></div>
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm text-center transform transition-all duration-300 scale-95 opacity-0" id="desktopAlertContent">
        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M12 2a10 10 0 100 20 10 10 0 000-20zM12 6v6" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-neutral-800 mb-2">Akses Terbatas</h3>
        <p class="text-neutral-600 mb-6 text-sm">Fitur redeem hanya tersedia untuk pengguna smartphone. Silakan buka website ini melalui HP Anda.</p>
        <button onclick="closeDesktopModal()" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold hover:shadow-lg transition-all active:scale-95 cursor-pointer">
            OK, Mengerti
        </button>
    </div>
  </div>

  <script>
    function openDesktopModal() {
        const modal = document.getElementById('desktopAlertModal');
        const content = document.getElementById('desktopAlertContent');
        if (modal && content) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    function closeDesktopModal() {
        const modal = document.getElementById('desktopAlertModal');
        const content = document.getElementById('desktopAlertContent');
        if (modal && content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }
  </script>