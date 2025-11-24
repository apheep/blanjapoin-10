<div class="relative inline-block">
    <button onclick="toggleDateFilterCompact('{{ $filterId }}')" class="flex items-center px-3 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
        <i class="fas fa-calendar-alt mr-2 text-xs"></i>
        <span>Date</span>
    </button>
    
    <!-- Compact Date Filter Dropdown -->
    <div id="{{ $filterId }}" class="hidden absolute right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 p-3 w-72 z-50 opacity-0 scale-95 translate-y-2 transition-all duration-200 ease-out" onclick="event.stopPropagation()">
        <!-- Date Inputs Row -->
        <div class="flex gap-2 mb-2">
            <div class="flex-1">
                <label class="block text-[10px] font-medium text-gray-600 mb-1">Start</label>
                    <input type="text" 
                           id="startInput{{ $filterId }}" 
                       placeholder="DD/MM/YYYY"
                           readonly
                           inputmode="none"
                       autocomplete="off"
                       onkeydown="return false;"
                       onclick="openDateCalendar('{{ $filterId }}', 'start', this)"
                       class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400 cursor-pointer bg-white"
                       value="">
            </div>
            <div class="flex-1">
                <label class="block text-[10px] font-medium text-gray-600 mb-1">End</label>
                    <input type="text" 
                           id="endInput{{ $filterId }}" 
                       placeholder="DD/MM/YYYY"
                           readonly
                           inputmode="none"
                       autocomplete="off"
                       onkeydown="return false;"
                       onclick="openDateCalendar('{{ $filterId }}', 'end', this)"
                       class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400 cursor-pointer bg-white"
                       value="">
            </div>
        </div>
        
        <!-- Inline Calendar Container -->
        <div id="calendarContainer{{ $filterId }}" class="hidden">
            <div id="activeCalendar{{ $filterId }}" class="bg-gray-50 rounded-md p-2 border border-gray-200"></div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2 mt-2 pt-2 border-t border-gray-200">
            <button onclick="event.stopPropagation(); clearDateFilter('{{ $filterId }}')" class="flex-1 px-2 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                Clear
            </button>
            <button onclick="event.stopPropagation(); applyDateFilterCompact('{{ $filterId }}'); closeDateFilter('{{ $filterId }}');" class="flex-1 px-2 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-md hover:shadow-sm transition-all">
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
    
    function toggleDateFilterCompact(filterId) {
        const dropdown = document.getElementById(filterId);
        if (!dropdown) return;
        
        // Close other date filters
        document.querySelectorAll("[id^='dateFilter']:not([id$='Backdrop'])").forEach(dd => {
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
    }
    
    window.toggleDateFilterCompact = toggleDateFilterCompact;
    
    function openDateCalendar(filterId, type, inputElement) {
        event.preventDefault();
        event.stopPropagation();
        
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        const activeCalendar = document.getElementById('activeCalendar' + filterId);
        
        if (!calendarContainer || !activeCalendar) return;
        
        // Show calendar container
        calendarContainer.classList.remove('hidden');
        
        // Initialize state if needed
        if (!window.calendarState[filterId]) {
            const today = new Date();
            window.calendarState[filterId] = {
                currentMonth: today.getMonth(),
                currentYear: today.getFullYear(),
                startDate: null,
                endDate: null,
                activeType: type
            };
        }
        
        window.calendarState[filterId].activeType = type;
        
        // Render calendar
        renderCompactCalendar(filterId, type, activeCalendar);
        
        // Scroll calendar into view if needed
        setTimeout(() => {
            calendarContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }
    
    window.openDateCalendar = openDateCalendar;
    
    function renderCompactCalendar(filterId, type, container) {
        const state = window.calendarState[filterId];
        if (!state) return;
        
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const daysOfWeek = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
        
        const firstDay = new Date(state.currentYear, state.currentMonth, 1);
        const lastDay = new Date(state.currentYear, state.currentMonth + 1, 0);
        const startDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
        const daysInMonth = lastDay.getDate();
        
        let html = `
            <div class="space-y-1.5">
                <div class="flex items-center justify-between mb-2">
                    <button onclick="changeMonthCompact('${filterId}', -1)" class="p-1 hover:bg-gray-200 rounded transition-colors">
                        <i class="fas fa-chevron-left text-xs text-gray-600"></i>
                    </button>
                    <div class="text-xs font-semibold text-gray-800">${months[state.currentMonth]} ${state.currentYear}</div>
                    <button onclick="changeMonthCompact('${filterId}', 1)" class="p-1 hover:bg-gray-200 rounded transition-colors">
                        <i class="fas fa-chevron-right text-xs text-gray-600"></i>
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-0.5 mb-1">
        `;
        
        daysOfWeek.forEach(day => {
            html += `<div class="text-center text-[9px] font-medium text-gray-500 py-0.5">${day}</div>`;
        });
        
        html += `</div><div class="grid grid-cols-7 gap-0.5">`;
        
        for (let i = 0; i < startDayOfWeek; i++) {
            html += `<div></div>`;
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const currentDate = new Date(state.currentYear, state.currentMonth, day);
            const isSelected = isDateSelectedCompact(filterId, type, currentDate);
            const isToday = isTodayDateCompact(currentDate);
            
            let dayClass = 'text-center text-[10px] py-1 rounded cursor-pointer transition-all aspect-square flex items-center justify-center ';
            if (isSelected) {
                dayClass += 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold';
            } else if (isToday) {
                dayClass += 'bg-orange-100 text-orange-700 font-medium';
            } else {
                dayClass += 'text-gray-700 hover:bg-orange-50 hover:text-orange-600';
            }
            
            html += `<div class="${dayClass}" onclick="selectDateCompact('${filterId}', '${type}', ${day})">${day}</div>`;
        }
        
        html += `</div></div>`;
        container.innerHTML = html;
    }
    
    window.renderCompactCalendar = renderCompactCalendar;
    
    function changeMonthCompact(filterId, delta) {
        const state = window.calendarState[filterId];
        if (!state) return;
        
        state.currentMonth += delta;
        if (state.currentMonth > 11) {
            state.currentMonth = 0;
            state.currentYear++;
        }
        if (state.currentMonth < 0) {
            state.currentMonth = 11;
            state.currentYear--;
        }
        
        const activeCalendar = document.getElementById('activeCalendar' + filterId);
        if (activeCalendar) {
            renderCompactCalendar(filterId, state.activeType, activeCalendar);
        }
    }
    
    window.changeMonthCompact = changeMonthCompact;
    
    function selectDateCompact(filterId, type, day) {
        const state = window.calendarState[filterId];
        if (!state) return;
        
        const selectedDate = new Date(state.currentYear, state.currentMonth, day);
        const inputId = type + 'Input' + filterId;
        const input = document.getElementById(inputId);
        
        const formattedDate = formatDateForDisplayCompact(selectedDate);
        if (input) input.value = formattedDate;
        
        if (type === 'start') {
            state.startDate = selectedDate;
            if (state.endDate && state.endDate < state.startDate) {
            state.endDate = null;
                const endInput = document.getElementById('endInput' + filterId);
                if (endInput) endInput.value = '';
            }
            } else {
                state.endDate = selectedDate;
            if (state.startDate && state.endDate < state.startDate) {
                alert('End date cannot be before start date');
                state.endDate = null;
                if (input) input.value = '';
                return;
            }
        }
        
        // Hide calendar after selection
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        if (calendarContainer) {
            calendarContainer.classList.add('hidden');
        }
        
        // Don't auto-apply filter - wait for Apply button
    }
    
    window.selectDateCompact = selectDateCompact;
    
    function isDateSelectedCompact(filterId, type, date) {
        const state = window.calendarState[filterId];
        if (!state) return false;
        
        const dateStr = formatDateForInputCompact(date);
        if (type === 'start' && state.startDate) {
            return formatDateForInputCompact(state.startDate) === dateStr;
        }
        if (type === 'end' && state.endDate) {
            return formatDateForInputCompact(state.endDate) === dateStr;
        }
        return false;
    }
    
    function isTodayDateCompact(date) {
        const today = new Date();
        return date.getDate() === today.getDate() &&
               date.getMonth() === today.getMonth() &&
               date.getFullYear() === today.getFullYear();
    }
    
    function formatDateForDisplayCompact(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
        }
        
    function formatDateForInputCompact(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function clearDateFilter(filterId) {
        const startInput = document.getElementById('startInput' + filterId);
        const endInput = document.getElementById('endInput' + filterId);
        
        if (startInput) startInput.value = '';
        if (endInput) endInput.value = '';
        
        if (window.calendarState[filterId]) {
            window.calendarState[filterId].startDate = null;
            window.calendarState[filterId].endDate = null;
        }
        
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        if (calendarContainer) {
            calendarContainer.classList.add('hidden');
        }
        
        applyDateFilterCompact(filterId);
    }
    
    window.clearDateFilter = clearDateFilter;

    function closeDateFilter(filterId) {
        const dropdown = document.getElementById(filterId);
        if (!dropdown) return;
        
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        if (calendarContainer) {
            calendarContainer.classList.add('hidden');
        }
        
        // Close dropdown with animation
        dropdown.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        dropdown.classList.add('opacity-0', 'scale-95', 'translate-y-2');
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
    
    window.closeDateFilter = closeDateFilter;
    
    function applyDateFilterCompact(filterId) {
        const state = window.calendarState[filterId];
        if (!state) {
            // If no state, show all rows
            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row, #keyword-cards-container .keyword-row');
            rows.forEach(row => {
                row.style.display = '';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            });
            return;
        }
        
        const startDate = state.startDate;
        const endDate = state.endDate;
        
        const filterStart = startDate ? formatDateForInputCompact(startDate) : null;
        const filterEnd = endDate ? formatDateForInputCompact(endDate) : null;
        
        // Helper function to check if keyword period is within filter range
        function dateRangesOverlap(rowStart, rowEnd, filterStart, filterEnd) {
            // If no filter dates, show all
            if (!filterStart && !filterEnd) return true;
            
            // If row has no dates, hide it when filter is active
            if (!rowStart && !rowEnd) return false;
            
            // Convert string dates (YYYY-MM-DD) to timestamps for comparison
            const rowStartTime = rowStart ? new Date(rowStart).getTime() : null;
            const rowEndTime = rowEnd ? new Date(rowEnd).getTime() : null;
            const filterStartTime = filterStart ? new Date(filterStart).getTime() : null;
            const filterEndTime = filterEnd ? new Date(filterEnd).getTime() : null;
            
            // If both filter dates are set - check if keyword period is within or overlaps filter range
            if (filterStartTime !== null && filterEndTime !== null) {
                if (rowStartTime !== null && rowEndTime !== null) {
                    // Keyword has both start and end date
                    // Show if keyword period overlaps with filter range
                    // Overlap: keywordStart <= filterEnd AND keywordEnd >= filterStart
                    return rowStartTime <= filterEndTime && rowEndTime >= filterStartTime;
                } else if (rowStartTime !== null) {
                    // Keyword only has start date - show if start falls within filter range
                    return rowStartTime >= filterStartTime && rowStartTime <= filterEndTime;
                } else if (rowEndTime !== null) {
                    // Keyword only has end date - show if end falls within filter range
                    return rowEndTime >= filterStartTime && rowEndTime <= filterEndTime;
                }
            } else if (filterStartTime !== null) {
                // Only start filter is set - show keywords that end on or after filter start
                if (rowStartTime !== null && rowEndTime !== null) {
                    return rowEndTime >= filterStartTime;
                } else if (rowStartTime !== null) {
                    return rowStartTime >= filterStartTime;
                } else if (rowEndTime !== null) {
                    return rowEndTime >= filterStartTime;
                }
            } else if (filterEndTime !== null) {
                // Only end filter is set - show keywords that start on or before filter end
                if (rowStartTime !== null && rowEndTime !== null) {
                    return rowStartTime <= filterEndTime;
                } else if (rowStartTime !== null) {
                    return rowStartTime <= filterEndTime;
                } else if (rowEndTime !== null) {
                    return rowEndTime <= filterEndTime;
                }
            }
            
            return false;
        }
        
        // Filter keyword rows with smooth animation
        const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
        rows.forEach((row, index) => {
            const rowStart = row.dataset.start || '';
            const rowEnd = row.dataset.end || '';
            
            const shouldShow = dateRangesOverlap(rowStart, rowEnd, filterStart, filterEnd);
            
            if (shouldShow) {
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.style.display = '';
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 20);
            } else {
                row.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    row.style.display = 'none';
                }, 200);
            }
        });
        
        // Filter keyword cards (mobile) with smooth animation
        const cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
        cards.forEach((card, index) => {
            const cardStart = card.dataset.start || '';
            const cardEnd = card.dataset.end || '';
            
            const shouldShow = dateRangesOverlap(cardStart, cardEnd, filterStart, filterEnd);
            
            if (shouldShow) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-10px)';
                card.style.display = '';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 20);
            } else {
                card.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 200);
            }
        });
    }
    
    window.applyDateFilterCompact = applyDateFilterCompact;
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="dateFilter"]') && !event.target.closest('button[onclick*="toggleDateFilter"]')) {
            document.querySelectorAll("[id^='dateFilter']:not([id$='Backdrop'])").forEach(dd => {
                dd.classList.add('hidden');
            });
        }
    });
</script>
