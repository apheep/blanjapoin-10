<div class="relative inline-block">
    <button onclick="toggleDateFilterCompact('{{ $filterId }}')" class="flex items-center px-3 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
        <i class="fas fa-calendar-alt mr-2 text-xs"></i>
        <span>Date</span>
    </button>
    
    <!-- Compact Date Filter Dropdown -->
    <div id="{{ $filterId }}" class="hidden absolute right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 p-3 w-72 z-50" onclick="event.stopPropagation()">
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
            <button onclick="event.stopPropagation(); closeDateFilter('{{ $filterId }}')" class="flex-1 px-2 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-md hover:shadow-sm transition-all">
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
                dd.classList.add('hidden');
            }
        });
        
        dropdown.classList.toggle('hidden');
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
        
        // Auto-apply filter
        applyDateFilterCompact(filterId);
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
        
            dropdown.classList.add('hidden');
    }
    
    window.closeDateFilter = closeDateFilter;
    
    function applyDateFilterCompact(filterId) {
        const state = window.calendarState[filterId];
        if (!state) return;
        
        const startDate = state.startDate;
        const endDate = state.endDate;
        
        const filterStart = startDate ? formatDateForInputCompact(startDate) : null;
        const filterEnd = endDate ? formatDateForInputCompact(endDate) : null;
        
        // Filter keyword rows
        const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
        rows.forEach(row => {
            const rowStart = row.dataset.start;
            const rowEnd = row.dataset.end;
            
            let shouldShow = true;
            
            if (filterStart || filterEnd) {
                shouldShow = false;
                
                if (rowStart || rowEnd) {
                    if (rowStart && rowEnd) {
                        if (filterStart && filterEnd) {
                            shouldShow = (rowStart <= filterEnd && rowEnd >= filterStart);
                        } else if (filterStart) {
                            shouldShow = rowEnd >= filterStart;
                        } else if (filterEnd) {
                            shouldShow = rowStart <= filterEnd;
    }
                    } else if (rowStart) {
                        if (filterStart && filterEnd) {
                            shouldShow = rowStart <= filterEnd;
                        } else if (filterStart) {
                            shouldShow = rowStart >= filterStart;
                        } else if (filterEnd) {
                            shouldShow = rowStart <= filterEnd;
                        }
                    } else if (rowEnd) {
                        if (filterStart && filterEnd) {
                            shouldShow = rowEnd >= filterStart;
                        } else if (filterStart) {
                            shouldShow = rowEnd >= filterStart;
                        } else if (filterEnd) {
                            shouldShow = rowEnd <= filterEnd;
                        }
                    }
                } else {
                    shouldShow = false;
                }
            }
            
            row.style.display = shouldShow ? '' : 'none';
        });
        
        // Filter keyword cards (mobile)
        const cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
        cards.forEach(card => {
            const cardStart = card.dataset.start;
            const cardEnd = card.dataset.end;
            
            let shouldShow = true;
            
            if (filterStart || filterEnd) {
                shouldShow = false;
                
                if (cardStart || cardEnd) {
                    if (cardStart && cardEnd) {
                        if (filterStart && filterEnd) {
                            shouldShow = (cardStart <= filterEnd && cardEnd >= filterStart);
                        } else if (filterStart) {
                            shouldShow = cardEnd >= filterStart;
                        } else if (filterEnd) {
                            shouldShow = cardStart <= filterEnd;
                        }
                    } else if (cardStart) {
                        if (filterStart && filterEnd) {
                            shouldShow = cardStart <= filterEnd;
                        } else if (filterStart) {
                            shouldShow = cardStart >= filterStart;
                        } else if (filterEnd) {
                            shouldShow = cardStart <= filterEnd;
                        }
                    } else if (cardEnd) {
                        if (filterStart && filterEnd) {
                            shouldShow = cardEnd >= filterStart;
                        } else if (filterStart) {
                            shouldShow = cardEnd >= filterStart;
                        } else if (filterEnd) {
                            shouldShow = cardEnd <= filterEnd;
                        }
                    }
                } else {
                    shouldShow = false;
                }
            }
            
            card.style.display = shouldShow ? '' : 'none';
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
