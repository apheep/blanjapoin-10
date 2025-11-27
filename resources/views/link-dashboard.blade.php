<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard • {{ $merchant->nama_merchant }} | BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.head')
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white transition-shadow duration-300 w-full shadow-sm sm:shadow-none">
        <div class="mx-auto max-w-[1120px] px-4 md:px-6 lg:px-8 py-3 md:py-5 lg:py-6">
            <div class="flex items-center justify-center sm:justify-start">
                <a href="{{ route('home') }}" class="inline-flex items-center">
                    <img src="/logo.png" alt="BlanjaPoin" class="h-8 sm:h-10 md:h-12 lg:h-14 w-auto" />
                </a>
            </div>
        </div>
    </nav>




    <!-- Main Content -->
    <div class="mx-auto max-w-[1120px]">
        <main class="px-4 md:px-7 lg:px-8 pb-12 md:pb-16">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mt-4">Dashboard</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1 break-words">{{ $merchant->nama_merchant }}</h1>
                
                </div>
            </div>

            <!-- Table Section -->
            <section class="mt-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 responsive-dashboard-table">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Link Pelanggan</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">QR Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">History</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900" data-label="No">1</td>
                                    
                                    <!-- Link Pelanggan -->
                                    <td class="px-4 py-4" data-label="Link Pelanggan">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ $linkPelanggan }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-2 text-sm font-medium max-w-full">
                                                <i class="fas fa-link"></i>
                                                <span class="truncate max-w-full sm:max-w-md">{{ $linkPelanggan }}</span>
                                            </a>
                                            <button onclick="copyToClipboard('{{ $linkPelanggan }}')" 
                                                    class="text-gray-500 hover:text-gray-700 transition-colors"
                                                    title="Copy Link">
                                                <i class="fas fa-copy text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                    
                                    <!-- QR Code -->
                                    <td class="px-4 py-4 text-center" data-label="QR Code">
                                        <div id="qrcode" class="inline-block p-2 bg-white rounded-lg border border-gray-200"></div>
                                        <div class="mt-2">
                                            <button onclick="downloadQRCode()" 
                                                    class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </div>
                                    </td>
                                    
                                    <!-- History -->
                                    <td class="px-4 py-4" data-label="History">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ $linkHistory }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="text-purple-600 hover:text-purple-800 hover:underline inline-flex items-center gap-2 text-sm font-medium max-w-full">
                                                <i class="fas fa-history"></i>
                                                <span class="truncate max-w-full sm:max-w-md">{{ $linkHistory }}</span>
                                            </a>
                                            <button onclick="copyToClipboard('{{ $linkHistory }}')" 
                                                    class="text-gray-500 hover:text-gray-700 transition-colors"
                                                    title="Copy Link">
                                                <i class="fas fa-copy text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

    <!-- Script untuk generate QR Code -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate QR Code
            const qrCodeElement = document.getElementById('qrcode');
            const linkPelanggan = '{{ $linkPelanggan }}';
            
            if (typeof QRCode !== 'undefined' && qrCodeElement) {
                new QRCode(qrCodeElement, {
                    text: linkPelanggan,
                    width: 150,
                    height: 150,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });

        // Function untuk copy link ke clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check text-green-600"></i>';
                button.classList.add('text-green-600');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('text-green-600');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                alert('Gagal menyalin link');
            });
        }

        // Function untuk download QR Code
        function downloadQRCode() {
            const qrCodeElement = document.getElementById('qrcode');
            const canvas = qrCodeElement.querySelector('canvas');
            
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'qrcode-{{ $merchant->nama_merchant }}-{{ now()->format("Ymd") }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            } else {
                alert('QR Code belum siap, silakan tunggu sebentar');
            }
        }
    </script>
</body>
</html>
<style>
    /* Mobile-friendly stack for dashboard table */
    @media (max-width: 640px) {
        .responsive-dashboard-table thead {
            display: none;
        }
        .responsive-dashboard-table tbody tr {
            display: block;
            margin-bottom: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }
        .responsive-dashboard-table tbody tr td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .responsive-dashboard-table tbody tr td:last-child {
            border-bottom: none;
        }
        .responsive-dashboard-table tbody tr td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #4b5563;
            font-size: 13px;
            margin-right: 16px;
        }
        .responsive-dashboard-table tbody tr td[data-label="QR Code"] {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .responsive-dashboard-table tbody tr td[data-label="QR Code"]::before {
            margin-right: 0;
        }
        .responsive-dashboard-table .truncate {
            max-width: 100%;
        }
        main {
            padding-left: 14px;
            padding-right: 14px;
        }
    }
</style>
