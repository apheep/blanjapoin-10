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
<body class="min-h-screen bg-white">
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

@include('partials.navbar-admin')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 page-enter ">
        <div class="space-y-4 mt-6">
            <div class="space-y-2">
                <a href="{{ route('admin', ['tab' => 'all']) }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-0">Overview</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-0">{{ $merchant->nama_merchant }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Status filter -->
                    <div class="relative overflow-visible">
                        <button
                            id="statusBtnDetail"
                            onclick="toggleStatusDropdownDetail()"
                            class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <i class="fas fa-filter mr-2"></i>
                            Status
                            <i id="statusArrowDetail" class="fas fa-chevron-down ml-2 text-xs transition-transform duration-300"></i>
                        </button>
                        <div
                            id="statusDropdownDetail"
                            class="hidden absolute left-0 right-0 sm:right-auto sm:left-0 mt-2 bg-white rounded-2xl shadow-2xl p-3 border border-gray-200 w-full sm:w-56 sm:max-w-none max-w-full z-50 pointer-events-auto"
                        >
                            <div class="py-1 space-y-1">
                                <a href="#" data-status="all" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('all'); return false;">All</a>
                                <a href="#" data-status="pending" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('pending'); return false;">Pending</a>
                                <a href="#" data-status="reject" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('reject'); return false;">Rejected</a>
                                <a href="#" data-status="approve" class="status-dropdown-option block px-4 py-2 text-sm text-gray-700 rounded-lg transition-colors duration-200" onclick="filterKeywordByStatusDetail('approve'); return false;">Approved</a>
                            </div>
                        </div>
                    </div>

                    <!-- Date filter -->
                    <div class="relative">
                        @include('partials.date-filter', ['filterId' => 'dateFilterMerchantDetail'])
                    </div>

                    <!-- Add button -->
                    <div class="relative">
                        <button
                            type="button"
                            onclick="openUploadKeyword()"
                            class="flex items-center px-4 py-2 text-sm rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Add
                        </button>
                    </div>

                    <!-- Export Excel button -->
                    <div class="relative">
                        <a
                            href="{{ route('merchants.keywords.export.excel', $merchant->id) }}"
                            class="flex items-center px-4 py-2 text-sm rounded-full border border-green-300 text-green-700 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 transition-colors"
                        >
                            <i class="fas fa-file-excel mr-2"></i>
                            Export
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input
                            type="text"
                            id="keywordSearchDetail"
                            placeholder="Search keyword..."
                            class="w-full pl-10 pr-10 py-2 rounded-full border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                        >
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </div>
                        <button
                            type="button"
                            id="keywordSearchDetailClear"
                            class="hidden absolute inset-y-0 right-2 px-2 text-gray-400 hover:text-gray-600 focus:outline-none"
                            aria-label="Clear search"
                        >
                            &times;
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="keyword-table-container-detail">
            @include('partials.table-keyword')
        </div>

    </main>

    @include('partials.upload-modal-keyword')
    @include('partials.edit-modal-keyword')
    @include('partials.delete-confirmation-modal')
    @include('partials.approve-confirmation-modal')

    <script>
        window.fixedMerchantId = {{ $merchant->id }};
        window.fixedMerchantName = {!! json_encode($merchant->nama_merchant) !!};
        window.fixedMerchantStartDate = {!! json_encode($merchant->start_date ?? '') !!};
        window.fixedMerchantEndDate = {!! json_encode($merchant->end_date ?? '') !!};
    </script>

    <script>
        // =======================
        // Keyword filter & search (merchant detail) - AJAX like main Keyword section
        // =======================
        let detailSelectedStatus = new URL(window.location.href).searchParams.get('status') || 'all';
        let currentDetailKeywordQuery = new URL(window.location.href).searchParams.get('keyword_search') || '';
        let currentDetailKeywordPage = new URL(window.location.href).searchParams.get('keyword_page') || new URL(window.location.href).searchParams.get('page') || '1';

        function toggleStatusDropdownDetail() {
            const dropdown = document.getElementById('statusDropdownDetail');
            const arrow = document.getElementById('statusArrowDetail');
            if (!dropdown) return;
            setActiveStatusOption(detailSelectedStatus);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('fade-in-up');
                setTimeout(() => { dropdown.classList.add('show'); }, 10);
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('show');
                dropdown.classList.remove('fade-in-up');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
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
            button.innerHTML = `<i class="fas fa-filter mr-2"></i>${label}<i id="statusArrowDetail" class="fas fa-chevron-down ml-2 text-xs transition-transform duration-300"></i>`;

            // Reset to page 1 when filtering
            currentDetailKeywordPage = '1';
            
            // Reload table from server with current status & search term
            const requestUrl = buildDetailKeywordSearchRequestUrl();
            fetchDetailKeywordTable(requestUrl);
            updateDetailKeywordUrlState();

            toggleStatusDropdownDetail();
        }

        const keywordSearchDetail = document.getElementById('keywordSearchDetail');
        const keywordSearchDetailClear = document.getElementById('keywordSearchDetailClear');

        keywordSearchDetail?.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') {
                return;
            }
            event.preventDefault();
            currentDetailKeywordQuery = event.target.value.trim();
            // Reset to page 1 when searching
            currentDetailKeywordPage = '1';
            fetchDetailKeywordTable(buildDetailKeywordSearchRequestUrl());
            updateDetailKeywordUrlState();
        });

        keywordSearchDetail?.addEventListener('input', (event) => {
            const value = event.target.value.trim();
            if (keywordSearchDetailClear) {
                keywordSearchDetailClear.classList.toggle('hidden', value.length === 0);
            }

            // Jika dikosongkan, reset hasil
            if (value === '' && currentDetailKeywordQuery !== '') {
                currentDetailKeywordQuery = '';
                currentDetailKeywordPage = '1';
                fetchDetailKeywordTable(buildDetailKeywordSearchRequestUrl());
                updateDetailKeywordUrlState();
            }
        });

        keywordSearchDetailClear?.addEventListener('click', () => {
            if (!keywordSearchDetail) return;
            keywordSearchDetail.value = '';
            currentDetailKeywordQuery = '';
            currentDetailKeywordPage = '1';
            fetchDetailKeywordTable(buildDetailKeywordSearchRequestUrl());
            updateDetailKeywordUrlState();
            keywordSearchDetailClear.classList.add('hidden');
        });

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('statusDropdownDetail');
            const button = document.getElementById('statusBtnDetail');
            const arrow = document.getElementById('statusArrowDetail');
            if (!dropdown || !button) return;
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });

        function buildDetailKeywordSearchRequestUrl(sourceHref = null) {
            const base = new URL(
                sourceHref || ('/merchants/' + window.fixedMerchantId),
                window.location.origin
            );
            const searchUrl = new URL('/keywords/search', window.location.origin);

            base.searchParams.forEach((value, key) => {
                // Abaikan param yang akan kita kelola sendiri
                if (key === 'tab' || key === 'keyword_search' || key === 'status' || key === 'keyword_page' || key === 'page') {
                    return;
                }
                searchUrl.searchParams.set(key, value);
            });

            if (currentDetailKeywordQuery) {
                searchUrl.searchParams.set('q', currentDetailKeywordQuery);
            } else {
                searchUrl.searchParams.delete('q');
            }

            if (detailSelectedStatus && detailSelectedStatus !== 'all') {
                searchUrl.searchParams.set('status', detailSelectedStatus);
            } else {
                searchUrl.searchParams.delete('status');
            }

            // Tambahkan parameter page jika ada
            if (currentDetailKeywordPage && currentDetailKeywordPage !== '1') {
                searchUrl.searchParams.set('keyword_page', currentDetailKeywordPage);
            } else {
                searchUrl.searchParams.delete('keyword_page');
            }

            // Batasi ke merchant saat ini
            if (window.fixedMerchantId) {
                searchUrl.searchParams.set('merchant_id', window.fixedMerchantId);
            }

            searchUrl.searchParams.set('tab', 'keyword');
            return searchUrl.toString();
        }

        function fetchDetailKeywordTable(requestUrl) {
            const url = requestUrl || buildDetailKeywordSearchRequestUrl();
            const container = document.getElementById('keyword-table-container-detail');
            if (!container) return;

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
                        setTimeout(() => {
                            container.innerHTML = data.html;
                            
                            // Execute scripts from the injected HTML
                            const scripts = container.querySelectorAll('script');
                            scripts.forEach(oldScript => {
                                const newScript = document.createElement('script');
                                Array.from(oldScript.attributes).forEach(attr => {
                                    newScript.setAttribute(attr.name, attr.value);
                                });
                                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                                oldScript.parentNode.replaceChild(newScript, oldScript);
                            });
                            
                            attachDetailKeywordPaginationHandlers();
                            attachDetailKeywordToggleHandlers();
                            updateDetailKeywordUrlState();

                            // Re-apply date filter jika sebelumnya sudah di-set
                            if (window.dateFilterState?.dateFilterMerchantDetail &&
                                (window.dateFilterState.dateFilterMerchantDetail.start || window.dateFilterState.dateFilterMerchantDetail.end)) {
                                applyDateFilterCompact('dateFilterMerchantDetail');
                            }

                            void container.offsetWidth;
                            container.style.opacity = '1';
                            container.style.transform = 'translateY(0)';
                        }, 200);
                    }
                })
                .catch(error => console.error('Detail keyword search error:', error));
        }

        function attachDetailKeywordPaginationHandlers() {
            const container = document.getElementById('keyword-table-container-detail');
            if (!container) return;

            // Handle all links in pagination nav (page numbers, prev, next)
            container.querySelectorAll('nav[aria-label="Pagination"] a').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    // Extract page number from href
                    const hrefUrl = new URL(this.href, window.location.origin);
                    const pageParam = hrefUrl.searchParams.get('keyword_page') || hrefUrl.searchParams.get('page') || '1';
                    currentDetailKeywordPage = pageParam;
                    
                    const requestUrl = buildDetailKeywordSearchRequestUrl(this.href);
                    fetchDetailKeywordTable(requestUrl);
                    updateDetailKeywordUrlState();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        }

        // Function to attach toggle keyword status handlers after AJAX reload
        function attachDetailKeywordToggleHandlers() {
            // Track ongoing requests to prevent duplicate toggles
            if (!window.keywordToggleInProgress) {
                window.keywordToggleInProgress = new Set();
            }

            // Define toggleKeywordStatus function specifically for merchant-detail page
            // This will override or complement the one from table-keyword.blade.php
            window.toggleKeywordStatusDetail = function(keywordId) {
                const checkbox = document.querySelector(`.toggle-keyword-status[data-keyword-id="${keywordId}"]`);
                if (!checkbox) return;

                // Prevent multiple simultaneous requests for the same keyword
                if (window.keywordToggleInProgress.has(keywordId)) {
                    console.log('Toggle already in progress for keyword:', keywordId);
                    return;
                }

                // Store original state before toggle
                const originalChecked = checkbox.checked;
                
                // Mark as in progress
                window.keywordToggleInProgress.add(keywordId);
                
                // Disable checkbox during request
                checkbox.disabled = true;

                fetch(`/api/keywords/${keywordId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Gagal memperbarui status');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Update checkbox to match database value
                    if (checkbox) {
                        checkbox.checked = data.is_active;
                        checkbox.disabled = false;
                    }
                    console.log('Status keyword berhasil diperbarui', data);
                    // No need to reload table - checkbox state already updated
                })
                .catch(error => {
                    console.error('Error toggling keyword status:', error);
                    // Revert checkbox on error
                    if (checkbox) {
                        checkbox.checked = originalChecked;
                        checkbox.disabled = false;
                    }
                    alert('Gagal memperbarui status: ' + error.message);
                })
                .finally(() => {
                    // Remove from in-progress set
                    window.keywordToggleInProgress.delete(keywordId);
                });
            };

            // Use the detail-specific function, or fallback to global one
            const toggleFunction = window.toggleKeywordStatusDetail || window.toggleKeywordStatus;
            if (!window.toggleKeywordStatus) {
                window.toggleKeywordStatus = window.toggleKeywordStatusDetail;
            }

            // Attach event handlers directly to checkboxes in the container
            // This ensures they work even if event delegation from table-keyword.blade.php doesn't work
            const container = document.getElementById('keyword-table-container-detail');
            if (container) {
                container.querySelectorAll('.toggle-keyword-status').forEach(checkbox => {
                    // Remove existing listeners by cloning
                    const newCheckbox = checkbox.cloneNode(true);
                    checkbox.parentNode.replaceChild(newCheckbox, checkbox);
                    
                    // Attach fresh event listener
                    newCheckbox.addEventListener('change', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const keywordId = this.getAttribute('data-keyword-id');
                        if (keywordId) {
                            const toggleFn = window.toggleKeywordStatusDetail || window.toggleKeywordStatus;
                            if (toggleFn) {
                                toggleFn(keywordId);
                            }
                        }
                    });
                });
            }

            // Also ensure global event delegation is set up as backup
            if (!window.keywordToggleHandlerAttachedDetail) {
                document.addEventListener('change', function(e) {
                    const container = document.getElementById('keyword-table-container-detail');
                    if (container && container.contains(e.target)) {
                        if (e.target && e.target.classList.contains('toggle-keyword-status') && !e.target.disabled) {
                            const keywordId = e.target.getAttribute('data-keyword-id');
                            if (keywordId) {
                                const toggleFn = window.toggleKeywordStatusDetail || window.toggleKeywordStatus;
                                if (toggleFn) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    toggleFn(keywordId);
                                }
                            }
                        }
                    }
                });
                window.keywordToggleHandlerAttachedDetail = true;
            }
        }

        function updateDetailKeywordUrlState() {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'keyword');

            if (currentDetailKeywordQuery) {
                url.searchParams.set('keyword_search', currentDetailKeywordQuery);
            } else {
                url.searchParams.delete('keyword_search');
            }

            if (detailSelectedStatus && detailSelectedStatus !== 'all') {
                url.searchParams.set('status', detailSelectedStatus);
            } else {
                url.searchParams.delete('status');
            }

            // Update page parameter
            if (currentDetailKeywordPage && currentDetailKeywordPage !== '1') {
                url.searchParams.set('keyword_page', currentDetailKeywordPage);
                // Remove generic 'page' param if exists
                url.searchParams.delete('page');
            } else {
                url.searchParams.delete('keyword_page');
                url.searchParams.delete('page');
            }

            window.history.replaceState({}, '', url);
        }

        // Inisialisasi status dropdown & pencarian saat halaman pertama kali load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure toggle handlers are attached on initial load
            attachDetailKeywordToggleHandlers();
            
            // Initialize page from URL if available
            const urlParams = new URL(window.location.href).searchParams;
            const pageFromUrl = urlParams.get('keyword_page') || urlParams.get('page') || '1';
            currentDetailKeywordPage = pageFromUrl;
            
            // If keyword_page is in URL (not page), trigger AJAX to load correct page
            // because initial load uses 'page' parameter, not 'keyword_page'
            const hasKeywordPage = urlParams.has('keyword_page');
            const needsAjaxLoad = hasKeywordPage || detailSelectedStatus !== 'all' || currentDetailKeywordQuery;
            
            if (needsAjaxLoad) {
                if (detailSelectedStatus !== 'all') {
                    setActiveStatusOption(detailSelectedStatus);
                    // Don't call filterKeywordByStatusDetail here to avoid double filter
                    // Just fetch with current status
                    fetchDetailKeywordTable(buildDetailKeywordSearchRequestUrl());
                } else if (currentDetailKeywordQuery) {
                    if (keywordSearchDetail) keywordSearchDetail.value = currentDetailKeywordQuery;
                    fetchDetailKeywordTable(buildDetailKeywordSearchRequestUrl());
                    if (keywordSearchDetailClear) keywordSearchDetailClear.classList.remove('hidden');
                } else if (hasKeywordPage) {
                    // If only keyword_page is in URL, trigger AJAX to load correct page
                    fetchDetailKeywordTable(buildDetailKeywordSearchRequestUrl());
                }
            } else {
                attachDetailKeywordPaginationHandlers();
            }
            
            // Update URL state on initial load (convert 'page' to 'keyword_page' if needed)
            updateDetailKeywordUrlState();
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
