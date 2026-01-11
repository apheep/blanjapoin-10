<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blocked IPs | BlanjaPoin Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white font-poppins">
    @include('partials.navbar-admin')

    <main class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-8">
            <!-- Header -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('click.history.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <span class="text-gray-300">|</span>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Admin Panel</p>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Suspicious & Blocked IPs</h1>
                    <p class="text-sm text-gray-600 mt-2">Daftar IP yang mencurigakan (>10 klik) atau diblokir (>20 klik) hari ini.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('click.history.blocked') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search IP / Device ID</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" name="search" value="{{ $search ?? '' }}" 
                                   placeholder="Cari IP Address atau Device ID..." 
                                   class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#F81611] to-[#F0B100] text-white rounded-lg hover:shadow-lg transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('click.history.blocked') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 text-sm font-semibold">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Latest Device ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Merchant</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Clicks</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($blockedIps as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-mono text-gray-900 font-medium">{{ $item->ip_address }}</span>
                                            <button onclick="copyToClipboard('{{ $item->ip_address }}')" class="text-gray-400 hover:text-blue-500 transition-colors" title="Copy IP">
                                                <i class="fas fa-copy text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->device_id)
                                            <div class="flex items-center gap-2 group">
                                                <span class="text-xs font-mono text-gray-600 truncate max-w-[150px]" title="{{ $item->device_id }}">
                                                    {{ $item->device_id }}
                                                </span>
                                                <button onclick="copyToClipboard('{{ $item->device_id }}')" class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-blue-500" title="Copy Device ID">
                                                    <i class="fas fa-copy text-xs"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->latest_merchant)
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-800">{{ $item->latest_merchant->nama_merchant }}</span>
                                                @if($item->merchant_count > 1)
                                                    <button type="button" 
                                                            onclick="showMerchants(this)" 
                                                            data-merchants="{{ json_encode($item->visited_merchants ?? []) }}"
                                                            class="inline-flex mt-1 cursor-pointer hover:opacity-80 transition-opacity group text-left focus:outline-none">
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-800 border border-blue-200 group-hover:bg-blue-200 group-hover:border-blue-300 transition-colors">
                                                            +{{ $item->merchant_count - 1 }} others
                                                        </span>
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($item->total_clicks) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->status == 'blocked')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                                Blocked
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-600"></span>
                                                Suspicious
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($item->status != 'blocked')
                                            <button onclick="confirmBlock('{{ $item->ip_address }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 text-xs font-medium shadow-sm mr-2" title="Force Block IP">
                                                <i class="fas fa-ban"></i> Block
                                            </button>
                                        @endif
                                        <button onclick="confirmUnlock('{{ $item->ip_address }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-300 transition-all duration-200 text-xs font-medium shadow-sm" title="Reset Click History">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-shield-alt text-green-500 text-3xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium">Aman Terkendali</p>
                                            <p class="text-gray-400 text-sm mt-2">Tidak ada IP yang mencurigakan (> 10 klik) atau terblokir hari ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($blockedIps->hasPages())
                <div class="mt-6">
                    {{ $blockedIps->links() }}
                </div>
            @endif

    </main>

    <!-- Merchant List Modal -->
    <div id="merchantModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeMerchantModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full relative z-[70]">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-store text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="merchant-modal-title">
                                Visited Merchants
                            </h3>
                            <div class="mt-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                <ul id="merchantList" class="divide-y divide-gray-200">
                                    <!-- List items will be injected here -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeMerchantModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Confirmation Modal -->
    <div id="blockModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeBlockModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div id="blockModalContent" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-[70] scale-95 opacity-0 duration-300">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-ban text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="block-modal-title">
                                Konfirmasi Block IP
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin memblokir IP <span id="blockModalIpAddress" class="font-bold text-gray-800"></span>? 
                                    Sistem akan memaksa jumlah klik menjadi > 20 agar IP ini terblokir hari ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="blockForm" action="{{ route('click.history.block') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ip_address" id="blockFormIpAddress">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Block IP
                        </button>
                    </form>
                    <button type="button" onclick="closeBlockModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-[70]">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Reset
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus history klik untuk IP <span id="modalIpAddress" class="font-bold text-gray-800"></span>? 
                                    Tindakan ini akan mereset hitungan klik hari ini menjadi 0.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="unlockForm" action="{{ route('click.history.unlock') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ip_address" id="formIpAddress">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Reset History
                        </button>
                    </form>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                console.log('Copied: ' + text);
            });
        }

        function confirmUnlock(ip) {
            document.getElementById('modalIpAddress').textContent = ip;
            document.getElementById('formIpAddress').value = ip;
            
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            
            // Animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }, 300);
        }

        function confirmBlock(ip) {
            document.getElementById('blockModalIpAddress').textContent = ip;
            document.getElementById('blockFormIpAddress').value = ip;
            
            const modal = document.getElementById('blockModal');
            const content = document.getElementById('blockModalContent');
            
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            
            // Animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeBlockModal() {
            const modal = document.getElementById('blockModal');
            const content = document.getElementById('blockModalContent');
            
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }, 300);
        }

        function showMerchants(element) {
            try {
                console.log('Button clicked');
                const dataAttr = element.getAttribute('data-merchants');
                console.log('Data attribute:', dataAttr);

                if (!dataAttr) {
                    console.error('No data-merchants attribute found');
                    alert('Data merchant tidak ditemukan.');
                    return;
                }
                
                const merchants = JSON.parse(dataAttr);
                console.log('Merchants parsed:', merchants);
                
                const listContainer = document.getElementById('merchantList');
                if (!listContainer) {
                    console.error('List container not found');
                    return;
                }
                listContainer.innerHTML = '';
                
                if (merchants && merchants.length > 0) {
                    merchants.forEach(merchant => {
                        const li = document.createElement('li');
                        li.className = 'py-3 px-4 bg-gray-50 rounded-lg text-sm text-gray-700 flex items-center gap-3 hover:bg-orange-50 transition-colors border border-transparent hover:border-orange-100';
                        li.innerHTML = `<i class="fas fa-store text-orange-400 text-lg"></i><span class="font-medium">${merchant}</span>`;
                        listContainer.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.className = 'py-8 text-center text-gray-400 flex flex-col items-center justify-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200';
                    li.innerHTML = `
                        <i class="fas fa-store-slash text-2xl mb-2 text-gray-300"></i>
                        <span class="text-sm font-medium">Tidak ada data merchant</span>
                    `;
                    listContainer.appendChild(li);
                }

                const modal = document.getElementById('merchantModal');
                const content = document.getElementById('merchantModalContent');
                
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.style.display = 'block';
                    
                    // Animation
                    setTimeout(() => {
                        if (content) {
                            content.classList.remove('scale-95', 'opacity-0');
                            content.classList.add('scale-100', 'opacity-100');
                        }
                    }, 10);
                    
                    console.log('Modal shown');
                } else {
                    console.error('Merchant modal element not found');
                }
            } catch (e) {
                console.error('Error showing merchants:', e);
                alert('Terjadi kesalahan: ' + e.message);
            }
        }

        function closeMerchantModal() {
            const modal = document.getElementById('merchantModal');
            const content = document.getElementById('merchantModalContent');
            
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            
            setTimeout(() => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                }
            }, 300);
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeMerchantModal();
            }
        });
        
        // Expose functions to window
        window.showMerchants = showMerchants;
        window.closeMerchantModal = closeMerchantModal;
        window.confirmUnlock = confirmUnlock;
        window.closeModal = closeModal;
        window.confirmBlock = confirmBlock;
        window.closeBlockModal = closeBlockModal;
        window.copyToClipboard = copyToClipboard;
    </script>
</body>
</html>