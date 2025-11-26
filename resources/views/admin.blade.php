<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>blanjapoin.id - Merchant</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
     <style>
        /* Font optimization for Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;    
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'kern' 1;
            letter-spacing: -0.01em;
        }
        /* Prevent horizontal scroll on mobile */
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }
        * {
            box-sizing: border-box;
        }
    </style>

</head>
<body class="min-h-screen bg-white font-poppins">

    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if(session('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif
@include('partials.navbar-admin')
        <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
            @php
                $tabParam = request()->get('tab', 'merchant');
                // Normalize: if tab is not 'keyword', default to 'merchant'
                $activeTab = ($tabParam === 'keyword') ? 'keyword' : 'merchant';
            @endphp
            <div class="mb-6 -mx-4 sm:mx-0 overflow-x-auto sm:overflow-x-visible">
                <div class="flex space-x-3 px-4 sm:px-0 sm:min-w-max">
                    <!-- TAB MERCHANT -->
                    <button
                        onclick="switchTab('merchant')"
                        id="tab-merchant"
                        class="shrink-0 px-6 py-2 rounded-full border border-orange-400
                               {{ $activeTab === 'merchant' ? 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium shadow-lg' : 'text-gray-700 hover:bg-orange-50 transition-colors' }}">
                        Merchant
                    </button>

                    <!-- TAB KEYWORD -->
                    <button
                        onclick="switchTab('keyword')"
                        id="tab-keyword"
                        class="shrink-0 px-6 py-2 rounded-full border border-orange-400
                               {{ $activeTab === 'keyword' ? 'bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium shadow-lg' : 'text-gray-700 hover:bg-orange-50 transition-colors' }}">
                        Keyword
                    </button>
                </div>
            </div>

            <script>
                // Dropdown Toggle Functions //

                function toggleDateFilter(id) {
                    const dropdown = document.getElementById(id);
                    if (!dropdown) return;
                    
                    const allDropdowns = document.querySelectorAll("[id^='dateFilterDropdown']");
                    allDropdowns.forEach(dd => {
                        if (dd.id !== id) {
                            dd.classList.add('hidden');
                            dd.classList.remove('opacity-100', 'translate-y-0');
                            dd.classList.add('opacity-0', 'translate-y-1');
                        }
                    });

                    const isHidden = dropdown.classList.contains('hidden');
                    if (isHidden) {
                        dropdown.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            dropdown.classList.remove('opacity-0', 'translate-y-1');
                            dropdown.classList.add('opacity-100', 'translate-y-0');
                        });
                    } else {
                        dropdown.classList.remove('opacity-100', 'translate-y-0');
                        dropdown.classList.add('opacity-0', 'translate-y-1');
                        setTimeout(() => dropdown.classList.add('hidden'), 150);
                    }
                }

                document.addEventListener('click', function(event) {
                    if (!event.target.closest("[id^='dateFilterDropdown']") && !event.target.closest("[id^='dateFilterButton']")) {
                        const allDropdowns = document.querySelectorAll("[id^='dateFilterDropdown']");
                        allDropdowns.forEach(dropdown => {
                            dropdown.classList.remove('opacity-100', 'translate-y-0');
                            dropdown.classList.add('opacity-0', 'translate-y-1');
                            setTimeout(() => dropdown.classList.add('hidden'), 150);
                        });
                    }
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('merchantUploadFormElement');
                    if (form) {
                        form.addEventListener('submit', handleMerchantFormSubmit);
                    }
                });

                ////////////////////////////////////////////////////////////////////
                // Keyword Status Filter
                ////////////////////////////////////////////////////////////////////

                let selectedKeywordStatus = 'all';

                function toggleKeywordStatusDropdown() {
                    const dropdown = document.getElementById('statusDropdownKeyword');
                    if (!dropdown) return;
                    
                    const isHidden = dropdown.classList.contains('hidden');
                    
                    const otherDropdowns = document.querySelectorAll("[id^='statusDropdown']");
                    otherDropdowns.forEach(dd => {
                        if (dd.id !== 'statusDropdownKeyword') {
                            dd.classList.add('hidden');
                            dd.classList.remove('opacity-100', 'translate-y-0');
                            dd.classList.add('opacity-0', 'translate-y-1');
                        }
                    });

                    if (isHidden) {
                        dropdown.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            dropdown.classList.remove('opacity-0', 'translate-y-1');
                            dropdown.classList.add('opacity-100', 'translate-y-0');
                        });
                    } else {
                        dropdown.classList.remove('opacity-100', 'translate-y-0');
                        dropdown.classList.add('opacity-0', 'translate-y-1');
                        setTimeout(() => dropdown.classList.add('hidden'), 150);
                    }
                }

                function filterKeywordByStatus(status) {
                    const button = document.getElementById('statusBtnKeyword');
                    if (!button) return;

                    if (selectedKeywordStatus === status) {
                        status = 'all';
                    }
                    selectedKeywordStatus = status;

                    let label = 'Status';
                    let buttonClasses = 'flex items-center px-4 py-2 text-sm rounded-full border transition-all duration-300 ';

                    if (status === 'all') {
                        buttonClasses += 'border-gray-300 text-gray-700 hover:bg-gray-50';
                    } else if (status === 'pending') {
                        label = 'Pending';
                        buttonClasses += 'border-yellow-300 text-yellow-800 bg-gradient-to-r from-yellow-100 to-amber-100';
                    } else if (status === 'reject') {
                        label = 'Rejected';
                        buttonClasses += 'border-red-300 text-red-800 bg-gradient-to-r from-red-100 to-rose-100';
                    } else if (status === 'approve') {
                        label = 'Approved';
                        buttonClasses += 'border-green-300 text-green-800 bg-gradient-to-r from-green-100 to-emerald-100';
                    }

                    button.className = buttonClasses;
                    button.innerHTML = `<i class="fas fa-filter mr-2"></i>${label}<i class="fas fa-chevron-down ml-2 text-xs"></i>`;

                    // ====== (update dropdown item active state) ======
                    const dropdownItems = document.querySelectorAll('#statusDropdownKeyword a[data-status]');
                    dropdownItems.forEach(item => {
                        const itemStatus = item.getAttribute('data-status');
                        const normalizedItemStatus = (itemStatus || '').toLowerCase();
                        const normalizedCurrentStatus = (status || '').toLowerCase();

                        // Reset semua item ke state default
                        item.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100', 'bg-green-100',
                            'text-gray-900', 'text-yellow-900', 'text-red-900', 'text-green-900');

                        // Apply active state jika status cocok
                        if (normalizedItemStatus === normalizedCurrentStatus || 
                            (normalizedCurrentStatus === 'all' && normalizedItemStatus === 'all')) {
                            
                            if (status === 'all') {
                                item.classList.add('bg-gray-100', 'text-gray-900');
                            } else if (status === 'pending') {
                                item.classList.add('bg-yellow-100', 'text-yellow-900');
                            } else if (status === 'reject') {
                                item.classList.add('bg-red-100', 'text-red-900');
                            } else if (status === 'approve') {
                                item.classList.add('bg-green-100', 'text-green-900');
                            }
                        }
                    });

                    const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
                    rows.forEach((row, index) => {
                        const s = (row.dataset.status || '').toLowerCase();
                        const normalized = s === 'approved' ? 'approve' : s === 'rejected' ? 'reject' : s;
                        const shouldShow = (status === 'all' || normalized === status);
                        
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

                    const cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
                    cards.forEach((card, index) => {
                        const s = (card.dataset.status || '').toLowerCase();
                        const normalized = s === 'approved' ? 'approve' : s === 'rejected' ? 'reject' : s;
                        const shouldShow = (status === 'all' || normalized === status);
                        
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

                    toggleKeywordStatusDropdown();
                }

                document.addEventListener('click', function(event) {
                    if (!event.target.closest('#statusDropdownKeyword') && 
                        !event.target.closest('#statusBtnKeyword')) {
                        const dropdown = document.getElementById('statusDropdownKeyword');
                        if (dropdown) {
                            dropdown.classList.add('hidden');
                        }
                    }
                });

                ////////////////////////////////////////////////////////////////////
                // Category Dropdown & Filter for Merchant and Telkom
                ////////////////////////////////////////////////////////////////////

                let selectedCategory = {
                    merchant: 'Semua',
                    telkom: 'Semua'
                };

                function hideDropdown(dropdown) {
                    dropdown.classList.remove('opacity-100', 'translate-y-0');
                    dropdown.classList.add('opacity-0', 'translate-y-1');
                    setTimeout(() => dropdown.classList.add('hidden'), 150);
                }

                function closeAllDropdowns() {
                    const dropdownIds = [
                        'kategoriDropdownAll1',
                        'kategoriDropdownAll3',
                        'kategoriDropdownMerchant',
                        'kategoriDropdownTelkom'
                    ];

                    dropdownIds.forEach(id => {
                        const dropdown = document.getElementById(id);
                        if (dropdown && !dropdown.classList.contains('hidden')) {
                            hideDropdown(dropdown);
                        }
                    });
                }

                function toggleKategoriDropdownMerchant() {
                    const dropdown = document.getElementById('kategoriDropdownMerchant');
                    if (!dropdown) return;

                    const isHidden = dropdown.classList.contains('hidden');
                    const otherDropdowns = document.querySelectorAll("[id^='kategoriDropdown']");

                    otherDropdowns.forEach(dd => {
                        if (dd.id !== 'kategoriDropdownMerchant' && !dd.classList.contains('hidden')) {
                            hideDropdown(dd);
                        }
                    });

                    if (isHidden) {
                        dropdown.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            dropdown.classList.remove('opacity-0', 'translate-y-1');
                            dropdown.classList.add('opacity-100', 'translate-y-0');
                        });
                    } else {
                        hideDropdown(dropdown);
                    }
                }

                function toggleKategoriDropdownTelkom() {
                    const dropdown = document.getElementById('kategoriDropdownTelkom');
                    if (!dropdown) return;

                    const isHidden = dropdown.classList.contains('hidden');
                    const otherDropdowns = document.querySelectorAll("[id^='kategoriDropdown']");

                    otherDropdowns.forEach(dd => {
                        if (dd.id !== 'kategoriDropdownTelkom' && !dd.classList.contains('hidden')) {
                            hideDropdown(dd);
                        }
                    });

                    if (isHidden) {
                        dropdown.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            dropdown.classList.remove('opacity-0', 'translate-y-1');
                            dropdown.classList.add('opacity-100', 'translate-y-0');
                        });
                    } else {
                        hideDropdown(dropdown);
                    }
                }

                document.addEventListener('click', function(event) {
                    const merchantDropdown = document.getElementById('kategoriDropdownMerchant');
                    const telkomDropdown = document.getElementById('kategoriDropdownTelkom');

                    const clickMerchant = event.target.closest('#kategoriDropdownMerchant') || event.target.closest('#kategoriBtnMerchant');
                    const clickTelkom = event.target.closest('#kategoriDropdownTelkom') || event.target.closest('#kategoriBtnTelkom');
                    const clickAll1 = event.target.closest('#kategoriDropdownAll1') || event.target.closest('#kategoriBtnAll1');
                    const clickAll3 = event.target.closest('#kategoriDropdownAll3') || event.target.closest('#kategoriBtnAll3');

                    if (!clickMerchant && merchantDropdown && !merchantDropdown.classList.contains('hidden')) {
                        hideDropdown(merchantDropdown);
                    }

                    if (!clickTelkom && telkomDropdown && !telkomDropdown.classList.contains('hidden')) {
                        hideDropdown(telkomDropdown);
                    }

                    if (!clickAll1) {
                        const dropdownAll1 = document.getElementById('kategoriDropdownAll1');
                        if (dropdownAll1 && !dropdownAll1.classList.contains('hidden')) {
                            hideDropdown(dropdownAll1);
                        }
                    }

                    if (!clickAll3) {
                        const dropdownAll3 = document.getElementById('kategoriDropdownAll3');
                        if (dropdownAll3 && !dropdownAll3.classList.contains('hidden')) {
                            hideDropdown(dropdownAll3);
                        }
                    }
                });

                ////////////////////////////////////////////////////////////////////
                // Tab Switching Functions
                ////////////////////////////////////////////////////////////////////

                // Store current active tab - get from URL or default to merchant
                const urlParams = new URLSearchParams(window.location.search);
                let tabParam = urlParams.get('tab');
                // Normalize: if tab is not 'keyword', default to 'merchant'
                let currentActiveTab = (tabParam === 'keyword') ? 'keyword' : 'merchant';
                // Make it accessible from partials
                window.currentActiveTab = currentActiveTab;

                function switchTab(tab) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);

                    currentActiveTab = tab;
                    window.currentActiveTab = tab;

                    const tabs = ['merchant', 'keyword'];
                    tabs.forEach(t => {
                        const btn = document.getElementById('tab-' + t);
                        if (btn) {
                            btn.className = 'shrink-0 px-6 py-2 rounded-full border border-orange-400 text-gray-700 hover:bg-orange-50 transition-colors';
                        }
                    });

                    const activeBtn = document.getElementById('tab-' + tab);
                    if (activeBtn) {
                        activeBtn.className = 'shrink-0 px-6 py-2 rounded-full border border-orange-400 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium shadow-lg';
                    }

                    const sections = ['merchant', 'keyword'];
                    sections.forEach(s => {
                        const section = document.getElementById('section-' + s);
                        if (section) {
                            if (s !== tab) {
                                section.classList.remove('opacity-100', 'translate-y-0');
                                section.classList.add('opacity-0', 'translate-y-5', 'pointer-events-none', 'hidden');
                            }
                        }
                    });

                    const activeSection = document.getElementById('section-' + tab);
                    if (activeSection) {
                        activeSection.classList.remove('hidden', 'pointer-events-none');
                        requestAnimationFrame(() => {
                            activeSection.classList.remove('opacity-0', 'translate-y-5');
                            activeSection.classList.add('opacity-100', 'translate-y-0');
                        });
                    }
                }

                ////////////////////////////////////////////////////////////////////
                // Filtering Function
                ////////////////////////////////////////////////////////////////////

                function filterTable(tableType, category) {
                    const incomingCategory = category || '';
                    const normalizedIncoming = incomingCategory.toLowerCase();

                    const prev = selectedCategory[tableType] || 'Semua';
                    if ((prev || '').toLowerCase() === normalizedIncoming) {
                        category = 'Semua';
                    }
                    selectedCategory[tableType] = category;

                    // ====== (update label & style tombol kategori) ======
                    let buttonId = '';
                    if (tableType === 'merchant') {
                        buttonId = 'kategoriBtnMerchant';
                    } else if (tableType === 'telkom') {
                        buttonId = 'kategoriBtnTelkom';
                    }

                    const button = document.getElementById(buttonId);
                    if (button) {
                        const label = category === 'Semua' ? 'Kategori' : category;
                        button.className = 'flex items-center px-4 py-2 text-sm rounded-full border transition-all duration-300';

                        if (category === 'Semua') {
                            button.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                        } else if (category === 'Kuliner') {
                            button.classList.add('border-orange-300', 'text-orange-800', 'bg-gradient-to-r', 'from-orange-100', 'to-red-100', 'hover:from-orange-200', 'hover:to-red-200');
                        } else if (category === 'Hiburan') {
                            button.classList.add('border-purple-300', 'text-purple-800', 'bg-gradient-to-r', 'from-purple-100', 'to-pink-100', 'hover:from-purple-200', 'hover:to-pink-200');
                        } else if (category === 'Liburan') {
                            button.classList.add('border-blue-300', 'text-blue-800', 'bg-gradient-to-r', 'from-blue-100', 'to-cyan-100', 'hover:from-blue-200', 'hover:to-cyan-200');
                        } else if (category === 'Belanja') {
                            button.classList.add('border-green-300', 'text-green-800', 'bg-gradient-to-r', 'from-green-100', 'to-emerald-100', 'hover:from-green-200', 'hover:to-emerald-200');
                        } else if (category === 'Kecantikan') {
                            button.classList.add('border-rose-300', 'text-rose-800', 'bg-gradient-to-r', 'from-rose-100', 'to-pink-100', 'hover:from-rose-200', 'hover:to-pink-200');
                        } else {
                            button.classList.add('border-orange-300', 'text-orange-800', 'bg-gradient-to-r', 'from-orange-100', 'to-yellow-100', 'hover:from-orange-200', 'hover:to-yellow-200');
                        }

                        button.innerHTML = `
                            <i class="fas fa-list mr-2"></i>
                            ${label}
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        `;
                    }

                    // ====== (update dropdown item active state) ======
                    if (tableType === 'merchant') {
                        const dropdownItems = document.querySelectorAll('#kategoriDropdownMerchant a[data-category]');
                        dropdownItems.forEach(item => {
                            const itemCategory = item.getAttribute('data-category');
                            const normalizedItemCategory = (itemCategory || '').toLowerCase();
                            const normalizedCurrentCategory = (category || '').toLowerCase();

                            // Reset semua item ke state default
                            item.classList.remove('bg-gray-100', 'bg-gradient-to-r', 
                                'from-orange-100', 'to-red-100', 'text-orange-900',
                                'from-purple-100', 'to-pink-100', 'text-purple-900',
                                'from-blue-100', 'to-cyan-100', 'text-blue-900',
                                'from-green-100', 'to-emerald-100', 'text-green-900',
                                'from-rose-100', 'to-pink-100', 'text-rose-900',
                                'from-red-100', 'to-orange-100', 'text-red-900',
                                'text-gray-900');

                            // Apply active state jika kategori cocok
                            if (normalizedItemCategory === normalizedCurrentCategory || 
                                (normalizedCurrentCategory === 'semua' && normalizedItemCategory === 'semua')) {
                                
                                if (category === 'Semua') {
                                    item.classList.add('bg-gray-100', 'text-gray-900');
                                } else if (category === 'Kuliner') {
                                    item.classList.add('bg-gradient-to-r', 'from-orange-100', 'to-red-100', 'text-orange-900');
                                } else if (category === 'Hiburan') {
                                    item.classList.add('bg-gradient-to-r', 'from-purple-100', 'to-pink-100', 'text-purple-900');
                                } else if (category === 'Liburan') {
                                    item.classList.add('bg-gradient-to-r', 'from-blue-100', 'to-cyan-100', 'text-blue-900');
                                } else if (category === 'Belanja') {
                                    item.classList.add('bg-gradient-to-r', 'from-green-100', 'to-emerald-100', 'text-green-900');
                                } else if (category === 'Kecantikan') {
                                    item.classList.add('bg-gradient-to-r', 'from-rose-100', 'to-pink-100', 'text-rose-900');
                                } else {
                                    item.classList.add('bg-gradient-to-r', 'from-red-100', 'to-orange-100', 'text-red-900');
                                }
                            }
                        });

                        const dropdown = document.getElementById('kategoriDropdownMerchant');
                        if (dropdown && !dropdown.classList.contains('hidden')) {
                            hideDropdown(dropdown);
                        }
                    } else if (tableType === 'telkom') {
                        const dropdownItems = document.querySelectorAll('#kategoriDropdownTelkom a[data-category]');
                        dropdownItems.forEach(item => {
                            const itemCategory = item.getAttribute('data-category');
                            const normalizedItemCategory = (itemCategory || '').toLowerCase();
                            const normalizedCurrentCategory = (category || '').toLowerCase();

                            item.classList.remove('bg-gray-100', 'text-gray-900', 'bg-gradient-to-r',
                                'from-orange-100', 'to-red-100', 'text-orange-900',
                                'from-purple-100', 'to-pink-100', 'text-purple-900');

                            if (normalizedItemCategory === normalizedCurrentCategory || 
                                (normalizedCurrentCategory === 'semua' && normalizedItemCategory === 'semua')) {
                                item.classList.add('bg-gray-100', 'text-gray-900');
                            }
                        });

                        const dropdown = document.getElementById('kategoriDropdownTelkom');
                        if (dropdown && !dropdown.classList.contains('hidden')) {
                            hideDropdown(dropdown);
                        }
                    }

                    // Mapping table type to actual DOM elements
                    let tableBodyId = '';
                    let rowClass = '';

                    if (tableType === 'merchant') {
                        tableBodyId = 'merchant-table-body';
                        rowClass = 'merchant-row';
                    } else if (tableType === 'telkom') {
                        tableBodyId = 'telkom-table-body';
                        rowClass = 'telkom-row';
                    }

                    const normalizedSelected = (selectedCategory[tableType] || '').toLowerCase();
                    const tableBody = document.getElementById(tableBodyId);

                    if (tableBody) {
                        const rows = tableBody.querySelectorAll(`.${rowClass}`);
                        rows.forEach((row, index) => {
                            const rowCategory = (row.getAttribute('data-category') || '').toLowerCase();
                            const shouldShow = !normalizedSelected || normalizedSelected === 'semua' || normalizedSelected === 'all' || rowCategory === normalizedSelected;
                            
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
                    }

                    // Filter juga tampilan mobile (cards)
                    if (tableType === 'merchant') {
                        const cardsContainer = document.getElementById('merchant-cards-container');
                        if (cardsContainer) {
                            const cards = cardsContainer.querySelectorAll('[data-category]');
                            cards.forEach((card, index) => {
                                const cardCategory = (card.getAttribute('data-category') || '').toLowerCase();
                                const shouldShow = !normalizedSelected || normalizedSelected === 'semua' || normalizedSelected === 'all' || cardCategory === normalizedSelected;
                                
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
                    }
                }

                ////////////////////////////////////////////////////////////////////
                // Upload & AJAX (Merchant, Keyword, etc)
                ////////////////////////////////////////////////////////////////////

                function openUploadMerchant() {
                    const modal = document.getElementById('uploadMerchantModal');
                    if (!modal) return;
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        modal.classList.add('opacity-100');
                    }, 10);
                }

                function closeUploadMerchant() {
                    const modal = document.getElementById('uploadMerchantModal');
                    if (!modal) return;
                    modal.classList.remove('opacity-100');
                    modal.classList.add('opacity-0');
                    setTimeout(() => modal.classList.add('hidden'), 150);
                }

                function handleMerchantFormSubmit(event) {
                    event.preventDefault();

                    const form = event.target;
                    const url = form.action;
                    const formData = new FormData(form);

                    const submitBtn = form.querySelector('button[type="submit"]');
                    const submitText = submitBtn.querySelector('.submit-text');
                    const submitSpinner = submitBtn.querySelector('.submit-spinner');

                    submitBtn.disabled = true;
                    submitText.classList.add('hidden');
                    submitSpinner.classList.remove('hidden');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeUploadMerchant();

                            const merchantTableContainer = document.getElementById('merchant-table-container');
                            if (merchantTableContainer && data.tableHtml) {
                                merchantTableContainer.innerHTML = data.tableHtml;
                            }

                            const cardsContainer = document.getElementById('merchant-cards-container');
                            if (cardsContainer && data.cardsHtml) {
                                cardsContainer.innerHTML = data.cardsHtml;
                            }
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat upload.');
                        }
                    })
                    .catch(error => {
                        console.error('Merchant upload error:', error);
                        alert('Terjadi kesalahan saat upload. Silakan coba lagi.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.classList.remove('hidden');
                        submitSpinner.classList.add('hidden');
                    });
                }

            </script>

            <div id="section-keyword" class="transition-all duration-300 {{ $activeTab === 'keyword' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 hidden pointer-events-none' }}">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Keyword</h2>
                
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div class="flex space-x-3">
                        <div class="relative">
                            <button id="statusBtnKeyword" onclick="toggleKeywordStatusDropdown()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-filter mr-2"></i>
                                Status
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div id="statusDropdownKeyword" class="hidden absolute left-0 right-0 sm:right-auto sm:left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-full sm:w-56 sm:max-w-none max-w-full z-40 transition-all duration-300 ease-out opacity-0 translate-y-1 max-h-[80vh] overflow-y-auto">
                                <div class="py-1">
                                    <a href="#" id="status-item-all" data-status="all" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('all'); return false;">All</a>
                                    <a href="#" id="status-item-pending" data-status="pending" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-100 hover:text-yellow-900 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('pending'); return false;">Pending</a>
                                    <a href="#" id="status-item-reject" data-status="reject" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-900 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('reject'); return false;">Rejected</a>
                                    <a href="#" id="status-item-approve" data-status="approve" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100 hover:text-green-900 rounded-lg transition-all duration-300" onclick="filterKeywordByStatus('approve'); return false;">Approved</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button
                                type="button"
                                onclick="openUploadKeyword()"
                                class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Add Keyword
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="keywordSearch" placeholder="Search keyword..." class="w-full sm:w-64 pl-9 pr-3 py-2.5 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search text-sm"></i>
                            </div>
                        </div>
                        
                        @include('partials.date-filter', ['filterId' => 'dateFilterKeyword'])
                    </div>
                    
                </div>
                
                <div id="keyword-table-container">
                    @include('partials.table-keyword')
                </div>
            </div>

            <div id="section-merchant" class="transition-all duration-300 {{ $activeTab === 'merchant' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 hidden pointer-events-none' }}">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Merchant</h2>
                
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div class="flex space-x-3">
                        <div class="relative">
                            <button id="kategoriBtnMerchant" onclick="toggleKategoriDropdownMerchant()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-list mr-2"></i>
                                Kategori
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div id="kategoriDropdownMerchant" class="hidden absolute left-0 right-0 sm:right-auto sm:left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-full sm:w-64 sm:max-w-none max-w-full z-50 transition-all duration-300 ease-out opacity-0 translate-y-1 max-h-[80vh] overflow-y-auto">
                                <div class="py-1">
                                    <a href="#" id="dropdown-item-semua" data-category="Semua" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Semua'); return false;">Semua</a>
                                    <a href="#" id="dropdown-item-kuliner" data-category="Kuliner" class="block px-4 py-2 text-sm text-orange-700 hover:bg-gradient-to-r hover:from-orange-100 hover:to-red-100 hover:text-orange-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Kuliner'); return false;">Kuliner</a>
                                    <a href="#" id="dropdown-item-hiburan" data-category="Hiburan" class="block px-4 py-2 text-sm text-purple-700 hover:bg-gradient-to-r hover:from-purple-100 hover:to-pink-100 hover:text-purple-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Hiburan'); return false;">Hiburan</a>
                                    <a href="#" id="dropdown-item-liburan" data-category="Liburan" class="block px-4 py-2 text-sm text-blue-700 hover:bg-gradient-to-r hover:from-blue-100 hover:to-cyan-100 hover:text-blue-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Liburan'); return false;">Liburan</a>
                                    <a href="#" id="dropdown-item-belanja" data-category="Belanja" class="block px-4 py-2 text-sm text-green-700 hover:bg-gradient-to-r hover:from-green-100 hover:to-emerald-100 hover:text-green-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Belanja'); return false;">Belanja</a>
                                    <a href="#" id="dropdown-item-kecantikan" data-category="Kecantikan" class="block px-4 py-2 text-sm text-rose-700 hover:bg-gradient-to-r hover:from-rose-100 hover:to-pink-100 hover:text-rose-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Kecantikan'); return false;">Kecantikan</a>
                                    <a href="#" id="dropdown-item-telkomsel" data-category="Telkomsel Packet" class="block px-4 py-2 text-sm text-red-700 hover:bg-gradient-to-r hover:from-red-100 hover:to-orange-100 hover:text-red-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Telkomsel Packet'); return false;">Telkomsel Packet</a>
                                    <a href="#" id="dropdown-item-merchandise" data-category="Merchandise" class="block px-4 py-2 text-sm text-red-700 hover:bg-gradient-to-r hover:from-red-100 hover:to-orange-100 hover:text-red-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Merchandise'); return false;">Merchandise</a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button
                                type="button"
                                onclick="openUploadMerchant()"
                                class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                <i class="fas fa-upload mr-2"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="merchantSearch" placeholder="Search merchant..." class="w-full sm:w-64 pl-9 pr-3 py-2.5 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search text-sm"></i>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div id="merchant-table-container">
                    @include('partials.table-merchant')
                </div>
            </div>
        </main>
        

        <script>
        // Search functionality for Merchant table - AJAX search across all pages
    
const merchantSearchInput = document.getElementById('merchantSearch');
let merchantSearchTimeout;
let currentMerchantQuery = new URL(window.location.href).searchParams.get('merchant_search') || '';

if (merchantSearchInput && currentMerchantQuery) {
    merchantSearchInput.value = currentMerchantQuery;
    fetchMerchantTable(buildMerchantSearchRequestUrl());
}

// Trigger search hanya saat ENTER
merchantSearchInput?.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter') {
        return;
    }

    event.preventDefault();
    currentMerchantQuery = event.target.value.trim();

    if (merchantSearchTimeout) {
        clearTimeout(merchantSearchTimeout);
    }

    merchantSearchTimeout = setTimeout(() => {
        fetchMerchantTable(buildMerchantSearchRequestUrl());
    }, 50);
});

