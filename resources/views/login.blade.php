<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">

    <div class="bg-white shadow-md rounded-2xl p-8 w-full max-w-sm">
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Login</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Username --}}
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username"
                       placeholder="Username"
                       value="{{ old('username') }}"
                       required autofocus
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            {{-- Password --}}
            <div class="mb-6 relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Password"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-400">
                <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.197.625-.435 1.23-.708 1.812M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            {{-- Tombol Login --}}
            <button type="submit"
                    class="w-full py-2 text-black font-semibold rounded-lg shadow-md transition-all bg-linear-to-r from-red-500 to-yellow-400 hover:opacity-90">
                Login
            </button>

            {{-- Lupa Password --}}
            <div class="text-center mt-4">
                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
                    Lupa password?
                </a>
            </div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.958-4.733m3.733-1.86A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-3.567 4.882M3 3l18 18"/>`;
            } else {
                password.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.197.625-.435 1.23-.708 1.812M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
            }
        });
    </script>
</body>
</html>
