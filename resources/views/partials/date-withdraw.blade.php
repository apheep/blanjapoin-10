<div class="relative inline-block" style="z-index: 50;">
    <button type="button" onclick="toggleWithdrawDateFilter('{{ $filterId }}', event); return false;" class="flex items-center px-3 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
        <i class="fas fa-calendar-alt mr-2 text-xs"></i>
        <span>Date</span>
    </button>
    
    <!-- Date Filter Dropdown -->
    <div id="{{ $filterId }}" class="date-filter-dropdown hidden absolute left-0 top-full mt-2 bg-white rounded-lg shadow-lg border border-gray-200 p-3 w-64 z-[60] opacity-0 scale-95 translate-y-2 transition-all duration-200 ease-out" onclick="event.stopPropagation()" style="min-width: 256px;">
        <!-- Date Input -->
        <div class="mb-3">
            <label class="block text-xs font-medium text-gray-600 mb-2">Pilih Tanggal</label>
            <input type="text" 
                   id="dateInput{{ $filterId }}" 
                   placeholder="DD/MM/YYYY"
                   readonly
                   inputmode="none"
                   autocomplete="off"
                   onkeydown="return false;"
                   onfocus="preventDateKeyboard(event, '{{ $filterId }}')"
                   onclick="openWithdrawDateCalendar('{{ $filterId }}', this)"
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400 cursor-pointer bg-white"
                   value="">
        </div>
        
        <!-- Inline Calendar Container -->
        <div id="calendarContainer{{ $filterId }}" class="hidden">
            <div id="activeCalendar{{ $filterId }}" class="bg-gray-50 rounded-md p-2 border border-gray-200"></div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2 mt-3 pt-3 border-t border-gray-200">
            <button type="button" onclick="event.stopPropagation(); clearWithdrawDateFilter('{{ $filterId }}')" class="flex-1 px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                Clear
            </button>
            <button type="button" onclick="event.stopPropagation(); event.preventDefault(); applyWithdrawDateFilter('{{ $filterId }}'); closeWithdrawDateFilter('{{ $filterId }}'); return false;" class="flex-1 px-3 py-2 text-xs font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-md hover:shadow-sm transition-all">
                Apply
            </button>
        </div>
    </div>
</div>