// Kalau input dikosongkan → reload ke data awal (route admin)
merchantSearchInput?.addEventListener('input', (event) => {
    const value = event.target.value.trim();

    if (value === '') {
        currentMerchantQuery = '';

        if (merchantSearchTimeout) {
            clearTimeout(merchantSearchTimeout);
        }

        const url = new URL(window.location.href);
        url.searchParams.set('tab', 'merchant');   // pastikan tetap di tab merchant
        url.searchParams.delete('merchant_search');
        url.searchParams.delete('page');           // balik ke page 1

        window.location.href = url.toString();
    }
});


function buildMerchantSearchRequestUrl(sourceHref = null) {
    const base = new URL(sourceHref || '/merchants', window.location.origin);
    const searchUrl = new URL('/merchants/search', window.location.origin);

    base.searchParams.forEach((value, key) => {
        if (key === 'tab' || key === 'merchant_search') {
            return;
        }
        searchUrl.searchParams.set(key, value);
    });

    if (currentMerchantQuery) {
        searchUrl.searchParams.set('q', currentMerchantQuery);
    } else {
        searchUrl.searchParams.delete('q');
    }

    searchUrl.searchParams.set('tab', 'merchant');
    return searchUrl.toString();
}

function fetchMerchantTable(requestUrl) {
    const url = requestUrl || buildMerchantSearchRequestUrl();

    fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateMerchantTable(data);
        updateMerchantUrlState();
    })
    .catch(error => console.error('Merchant search error:', error));
}

