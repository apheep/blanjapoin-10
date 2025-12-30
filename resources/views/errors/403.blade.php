<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditolak - BlanjaPoin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white text-neutral-900 antialiased font-poppins min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md text-center">
            <div class="mb-8">
                <img src="{{ asset('/logo.png') }}" alt="BlanjaPoin" class="h-16 w-auto mx-auto mb-6">
            </div>
            <div class="bg-white rounded-2xl shadow-xl border border-neutral-200/60 p-8 md:p-12">
                @if(str_contains($exception->getMessage(), 'Bot'))
                    {{-- Tampilan Khusus Bot --}}
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-xl md:text-2xl font-bold text-red-600 mb-2">Akses Dibatasi</h1>
                    <p class="text-sm md:text-base text-neutral-600 mb-6">
                        {{ $exception->getMessage() }}
                    </p>
                    <p class="text-xs text-neutral-400">
                        IP Address Anda telah diblokir sementara karena aktivitas yang tidak wajar.
                        Silakan coba lagi besok.
                    </p>
                @else
                    {{-- Tampilan 403 Biasa --}}
                    <div class="text-6xl md:text-7xl font-black text-neutral-300 mb-4">403</div>
                    <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-2">Akses Ditolak</h1>
                    <p class="text-sm md:text-base text-neutral-600 mb-8">{{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>


