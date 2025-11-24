<!-- Custom Date Picker for Upload Modal -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
        <div class="relative">
            <input type="text" 
                   id="startDateUpload" 
                   name="start_date"
                   placeholder="DD/MM/YYYY"
                   autocomplete="off"
                   readonly
                   onclick="openDatePickerUpload('start')"
                   class="w-full px-4 pr-10 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm bg-white cursor-pointer">
            <button type="button" 
                    onclick="openDatePickerUpload('start')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 transition-colors cursor-pointer z-10 p-1">
                <i class="fas fa-calendar-alt"></i>
            </button>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
        <div class="relative">
            <input type="text" 
                   id="endDateUpload" 
                   name="end_date"
                   placeholder="DD/MM/YYYY"
                   autocomplete="off"
                   readonly
                   onclick="openDatePickerUpload('end')"
                   class="w-full px-4 pr-10 h-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm bg-white cursor-pointer">
            <button type="button" 
                    onclick="openDatePickerUpload('end')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 transition-colors cursor-pointer z-10 p-1">
                <i class="fas fa-calendar-alt"></i>
            </button>
        </div>
    </div>
</div>

<!-- Calendar Modal -->
<div id="datePickerModalUpload" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="datePickerContentUpload">
        <div class="p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pilih Tanggal</h3>
                <button onclick="closeDatePickerUpload()" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="calendarUpload" class="space-y-3"></div>
            
            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                <button type="button" onclick="setTodayUpload(); return false;" class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Today
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let uploadCalendarState = {
    currentMonth: new Date().getMonth(),
    currentYear: new Date().getFullYear(),
    activeType: 'start',
    startDate: null,
    endDate: null
};

function openDatePickerUpload(type) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    uploadCalendarState.activeType = type;
    
    // If selecting end date and start date is already selected, show start date's month
    if (type === 'end' && uploadCalendarState.startDate) {
        uploadCalendarState.currentMonth = uploadCalendarState.startDate.getMonth();
        uploadCalendarState.currentYear = uploadCalendarState.startDate.getFullYear();
    }
    
    const modal = document.getElementById('datePickerModalUpload');
    const content = document.getElementById('datePickerContentUpload');
    
    if (!modal || !content) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        content.style.opacity = '1';
        content.style.transform = 'scale(1)';
    });
    
    renderCalendarUpload();
}

// Make function globally available
window.openDatePickerUpload = openDatePickerUpload;

function closeDatePickerUpload() {
    const modal = document.getElementById('datePickerModalUpload');
    const content = document.getElementById('datePickerContentUpload');
    
    if (!modal || !content) return;
    
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function renderCalendarUpload() {
    const container = document.getElementById('calendarUpload');
    if (!container) return;
    
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const daysOfWeek = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
    
    const firstDay = new Date(uploadCalendarState.currentYear, uploadCalendarState.currentMonth, 1);
    const lastDay = new Date(uploadCalendarState.currentYear, uploadCalendarState.currentMonth + 1, 0);
    const startDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
    const daysInMonth = lastDay.getDate();
    
    let html = `
        <div class="flex items-center justify-between mb-3">
            <button type="button" onclick="changeMonthUpload(-1); return false;" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <div class="text-base font-semibold text-gray-800">${months[uploadCalendarState.currentMonth]} ${uploadCalendarState.currentYear}</div>
            <button type="button" onclick="changeMonthUpload(1); return false;" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-chevron-right text-gray-600"></i>
            </button>
        </div>
        <div class="grid grid-cols-7 gap-1 mb-2">
    `;
    
    daysOfWeek.forEach(day => {
        html += `<div class="text-center text-xs font-semibold text-gray-500 py-1">${day}</div>`;
    });
    
    html += `</div><div class="grid grid-cols-7 gap-1">`;
    
    for (let i = 0; i < startDayOfWeek; i++) {
        html += `<div></div>`;
    }
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Check if selecting end date and start date is already selected
    const isSelectingEndDate = uploadCalendarState.activeType === 'end';
    const minDate = isSelectingEndDate && uploadCalendarState.startDate ? uploadCalendarState.startDate : today;
    
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = new Date(uploadCalendarState.currentYear, uploadCalendarState.currentMonth, day);
        currentDate.setHours(0, 0, 0, 0);
        
        const isToday = currentDate.getTime() === today.getTime();
        const isPast = currentDate.getTime() < minDate.getTime();
        const isSelected = isDateSelectedUpload(currentDate);
        
        // Check if this is the start date when selecting end date
        const isStartDate = isSelectingEndDate && uploadCalendarState.startDate && 
                           formatDateForInputUpload(currentDate) === formatDateForInputUpload(uploadCalendarState.startDate);
        
        let dayClass = 'text-center text-sm py-2 rounded-lg transition-all aspect-square flex items-center justify-center ';
        
        if (isPast) {
            // Disable past dates or dates before start date
            dayClass += 'text-gray-300 cursor-not-allowed bg-gray-50';
            html += `<div class="${dayClass}">${day}</div>`;
        } else if (isSelected) {
            dayClass += 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold cursor-pointer';
            html += `<div class="${dayClass}" onclick="selectDateUpload(${day}); return false;">${day}</div>`;
        } else if (isStartDate) {
            // Highlight start date when selecting end date
            dayClass += 'bg-blue-100 text-blue-700 font-semibold cursor-pointer border-2 border-blue-400';
            html += `<div class="${dayClass}" onclick="selectDateUpload(${day}); return false;">${day}</div>`;
        } else if (isToday) {
            dayClass += 'bg-orange-100 text-orange-700 font-medium cursor-pointer hover:bg-orange-200';
            html += `<div class="${dayClass}" onclick="selectDateUpload(${day}); return false;">${day}</div>`;
        } else {
            dayClass += 'text-gray-700 hover:bg-orange-50 hover:text-orange-600 cursor-pointer';
            html += `<div class="${dayClass}" onclick="selectDateUpload(${day}); return false;">${day}</div>`;
        }
    }
    
    html += `</div>`;
    container.innerHTML = html;
}

