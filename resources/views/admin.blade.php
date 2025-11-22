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

    <nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-5 lg:py-6 relative">
     <div class="flex items-center justify-between">
      <div class="flex items-center gap-6">
       <img src="/logo.png" alt="BlanjaPoin" class="h-10 md:h-12 lg:h-14 w-auto" />
      </div>

      <div class="hidden md:flex items-center gap-6 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
      @if(Auth::check() && Auth::user()->can_approve == 1) 
        <a href="{{ route('admin') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">Home</a>
        <a href="{{ route('user.management') }}" class="text-sm font-semibold bg-gradient-to-r from-[#F81611] to-[#F0B100] bg-clip-text text-transparent hover:opacity-80 transition-opacity">User Management</a>       @endif
      </div>

       <div class="relative">
        <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="inline-flex items-center gap-1.5 md:gap-2 rounded-xl md:rounded-2xl bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] px-4 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white shadow-lg shadow-orange-300/50 drop-shadow-lg ring-1 ring-white/30 transition-all hover:shadow-xl hover:shadow-orange-400/50 hover:drop-shadow-xl hover:scale-105 active:scale-95">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 md:h-4 md:w-4 opacity-95">
          <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
         </svg>
         <span class="tracking-tight">{{ Auth::user()->username }}</span>
         <svg id="userDropdownArrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 md:h-3.5 md:w-3.5 opacity-95 transition-transform duration-300">
          <path d="M7 10l5 5 5-5z"/>
         </svg>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-neutral-200 overflow-hidden opacity-0 invisible scale-95 origin-top-right transition-all duration-300 ease-out z-50 backdrop-blur-sm">
         <div class="py-1">
          <form method="POST" action="{{ route('logout') }}">
           @csrf
           <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
             <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd" />
            </svg>
            <span>Logout</span>
           </button>
          </form>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </nav>
        <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
            <div class="mb-6 -mx-4 sm:mx-0 overflow-x-auto">
                <div class="flex space-x-3 px-4 sm:px-0 min-w-max">
                    <!-- TAB MERCHANT (default active) -->
                    <button
                        onclick="switchTab('merchant')"
                        id="tab-merchant"
                        class="shrink-0 px-6 py-2 rounded-full border border-orange-400
                               bg-gradient-to-r from-[#F81611] to-[#F0B100]
                               text-white font-medium shadow-lg">
                        Merchant
                    </button>

                    <!-- TAB KEYWORD -->
                    <button
                        onclick="switchTab('keyword')"
                        id="tab-keyword"
                        class="shrink-0 px-6 py-2 rounded-full border border-orange-400
                               text-gray-700 hover:bg-orange-50 transition-colors">
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

                document.addEventListener('click', function(event) {
                    if (!event.target.closest('#statusDropdownKeyword') && 
                        !event.target.closest('#statusBtnKeyword')) {
                        const dropdown = document.getElementById('statusDropdownKeyword');
                        if (dropdown) {
                            dropdown.classList.remove('opacity-100', 'translate-y-0');
                            dropdown.classList.add('opacity-0', 'translate-y-1');
                            setTimeout(() => dropdown.classList.add('hidden'), 150);
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

                function closeAllDropdowns() {
                    const dropdownIds = [
                        'kategoriDropdownAll1',
                        'kategoriDropdownAll3',
                        'kategoriDropdownMerchant',
                        'kategoriDropdownTelkom'
                    ];

                    dropdownIds.forEach(id => {
                        const dropdown = document.getElementById(id);
                        if (dropdown) {
                            dropdown.classList.add('hidden');
                            dropdown.style.opacity = '0';
                            dropdown.style.transform = 'translateY(-10px)';
                        }
                    });
                }

                function toggleKategoriDropdownMerchant() {
                    const dropdown = document.getElementById('kategoriDropdownMerchant');
                    if (!dropdown) return;
                    
                    if (dropdown.classList.contains('hidden')) {
                        closeAllDropdowns();
                        dropdown.classList.remove('hidden');
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            dropdown.style.opacity = '1';
                            dropdown.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-10px)';
                        setTimeout(() => dropdown.classList.add('hidden'), 150);
                    }
                }

                function toggleKategoriDropdownTelkom() {
                    const dropdown = document.getElementById('kategoriDropdownTelkom');
                    if (!dropdown) return;
                    
                    if (dropdown.classList.contains('hidden')) {
                        closeAllDropdowns();
                        dropdown.classList.remove('hidden');
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            dropdown.style.opacity = '1';
                            dropdown.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        dropdown.style.opacity = '0';
                        dropdown.style.transform = 'translateY(-10px)';
                        setTimeout(() => dropdown.classList.add('hidden'), 150);
                    }
                }

                document.addEventListener('click', function(event) {
                    const kategoriDropdownMerchant = document.getElementById('kategoriDropdownMerchant');
                    const kategoriDropdownTelkom = document.getElementById('kategoriDropdownTelkom');
                    
                    const isClickInsideAnyDropdown = 
                        (kategoriDropdownMerchant && kategoriDropdownMerchant.contains(event.target)) ||
                        (kategoriDropdownTelkom && kategoriDropdownTelkom.contains(event.target)) ||
                        (event.target.closest('#kategoriBtnMerchant')) ||
                        (event.target.closest('#kategoriBtnTelkom')) ||
                        (event.target.closest('#kategoriBtnAll1')) ||
                        (event.target.closest('#kategoriBtnAll3'));

                    if (!isClickInsideAnyDropdown) {
                        closeAllDropdowns();
                    }
                });

                ////////////////////////////////////////////////////////////////////
                // Tab Switching Functions
                ////////////////////////////////////////////////////////////////////

                // Store current active tab
                let currentActiveTab = 'merchant';
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
                    closeAllDropdowns();

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
                        rows.forEach(row => {
                            const rowCategory = (row.getAttribute('data-category') || '').toLowerCase();

                            if (!normalizedSelected || normalizedSelected === 'semua' || normalizedSelected === 'all') {
                                row.style.display = '';
                            } else if (rowCategory === normalizedSelected) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    }

                    // Filter juga tampilan mobile (cards)
                    if (tableType === 'merchant') {
                        const cardsContainer = document.getElementById('merchant-cards-container');
                        if (cardsContainer) {
                            const cards = cardsContainer.querySelectorAll('[data-category]');
                            cards.forEach(card => {
                                const cardCategory = (card.getAttribute('data-category') || '').toLowerCase();

                                if (!normalizedSelected || normalizedSelected === 'semua' || normalizedSelected === 'all') {
                                    card.style.display = '';
                                } else if (cardCategory === normalizedSelected) {
                                    card.style.display = '';
                                } else {
                                    card.style.display = 'none';
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

                document.querySelectorAll('.keyword-pagination').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        const url = this.getAttribute('href');

                        fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newTable = doc.querySelector('#keyword-table-container');
                            
                            if (newTable) {
                                document.querySelector('#keyword-table-container').innerHTML = newTable.innerHTML;
                            }

                            try {
                                const newUrl = new URL(url, window.location.origin);
                                window.history.replaceState({}, '', newUrl);
                            } catch (e) {
                                console.warn('Tidak bisa update URL state:', e);
                            }
                        })
                        .catch(error => {
                            console.error('Keyword pagination AJAX error:', error);
                            window.location.href = url;
                        });
                    });
                });
            </script>

            <div id="section-keyword" class="transition-all duration-300 opacity-0 translate-y-5 hidden pointer-events-none">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Keyword</h2>
                
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div class="flex space-x-3">
                        <div class="relative">
                            <button id="statusBtnKeyword" onclick="toggleKeywordStatusDropdown()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-filter mr-2"></i>
                                Status
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div id="statusDropdownKeyword" class="hidden absolute mt-2 w-52 rounded-2xl bg-white shadow-2xl border border-gray-200 py-2 z-50">
                                <a href="#" onclick="event.preventDefault();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Semua</a>
                                <a href="#" onclick="event.preventDefault();" class="block px-4 py-2 text-sm text-green-700 hover:bg-green-50">Aktif</a>
                                <a href="#" onclick="event.preventDefault();" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-50">Tidak Aktif</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" placeholder="Search keyword..." class="w-full sm:w-64 pl-9 pr-3 py-2.5 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

                @include('partials.table-keyword')
            </div>

            <div id="section-merchant" class="transition-all duration-300 opacity-100 translate-y-0">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Merchant</h2>
                
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div class="flex space-x-3">
                        <div class="relative">
                            <button id="kategoriBtnMerchant" onclick="toggleKategoriDropdownMerchant()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-list mr-2"></i>
                                Kategori
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div id="kategoriDropdownMerchant" class="hidden absolute mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-64 z-50">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 hover:text-gray-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Semua'); return false;">Semua</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-orange-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-red-50 hover:text-orange-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Kuliner'); return false;">Kuliner</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-purple-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 hover:text-purple-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Hiburan'); return false;">Hiburan</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-blue-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 hover:text-blue-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Liburan'); return false;">Liburan</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-green-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Belanja'); return false;">Belanja</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-rose-700 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 hover:text-rose-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Kecantikan'); return false;">Kecantikan</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 hover:text-red-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Telkomsel Packet'); return false;">Telkomsel Packet</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 hover:text-red-900 rounded-lg transition-all duration-300" onclick="filterTable('merchant', 'Merchandise'); return false;">Merchandise</a>
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
                            <input type="text" placeholder="Search..." class="w-full sm:w-64 pl-9 pr-3 py-2.5 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search text-sm"></i>
                            </div>
                        </div>
                        
                        @include('partials.date-filter', ['filterId' => 'dateFilter4'])
                    </div>
                </div>
                
                @include('partials.table-merchant')
            </div>
        </main>
        
        @include('partials.upload-modal-merchant')
        @include('partials.upload-modal-merchandise')
        @include('partials.upload-modal-keyword')
        @include('partials.edit-modal-merchant')
        @include('partials.edit-modal-merchandise')
        @include('partials.edit-modal-keyword')
    </div>
</body>
</html>
