<!-- Custom Date Picker for Edit Modal -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
        <div class="relative">
            <input type="text" 
                   id="editStartDate" 
                   name="start_date"
                   placeholder="DD/MM/YYYY"
                   autocomplete="off"
                   readonly
                   onclick="openDatePickerEdit('start')"
                   class="w-full px-4 pr-10 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm bg-white cursor-pointer">
            <button type="button" 
                    onclick="openDatePickerEdit('start')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 transition-colors cursor-pointer z-10 p-1">
                <i class="fas fa-calendar-alt"></i>
            </button>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
        <div class="relative">
            <input type="text" 
                   id="editEndDate" 
                   name="end_date"
                   placeholder="DD/MM/YYYY"
                   autocomplete="off"
                   readonly
                   onclick="openDatePickerEdit('end')"
                   class="w-full px-4 pr-10 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm bg-white cursor-pointer">
            <button type="button" 
                    onclick="openDatePickerEdit('end')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 transition-colors cursor-pointer z-10 p-1">
                <i class="fas fa-calendar-alt"></i>
            </button>
        </div>
    </div>
</div>

<!-- Calendar Modal -->
<div id="datePickerModalEdit" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="datePickerContentEdit">
        <div class="p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pilih Tanggal</h3>
                <button onclick="closeDatePickerEdit()" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="calendarEdit" class="space-y-3"></div>
            
            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                <button type="button" onclick="setTodayEdit(); return false;" class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Today
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function setTodayEdit() {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    selectDateEdit(today.getDate());
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('datePickerModalEdit');
    const content = document.getElementById('datePickerContentEdit');
    
    if (modal && content) {
        const isClickInsideContent = content.contains(event.target);
        const isClickOnDateInput = event.target.closest('#editStartDate') || event.target.closest('#editEndDate');
        const isClickOnCalendarButton = event.target.closest('button[onclick*="changeMonthEdit"]') || 
                                        event.target.closest('button[onclick*="selectDateEdit"]') ||
                                        event.target.closest('button[onclick*="setTodayEdit"]') ||
                                        event.target.closest('button[onclick*="closeDatePickerEdit"]');
        
        if (!isClickInsideContent && !isClickOnDateInput && !isClickOnCalendarButton) {
            if (!modal.classList.contains('hidden')) {
                closeDatePickerEdit();
            }
        }
    }
});
</script>

