<div class="relative inline-block">
    <button onclick="toggleDateFilterCompact('{{ $filterId }}')" class="flex items-center px-3 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
        <i class="fas fa-calendar-alt mr-2 text-xs"></i>
        <span>Date</span>
    </button>
    
    <!-- Compact Date Filter Dropdown -->
    <div id="{{ $filterId }}" class="date-filter-dropdown hidden absolute right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 p-3 w-72 z-50 opacity-0 scale-95 translate-y-2 transition-all duration-200 ease-out" onclick="event.stopPropagation()">
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
                       onfocus="preventDateKeyboard(event, '{{ $filterId }}')"
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
                       onfocus="preventDateKeyboard(event, '{{ $filterId }}')"
                       onclick="openDateCalendar('{{ $filterId }}', 'end', this)"
                       class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-400 focus:border-orange-400 cursor-pointer bg-white"
                       value="">
            </div>
        </div>
        <p id="dateError{{ $filterId }}" class="text-[10px] text-red-500 mb-1 hidden">Tanggal akhir tidak boleh sebelum tanggal mulai</p>
        
        <!-- Inline Calendar Container -->
        <div id="calendarContainer{{ $filterId }}" class="hidden">
            <div id="activeCalendar{{ $filterId }}" class="bg-gray-50 rounded-md p-2 border border-gray-200"></div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2 mt-2 pt-2 border-t border-gray-200">
            <button onclick="event.stopPropagation(); clearDateFilter('{{ $filterId }}')" class="flex-1 px-2 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                Clear
            </button>
            <button type="button" onclick="event.stopPropagation(); event.preventDefault(); applyDateFilterCompact('{{ $filterId }}'); closeDateFilter('{{ $filterId }}'); return false;" class="flex-1 px-2 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-md hover:shadow-sm transition-all">
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

    function preventDateKeyboard(event, filterId) {
        event.preventDefault();
        event.stopPropagation();
        // Blurs input so mobile keyboards don't appear, but keep openDateCalendar usable
        if (event.target) {
            event.target.blur();
        }
    }

    function showDateFilterError(filterId, message) {
        const errorEl = document.getElementById('dateError' + filterId);
        const startInput = document.getElementById('startInput' + filterId);
        const endInput = document.getElementById('endInput' + filterId);
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }
        [startInput, endInput].forEach((input) => {
            if (input) {
                input.classList.add('border-red-400', 'focus:ring-red-400', 'focus:border-red-400');
            }
        });
    }

    function clearDateFilterError(filterId) {
        const errorEl = document.getElementById('dateError' + filterId);
        const startInput = document.getElementById('startInput' + filterId);
        const endInput = document.getElementById('endInput' + filterId);
        if (errorEl) {
            errorEl.classList.add('hidden');
        }
        [startInput, endInput].forEach((input) => {
            if (input) {
                input.classList.remove('border-red-400', 'focus:ring-red-400', 'focus:border-red-400');
            }
        });
    }
    
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
            const startDate = state.startDate;
            const endDate = state.endDate;
            const currentDateStr = formatDateForInputCompact(currentDate);
            const startStr = startDate ? formatDateForInputCompact(startDate) : null;
            const endStr = endDate ? formatDateForInputCompact(endDate) : null;
            const isRangeStart = startStr && startStr === currentDateStr;
            const isRangeEnd = endStr && endStr === currentDateStr;
            const isInRange = startDate && endDate && currentDate >= startDate && currentDate <= endDate;
            const hasEndDate = Boolean(endDate);
            
            let dayClass = 'text-center text-[10px] py-1 rounded cursor-pointer transition-all aspect-square flex items-center justify-center ';
            if (isRangeStart && isRangeEnd) {
                dayClass += 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold';
            } else if (isRangeEnd || (type === 'end' && isSelected)) {
                dayClass += 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold';
            } else if (type === 'start' && isSelected && !hasEndDate) {
                dayClass += 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-semibold';
            } else if (isRangeStart) {
                dayClass += 'border border-orange-400 bg-orange-50 text-orange-700 font-semibold';
            } else if (isInRange) {
                dayClass += 'bg-orange-50 text-orange-700';
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
        selectedDate.setHours(0, 0, 0, 0); // Normalize to start of day
        clearDateFilterError(filterId);
        
        const inputId = type + 'Input' + filterId;
        const input = document.getElementById(inputId);
        
        const formattedDate = formatDateForDisplayCompact(selectedDate);
        if (type === 'end' && state.startDate && selectedDate < state.startDate) {
            showDateFilterError(filterId, 'Tanggal akhir tidak boleh sebelum tanggal mulai');
            if (input) input.value = '';
            const calendarContainer = document.getElementById('calendarContainer' + filterId);
            if (calendarContainer) {
                renderCompactCalendar(filterId, type, document.getElementById('activeCalendar' + filterId));
            }
            return;
        }

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

    function togglePaginationForDateFilter(isFiltering) {
        const paginationContainers = document.querySelectorAll('.keyword-pagination-container');
        paginationContainers.forEach((container) => {
            container.style.display = isFiltering ? 'none' : '';
        });
    }

    function updateKeywordDateEmptyState(show) {
        const emptyRow = document.getElementById('keyword-filter-empty-row');
        const emptyCard = document.getElementById('keyword-filter-empty-card');

        if (emptyRow) {
            emptyRow.style.display = show ? 'table-row' : 'none';
        }
        if (emptyCard) {
            emptyCard.classList.toggle('hidden', !show);
        }
    }
    
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

    // Robust date utilities
    function normalizeDateString(value) {
        if (!value) return null;
        const trimmed = value.trim();
        const base = trimmed.split('T')[0].split(' ')[0];
        if (/^\d{4}-\d{2}-\d{2}$/.test(base)) return base;
        if (/^\d{2}\/\d{2}\/\d{4}$/.test(base)) {
            const [d, m, y] = base.split('/');
            return `${y}-${m}-${d}`;
        }
        return null;
    }

    function toUtcTimestamp(dateString) {
        const normalized = normalizeDateString(dateString);
        if (!normalized) return null;
        const parts = normalized.split('-').map(Number);
        if (parts.length !== 3) return null;
        const [year, month, day] = parts;
        const ts = Date.UTC(year, month - 1, day);
        return isNaN(ts) ? null : ts;
    }

    function ensureFilterState(filterId) {
        const existing = window.calendarState[filterId];
        if (existing && (existing.startDate || existing.endDate)) {
            return existing;
        }

        const persisted = window.dateFilterState?.[filterId];
        if (persisted && (persisted.start || persisted.end)) {
            const today = new Date();
            const toDateObj = (val) => {
                const normalized = normalizeDateString(val);
                if (!normalized) return null;
                const [y, m, d] = normalized.split('-').map(Number);
                const dateObj = new Date(y, m - 1, d);
                if (isNaN(dateObj.getTime())) return null;
                dateObj.setHours(0, 0, 0, 0);
                return dateObj;
            };

            const startDate = persisted.start ? toDateObj(persisted.start) : null;
            const endDate = persisted.end ? toDateObj(persisted.end) : null;

            window.calendarState[filterId] = {
                currentMonth: startDate ? startDate.getMonth() : today.getMonth(),
                currentYear: startDate ? startDate.getFullYear() : today.getFullYear(),
                startDate,
                endDate,
                activeType: 'start'
            };

            // Sync input fields so user sees persisted values
            const startInput = document.getElementById('startInput' + filterId);
            const endInput = document.getElementById('endInput' + filterId);
            if (startInput && startDate) startInput.value = formatDateForDisplayCompact(startDate);
            if (endInput && endDate) endInput.value = formatDateForDisplayCompact(endDate);

            return window.calendarState[filterId];
        }

        return existing;
    }
    
    function clearDateFilter(filterId) {
        const startInput = document.getElementById('startInput' + filterId);
        const endInput = document.getElementById('endInput' + filterId);
        
        if (startInput) startInput.value = '';
        if (endInput) endInput.value = '';
        clearDateFilterError(filterId);
        
        if (window.calendarState[filterId]) {
            window.calendarState[filterId].startDate = null;
            window.calendarState[filterId].endDate = null;
        }
        
        // Clear date filter state
        if (window.dateFilterState && window.dateFilterState[filterId]) {
            window.dateFilterState[filterId] = { start: null, end: null };
        }
        
        const calendarContainer = document.getElementById('calendarContainer' + filterId);
        if (calendarContainer) {
            calendarContainer.classList.add('hidden');
        }
        
        togglePaginationForDateFilter(false);
        updateKeywordDateEmptyState(false);

        // Show all rows (date filter cleared, but status filter may still be active)
        const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row, #keyword-cards-container .keyword-row');
        rows.forEach((row, index) => {
            // Clear date filter match
            row.dataset.dateFilterMatch = 'true';
            
            // Only show if not hidden by status filter
            if (row.dataset.statusHidden !== 'true') {
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.style.display = '';
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 20);
            }
        });
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
        let state = ensureFilterState(filterId);
        const tableRows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
        const cardRows = document.querySelectorAll('#keyword-cards-container .keyword-row');

        if (!tableRows.length && !cardRows.length) {
            updateKeywordDateEmptyState(false);
            togglePaginationForDateFilter(false);
            return;
        }
        
        // If no state or no dates selected, show all rows (clear date filter)
        if (!state || (!state.startDate && !state.endDate)) {
            // Clear date filter - show all rows that are not hidden by status filter
            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row, #keyword-cards-container .keyword-row');
            rows.forEach((row, index) => {
                // Only show if not hidden by status filter
                if (row.dataset.statusHidden !== 'true') {
                    row.style.display = '';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                    row.dataset.dateFilterMatch = 'true';
                }
            });
            // Clear date filter state
            if (window.dateFilterState && window.dateFilterState[filterId]) {
                window.dateFilterState[filterId] = { start: null, end: null };
            }
            togglePaginationForDateFilter(false);
            updateKeywordDateEmptyState(false);
            return;
        }
        
        const startDate = state.startDate;
        const endDate = state.endDate;
        
        // Validate dates
        if (startDate && isNaN(startDate.getTime())) {
            return;
        }
        if (endDate && isNaN(endDate.getTime())) {
            return;
        }
        
        // Ensure dates are Date objects
        if (startDate && !(startDate instanceof Date)) {
            return;
        }
        if (endDate && !(endDate instanceof Date)) {
            return;
        }
        
        const filterStart = startDate ? formatDateForInputCompact(startDate) : null;
        const filterEnd = endDate ? formatDateForInputCompact(endDate) : null;
        
        // Validate that we have at least one filter date
        if (!filterStart && !filterEnd) {
            // No filter dates - show all rows
            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row, #keyword-cards-container .keyword-row');
            rows.forEach((row) => {
                if (row.dataset.statusHidden !== 'true') {
                    row.style.display = '';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }
            });
            togglePaginationForDateFilter(false);
            updateKeywordDateEmptyState(false);
            return;
        }
        
        // Store date filter state
        window.dateFilterState = window.dateFilterState || {};
        window.dateFilterState[filterId] = {
            start: filterStart,
            end: filterEnd
        };
        togglePaginationForDateFilter(true);
        
        // Helper function to check if keyword period is within filter range (containment, not just overlap)
        function dateRangesOverlap(rowStart, rowEnd, filterStart, filterEnd) {
            // This function should only be called when filter dates exist
            if (!filterStart && !filterEnd) {
                return true;
            }

            const rowStartTime = toUtcTimestamp(rowStart);
            const rowEndTime = toUtcTimestamp(rowEnd);
            const filterStartTime = toUtcTimestamp(filterStart);
            const filterEndTime = toUtcTimestamp(filterEnd);

            // If row has no dates, hide it when filter is active
            if (rowStartTime === null && rowEndTime === null) {
                return false;
            }

            // Validate filters
            if (filterStart && filterStartTime === null) return false;
            if (filterEnd && filterEndTime === null) return false;

            // Both filter dates provided: keyword period must be inside the selected window
            if (filterStartTime !== null && filterEndTime !== null) {
                if (rowStartTime !== null && rowEndTime !== null) {
                    return rowStartTime >= filterStartTime && rowEndTime <= filterEndTime;
                }
                if (rowStartTime !== null) {
                    return rowStartTime >= filterStartTime && rowStartTime <= filterEndTime;
                }
                if (rowEndTime !== null) {
                    return rowEndTime >= filterStartTime && rowEndTime <= filterEndTime;
                }
                return false;
            }

            // Only start filter provided
            if (filterStartTime !== null) {
                if (rowStartTime !== null) {
                    return rowStartTime >= filterStartTime;
                }
                if (rowEndTime !== null) {
                    return rowEndTime >= filterStartTime;
                }
                return false;
            }

            // Only end filter provided
            if (filterEndTime !== null) {
                if (rowEndTime !== null) {
                    return rowEndTime <= filterEndTime;
                }
                if (rowStartTime !== null) {
                    return rowStartTime <= filterEndTime;
                }
                return false;
            }

            return false;
        }
        
        // Filter keyword rows with smooth animation
        let visibleCount = 0;
        
        tableRows.forEach((row, index) => {
            // Get date values from data attributes - use getAttribute to ensure we get the value
            const rowStartAttr = row.getAttribute('data-start');
            const rowEndAttr = row.getAttribute('data-end');
            const rowStart = rowStartAttr ? rowStartAttr.trim() : '';
            const rowEnd = rowEndAttr ? rowEndAttr.trim() : '';
            
            // Check if row passes date filter
            const shouldShow = dateRangesOverlap(rowStart, rowEnd, filterStart, filterEnd);
            const statusHidden = row.dataset.statusHidden === 'true';
            const finalShow = shouldShow && !statusHidden;
            
            // Store date filter result in data attribute
            row.dataset.dateFilterMatch = shouldShow ? 'true' : 'false';
            
            // Apply filter - hide rows that don't match
            if (finalShow) {
                visibleCount++;
                // Show row with animation
                row.style.transition = '';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.style.display = '';
                
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 20);
            } else {
                // Hide row that doesn't match date filter - force hide immediately
                row.style.display = 'none';
                row.style.opacity = '0';
                row.style.transform = 'translateY(0)';
                row.style.transition = '';
            }
        });
        
        // Filter keyword cards (mobile) with smooth animation
        let visibleCardCount = 0;
        
        cardRows.forEach((card, index) => {
            // Get date values from data attributes - use getAttribute to ensure we get the value
            const cardStartAttr = card.getAttribute('data-start');
            const cardEndAttr = card.getAttribute('data-end');
            const cardStart = cardStartAttr ? cardStartAttr.trim() : '';
            const cardEnd = cardEndAttr ? cardEndAttr.trim() : '';
            
            // Check if card passes date filter
            const shouldShow = dateRangesOverlap(cardStart, cardEnd, filterStart, filterEnd);
            const statusHidden = card.dataset.statusHidden === 'true';
            const finalShow = shouldShow && !statusHidden;
            
            // Store date filter result in data attribute
            card.dataset.dateFilterMatch = shouldShow ? 'true' : 'false';
            
            // Apply filter - hide cards that don't match
            if (finalShow) {
                visibleCardCount++;
                // Show card with animation
                card.style.transition = '';
                card.style.opacity = '0';
                card.style.transform = 'translateY(-10px)';
                card.style.display = '';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 20);
            } else {
                // Hide card that doesn't match date filter - force hide immediately
                card.style.display = 'none';
                card.style.opacity = '0';
                card.style.transform = 'translateY(0)';
                card.style.transition = '';
            }
        });

        updateKeywordDateEmptyState(visibleCount === 0 && visibleCardCount === 0);
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
<style>
    /* Mobile-friendly date dropdown layout */
    @media (max-width: 640px) {
        .date-filter-dropdown {
            position: fixed !important;
            left: 50% !important;
            right: auto !important;
            top: 90px !important;
            transform: translateX(-50%) translateY(8px);
            width: calc(100vw - 24px);
            max-width: 420px;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.12);
        }

        .date-filter-dropdown .flex.gap-2.mb-2 {
            gap: 10px;
            margin-bottom: 10px;
        }

        .date-filter-dropdown .flex.gap-2.mt-2.pt-2 {
            padding-top: 10px;
        }
    }
</style>