<script>
    // Initialize calendar state
    if (typeof window.calendarState === 'undefined') {
        window.calendarState = {};
    }
    
    function toggleWithdrawDateFilter(filterId, event) {
        // Prevent default behavior and form submission
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const dropdown = document.getElementById(filterId);
        if (!dropdown) return false;
        
        // Close other date filters
        document.querySelectorAll("[id^='dateFilter']:not([id$='Backdrop']), [id^='withdrawDateFilter']:not([id$='Backdrop'])").forEach(dd => {
            if (dd.id !== filterId && !dd.classList.contains('hidden')) {
                dd.classList.add('opacity-0', 'scale-95', 'translate-y-2');
                setTimeout(() => {
                    dd.classList.add('hidden');
                }, 200);
            }
        });
        
        if (dropdown.classList.contains('hidden')) {
            // Open dropdown with animation
            dropdown.classList.remove('hidden');
            requestAnimationFrame(() => {
                dropdown.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                dropdown.classList.add('opacity-100', 'scale-100', 'translate-y-0');
            });
        } else {
            // Close dropdown with animation
            dropdown.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
            dropdown.classList.add('opacity-0', 'scale-95', 'translate-y-2');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }
        
        return false;
    }
    
    function closeWithdrawDateFilter(filterId) {
        const dropdown = document.getElementById(filterId);
        if (dropdown) {
            dropdown.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
            dropdown.classList.add('opacity-0', 'scale-95', 'translate-y-2');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }
    }
    
    function preventDateKeyboard(event, filterId) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    function openWithdrawDateCalendar(filterId, inputElement) {
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        const activeCalendar = document.getElementById('activeCalendar' + filterId);
        
        if (!calendarContainer || !activeCalendar) return;
        
        // Toggle calendar visibility
        if (calendarContainer.classList.contains('hidden')) {
            calendarContainer.classList.remove('hidden');
            
            // Initialize or get calendar state
            if (!window.calendarState[filterId]) {
                const inputValue = inputElement.value;
                let initialDate = new Date();
                
                if (inputValue) {
                    // Parse DD/MM/YYYY format
                    const parts = inputValue.split('/');
                    if (parts.length === 3) {
                        const day = parseInt(parts[0]);
                        const month = parseInt(parts[1]) - 1;
                        const year = parseInt(parts[2]);
                        initialDate = new Date(year, month, day);
                    }
                }
                
                window.calendarState[filterId] = {
                    currentMonth: initialDate.getMonth(),
                    currentYear: initialDate.getFullYear(),
                    selectedDate: null,
                    activeType: 'date'
                };
            }
            
            // Render calendar
            renderWithdrawDateCalendar(filterId);
        } else {
            calendarContainer.classList.add('hidden');
        }
    }
    
    function renderWithdrawDateCalendar(filterId) {
        const activeCalendar = document.getElementById('activeCalendar' + filterId);
        if (!activeCalendar) return;
        
        const state = window.calendarState[filterId];
        if (!state) return;
        
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        
        let html = '<div class="text-center mb-3">';
        html += '<div class="flex items-center justify-between mb-2">';
        html += `<button type="button" onclick="changeWithdrawCalendarMonth('${filterId}', -1)" class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-200 rounded"><i class="fas fa-chevron-left"></i></button>`;
        html += `<span class="text-xs font-semibold text-gray-800">${monthNames[state.currentMonth]} ${state.currentYear}</span>`;
        html += `<button type="button" onclick="changeWithdrawCalendarMonth('${filterId}', 1)" class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-200 rounded"><i class="fas fa-chevron-right"></i></button>`;
        html += '</div>';
        
        // Day headers
        html += '<div class="grid grid-cols-7 gap-1 mb-1">';
        dayNames.forEach(day => {
            html += `<div class="text-[10px] font-medium text-gray-500 text-center py-1">${day}</div>`;
        });
        html += '</div>';
        
        // Calendar days
        html += '<div class="grid grid-cols-7 gap-1">';
        
        const firstDay = new Date(state.currentYear, state.currentMonth, 1).getDay();
        const daysInMonth = new Date(state.currentYear, state.currentMonth + 1, 0).getDate();
        
        // Empty cells for days before month starts
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="aspect-square"></div>';
        }
        
        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(state.currentYear, state.currentMonth, day);
            const isSelected = state.selectedDate && 
                state.selectedDate.getDate() === day &&
                state.selectedDate.getMonth() === state.currentMonth &&
                state.selectedDate.getFullYear() === state.currentYear;
            
            html += `<button type="button" onclick="selectWithdrawDate('${filterId}', ${day})" class="aspect-square text-[11px] rounded hover:bg-orange-100 transition-colors ${isSelected ? 'bg-orange-500 text-white font-semibold' : 'text-gray-700 hover:text-orange-600'}">${day}</button>`;
        }
        
        html += '</div>';
        html += '</div>';
        
        activeCalendar.innerHTML = html;
    }
    
    function changeWithdrawCalendarMonth(filterId, direction) {
        const state = window.calendarState[filterId];
        if (!state) return;
        
        state.currentMonth += direction;
        if (state.currentMonth < 0) {
            state.currentMonth = 11;
            state.currentYear--;
        } else if (state.currentMonth > 11) {
            state.currentMonth = 0;
            state.currentYear++;
        }
        
        renderWithdrawDateCalendar(filterId);
    }
    
    function selectWithdrawDate(filterId, day) {
        const state = window.calendarState[filterId];
        if (!state) return;
        
        const selectedDate = new Date(state.currentYear, state.currentMonth, day);
        selectedDate.setHours(0, 0, 0, 0);
        state.selectedDate = selectedDate;
        
        // Update input field
        const input = document.getElementById('dateInput' + filterId);
        if (input) {
            const dayStr = String(day).padStart(2, '0');
            const monthStr = String(state.currentMonth + 1).padStart(2, '0');
            const yearStr = state.currentYear;
            input.value = `${dayStr}/${monthStr}/${yearStr}`;
        }
        
        // Re-render calendar to show selected date
        renderWithdrawDateCalendar(filterId);
    }
    
    function clearWithdrawDateFilter(filterId) {
        const input = document.getElementById('dateInput' + filterId);
        if (input) {
            input.value = '';
        }
        
        const state = window.calendarState[filterId];
        if (state) {
            state.selectedDate = null;
        }
        
        // Close calendar if open
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        if (calendarContainer) {
            calendarContainer.classList.add('hidden');
        }
    }
    
    function applyWithdrawDateFilter(filterId) {
        const input = document.getElementById('dateInput' + filterId);
        if (!input || !input.value.trim()) {
            // Clear filter if no date selected
            // Try to find the form (could be withdrawSearchForm or historyDateFilterForm)
            const form = document.getElementById('withdrawSearchForm') || document.getElementById('historyDateFilterForm');
            if (form) {
                const dateInput = form.querySelector('input[name="date"]');
                if (dateInput) dateInput.remove();
                form.submit();
            }
            return;
        }
        
        // Parse date from DD/MM/YYYY format
        const dateValue = input.value.trim();
        const parts = dateValue.split('/');
        
        if (parts.length !== 3) {
            alert('Format tanggal tidak valid. Gunakan format DD/MM/YYYY');
            return;
        }
        
        const day = parseInt(parts[0]);
        const month = parseInt(parts[1]);
        const year = parseInt(parts[2]);
        
        if (isNaN(day) || isNaN(month) || isNaN(year)) {
            alert('Format tanggal tidak valid. Gunakan format DD/MM/YYYY');
            return;
        }
        
        // Convert to YYYY-MM-DD format for form submission
        const dateObj = new Date(year, month - 1, day);
        const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Submit form with date filter (try both possible form IDs)
        const form = document.getElementById('withdrawSearchForm') || document.getElementById('historyDateFilterForm');
        if (form) {
            // Remove existing date input
            const existingDate = form.querySelector('input[name="date"]');
            if (existingDate) existingDate.remove();
            
            // Add new date input
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'date';
            dateInput.value = formattedDate;
            form.appendChild(dateInput);
            
            form.submit();
        }
    }
    
    // Close date filter when clicking outside
    document.addEventListener('click', function(event) {
        // Close all date filter dropdowns when clicking outside
        document.querySelectorAll('[id^="withdrawDateFilter"], [id^="dateFilter"], [id^="historyWithdrawDateFilter"], [id^="withdrawApprovalDateFilter"]').forEach(dropdown => {
            if (!dropdown.classList.contains('hidden') && 
                !event.target.closest('#' + dropdown.id) && 
                !event.target.closest('button[onclick*="toggleWithdrawDateFilter"]')) {
                dropdown.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                dropdown.classList.add('opacity-0', 'scale-95', 'translate-y-2');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        });
    });
</script>

