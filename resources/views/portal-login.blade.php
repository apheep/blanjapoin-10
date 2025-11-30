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
                    <p class="text-sm text-neutral-500 mt-1">Gunakan email terdaftar atau login Google</p>
                </div>

                @if($errors->any())
                    <div class="mx-6 mb-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 px-4 py-3 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="px-6 pb-8 space-y-4">
                    <form method="POST" action="{{ route('portal.login.post') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="returnTo" value="{{ $returnTo }}">

                        <div>
                            <label class="text-xs font-semibold text-neutral-600 mb-1 block">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-purple-500">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" required placeholder="nama@merchant.com" value="{{ old('email') }}"
                                       class="w-full rounded-2xl border border-neutral-200 pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-purple-400 focus:border-purple-400 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-neutral-600 mb-1 block">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-purple-500">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input type="password" name="password" required placeholder="Masukkan password"
                                       class="w-full rounded-2xl border border-neutral-200 pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-purple-400 focus:border-purple-400 outline-none transition">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-500 text-white font-semibold shadow-lg hover:shadow-xl transition">
                            Masuk dengan Email
                        </button>
                    </form>

                    <div class="flex items-center gap-3">
                        <span class="flex-1 h-px bg-neutral-200"></span>
                        <span class="text-xs font-semibold text-neutral-400 uppercase">atau</span>
                        <span class="flex-1 h-px bg-neutral-200"></span>
                    </div>

                    <a href="{{ route('portal.google.redirect', ['returnTo' => $returnTo]) }}"
                       class="w-full flex items-center justify-center gap-3 border border-neutral-200 rounded-2xl py-3 font-semibold text-neutral-700 hover:bg-neutral-50 transition">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="h-5 w-5" alt="Google Logo">
                        Masuk dengan Google
                    </a>
                </div>
            </div>

            <p class="text-center text-xs text-neutral-400 mt-6">© {{ now()->year }} BlanjaPoin. Semua hak dilindungi.</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
</body>
</html>

