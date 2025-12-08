<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Withdraw Saldo • {{ $merchant->nama_merchant }} | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.head')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        html, body {
            overflow-x: hidden;
            overflow-y: auto;
            max-width: 100%;
            height: auto;
            position: relative;
        }
        
        /* Ensure dropdowns are not clipped */
        .page-content {
            overflow: visible;
        }
        
        main {
            overflow: visible !important;
        }
        
        /* Page fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .page-content {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Card hover animation */
        .payment-card {
            transition: all 0.2s ease;
            opacity: 0;
            animation: fadeInCard 0.4s ease-out forwards;
        }
        
        .payment-card:nth-child(1) { animation-delay: 0.1s; }
        .payment-card:nth-child(2) { animation-delay: 0.15s; }
        .payment-card:nth-child(3) { animation-delay: 0.2s; }
        .payment-card:nth-child(4) { animation-delay: 0.25s; }
        .payment-card:nth-child(5) { animation-delay: 0.3s; }
        .payment-card:nth-child(6) { animation-delay: 0.35s; }
        
        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .payment-card:hover {
            transform: translateY(-2px);
        }
        
        .payment-card:active {
            transform: translateY(0);
        }
        
        /* Dropdown chevron rotation */
        #bankChevron, #ewalletChevron {
            transition: transform 0.2s ease;
        }
        
        #bankChevron.rotate-180, #ewalletChevron.rotate-180 {
            transform: rotate(180deg);
        }
        
        /* Ensure dropdown menus are positioned correctly relative to their parent */
        #bankMenu, #ewalletMenu {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: auto !important;
            width: 100% !important;
            margin-top: 0.5rem !important;
            z-index: 9999 !important;
        }
        
        /* Ensure parent containers have position relative */
        #bankDropdown, #ewalletDropdown {
            position: relative;
        }
        
        /* Ensure parent divs have position relative */
        #bankDropdown, #ewalletDropdown {
            position: relative !important;
        }
        
        /* Mobile specific fixes */
        @media (max-width: 768px) {
            /* Ensure parent container for bank dropdown */
            #bankDropdownContainer {
                position: relative !important;
                overflow: visible !important;
                z-index: 20 !important;
            }
            
            /* Bank dropdown specific positioning - ensure it's inside its container */
            #bankDropdownContainer #bankMenu {
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: auto !important;
                width: 100% !important;
                transform: none !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-top: 0.5rem !important;
                z-index: 99999 !important;
            }
            
            /* Hide bank menu when it has hidden class */
            #bankDropdownContainer #bankMenu.hidden {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }
            
            /* Show bank menu when not hidden */
            #bankDropdownContainer #bankMenu:not(.hidden) {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* E-wallet dropdown specific positioning */
            #ewalletMenu {
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: auto !important;
                width: 100% !important;
                transform: none !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-top: 0.5rem !important;
                z-index: 99999 !important;
            }
            
            /* Hide e-wallet menu when it has hidden class */
            #ewalletMenu.hidden {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }
            
            /* Show e-wallet menu when not hidden */
            #ewalletMenu:not(.hidden) {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Ensure grid items have proper positioning */
            .grid > div {
                position: relative !important;
                overflow: visible !important;
            }
            
            /* Ensure parent containers don't clip dropdowns */
            #withdrawFormContainer {
                overflow: visible !important;
            }
            
            /* Payment method section */
            .mb-6[style*="overflow"] {
                overflow: visible !important;
            }
        }
        
        /* Input container fade-in */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .input-container-visible {
            animation: slideDown 0.3s ease-out;
        }
        
        /* Form container transition */
        #withdrawFormContainer {
            transition: padding-bottom 0.3s ease-out;
        }
        
        /* Ensure smooth transition for form content */
        #formContentBelow {
            transition: margin-top 0.3s ease-out;
        }
        
        /* Account input container transition */
        #accountInputContainer {
            transition: all 0.3s ease-out;
        }
        
        /* Remaining balance animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        #remainingBalanceContainer {
            transition: all 0.3s ease;
        }
        
        .remaining-balance-visible {
            animation: fadeInUp 0.3s ease-out;
        }
    </style>
</head>
<body class="min-h-screen bg-white font-poppins">
    <div class="page-content">
    @php
        $code = request()->route('code');
        $decodedCode = $code ? urldecode($code) : '';
        // Placeholder balance - replace with actual balance from database
        // For testing: set balance to 100000
        $accountBalance = 100000; // TODO: Get from merchant/portal_user balance
        $merchantId = $merchant->id ?? null;
    @endphp

    @include('partials.navbar-admin')

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8" style="overflow: visible;">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
            <div class="flex-1">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Withdraw Saldo</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Ajukan penarikan saldo Anda ke rekening bank atau e-wallet</p>
            </div>
            <a href="{{ route('link.history-withdraw', $code) }}" 
               class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-lg sm:rounded-full hover:bg-gray-50 transition-colors shadow-sm w-full sm:w-auto">
                <i class="fas fa-history text-sm sm:text-base text-gray-600"></i>
                <span class="text-xs sm:text-sm font-medium text-gray-700">Riwayat</span>
            </a>
        </div>

        <!-- Account Balance Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Saldo Akun</p>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900" id="accountBalanceDisplay">
                        Rp {{ number_format($accountBalance, 0, ',', '.') }}
                    </h2>
                                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                    <i class="fas fa-wallet text-2xl text-orange-600"></i>
                            </div>
                            </div>
        </div>

        <!-- Withdraw Form -->
        <div id="withdrawFormContainer" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6" style="overflow: visible; position: relative; transition: padding-bottom 0.3s ease-out; display: flex; flex-direction: column;">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Form Withdraw</h3>

            <!-- Amount Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Jumlah Penarikan</label>
                
                <!-- Toggle Switch: Tarik Semua -->
                <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700 mb-1">Tarik Semua Saldo</p>
                        <p class="text-xs text-gray-500">Aktifkan untuk menarik semua saldo secara otomatis</p>
                                        </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-4">
                        <input type="checkbox" id="withdrawAllToggle" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                                    </div>

                <!-- Amount Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 font-semibold">Rp</span>
                                </div>
                    <input type="text" 
                           id="withdrawAmount" 
                           name="amount"
                           value=""
                           class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm"
                           placeholder="0">
                            </div>

                <!-- Remaining Balance Display -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200" id="remainingBalanceContainer" style="display: none;">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Sisa Saldo:</span>
                        <span class="text-base font-bold text-gray-900" id="remainingBalance">Rp 0</span>
                                    </div>
                                </div>
                            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6" style="overflow: visible; position: relative;">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Metode Penarikan</label>
                
                <!-- Notification Message -->
                <div id="paymentMethodNotification" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-yellow-600 mt-0.5"></i>
                        <p class="text-sm text-yellow-800">
                            <span class="font-semibold">Masukkan jumlah penarikan terlebih dahulu</span> sebelum memilih metode pembayaran.
                                    </p>
                                </div>
                                    </div>
                
                <div class="grid grid-cols-2 gap-4" style="position: relative; z-index: 10; overflow: visible !important;">
                    <!-- Bank Dropdown -->
                    <div id="bankDropdownContainer" class="relative" style="z-index: 20; overflow: visible !important; position: relative !important; isolation: isolate;">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Bank</label>
                        <button type="button" id="bankDropdown" class="w-full px-4 py-3 border border-gray-300 rounded-full bg-white text-left focus:outline-none focus:ring-2 focus:ring-orange-400 flex items-center justify-between relative z-10">
                            <span id="bankSelected" class="text-sm text-gray-500">Pilih Bank</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" id="bankChevron"></i>
                        </button>
                        
                        <!-- Bank Dropdown Menu -->
                        <div id="bankMenu" class="hidden absolute z-[9999] w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg" style="position: absolute !important; top: 100% !important; left: 0 !important; right: auto !important; width: 100% !important; margin-top: 0.5rem !important;">
                            <div class="p-2">
                                <button type="button" data-value="bca" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-orange-50 rounded-lg transition-colors payment-option bank-option">
                                    <span class="text-sm font-medium text-gray-700">BCA</span>
                                    <i class="fas fa-check text-orange-600 hidden payment-check"></i>
                                </button>
                                <button type="button" data-value="bni" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-orange-50 rounded-lg transition-colors payment-option bank-option">
                                    <span class="text-sm font-medium text-gray-700">BNI</span>
                                    <i class="fas fa-check text-orange-600 hidden payment-check"></i>
                                </button>
                                <button type="button" data-value="bri" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-orange-50 rounded-lg transition-colors payment-option bank-option">
                                    <span class="text-sm font-medium text-gray-700">BRI</span>
                                    <i class="fas fa-check text-orange-600 hidden payment-check"></i>
                                </button>
                                <button type="button" data-value="mandiri" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-orange-50 rounded-lg transition-colors payment-option bank-option">
                                    <span class="text-sm font-medium text-gray-700">Mandiri</span>
                                    <i class="fas fa-check text-orange-600 hidden payment-check"></i>
                                </button>
                        </div>
                    </div>
                            </div>
                    
                    <!-- E-Wallet Dropdown -->
                    <div class="relative" style="z-index: 20; overflow: visible; position: relative;">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">E-Wallet</label>
                        <button type="button" id="ewalletDropdown" class="w-full px-4 py-3 border border-gray-300 rounded-full bg-white text-left focus:outline-none focus:ring-2 focus:ring-orange-400 flex items-center justify-between relative z-10">
                            <span id="ewalletSelected" class="text-sm text-gray-500">Pilih E-Wallet</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" id="ewalletChevron"></i>
                        </button>
                        
                        <!-- E-Wallet Dropdown Menu -->
                        <div id="ewalletMenu" class="hidden absolute z-[9999] w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg" style="position: absolute; top: 100%; left: 0; width: 100%;">
                            <div class="p-2">
                                <button type="button" data-value="linkaja" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-orange-50 rounded-lg transition-colors payment-option ewallet-option">
                                    <span class="text-sm font-medium text-gray-700">Link Aja</span>
                                    <i class="fas fa-check text-orange-600 hidden payment-check"></i>
                                </button>
                                <button type="button" data-value="dana" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-orange-50 rounded-lg transition-colors payment-option ewallet-option">
                                    <span class="text-sm font-medium text-gray-700">Dana</span>
                                    <i class="fas fa-check text-orange-600 hidden payment-check"></i>
                                </button>
                        </div>
                    </div>
                    </div>
            </div>

                <!-- Hidden input for form submission -->
                <input type="hidden" name="paymentMethod" id="paymentMethodInput" value="">
                    </div>
                    
            <!-- Form Content Below Dropdown -->
            <div id="formContentBelow" style="position: relative; flex: 1; display: flex; flex-direction: column; transition: margin-top 0.3s ease-out;">
                <!-- Account Number Input (Hidden when "Tarik Semua") -->
                <div class="mb-6" id="accountInputContainer" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" id="accountLabel">
                        Nomor Rekening
                    </label>
                    <div class="relative">
                        <div id="accountPrefix" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none hidden">
                            <span class="text-gray-500 font-semibold">+62</span>
                        </div>
                        <input type="text" 
                               id="accountNumber" 
                               name="account_number"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm"
                               placeholder="Masukkan nomor rekening / e-wallet">
                    </div>
                    <p class="text-xs text-gray-500 mt-2" id="accountHint">
                        Masukkan nomor rekening Anda
                    </p>
                </div>

                <!-- Submit Button - Always at bottom -->
                <button type="button" 
                        id="submitWithdraw"
                        class="w-full py-2.5 px-4 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white font-medium rounded-full hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed mt-auto">
                    <span id="submitButtonText">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Penarikan Saldo
                    </span>
                    <span id="submitButtonLoading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Memproses...
                    </span>
                                </button>
                    </div>
                </div>
    </main>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 z-40 hidden items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4">
                    <div class="w-16 h-16 border-4 border-orange-200 border-t-orange-600 rounded-full animate-spin"></div>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Memproses Withdraw</h3>
                <p class="text-sm text-gray-600">Mohon tunggu sebentar...</p>
            </div>
        </div>
    </div>

    <!-- Include Receipt Partials -->
    @include('partials_dash.validation-receipt')
    @include('partials_dash.receiptwithdraw')

    <script>
        // Account balance (replace with actual value from backend)
        const accountBalance = {{ $accountBalance }};
        
        // Format number with thousand separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        // Remove formatting and parse to number
        function parseAmount(str) {
            return parseInt(str.replace(/\./g, '')) || 0;
        }
        
        // Get DOM elements
        const withdrawAllToggle = document.getElementById('withdrawAllToggle');
        const withdrawAmountInput = document.getElementById('withdrawAmount');
        const remainingBalanceContainer = document.getElementById('remainingBalanceContainer');
        const remainingBalance = document.getElementById('remainingBalance');
        const bankDropdown = document.getElementById('bankDropdown');
        const bankMenu = document.getElementById('bankMenu');
        const bankSelected = document.getElementById('bankSelected');
        const bankChevron = document.getElementById('bankChevron');
        const ewalletDropdown = document.getElementById('ewalletDropdown');
        const ewalletMenu = document.getElementById('ewalletMenu');
        const ewalletSelected = document.getElementById('ewalletSelected');
        const ewalletChevron = document.getElementById('ewalletChevron');
        const paymentMethodInput = document.getElementById('paymentMethodInput');
        const paymentOptions = document.querySelectorAll('.payment-option');
        const accountInputContainer = document.getElementById('accountInputContainer');
        const accountNumberInput = document.getElementById('accountNumber');
        const accountLabel = document.getElementById('accountLabel');
        const accountHint = document.getElementById('accountHint');
        const submitButton = document.getElementById('submitWithdraw');
        
        // Payment method names
        const paymentMethodNames = {
            'bca': 'BCA',
            'bni': 'BNI',
            'bri': 'BRI',
            'mandiri': 'Mandiri',
            'linkaja': 'Link Aja',
            'dana': 'Dana'
        };
        
        // Function to check if mobile device
        function isMobileDevice() {
            return window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }
        
        // Function to scroll to element smoothly
        function scrollToElement(element) {
            if (isMobileDevice() && element) {
                setTimeout(() => {
                    element.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center',
                        inline: 'nearest'
                    });
                    // Focus on input after scroll
                    setTimeout(() => {
                        accountNumberInput.focus();
                    }, 500);
                }, 100);
            }
        }
        
        // Function to close all dropdowns
        function closeAllDropdowns() {
            if (bankMenu) {
                bankMenu.classList.add('hidden');
                bankMenu.style.setProperty('display', 'none', 'important');
                bankMenu.style.setProperty('visibility', 'hidden', 'important');
                bankMenu.style.setProperty('opacity', '0', 'important');
            }
            if (bankChevron) bankChevron.classList.remove('rotate-180');
            if (ewalletMenu) {
                ewalletMenu.classList.add('hidden');
                ewalletMenu.style.setProperty('display', 'none', 'important');
                ewalletMenu.style.setProperty('visibility', 'hidden', 'important');
                ewalletMenu.style.setProperty('opacity', '0', 'important');
            }
            if (ewalletChevron) ewalletChevron.classList.remove('rotate-180');
            // Update padding based on open dropdowns (will reset if none open)
            updateFormPadding();
        }
        
        const formContentBelow = document.getElementById('formContentBelow');
        const withdrawFormContainer = document.getElementById('withdrawFormContainer');
        
        // Function to get the tallest dropdown height (for when switching between dropdowns)
        function getMaxDropdownHeight() {
            let maxHeight = 0;
            
            // Check currently visible dropdowns
            if (bankMenu && !bankMenu.classList.contains('hidden')) {
                maxHeight = Math.max(maxHeight, bankMenu.scrollHeight);
            }
            
            if (ewalletMenu && !ewalletMenu.classList.contains('hidden')) {
                maxHeight = Math.max(maxHeight, ewalletMenu.scrollHeight);
            }
            
            return maxHeight;
        }
        
        // Function to update form padding based on currently open dropdowns
        function updateFormPadding() {
            if (!withdrawFormContainer || !formContentBelow) return;
            
            // Use double requestAnimationFrame to ensure DOM is fully updated and measured
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    const maxHeight = getMaxDropdownHeight();
                    
                    if (maxHeight > 0) {
                        // Calculate margin needed: dropdown height + margin-top (mt-2 = 8px) + spacing (8px)
                        const marginTop = maxHeight + 8 + 8;
                        formContentBelow.style.marginTop = marginTop + 'px';
                        // Also add padding to container to ensure button stays visible
                        withdrawFormContainer.style.paddingBottom = '24px';
                    } else {
                        // Reset margin and padding
                        formContentBelow.style.marginTop = '0';
                        withdrawFormContainer.style.paddingBottom = '24px';
                    }
                });
            });
        }
        
        // Function to reset form container padding when dropdown closes
        function resetFormContainerPadding() {
            updateFormPadding(); // Use updateFormPadding to handle reset
        }
        
        // Function to ensure dropdown stays within form container
        function ensureDropdownInContainer(dropdownMenu, dropdownButton) {
            if (!dropdownMenu || !dropdownButton) return;
            
            // Get parent container (the div with class "relative")
            const parentContainer = dropdownButton.parentElement;
            if (!parentContainer) return;
            
            // Ensure parent has position relative
            if (window.getComputedStyle(parentContainer).position !== 'relative') {
                parentContainer.style.position = 'relative';
            }
            
            // Reset positioning to ensure it's relative to parent
            dropdownMenu.style.position = 'absolute';
            dropdownMenu.style.top = '100%';
            dropdownMenu.style.left = '0';
            dropdownMenu.style.right = 'auto';
            dropdownMenu.style.width = '100%';
            dropdownMenu.style.marginTop = '0.5rem';
            dropdownMenu.style.marginBottom = '0';
            dropdownMenu.style.zIndex = '9999';
        }
        
        // Function to check if dropdown will overlap with button and adjust position
        function adjustDropdownPosition(dropdownMenu, dropdownButton) {
            if (!dropdownMenu || !dropdownButton) return;
            
            // Get parent container
            const parentContainer = dropdownButton.parentElement;
            if (!parentContainer) return;
            
            // Ensure parent has position relative
            if (window.getComputedStyle(parentContainer).position !== 'relative') {
                parentContainer.style.position = 'relative';
            }
            
            // Reset and set positioning relative to parent
            dropdownMenu.style.position = 'absolute';
            dropdownMenu.style.top = '100%';
            dropdownMenu.style.left = '0';
            dropdownMenu.style.right = 'auto';
            dropdownMenu.style.width = '100%';
            dropdownMenu.style.marginTop = '0.5rem';
            dropdownMenu.style.marginBottom = '0';
            dropdownMenu.style.zIndex = '9999';
        }
        
        // Toggle Bank dropdown
        if (bankDropdown && bankMenu) {
            bankDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Check if amount is entered first
                const amountValue = withdrawAmountInput.value.trim();
                if (!amountValue || amountValue === '' || amountValue === '0') {
                    // Show notification
                    if (paymentMethodNotification) {
                        paymentMethodNotification.classList.remove('hidden');
                        setTimeout(() => {
                            paymentMethodNotification.classList.add('hidden');
                        }, 5000);
                    }
                    // Don't open dropdown
                    return;
                }
                
                const isOpen = !bankMenu.classList.contains('hidden');
                
                if (isOpen) {
                    closeAllDropdowns();
                } else {
                    // Close other dropdown first
                    if (ewalletMenu) {
                        ewalletMenu.classList.add('hidden');
                        ewalletMenu.style.setProperty('display', 'none', 'important');
                        if (ewalletChevron) ewalletChevron.classList.remove('rotate-180');
                    }
                    
                    // Get parent container (the div with id "bankDropdownContainer")
                    const parentContainer = document.getElementById('bankDropdownContainer') || bankDropdown.parentElement;
                    if (parentContainer) {
                        // Force position relative on parent with !important equivalent
                        parentContainer.style.setProperty('position', 'relative', 'important');
                        parentContainer.style.setProperty('z-index', '20', 'important');
                        parentContainer.style.setProperty('overflow', 'visible', 'important');
                        parentContainer.style.setProperty('isolation', 'isolate', 'important');
                    }
                    
                    // Use requestAnimationFrame to ensure DOM is ready
                    requestAnimationFrame(() => {
                        // Reset all positioning - ensure it's relative to parent
                        bankMenu.style.setProperty('position', 'absolute', 'important');
                        bankMenu.style.setProperty('top', '100%', 'important');
                        bankMenu.style.setProperty('left', '0', 'important');
                        bankMenu.style.setProperty('right', 'auto', 'important');
                        bankMenu.style.setProperty('width', '100%', 'important');
                        bankMenu.style.setProperty('margin-top', '0.5rem', 'important');
                        bankMenu.style.setProperty('margin-bottom', '0', 'important');
                        bankMenu.style.setProperty('z-index', '99999', 'important');
                        bankMenu.style.setProperty('transform', 'none', 'important');
                        bankMenu.style.setProperty('margin-left', '0', 'important');
                        bankMenu.style.setProperty('margin-right', '0', 'important');
                        bankMenu.style.setProperty('display', 'block', 'important');
                        bankMenu.style.setProperty('visibility', 'visible', 'important');
                        bankMenu.style.setProperty('opacity', '1', 'important');
                        
                        // Ensure dropdown stays in container
                        ensureDropdownInContainer(bankMenu, bankDropdown);
                        // Adjust dropdown position
                        adjustDropdownPosition(bankMenu, bankDropdown);
                        
                        // Show bank dropdown - remove hidden class first, then set display
                        bankMenu.classList.remove('hidden');
                        bankMenu.style.setProperty('display', 'block', 'important');
                        bankMenu.style.setProperty('visibility', 'visible', 'important');
                        bankMenu.style.setProperty('opacity', '1', 'important');
                        if (bankChevron) bankChevron.classList.add('rotate-180');
                        
                        // Update form padding after dropdown is shown (with delay to ensure DOM is updated)
                        setTimeout(() => {
                            updateFormPadding();
                        }, 50);
                    });
                }
            });
        }
        
        // Toggle E-Wallet dropdown
        if (ewalletDropdown && ewalletMenu) {
            ewalletDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Check if amount is entered first
                const amountValue = withdrawAmountInput.value.trim();
                if (!amountValue || amountValue === '' || amountValue === '0') {
                    // Show notification
                    if (paymentMethodNotification) {
                        paymentMethodNotification.classList.remove('hidden');
                        setTimeout(() => {
                            paymentMethodNotification.classList.add('hidden');
                        }, 5000);
                    }
                    // Don't open dropdown
                    return;
                }
                
                const isOpen = !ewalletMenu.classList.contains('hidden');
                
                if (isOpen) {
                    closeAllDropdowns();
                } else {
                    // Close other dropdown first
                    if (bankMenu) {
                        bankMenu.classList.add('hidden');
                        bankMenu.style.setProperty('display', 'none', 'important');
                        if (bankChevron) bankChevron.classList.remove('rotate-180');
                    }
                    
                    // Use requestAnimationFrame to ensure DOM is ready
                    requestAnimationFrame(() => {
                        // Reset all positioning - ensure it's relative to parent
                        ewalletMenu.style.setProperty('position', 'absolute', 'important');
                        ewalletMenu.style.setProperty('top', '100%', 'important');
                        ewalletMenu.style.setProperty('left', '0', 'important');
                        ewalletMenu.style.setProperty('right', 'auto', 'important');
                        ewalletMenu.style.setProperty('width', '100%', 'important');
                        ewalletMenu.style.setProperty('margin-top', '0.5rem', 'important');
                        ewalletMenu.style.setProperty('margin-bottom', '0', 'important');
                        ewalletMenu.style.setProperty('z-index', '99999', 'important');
                        ewalletMenu.style.setProperty('transform', 'none', 'important');
                        ewalletMenu.style.setProperty('margin-left', '0', 'important');
                        ewalletMenu.style.setProperty('margin-right', '0', 'important');
                        ewalletMenu.style.setProperty('display', 'block', 'important');
                        ewalletMenu.style.setProperty('visibility', 'visible', 'important');
                        ewalletMenu.style.setProperty('opacity', '1', 'important');
                        
                        // Ensure dropdown stays in container
                        ensureDropdownInContainer(ewalletMenu, ewalletDropdown);
                        // Adjust dropdown position
                        adjustDropdownPosition(ewalletMenu, ewalletDropdown);
                        
                        // Show e-wallet dropdown - remove hidden class first, then set display
                        ewalletMenu.classList.remove('hidden');
                        ewalletMenu.style.setProperty('display', 'block', 'important');
                        ewalletMenu.style.setProperty('visibility', 'visible', 'important');
                        ewalletMenu.style.setProperty('opacity', '1', 'important');
                        if (ewalletChevron) ewalletChevron.classList.add('rotate-180');
                        
                        // Update form padding after dropdown is shown (with delay to ensure DOM is updated)
                        setTimeout(() => {
                            updateFormPadding();
                        }, 50);
                    });
                }
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (bankDropdown && bankMenu && ewalletDropdown && ewalletMenu) {
                const isClickInsideBank = bankDropdown.contains(e.target) || bankMenu.contains(e.target);
                const isClickInsideEWallet = ewalletDropdown.contains(e.target) || ewalletMenu.contains(e.target);
                
                if (!isClickInsideBank && !isClickInsideEWallet) {
                    closeAllDropdowns();
                }
            }
        });
        
        // Handle payment method selection - Bank options only
        if (bankMenu) {
            const bankOptions = bankMenu.querySelectorAll('.bank-option');
            bankOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Check if amount is entered first
                    const amountValue = withdrawAmountInput.value.trim();
                    if (!amountValue || amountValue === '' || amountValue === '0') {
                        // Show notification
                        if (paymentMethodNotification) {
                            paymentMethodNotification.classList.remove('hidden');
                            setTimeout(() => {
                                paymentMethodNotification.classList.add('hidden');
                            }, 5000);
                        }
                        // Close dropdowns
                        closeAllDropdowns();
                        return;
                    }
                    
                    const value = this.getAttribute('data-value');
                    const name = paymentMethodNames[value] || value;
                    
                    // Update hidden input
                    paymentMethodInput.value = value;
                    
                    // Update bank display
                    bankSelected.textContent = name;
                    bankSelected.classList.remove('text-gray-500');
                    bankSelected.classList.add('text-gray-900', 'font-medium');
                    // Reset e-wallet
                    ewalletSelected.textContent = 'Pilih E-Wallet';
                    ewalletSelected.classList.remove('text-gray-900', 'font-medium');
                    ewalletSelected.classList.add('text-gray-500');
                    
                    // Update checkmarks - reset all first
                    paymentOptions.forEach(opt => {
                        const check = opt.querySelector('.payment-check');
                        if (check) check.classList.add('hidden');
                    });
                    // Show checkmark for selected
                    const selectedCheck = this.querySelector('.payment-check');
                    if (selectedCheck) selectedCheck.classList.remove('hidden');
                    
                    // Close all dropdowns
                    closeAllDropdowns();
                    
                    // Update padding after closing dropdowns
                    updateFormPadding();
                    
                    // Hide notification
                    if (paymentMethodNotification) {
                        paymentMethodNotification.classList.add('hidden');
                    }
                    
                    // Show account input
                    updateAccountInputDisplay();
                    accountNumberInput.value = '';
                });
            });
        }
        
        // Handle payment method selection - E-Wallet options only
        if (ewalletMenu) {
            const ewalletOptions = ewalletMenu.querySelectorAll('.ewallet-option');
            ewalletOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Check if amount is entered first
                    const amountValue = withdrawAmountInput.value.trim();
                    if (!amountValue || amountValue === '' || amountValue === '0') {
                        // Show notification
                        if (paymentMethodNotification) {
                            paymentMethodNotification.classList.remove('hidden');
                            setTimeout(() => {
                                paymentMethodNotification.classList.add('hidden');
                            }, 5000);
                        }
                        // Close dropdowns
                        closeAllDropdowns();
                        return;
                    }
                    
                    const value = this.getAttribute('data-value');
                    const name = paymentMethodNames[value] || value;
                    
                    // Update hidden input
                    paymentMethodInput.value = value;
                    
                    // Update e-wallet display
                    ewalletSelected.textContent = name;
                    ewalletSelected.classList.remove('text-gray-500');
                    ewalletSelected.classList.add('text-gray-900', 'font-medium');
                    // Reset bank
                    bankSelected.textContent = 'Pilih Bank';
                    bankSelected.classList.remove('text-gray-900', 'font-medium');
                    bankSelected.classList.add('text-gray-500');
                    
                    // Update checkmarks - reset all first
                    paymentOptions.forEach(opt => {
                        const check = opt.querySelector('.payment-check');
                        if (check) check.classList.add('hidden');
                    });
                    // Show checkmark for selected
                    const selectedCheck = this.querySelector('.payment-check');
                    if (selectedCheck) selectedCheck.classList.remove('hidden');
                    
                    // Close all dropdowns
                    closeAllDropdowns();
                    
                    // Update padding after closing dropdowns
                    updateFormPadding();
                    
                    // Hide notification
                    if (paymentMethodNotification) {
                        paymentMethodNotification.classList.add('hidden');
                    }
                    
                    // Show account input
                    updateAccountInputDisplay();
                    accountNumberInput.value = '';
                });
            });
        }
        
        // Function to update account input display and labels
        function updateAccountInputDisplay() {
            const selectedPaymentValue = paymentMethodInput.value;
            if (selectedPaymentValue) {
                const method = selectedPaymentValue;
                const isBank = ['bca', 'bni', 'bri', 'mandiri'].includes(method);
                const isEWallet = ['linkaja', 'dana'].includes(method);
                
                if (isBank) {
                    accountLabel.textContent = 'Nomor Rekening';
                    const bankHints = {
                        'bca': 'Masukkan nomor rekening BCA (10 digit)',
                        'bni': 'Masukkan nomor rekening BNI (10 digit)',
                        'bri': 'Masukkan nomor rekening BRI (15 digit)',
                        'mandiri': 'Masukkan nomor rekening Mandiri (13 digit)'
                    };
                    accountNumberInput.placeholder = 'Masukkan nomor rekening';
                    accountHint.textContent = bankHints[method] || 'Masukkan nomor rekening Anda';
                    accountNumberInput.style.paddingLeft = '1rem';
                    if (accountPrefix) accountPrefix.classList.add('hidden');
                } else if (isEWallet) {
                    accountLabel.textContent = 'Nomor E-Wallet';
                    accountNumberInput.placeholder = '81234567890';
                    accountHint.textContent = 'Masukkan nomor e-wallet Anda (10-13 digit, tanpa +62)';
                    accountNumberInput.style.paddingLeft = '3.5rem';
                    if (accountPrefix) accountPrefix.classList.remove('hidden');
                }
                
                // Reset hint color
                accountHint.classList.remove('text-red-500');
                accountHint.classList.add('text-gray-500');
                
                // Show with smooth animation
                if (accountInputContainer.style.display === 'none') {
                    accountInputContainer.style.opacity = '0';
                    accountInputContainer.style.transform = 'translateY(-10px)';
                    accountInputContainer.style.display = 'block';
                    
                    // Trigger animation
                    setTimeout(() => {
                        accountInputContainer.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        accountInputContainer.style.opacity = '1';
                        accountInputContainer.style.transform = 'translateY(0)';
                        
                        // Update padding after input appears (in case dropdown is still open)
                        updateFormPadding();
                    }, 10);
                } else {
                    // Update padding if input is already visible
                    updateFormPadding();
                }
                
                // Scroll to input on mobile
                scrollToElement(accountInputContainer);
            } else {
                // Hide with smooth animation
                if (accountInputContainer.style.display !== 'none') {
                    accountInputContainer.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                    accountInputContainer.style.opacity = '0';
                    accountInputContainer.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        accountInputContainer.style.display = 'none';
                        // Update padding after hiding input
                        updateFormPadding();
                    }, 300);
                }
            }
        }
        
        // Handle withdraw all toggle change
        withdrawAllToggle.addEventListener('change', function() {
            if (this.checked) {
                // Fill with all balance
                withdrawAmountInput.value = formatNumber(accountBalance);
                withdrawAmountInput.readOnly = true;
                remainingBalanceContainer.style.display = 'none';
                // Hide notification if shown
                if (paymentMethodNotification) {
                    paymentMethodNotification.classList.add('hidden');
                }
                // Show account input if payment method is already selected
                updateAccountInputDisplay();
            } else {
                // Clear and allow manual input
                withdrawAmountInput.readOnly = false;
                withdrawAmountInput.value = '';
                withdrawAmountInput.focus();
                // Show account input if payment method is already selected
                updateAccountInputDisplay();
            }
        });
        
        // Handle amount input change
        withdrawAmountInput.addEventListener('input', function(e) {
            // Only process if toggle is off (manual input mode)
            if (!withdrawAllToggle.checked) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    e.target.value = formatNumber(parseInt(value));
                    updateRemainingBalance();
                } else {
                    e.target.value = '';
                    remainingBalanceContainer.style.display = 'none';
                }
            }
        });
        
        // Update remaining balance with smooth animation
        function updateRemainingBalance() {
            const withdrawAmount = parseAmount(withdrawAmountInput.value);
            if (withdrawAmount > 0 && withdrawAmount <= accountBalance) {
                const remaining = accountBalance - withdrawAmount;
                remainingBalance.textContent = 'Rp ' + formatNumber(remaining);
                
                // Show with animation
                if (remainingBalanceContainer.style.display === 'none' || !remainingBalanceContainer.classList.contains('remaining-balance-visible')) {
                    remainingBalanceContainer.style.display = 'block';
                    remainingBalanceContainer.style.opacity = '0';
                    remainingBalanceContainer.style.transform = 'translateY(-5px)';
                    setTimeout(() => {
                        remainingBalanceContainer.classList.add('remaining-balance-visible');
                        remainingBalanceContainer.style.opacity = '1';
                        remainingBalanceContainer.style.transform = 'translateY(0)';
                    }, 10);
                }
            } else if (withdrawAmount > accountBalance) {
                withdrawAmountInput.value = formatNumber(accountBalance);
                remainingBalance.textContent = 'Rp 0';
                
                // Show with animation
                if (remainingBalanceContainer.style.display === 'none' || !remainingBalanceContainer.classList.contains('remaining-balance-visible')) {
                    remainingBalanceContainer.style.display = 'block';
                    remainingBalanceContainer.style.opacity = '0';
                    remainingBalanceContainer.style.transform = 'translateY(-5px)';
                    setTimeout(() => {
                        remainingBalanceContainer.classList.add('remaining-balance-visible');
                        remainingBalanceContainer.style.opacity = '1';
                        remainingBalanceContainer.style.transform = 'translateY(0)';
                    }, 10);
                }
            } else {
                // Hide with animation
                remainingBalanceContainer.style.opacity = '0';
                remainingBalanceContainer.style.transform = 'translateY(-5px)';
                setTimeout(() => {
                    remainingBalanceContainer.style.display = 'none';
                    remainingBalanceContainer.classList.remove('remaining-balance-visible');
                }, 200);
            }
        }
        
        const accountPrefix = document.getElementById('accountPrefix');
        
        // Validate account number format
        function validateAccountNumber(accountNumber, paymentMethod) {
            const isBank = ['bca', 'bni', 'bri', 'mandiri'].includes(paymentMethod);
            const isEWallet = ['linkaja', 'dana'].includes(paymentMethod);
            
            if (!accountNumber || accountNumber.trim() === '') {
                return { valid: false, message: 'Nomor tidak boleh kosong' };
            }
            
            // Remove any non-digit characters for validation
            const digits = accountNumber.replace(/[^\d]/g, '');
            
            if (isBank) {
                // Bank validation
                const bankRules = {
                    'bca': { min: 10, max: 10, name: 'BCA' },
                    'bni': { min: 10, max: 10, name: 'BNI' },
                    'bri': { min: 15, max: 15, name: 'BRI' },
                    'mandiri': { min: 13, max: 13, name: 'Mandiri' }
                };
                
                const rule = bankRules[paymentMethod];
                if (digits.length < rule.min || digits.length > rule.max) {
                    return { 
                        valid: false, 
                        message: `Nomor rekening ${rule.name} harus ${rule.min} digit` 
                    };
                }
            } else if (isEWallet) {
                // E-Wallet validation (10-13 digits without +62)
                if (digits.length < 10 || digits.length > 13) {
                    return { 
                        valid: false, 
                        message: 'Nomor e-wallet harus 10-13 digit' 
                    };
                }
            }
            
            return { valid: true };
        }
        
        const paymentMethodNotification = document.getElementById('paymentMethodNotification');
        
        // Hide notification when amount is entered
        withdrawAmountInput.addEventListener('input', function() {
            if (this.value && this.value.trim() !== '' && paymentMethodNotification) {
                paymentMethodNotification.classList.add('hidden');
            }
        });
        
        // Hide notification when toggle is activated
        withdrawAllToggle.addEventListener('change', function() {
            if (this.checked && paymentMethodNotification) {
                paymentMethodNotification.classList.add('hidden');
            }
        });
        
        // Handle account number input for e-wallet (+62 prefix) and bank validation
        accountNumberInput.addEventListener('input', function(e) {
            const selectedPaymentValue = paymentMethodInput.value;
            if (selectedPaymentValue) {
                const isEWallet = ['linkaja', 'dana'].includes(selectedPaymentValue);
                const isBank = ['bca', 'bni', 'bri', 'mandiri'].includes(selectedPaymentValue);
                
                if (isEWallet) {
                    // Remove any non-digit characters
                    let value = e.target.value.replace(/[^\d]/g, '');
                    // Remove leading 0 if exists
                    if (value.startsWith('0')) {
                        value = value.substring(1);
                    }
                    // Remove +62 if user types it
                    if (value.startsWith('62')) {
                        value = value.substring(2);
                    }
                    // Limit to 13 digits
                    if (value.length > 13) {
                        value = value.substring(0, 13);
                    }
                    e.target.value = value;
                } else if (isBank) {
                    // Bank: only allow digits and limit based on bank
                    let value = e.target.value.replace(/[^\d]/g, '');
                    const bankLimits = {
                        'bca': 10,
                        'bni': 10,
                        'bri': 15,
                        'mandiri': 13
                    };
                    const limit = bankLimits[selectedPaymentValue] || 15;
                    if (value.length > limit) {
                        value = value.substring(0, limit);
                    }
                    e.target.value = value;
                }
            }
        });
        
        // Show validation message on blur
        accountNumberInput.addEventListener('blur', function() {
            const selectedPaymentValue = paymentMethodInput.value;
            if (selectedPaymentValue && this.value.trim()) {
                const validation = validateAccountNumber(this.value, selectedPaymentValue);
                if (!validation.valid) {
                    accountHint.textContent = validation.message;
                    accountHint.classList.remove('text-gray-500');
                    accountHint.classList.add('text-red-500');
                } else {
                    const isEWallet = ['linkaja', 'dana'].includes(selectedPaymentValue);
                    accountHint.textContent = isEWallet ? 'Masukkan nomor e-wallet Anda (tanpa +62)' : 'Masukkan nomor rekening Anda';
                    accountHint.classList.remove('text-red-500');
                    accountHint.classList.add('text-gray-500');
                }
            }
        });
        
        // Get method name
        function getMethodName(value) {
            const methods = {
                'bca': 'BCA',
                'bni': 'BNI',
                'bri': 'BRI',
                'mandiri': 'Mandiri',
                'linkaja': 'Link Aja',
                'dana': 'Dana'
            };
            return methods[value] || value;
        }

        // Show loading overlay
        function showLoading() {
            const overlay = document.getElementById('loadingOverlay');
            const buttonText = document.getElementById('submitButtonText');
            const buttonLoading = document.getElementById('submitButtonLoading');
            
            if (overlay) overlay.classList.remove('hidden');
            if (overlay) overlay.classList.add('flex');
            if (buttonText) buttonText.classList.add('hidden');
            if (buttonLoading) buttonLoading.classList.remove('hidden');
            submitButton.disabled = true;
        }

        // Hide loading overlay
        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            const buttonText = document.getElementById('submitButtonText');
            const buttonLoading = document.getElementById('submitButtonLoading');
            
            if (overlay) {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                // Force hide by setting display to none
                overlay.style.display = 'none';
            }
            if (buttonText) buttonText.classList.remove('hidden');
            if (buttonLoading) buttonLoading.classList.add('hidden');
            if (submitButton) submitButton.disabled = false;
        }

        // Close receipt modal
        function closeReceiptModal() {
            const successModal = document.getElementById('receiptSuccessModal');
            
            if (successModal) {
                const content = successModal.querySelector('.relative');
                if (content) {
                    content.classList.add('opacity-0', 'scale-95');
                    content.classList.remove('opacity-100', 'scale-100');
                }
                setTimeout(() => {
                    successModal.classList.add('hidden');
                    successModal.classList.remove('flex');
                    successModal.style.display = 'none'; // Force hide
                    
                    // Reset form state after closing receipt
                    resetFormAfterReceipt();
                    
                    // Ensure body is scrollable
                    document.body.style.overflow = 'auto';
                    document.body.style.position = 'relative';
                    document.documentElement.style.overflow = 'auto';
                }, 300);
            }
        }
        
        // Reset form state after receipt is closed
        function resetFormAfterReceipt() {
            // Reset form container padding
            if (withdrawFormContainer) {
                withdrawFormContainer.style.paddingBottom = '24px';
            }
            
            // Reset form content margin
            if (formContentBelow) {
                formContentBelow.style.marginTop = '0';
            }
            
            // Reset all dropdowns
            closeAllDropdowns();
            
            // Reset form inputs
            const withdrawAllToggle = document.getElementById('withdrawAllToggle');
            const withdrawAmountInput = document.getElementById('withdrawAmount');
            const remainingBalanceContainer = document.getElementById('remainingBalanceContainer');
            const accountInputContainer = document.getElementById('accountInputContainer');
            const accountNumberInput = document.getElementById('accountNumber');
            const accountPrefix = document.getElementById('accountPrefix');
            
            // Reset withdraw toggle
            if (withdrawAllToggle) withdrawAllToggle.checked = false;
            
            // Reset amount input
            if (withdrawAmountInput) {
                withdrawAmountInput.value = '';
                withdrawAmountInput.readOnly = false;
                withdrawAmountInput.style.backgroundColor = '#ffffff';
            }
            
            // Hide remaining balance
            if (remainingBalanceContainer) {
                remainingBalanceContainer.style.display = 'none';
            }
            
            // Reset payment method
            if (paymentMethodInput) paymentMethodInput.value = '';
            if (bankSelected) {
                bankSelected.textContent = 'Pilih Bank';
                bankSelected.classList.remove('text-gray-900', 'font-medium');
                bankSelected.classList.add('text-gray-500');
            }
            if (ewalletSelected) {
                ewalletSelected.textContent = 'Pilih E-Wallet';
                ewalletSelected.classList.remove('text-gray-900', 'font-medium');
                ewalletSelected.classList.add('text-gray-500');
            }
            paymentOptions.forEach(opt => {
                const check = opt.querySelector('.payment-check');
                if (check) check.classList.add('hidden');
            });
            
            // Hide account input
            if (accountInputContainer) {
                accountInputContainer.style.display = 'none';
                accountInputContainer.style.opacity = '0';
                accountInputContainer.style.transform = 'translateY(-10px)';
            }
            if (accountNumberInput) {
                accountNumberInput.value = '';
            }
            if (accountPrefix) accountPrefix.classList.add('hidden');
            if (accountNumberInput) accountNumberInput.style.paddingLeft = '1rem';
            
            // Clear pending data
            pendingWithdrawData = null;
            
            // Scroll to top of form
            if (withdrawFormContainer) {
                withdrawFormContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Make closeReceiptModal globally accessible
        window.closeReceiptModal = closeReceiptModal;

        // Store withdraw data temporarily for confirmation
        let pendingWithdrawData = null;

        // Format receipt number
        function formatReceiptNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Format date to match history format (d M Y) - hanya tanggal tanpa waktu
        function formatDate(date) {
            const d = new Date(date);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = String(d.getDate()).padStart(2, '0');
            const month = months[d.getMonth()];
            const year = d.getFullYear();
            return `${day} ${month} ${year}`;
        }

        // Show validation/confirmation modal
        function showValidationModal(data) {
            const modal = document.getElementById('validationReceiptModal');
            if (!modal) return;

            // Get current date
            const currentDate = formatDate(new Date());

            // Fill validation data
            document.getElementById('validationName').textContent = data.customerName || 'Alexander';
            document.getElementById('validationAmount').textContent = 'Rp ' + formatReceiptNumber(data.amount || 0);
            document.getElementById('validationMethod').textContent = data.methodName || '-';
            document.getElementById('validationDate').textContent = currentDate;
            
            if (data.accountNumber) {
                const isEWallet = ['linkaja', 'dana'].includes(data.paymentMethod || '');
                const displayAccount = isEWallet ? '+62' + data.accountNumber : data.accountNumber;
                document.getElementById('validationAccount').textContent = displayAccount;
                document.getElementById('validationAccountLabel').textContent = isEWallet ? 'Nomor E-Wallet' : 'Nomor Rekening';
                document.getElementById('validationAccountContainer').style.display = 'flex';
            } else {
                document.getElementById('validationAccountContainer').style.display = 'none';
            }

            // Show modal with animation
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                const content = modal.querySelector('.relative');
                content.classList.remove('opacity-0', 'scale-95');
                content.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        // Close validation modal
        function closeValidationModal() {
            const modal = document.getElementById('validationReceiptModal');
            if (!modal) return;

            const content = modal.querySelector('.relative');
            content.classList.add('opacity-0', 'scale-95');
            content.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                pendingWithdrawData = null;
            }, 300);
        }

        // Make closeValidationModal globally accessible
        window.closeValidationModal = closeValidationModal;

        // Confirm withdraw and proceed to progress
        function confirmWithdraw() {
            if (!pendingWithdrawData) {
                console.error('No pending withdraw data!');
                return;
            }

            console.log('Confirming withdraw with data:', pendingWithdrawData); // Debug log

            // Save data to local variable BEFORE closing modal (to prevent null reference)
            const withdrawData = {
                amount: pendingWithdrawData.amount,
                methodName: pendingWithdrawData.methodName,
                accountNumber: pendingWithdrawData.accountNumber,
                paymentMethod: pendingWithdrawData.paymentMethod,
                customerName: 'Alexander' // Always use hardcoded name
            };

            // Close validation modal (this will set pendingWithdrawData to null after 300ms)
            closeValidationModal();

            // Show loading
            showLoading();

            // Format account number for e-wallet (untuk database: hapus +62 dan leading 0)
            let formattedAccountNumber = withdrawData.accountNumber;
            const isEWallet = ['linkaja', 'dana'].includes(withdrawData.paymentMethod);
            if (isEWallet && formattedAccountNumber) {
                formattedAccountNumber = formattedAccountNumber.replace(/^\+62/, '').replace(/^0/, '');
            }

            // Prepare data untuk API
            const submitData = {
                merchant_id: {{ $merchantId ?? 'null' }},
                amount: withdrawData.amount,
                payment_method: withdrawData.paymentMethod,
                account_number: formattedAccountNumber,
                _token: '{{ csrf_token() }}'
            };

            // Submit ke backend API
            fetch('{{ route("withdraw.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(submitData)
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading
                hideLoading();
                
                if (data.success) {
                    // Get current date in format matching history (d M Y, H:i)
                    const currentDate = formatDate(new Date());
                    
                    // Prepare receipt data - always use 'Alexander' as customer name
                    const receiptData = {
                        amount: withdrawData.amount,
                        methodName: withdrawData.methodName,
                        accountNumber: formattedAccountNumber,
                        paymentMethod: withdrawData.paymentMethod,
                        customerName: 'Alexander', // Always use hardcoded name
                        transferTime: currentDate, // Format: "15 Jan 2024, 10:30"
                        transactionId: data.data.transaction_id || 'WD' + Date.now()
                    };
                    
                    // Wait a bit longer to ensure loading overlay is fully hidden before showing receipt
                    setTimeout(() => {
                        // Show success receipt directly
                        console.log('About to show receipt with data:', receiptData); // Debug log
                        try {
                            showReceiptSuccess(receiptData);
                            console.log('showReceiptSuccess executed'); // Debug log
                        } catch (error) {
                            console.error('Error in showReceiptSuccess:', error); // Debug log
                        }
                    }, 300); // Delay to ensure loading is fully hidden and transition is smooth
                } else {
                    // Error handling
                    alert('Gagal mengajukan penarikan saldo: ' + (data.message || 'Terjadi kesalahan'));
                    hideLoading();
                }
            })
            .catch(error => {
                console.error('Error submitting withdraw:', error);
                alert('Terjadi kesalahan saat mengajukan penarikan saldo');
                hideLoading();
            });
        }

        // Make confirmWithdraw globally accessible
        window.confirmWithdraw = confirmWithdraw;

        // Show receipt success
        function showReceiptSuccess(data) {
            console.log('showReceiptSuccess called with:', data); // Debug log
            
            const modal = document.getElementById('receiptSuccessModal');
            if (!modal) {
                console.error('Receipt modal not found!');
                return;
            }

            // Fill receipt data
            const nameEl = document.getElementById('receiptNameSuccess');
            const amountEl = document.getElementById('receiptAmountSuccess');
            const methodEl = document.getElementById('receiptMethodSuccess');
            const timeEl = document.getElementById('receiptTimeSuccess');
            
            if (nameEl) nameEl.textContent = data.customerName || 'Alexander';
            if (amountEl) amountEl.textContent = 'Rp ' + formatReceiptNumber(data.amount || 0);
            if (methodEl) methodEl.textContent = data.methodName || '-';
            if (timeEl) timeEl.textContent = data.transferTime || '-';
            
            if (data.accountNumber) {
                const isEWallet = ['linkaja', 'dana'].includes(data.paymentMethod || '');
                const displayAccount = isEWallet ? '+62' + data.accountNumber : data.accountNumber;
                const accountEl = document.getElementById('receiptAccountSuccess');
                const accountLabelEl = document.getElementById('receiptAccountLabelSuccess');
                const accountContainerEl = document.getElementById('receiptAccountSuccessContainer');
                
                if (accountEl) accountEl.textContent = displayAccount;
                if (accountLabelEl) accountLabelEl.textContent = isEWallet ? 'Nomor E-Wallet' : 'Nomor Rekening';
                if (accountContainerEl) accountContainerEl.style.display = 'flex';
            } else {
                const accountContainerEl = document.getElementById('receiptAccountSuccessContainer');
                if (accountContainerEl) accountContainerEl.style.display = 'none';
            }

            // Show modal with animation
            console.log('Showing modal...'); // Debug log
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.display = 'flex'; // Force display
            modal.style.zIndex = '9999'; // Ensure it's on top
            
            // Force show immediately
            requestAnimationFrame(() => {
                const content = modal.querySelector('.relative');
                if (content) {
                    content.classList.remove('opacity-0', 'scale-95');
                    content.classList.add('opacity-100', 'scale-100');
                    console.log('Modal content animated'); // Debug log
                } else {
                    console.error('Modal content not found!'); // Debug log
                }
            });
        }
        
        // Make showReceiptSuccess globally accessible for debugging
        window.showReceiptSuccess = showReceiptSuccess;

        // Handle form submission
        submitButton.addEventListener('click', function() {
            // Get amount based on toggle state
            const amount = withdrawAllToggle.checked ? accountBalance : parseAmount(withdrawAmountInput.value);
            const paymentMethodValue = paymentMethodInput.value;
            const accountNumber = accountNumberInput.value.trim();
            
            // Validation
            if (amount <= 0) {
                alert('Jumlah penarikan harus lebih dari 0');
                return;
            }
            
            if (amount > accountBalance) {
                alert('Jumlah penarikan tidak boleh melebihi saldo akun');
                return;
            }
            
            if (!paymentMethodValue) {
                alert('Pilih metode pembayaran terlebih dahulu');
                return;
            }
            
            // Account number is always required when payment method is selected (both "Tarik Semua" and "Input Manual")
            if (!accountNumber || accountNumber === '') {
                const methodName = getMethodName(paymentMethodValue);
                alert(`Masukkan nomor ${['bca', 'bni', 'bri', 'mandiri'].includes(paymentMethodValue) ? 'rekening' : 'e-wallet'} ${methodName} Anda`);
                return;
            }
            
            // Validate account number format
            const accountValidation = validateAccountNumber(accountNumber, paymentMethodValue);
            if (!accountValidation.valid) {
                alert(accountValidation.message);
                accountNumberInput.focus();
                return;
            }
            
            // Customer name (always hardcoded, never from dashboard)
            // Always use 'Alexander' as customer name
            const customerName = 'Alexander';
            
            // Store withdraw data for confirmation
            pendingWithdrawData = {
                amount: amount,
                methodName: getMethodName(paymentMethodValue),
                accountNumber: accountNumber,
                paymentMethod: paymentMethodValue,
                customerName: 'Alexander' // Always hardcoded, never from dashboard
            };
            
            // Show validation/confirmation modal first
            showValidationModal(pendingWithdrawData);
        });

        
        // Initialize - No default selection
        if (!withdrawAllToggle.checked) {
            // Toggle is off by default
            withdrawAmountInput.readOnly = false;
            withdrawAmountInput.value = '';
            accountInputContainer.style.display = 'none';
        } else {
            withdrawAmountInput.value = formatNumber(accountBalance);
            withdrawAmountInput.readOnly = true;
            // Show account input if payment method is already selected
            updateAccountInputDisplay();
        }
    </script>
    </div>
</body>
</html>
