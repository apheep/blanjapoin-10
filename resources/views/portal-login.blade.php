<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Dashboard Merchant • BlanjaPoin</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @include('partials.head')
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 via-white to-purple-50 font-poppins">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-3xl shadow-2xl border border-purple-100 overflow-hidden">
                <div class="px-8 pt-8 pb-4 text-center">
                    <img src="{{ asset('/logo.png') }}" alt="BlanjaPoin" class="h-14 mx-auto mb-3">
                    <h1 class="text-2xl font-semibold text-neutral-900">Masuk Dashboard Merchant</h1>
                    <p class="text-sm text-neutral-500 mt-1">Login menggunakan akun Google Anda</p>
                </div>

                @if($errors->any())
                    <div class="mx-6 mb-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 px-4 py-3 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="px-6 pb-8">
                    <a href="{{ route('portal.google.redirect', ['returnTo' => $returnTo]) }}" 
                       class="w-full py-4 rounded-2xl bg-white border-2 border-neutral-200 text-neutral-700 font-semibold shadow-lg hover:shadow-xl hover:border-neutral-300 transition-all duration-200 flex items-center justify-center gap-3 group">
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Masuk dengan Google</span>
                    </a>
                </div>
            </div>

            <p class="text-center text-xs text-neutral-400 mt-6">© {{ now()->year }} BlanjaPoin. Semua hak dilindungi.</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
</body>
</html>