function changeMonthUpload(delta) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    uploadCalendarState.currentMonth += delta;
    if (uploadCalendarState.currentMonth > 11) {
        uploadCalendarState.currentMonth = 0;
        uploadCalendarState.currentYear++;
    }
    if (uploadCalendarState.currentMonth < 0) {
        uploadCalendarState.currentMonth = 11;
        uploadCalendarState.currentYear--;
    }
    renderCalendarUpload();
}

function selectDateUpload(day) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const selectedDate = new Date(uploadCalendarState.currentYear, uploadCalendarState.currentMonth, day);
    selectedDate.setHours(0, 0, 0, 0);
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Prevent selecting past dates
    if (selectedDate.getTime() < today.getTime()) {
        return;
    }
    
    const formattedDate = formatDateForDisplayUpload(selectedDate);
    const dateValue = formatDateForInputUpload(selectedDate);
    
    if (uploadCalendarState.activeType === 'start') {
        uploadCalendarState.startDate = selectedDate;
        document.getElementById('startDateUpload').value = formattedDate;
        
        // Update hidden input for form submission
        let hiddenInput = document.getElementById('startDateHiddenUpload');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'startDateHiddenUpload';
            hiddenInput.name = 'start_date';
            document.getElementById('startDateUpload').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = dateValue;
        
        // Validate date range
        if (uploadCalendarState.endDate && selectedDate > uploadCalendarState.endDate) {
            uploadCalendarState.endDate = null;
            document.getElementById('endDateUpload').value = '';
            const endHidden = document.getElementById('endDateHiddenUpload');
            if (endHidden) endHidden.value = '';
        }
    } else {
        // For end date, must be >= start date if start date is selected
        if (uploadCalendarState.startDate && selectedDate < uploadCalendarState.startDate) {
            alert('Tanggal akhir tidak boleh sebelum tanggal mulai');
            return;
        }
        // Also check if end date is before today
        if (selectedDate.getTime() < today.getTime()) {
            return;
        }
        uploadCalendarState.endDate = selectedDate;
        document.getElementById('endDateUpload').value = formattedDate;
        
        // Update hidden input for form submission
        let hiddenInput = document.getElementById('endDateHiddenUpload');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'endDateHiddenUpload';
            hiddenInput.name = 'end_date';
            document.getElementById('endDateUpload').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = dateValue;
    }
    
    renderCalendarUpload();
    validateDateRange();
    
    // Auto close modal after selecting date
    closeDatePickerUpload();
}

function isDateSelectedUpload(date) {
    const dateStr = formatDateForInputUpload(date);
    if (uploadCalendarState.activeType === 'start' && uploadCalendarState.startDate) {
        return formatDateForInputUpload(uploadCalendarState.startDate) === dateStr;
    }
    if (uploadCalendarState.activeType === 'end' && uploadCalendarState.endDate) {
        return formatDateForInputUpload(uploadCalendarState.endDate) === dateStr;
    }
    return false;
}

