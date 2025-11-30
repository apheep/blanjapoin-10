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
    <style>
        .gradient-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-border {
            position: relative;
            background: white;
            border-radius: 20px;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        .qr-container {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-purple-50 text-neutral-900 antialiased font-poppins min-h-screen" id="pageBody">
    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg transition-shadow duration-300 w-full shadow-sm">
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
            <!-- Header Section -->
            <div class="animate-fade-in-up">
                <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                    <div class="flex-1 min-w-[220px]">
                        <p class="text-xs font-semibold uppercase tracking-wider text-purple-600 flex items-center gap-2">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </p>
                        <h1 class="text-3xl sm:text-4xl font-bold bg-black bg-clip-text text-transparent mt-1 break-words">
                            Hello {{ $merchant->nama_merchant }}
                        </h1>
                    </div>
                    @if($merchant->logo_merchant)
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white shadow-lg border border-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/' . $merchant->logo_merchant) }}" alt="Logo {{ $merchant->nama_merchant }}" class="w-full h-full object-contain p-2">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dashboard Cards Section -->
            <section class="mt-8 space-y-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                
                <!-- QR Code Card - Featured -->
                <div class="gradient-card rounded-3xl shadow-2xl p-6 md:p-8 text-white overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-48 h-48 md:w-56 md:h-56 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-36 h-36 md:w-44 md:h-44 bg-white/10 rounded-full -ml-16 -mb-16"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4 md:mb-6">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-qrcode text-xl md:text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl md:text-2xl font-bold">QR Code Pelanggan</h2>
                                <p class="text-white/80 text-xs md:text-sm">Scan untuk akses cepat</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-row items-start gap-3 md:gap-6 w-full overflow-x-auto">
                            <!-- QR Code Display -->
                            <div class="qr-container p-2.5 md:p-3 rounded-2xl shadow-xl bg-white flex-shrink-0">
                                <div id="qrcode" class="inline-block w-24 h-24 md:w-28 md:h-28"></div>
                            </div>
                            
                            <!-- QR Actions -->
                            <div class="flex-1 min-w-[160px] md:min-w-[200px] space-y-3 md:space-y-4 sm:pl-2">
                                <button onclick="downloadQRCode()" 
                                        class="w-full bg-white text-purple-600 hover:bg-purple-50 py-3 md:py-4 px-5 md:px-6 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-download text-base md:text-lg"></i>
                                    <span>Download</span>
                                </button>
                                <button onclick="printQRCode()" 
                                        class="w-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 py-3 md:py-4 px-5 md:px-6 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-3 border-2 border-white/30">
                                    <i class="fas fa-print text-base md:text-lg"></i>
                                    <span>Print</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Links Grid -->
                <div class="flex flex-col xl:flex-row gap-4 items-stretch">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1">
                        <!-- Link Pelanggan Button -->
                        <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg p-4 border border-gray-100 flex flex-col items-center gap-3">
                            <a href="{{ $linkPelanggan }}"
                               class="w-12 h-12 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow-lg transition-all duration-200"
                               title="Kunjungi link pelanggan">
                                <i class="fas fa-link text-lg"></i>
                                <span class="sr-only">Link Pelanggan</span>
                            </a>
                            <div class="text-sm font-semibold text-gray-700">Link Pelanggan</div>
                        </div>

                        <!-- History Button -->
                        <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-lg p-4 border border-gray-100 flex flex-col items-center gap-3">
                            <a href="{{ $linkHistory }}"
                               class="w-12 h-12 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white flex items-center justify-center shadow-lg transition-all duration-200"
                               title="Kunjungi link riwayat transaksi">
                                <i class="fas fa-history text-lg"></i>
                                <span class="sr-only">History Transaksi</span>
                            </a>
                            <div class="text-sm font-semibold text-gray-700">History Transaksi</div>
                        </div>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('portal.logout') }}" class="stat-card bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-lg p-4 border border-gray-100 flex flex-col items-center gap-3">
                            @csrf
                            <button type="submit" class="w-12 h-12 rounded-2xl bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg transition-all duration-200" title="Keluar akun">
                                <i class="fas fa-arrow-right-from-bracket text-lg"></i>
                                <span class="sr-only">Logout</span>
                            </button>
                            <div class="text-sm font-semibold text-gray-700">Logout</div>
                        </form>
                    </div>
                </div>

                {{-- <!-- Info Banner -->
                <div class="bg-gradient-to-r from-orange-50 to-rose-50 rounded-2xl p-6 border border-orange-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-info-circle text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Cara Penggunaan</h4>
                            <p class="text-sm text-gray-600">Bagikan QR Code atau Link Pelanggan kepada customer Anda untuk melakukan redeem poin Telkomsel dengan mudah.</p>
                        </div>
                    </div>
                </div>
            </section> --}}

            <!-- Footer -->
            <footer class="mt-16 pb-12 text-center animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="inline-block px-6 py-3 rounded-2xl bg-gradient-to-r from-orange-50 to-rose-50 shadow-sm ring-1 ring-neutral-200/50 mb-4">
                    <div class="text-sm font-semibold text-neutral-700">✨ Redeem Poin Telkomsel</div>
                </div>
                <div class="text-xs text-neutral-500 font-medium">© 2025 BelanjaPoin. All rights reserved.</div>
            </footer>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate QR Code
            const qrCodeElement = document.getElementById('qrcode');
            const linkPelanggan = '{{ $linkPelanggan }}';
            
            if (typeof QRCode !== 'undefined' && qrCodeElement) {
                new QRCode(qrCodeElement, {
                    text: linkPelanggan,
                    width: 100,
                    height: 100,
                    colorDark: '#5b21b6',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });

        // Function untuk copy link ke clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i><span>Tersalin!</span>';
                button.classList.add('!bg-green-600', '!hover:bg-green-700');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('!bg-green-600', '!hover:bg-green-700');
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

        function printQRCode() {
            const qrCodeElement = document.getElementById('qrcode');
            const canvas = qrCodeElement.querySelector('canvas');
            if (!canvas) {
                alert('QR Code belum siap, silakan tunggu sebentar');
                return;
            }

            const dataUrl = canvas.toDataURL('image/png');
            const printWindow = window.open('', '_blank');
            if (!printWindow) {
                alert('Tidak dapat membuka jendela baru untuk cetak.');
                return;
            }

            printWindow.document.write(`
                <html>
                <head>
                    <title>Print QR Code - {{ $merchant->nama_merchant }}</title>
                    <style>
                        body {
                            margin: 0;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            min-height: 100vh;
                            font-family: 'Poppins', sans-serif;
                        }
                        h1 {
                            margin-bottom: 20px;
                            color: #333;
                        }
                        img {
                            border: 3px solid #5b21b6;
                            border-radius: 10px;
                            padding: 10px;
                            background: white;
                        }
                    </style>
                </head>
                <body>
                    <h1>{{ $merchant->nama_merchant }}</h1>
                    <img src="${dataUrl}" alt="QR Code" width="300" height="300" />
                    <p style="margin-top: 20px; color: #666;">Scan untuk akses link pelanggan</p>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
            }, 250);
        }
    </script>
</body>
</html>