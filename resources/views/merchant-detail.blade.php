@php

    $keywordPaginator = $keywords->appends(array_merge(request()->query(), ['tab' => 'keyword']));
    if (!isset($allMerchants)) {
        $allMerchants = \App\Models\Merchant::orderBy('nama_merchant')->get();
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keyword • {{ $merchant->nama_merchant }} | blanjapoin.id</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
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
        #statusDropdownDetail .status-dropdown-option {
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        #statusDropdownDetail .status-dropdown-option:hover {
            background-color: #f3f4f6; /* gray-100 */
            color: #111827; /* gray-900 */
        }
        #statusDropdownDetail .status-dropdown-option[data-status="pending"]:hover {
            background-color: #fef3c7; /* yellow-100 */
            color: #78350f; /* yellow-900 */
        }
        #statusDropdownDetail .status-dropdown-option[data-status="reject"]:hover {
            background-color: #fee2e2; /* red-100 */
            color: #7f1d1d; /* red-900 */
        }
        #statusDropdownDetail .status-dropdown-option[data-status="approve"]:hover {
            background-color: #dcfce7; /* green-100 */
            color: #14532d; /* green-900 */
        }
        #statusDropdownDetail .status-dropdown-option.active-all {
            background-color: #f3f4f6; /* gray-100 */
            color: #111827; /* gray-900 */
        }
        #statusDropdownDetail .status-dropdown-option.active-pending {
            background-color: #fef3c7; /* yellow-100 */
            color: #78350f; /* yellow-900 */
        }
        #statusDropdownDetail .status-dropdown-option.active-reject {
            background-color: #fee2e2; /* red-100 */
            color: #7f1d1d; /* red-900 */
        }
        #statusDropdownDetail .status-dropdown-option.active-approve {
            background-color: #dcfce7; /* green-100 */
            color: #14532d; /* green-900 */
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc]">
<style>
.page-enter{opacity:0;transform:translateY(8px)}
.page-enter-active{opacity:1;transform:translateY(0);transition:opacity .3s ease,transform .3s ease}
.fade-in-up{opacity:0;transform:translateY(10px)}
.fade-in-up.show{opacity:1;transform:translateY(0);transition:opacity .3s ease,transform .3s ease}
</style>

    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if(session('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

<nav id="navbar" class="sticky top-0 z-20 bg-white transition-shadow duration-300 w-full page-enter">
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


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 page-enter">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Overview</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $merchant->nama_merchant }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        {{ $merchant->daerah ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i class="fas fa-tags text-gray-400"></i>
                        {{ $merchant->kategori ?? '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i class="fas fa-key text-gray-400"></i>
                        {{ $keywordPaginator->total() }} Keyword
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin', ['tab' => 'all']) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex space-x-3">
                <div class="relative overflow-visible">
                    <button id="statusBtnDetail" onclick="toggleStatusDropdownDetail()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Status
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div id="statusDropdownDetail" class="hidden absolute left-0 right-0 md:left-0 md:right-auto mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-full max-w-[18rem] md:w-56 z-50 pointer-events-auto">
                        <div class="py-1 space-y-1">
                            <a href="#" data-status="all" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('all'); return false;">All</a>
                            <a href="#" data-status="pending" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('pending'); return false;">Pending</a>
                            <a href="#" data-status="reject" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('reject'); return false;">Rejected</a>
                            <a href="#" data-status="approve" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('approve'); return false;">Approved</a>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="openUploadKeyword()" class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Keyword
                </button>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <input type="text" id="keywordSearchDetail" placeholder="Search..." class="w-full sm:w-48 pl-9 pr-9 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <button type="button" id="keywordSearchDetailClear" class="hidden absolute inset-y-0 right-2 px-2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Clear search">
                        &times;
                    </button>
                </div>

                @include('partials.date-filter', ['filterId' => 'dateFilterMerchantDetail'])
            </div>
        </div>

        @include('partials.table-keyword')

    </main>

    @include('partials.upload-modal-keyword')
    @include('partials.edit-modal-keyword')
    @include('partials.delete-confirmation-modal')
    @include('partials.approve-confirmation-modal')

    <script>
        window.fixedMerchantId = {{ $merchant->id }};
        window.fixedMerchantName = {!! json_encode($merchant->nama_merchant) !!};
    </script>

    <script>
        let detailSelectedStatus = 'all';

        function toggleStatusDropdownDetail() {
            const dropdown = document.getElementById('statusDropdownDetail');
            if (!dropdown) return;
            setActiveStatusOption(detailSelectedStatus);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('fade-in-up');
                setTimeout(() => { dropdown.classList.add('show'); }, 10);
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('show');
                dropdown.classList.remove('fade-in-up');
            }
        }

        function filterKeywordByStatusDetail(status) {
            const button = document.getElementById('statusBtnDetail');
            if (!button) return;

            if (detailSelectedStatus === status) {
                status = 'all';
            }
            detailSelectedStatus = status;
            setActiveStatusOption(status);

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

            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
            rows.forEach((row, index) => {
                const s = (row.dataset.status || '').toLowerCase();
                const normalized = s === 'approved' ? 'approve' : s === 'rejected' ? 'reject' : s;
                const matchesStatus = (status === 'all' || normalized === status);
                const matchesDate = (row.dataset.dateFilterMatch ?? 'true') !== 'false';
                const shouldShow = matchesStatus && matchesDate;

                row.dataset.statusHidden = matchesStatus ? 'false' : 'true';
                
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
                const matchesStatus = (status === 'all' || normalized === status);
                const matchesDate = (card.dataset.dateFilterMatch ?? 'true') !== 'false';
                const shouldShow = matchesStatus && matchesDate;

                card.dataset.statusHidden = matchesStatus ? 'false' : 'true';
                
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

            toggleStatusDropdownDetail();
        }

        function setActiveStatusOption(status) {
            const options = document.querySelectorAll('#statusDropdownDetail .status-dropdown-option');
            options.forEach((opt) => {
                opt.classList.remove('active-all', 'active-pending', 'active-reject', 'active-approve');
                if (opt.dataset.status === status) {
                    opt.classList.add('active-' + status);
                }
            });
        }

        const keywordSearchDetail = document.getElementById('keywordSearchDetail');
        const keywordSearchDetailClear = document.getElementById('keywordSearchDetailClear');

        function filterKeywordDetail(query) {
            const lower = (query || '').toLowerCase();
            const rows = document.querySelectorAll('#keyword-table-body tr.keyword-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(lower) ? '' : 'none';
            });
            const cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(lower) ? '' : 'none';
            });
        }

        keywordSearchDetail?.addEventListener('input', function(e) {
            const query = e.target.value;
            if (keywordSearchDetailClear) {
                keywordSearchDetailClear.classList.toggle('hidden', query.length === 0);
            }
            filterKeywordDetail(query);
        });

        keywordSearchDetailClear?.addEventListener('click', function() {
            if (!keywordSearchDetail) return;
            keywordSearchDetail.value = '';
            filterKeywordDetail('');
            keywordSearchDetailClear.classList.add('hidden');
        });

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('statusDropdownDetail');
            const button = document.getElementById('statusBtnDetail');
            if (!dropdown || !button) return;
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Pastikan redirect pada form add/edit tetap ke halaman detail ini
        function applyDetailRedirect() {
            const addRedirect = document.getElementById('keywordRedirectUpload');
            const editRedirect = document.getElementById('keywordRedirectEdit');
            const stayAdd = document.getElementById('keywordStayOnDetailUpload');
            const stayEdit = document.getElementById('keywordStayOnDetailEdit');
            if (addRedirect && window.detailRedirectUrl) addRedirect.value = window.detailRedirectUrl;
            if (editRedirect && window.detailRedirectUrl) editRedirect.value = window.detailRedirectUrl;
            if (stayAdd && window.detailRedirectUrl) stayAdd.value = '1';
            if (stayEdit && window.detailRedirectUrl) stayEdit.value = '1';
        }
        document.addEventListener('DOMContentLoaded', applyDetailRedirect);

        function toggleDateFilter(id) {
            const dropdown = document.getElementById(id);
            if (!dropdown) return;
            dropdown.classList.toggle('hidden');
        }
    </script>

    <script>
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var nav = document.getElementById('navbar');
            var mainEl = document.querySelector('main');
            if (nav) { nav.classList.add('page-enter-active'); setTimeout(function(){ nav.classList.remove('page-enter'); }, 300); }
            if (mainEl) { mainEl.classList.add('page-enter-active'); setTimeout(function(){ mainEl.classList.remove('page-enter'); }, 300); }
            var cards = document.querySelectorAll('#keyword-cards-container .keyword-row');
            cards.forEach(function(card, i) {
                card.classList.add('fade-in-up');
                setTimeout(function(){ card.classList.add('show'); }, 60 + i * 50);
            });
        });
    </script>
</body>
</html>