function updateMerchantUrlState() {
    const url = new URL(window.location.href);
    url.searchParams.set('tab', 'merchant');

    if (currentMerchantQuery) {
        url.searchParams.set('merchant_search', currentMerchantQuery);
    } else {
        url.searchParams.delete('merchant_search');
    }

    window.history.replaceState({}, '', url);
}

function updateMerchantTable(data) {
    const merchants = data.merchants;
    const pagination = data.pagination;
    const container = document.getElementById('merchant-table-container');

    if (!container) {
        return;
    }

    container.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
    container.style.opacity = '0';
    container.style.transform = 'translateY(8px)';

    setTimeout(() => {
        const tableBody = document.getElementById('merchant-table-body');
        if (tableBody) {
            if (!merchants || merchants.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="11" class="px-4 py-4 text-center text-sm text-gray-500">Belum ada data merchant.</td></tr>';
            } else {
                tableBody.innerHTML = merchants.map((merchant, index) => `
                    <tr class="hover:bg-gray-50 transition-colors merchant-row cursor-pointer" data-category="${merchant.kategori || 'All'}"
                        onclick="window.location='/merchants/${merchant.id}'">
                        <td class="px-4 py-4 w-20 text-center text-sm font-medium text-gray-900">${(pagination.current_page - 1) * pagination.per_page + index + 1}</td>
                        <td class="px-4 py-4 w-20 text-center">
                            <div class="flex items-center justify-center h-full">
                                <button type="button"
                                        onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', '${merchant.nama_merchant.replace(/'/g, "\\'")}', ${merchant.id})"
                                        class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                        title="Hapus">
                                    <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">${merchant.daerah}</td>
                        <td class="px-4 py-4 w-20 text-center text-sm font-semibold text-gray-900">${merchant.nama_merchant}</td>
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">${merchant.kategori || '-'}</td>
                        <td class="px-4 py-4 w-20 text-center text-sm text-gray-700">
                            ${merchant.logo_merchant ? `
                                <a href="/storage/${merchant.logo_merchant}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center h-10 w-10 rounded-lg overflow-hidden border border-gray-300 hover:border-blue-500 transition-colors hover:shadow-md">
                                    <img src="/storage/${merchant.logo_merchant}" 
                                         alt="${merchant.nama_merchant}" 
                                         class="h-full w-full object-cover">
                                </a>
                            ` : '<span class="text-gray-400">-</span>'}
                        </td>
                    </tr>
                `).join('');
            }
        }

        const cardsContainer = document.getElementById('merchant-cards-container');
        if (cardsContainer) {
            if (!merchants || merchants.length === 0) {
                cardsContainer.innerHTML = '<p class="text-sm text-center text-gray-500">Belum ada data merchant.</p>';
            } else {
                cardsContainer.innerHTML = merchants.map((merchant, index) => `
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col space-y-3 merchant-row cursor-pointer" data-category="${merchant.kategori || 'All'}"
                         onclick="window.location='/merchants/${merchant.id}'">
                        <div class="flex items-start justify-between pb-3 border-b border-gray-200">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">No</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">${(pagination.current_page - 1) * pagination.per_page + index + 1}</p>
                            </div>
                            <div class="flex items-center">
                                <button type="button"
                                        onclick="event.stopPropagation(); showDeleteConfirmation('Merchant', '${merchant.nama_merchant.replace(/'/g, "\\'")}', ${merchant.id})"
                                        class="flex items-center justify-center h-6 w-6 hover:opacity-70 transition-opacity"
                                        title="Hapus">
                                    <i class="fas fa-trash text-red-600 text-lg leading-none"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Daerah</p>
                            <p class="text-sm text-gray-700 mt-1">${merchant.daerah}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Merchant</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">${merchant.nama_merchant}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Kategori</p>
                            <p class="text-sm text-gray-700 mt-1">${merchant.kategori || '-'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Logo Merchant</p>
                            <div class="mt-2 flex items-center space-x-2">
                                ${merchant.logo_merchant ? `
                                    <button type="button" 
                                            onclick="event.stopPropagation(); previewMerchantLogo('/storage/${merchant.logo_merchant}', '${merchant.logo_merchant.split('/').pop()}')"
                                            class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden border border-gray-200 hover:border-gray-300 transition-colors">
                                        <img src="/storage/${merchant.logo_merchant}" 
                                             alt="${merchant.nama_merchant}" 
                                             class="h-full w-full object-cover">
                                    </button>
                                    <span class="text-sm text-gray-700 font-medium">${merchant.nama_merchant}</span>
                                ` : '<span class="text-sm text-gray-400">-</span>'}
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        updatePagination(pagination);

        void container.offsetWidth;
        container.style.opacity = '1';
        container.style.transform = 'translateY(0)';
    }, 200);
}

function updatePagination(pagination) {
    const paginationContainer = document.querySelector('.bg-white.px-4.py-4.border-t.border-gray-200');
    if (!paginationContainer) return;
    
    let paginationHTML = `
        <div class="text-sm text-gray-600">
            Menampilkan <span class="font-semibold">${pagination.from || 0}</span> hingga <span class="font-semibold">${pagination.to || 0}</span> dari <span class="font-semibold">${pagination.total}</span> data
        </div>
        <div class="flex items-center space-x-2">
    `;
    
    if (pagination.current_page === 1) {
        paginationHTML += '<button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"><i class="fas fa-chevron-left"></i></button>';
    } else {
        paginationHTML += `<button onclick="searchPage(${pagination.current_page - 1})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"><i class="fas fa-chevron-left"></i></button>`;
    }
    
    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === pagination.current_page) {
            paginationHTML += `<button disabled class="px-3 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#F81611] to-[#F0B100] rounded-lg">${i}</button>`;
        } else {
            paginationHTML += `<button onclick="searchPage(${i})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">${i}</button>`;
        }
    }
    
    if (pagination.current_page === pagination.last_page) {
        paginationHTML += '<button disabled class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"><i class="fas fa-chevron-right"></i></button>';
    } else {
        paginationHTML += `<button onclick="searchPage(${pagination.current_page + 1})" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"><i class="fas fa-chevron-right"></i></button>`;
    }
    
    paginationHTML += '</div>';
    paginationContainer.innerHTML = paginationHTML;
}

function searchPage(page) {
    const baseUrl = buildMerchantSearchRequestUrl();
    const url = new URL(baseUrl);
    url.searchParams.set('page', page);

    fetchMerchantTable(url.toString());
    window.scrollTo({ top: 0, behavior: 'smooth' });
}


      
        const keywordSearchInput = document.getElementById('keywordSearch');
        let keywordSearchTimeout;
        let currentKeywordQuery = new URL(window.location.href).searchParams.get('keyword_search') || '';

        if (keywordSearchInput && currentKeywordQuery) {
            keywordSearchInput.value = currentKeywordQuery;
            fetchKeywordTable(buildKeywordSearchRequestUrl());
        }

        // Trigger search hanya saat Enter, tapi kalau input dikosongkan,
        // otomatis reset tabel tanpa perlu Enter lagi.
        keywordSearchInput?.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            currentKeywordQuery = event.target.value.trim();

            if (keywordSearchTimeout) {
                clearTimeout(keywordSearchTimeout);
            }

            keywordSearchTimeout = setTimeout(() => {
                fetchKeywordTable(buildKeywordSearchRequestUrl());
            }, 50);
        });

        keywordSearchInput?.addEventListener('input', (event) => {
            const value = event.target.value.trim();

            // Kalau dikosongkan, langsung reset ke semua data
            if (value === '' && currentKeywordQuery !== '') {
                currentKeywordQuery = '';

                if (keywordSearchTimeout) {
                    clearTimeout(keywordSearchTimeout);
                }

                keywordSearchTimeout = setTimeout(() => {
                    fetchKeywordTable(buildKeywordSearchRequestUrl());
                }, 50);
            }
        });

        function buildKeywordSearchRequestUrl(sourceHref = null) {
            const base = new URL(sourceHref || '/keywords', window.location.origin);
            const searchUrl = new URL('/keywords/search', window.location.origin);

            base.searchParams.forEach((value, key) => {
                if (key === 'tab' || key === 'keyword_search') {
                    return;
                }
                searchUrl.searchParams.set(key, value);
            });

            if (currentKeywordQuery) {
                searchUrl.searchParams.set('q', currentKeywordQuery);
            } else {
                searchUrl.searchParams.delete('q');
            }

            searchUrl.searchParams.set('tab', 'keyword');
            return searchUrl.toString();
        }

        function fetchKeywordTable(requestUrl) {
            const url = requestUrl || buildKeywordSearchRequestUrl();
            const container = document.getElementById('keyword-table-container');

            if (!container) {
                return;
            }

            // Animasi keluar (fade + slight slide)
            container.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            container.style.opacity = '0';
            container.style.transform = 'translateY(8px)';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    // Ganti konten setelah animasi keluar selesai
                    setTimeout(() => {
                        container.innerHTML = data.html;
                        attachKeywordPaginationHandlers();
                        updateKeywordUrlState();

                        // Trigger reflow sebelum animasi masuk
                        void container.offsetWidth;

                        // Animasi masuk (fade + slide up)
                        container.style.opacity = '1';
                        container.style.transform = 'translateY(0)';
                    }, 200);
                }
            })
            .catch(error => console.error('Keyword search error:', error));
        }

        function attachKeywordPaginationHandlers() {
            const container = document.getElementById('keyword-table-container');
            if (!container) return;

            container.querySelectorAll('.keyword-pagination-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const requestUrl = buildKeywordSearchRequestUrl(this.href);
                    fetchKeywordTable(requestUrl);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        }

        function updateKeywordUrlState() {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'keyword');
            if (currentKeywordQuery) {
                url.searchParams.set('keyword_search', currentKeywordQuery);
            } else {
                url.searchParams.delete('keyword_search');
            }
            window.history.replaceState({}, '', url);
        }

        attachKeywordPaginationHandlers();

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const arrow = document.getElementById('userDropdownArrow');
            if (!dropdown) return;

            if (dropdown.classList.contains('opacity-0')) {
                dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.add('opacity-100', 'visible', 'scale-100');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener('click', function(event) {
            const btn = document.getElementById('userDropdownBtn');
            const dropdown = document.getElementById('userDropdown');
            if (!btn || !dropdown) return;

            if (!btn.contains(event.target) && !dropdown.contains(event.target) && dropdown.classList.contains('opacity-100')) {
                toggleUserDropdown();
            }
        });

        function previewKeywordImage(imageUrl, fileName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">${fileName}</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <img src="${imageUrl}" alt="${fileName}" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Initialize dropdown active state on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Update dropdown item active state for merchant
            const merchantCategory = selectedCategory.merchant || 'Semua';
            const dropdownItems = document.querySelectorAll('#kategoriDropdownMerchant a[data-category]');
            dropdownItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                const normalizedItemCategory = (itemCategory || '').toLowerCase();
                const normalizedCurrentCategory = (merchantCategory || '').toLowerCase();

                // Reset semua item ke state default
                item.classList.remove('bg-gray-100', 'bg-gradient-to-r', 
                    'from-orange-100', 'to-red-100', 'text-orange-900',
                    'from-purple-100', 'to-pink-100', 'text-purple-900',
                    'from-blue-100', 'to-cyan-100', 'text-blue-900',
                    'from-green-100', 'to-emerald-100', 'text-green-900',
                    'from-rose-100', 'to-pink-100', 'text-rose-900',
                    'from-red-100', 'to-orange-100', 'text-red-900',
                    'text-gray-900');

                // Apply active state jika kategori cocok
                if (normalizedItemCategory === normalizedCurrentCategory || 
                    (normalizedCurrentCategory === 'semua' && normalizedItemCategory === 'semua')) {
                    
                    if (merchantCategory === 'Semua') {
                        item.classList.add('bg-gray-100', 'text-gray-900');
                    } else if (merchantCategory === 'Kuliner') {
                        item.classList.add('bg-gradient-to-r', 'from-orange-100', 'to-red-100', 'text-orange-900');
                    } else if (merchantCategory === 'Hiburan') {
                        item.classList.add('bg-gradient-to-r', 'from-purple-100', 'to-pink-100', 'text-purple-900');
                    } else if (merchantCategory === 'Liburan') {
                        item.classList.add('bg-gradient-to-r', 'from-blue-100', 'to-cyan-100', 'text-blue-900');
                    } else if (merchantCategory === 'Belanja') {
                        item.classList.add('bg-gradient-to-r', 'from-green-100', 'to-emerald-100', 'text-green-900');
                    } else if (merchantCategory === 'Kecantikan') {
                        item.classList.add('bg-gradient-to-r', 'from-rose-100', 'to-pink-100', 'text-rose-900');
                    } else {
                        item.classList.add('bg-gradient-to-r', 'from-red-100', 'to-orange-100', 'text-red-900');
                    }
                }
            });

            // Update dropdown item active state for keyword status
            const keywordStatus = selectedKeywordStatus || 'all';
            const statusDropdownItems = document.querySelectorAll('#statusDropdownKeyword a[data-status]');
            statusDropdownItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                const normalizedItemStatus = (itemStatus || '').toLowerCase();
                const normalizedCurrentStatus = (keywordStatus || '').toLowerCase();

                // Reset semua item ke state default
                item.classList.remove('bg-gray-100', 'bg-yellow-100', 'bg-red-100', 'bg-green-100',
                    'text-gray-900', 'text-yellow-900', 'text-red-900', 'text-green-900');

                // Apply active state jika status cocok
                if (normalizedItemStatus === normalizedCurrentStatus || 
                    (normalizedCurrentStatus === 'all' && normalizedItemStatus === 'all')) {
                    
                    if (keywordStatus === 'all') {
                        item.classList.add('bg-gray-100', 'text-gray-900');
                    } else if (keywordStatus === 'pending') {
                        item.classList.add('bg-yellow-100', 'text-yellow-900');
                    } else if (keywordStatus === 'reject') {
                        item.classList.add('bg-red-100', 'text-red-900');
                    } else if (keywordStatus === 'approve') {
                        item.classList.add('bg-green-100', 'text-green-900');
                    }
                }
            });
        });

    </script>
        @include('partials.upload-modal-merchant')
        @include('partials.upload-modal-merchandise')
        @include('partials.upload-modal-keyword')
        @include('partials.edit-modal-merchant')
        @include('partials.edit-modal-merchandise')
        @include('partials.edit-modal-keyword')
        @include('partials.delete-confirmation-modal')
        @include('partials.approve-confirmation-modal')

</body>
</html>