function formatDateForDisplayUpload(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function formatDateForInputUpload(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function setTodayUpload() {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    selectDateUpload(today.getDate());
    // selectDateUpload already closes the modal, so no need to call closeDatePickerUpload again
}

// Format date input as user types (DD/MM/YYYY)
function formatDateInput(input, type) {
    let value = input.value.replace(/\D/g, ''); // Remove non-digits
    
    if (value.length > 8) {
        value = value.substring(0, 8);
    }
    
    // Format as DD/MM/YYYY
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    if (value.length >= 5) {
        value = value.substring(0, 5) + '/' + value.substring(5);
    }
    
    input.value = value;
    
    // Update hidden input and state
    updateDateFromInput(type, value);
}

// Validate and convert date input
function validateDateInput(input, type) {
    const value = input.value.trim();
    
    if (!value) {
        // Clear if empty
        if (type === 'start') {
            uploadCalendarState.startDate = null;
            const hidden = document.getElementById('startDateHiddenUpload');
            if (hidden) hidden.value = '';
        } else {
            uploadCalendarState.endDate = null;
            const hidden = document.getElementById('endDateHiddenUpload');
            if (hidden) hidden.value = '';
        }
        validateDateRange();
        return;
    }
    
    // Parse DD/MM/YYYY format
    const parts = value.split('/');
    if (parts.length !== 3) {
        input.classList.add('border-red-500');
        return;
    }
    
    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1; // Month is 0-indexed
    const year = parseInt(parts[2], 10);
    
    if (isNaN(day) || isNaN(month) || isNaN(year)) {
        input.classList.add('border-red-500');
        return;
    }
    
    const date = new Date(year, month, day);
    
    // Validate date
    if (date.getDate() !== day || date.getMonth() !== month || date.getFullYear() !== year) {
        input.classList.add('border-red-500');
        return;
    }
    
    input.classList.remove('border-red-500');
    
    // Update state and hidden input
    if (type === 'start') {
        uploadCalendarState.startDate = date;
        let hiddenInput = document.getElementById('startDateHiddenUpload');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'startDateHiddenUpload';
            hiddenInput.name = 'start_date';
            document.getElementById('startDateUpload').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = formatDateForInputUpload(date);
        
        // Validate date range
        if (uploadCalendarState.endDate && date > uploadCalendarState.endDate) {
            uploadCalendarState.endDate = null;
            document.getElementById('endDateUpload').value = '';
            const endHidden = document.getElementById('endDateHiddenUpload');
            if (endHidden) endHidden.value = '';
        }
    } else {
        if (uploadCalendarState.startDate && date < uploadCalendarState.startDate) {
            input.classList.add('border-red-500');
            alert('Tanggal akhir tidak boleh sebelum tanggal mulai');
            return;
        }
        uploadCalendarState.endDate = date;
        let hiddenInput = document.getElementById('endDateHiddenUpload');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'endDateHiddenUpload';
            hiddenInput.name = 'end_date';
            document.getElementById('endDateUpload').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = formatDateForInputUpload(date);
    }
    
    validateDateRange();
}

// Update date from input value
function updateDateFromInput(type, value) {
    if (!value || value.length < 10) return;
    
    const parts = value.split('/');
    if (parts.length !== 3) return;
    
    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1;
    const year = parseInt(parts[2], 10);
    
    if (isNaN(day) || isNaN(month) || isNaN(year)) return;
    
    const date = new Date(year, month, day);
    
    if (date.getDate() !== day || date.getMonth() !== month || date.getFullYear() !== year) return;
    
    if (type === 'start') {
        uploadCalendarState.startDate = date;
        let hiddenInput = document.getElementById('startDateHiddenUpload');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'startDateHiddenUpload';
            hiddenInput.name = 'start_date';
            document.getElementById('startDateUpload').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = formatDateForInputUpload(date);
    } else {
        uploadCalendarState.endDate = date;
        let hiddenInput = document.getElementById('endDateHiddenUpload');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'endDateHiddenUpload';
            hiddenInput.name = 'end_date';
            document.getElementById('endDateUpload').parentElement.appendChild(hiddenInput);
        }
        hiddenInput.value = formatDateForInputUpload(date);
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('datePickerModalUpload');
    const content = document.getElementById('datePickerContentUpload');
    
    // Don't close if clicking inside modal content or on calendar buttons
    if (modal && content) {
        const isClickInsideContent = content.contains(event.target);
        const isClickOnDateInput = event.target.closest('#startDateUpload') || event.target.closest('#endDateUpload');
        const isClickOnCalendarButton = event.target.closest('button[onclick*="changeMonthUpload"]') || 
                                        event.target.closest('button[onclick*="selectDateUpload"]') ||
                                        event.target.closest('button[onclick*="setTodayUpload"]') ||
                                        event.target.closest('button[onclick*="closeDatePickerUpload"]');
        
        if (!isClickInsideContent && !isClickOnDateInput && !isClickOnCalendarButton) {
            if (!modal.classList.contains('hidden')) {
                closeDatePickerUpload();
            }
        }
    }
});
</script>

