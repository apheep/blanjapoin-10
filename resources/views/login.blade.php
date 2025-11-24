@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white px-4">
  <div class="w-full max-w-sm">
    <div class="bg-white rounded-2xl shadow-xl border border-neutral-200/60 overflow-hidden">
      <div class="px-6 pt-6">
        <div class="flex flex-col items-center">
          <div class="">
            <img src="{{ asset('/logo.png') }}" alt="" class="w-55 h-20">
          </div>
          <!-- <h1 class="mt-3 text-2xl font-black tracking-tight text-neutral-900">Masuk</h1>
          <p class="mt-1 text-sm text-neutral-600">Silakan login untuk melanjutkan</p> -->
        </div>

        @if(session('error'))
          <div class="mt-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            {{ session('error') }}
          </div>
        @endif
      </div>

      <div class="px-6 pb-6 pt-4">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
          @csrf

          <div class="relative">
            <label for="username" class="block text-xs font-semibold text-neutral-700 mb-1">Username</label>
            <div class="relative">
              <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-2 pl-10 text-sm outline-none ring-0 focus:border-orange-400 focus:ring-2 focus:ring-orange-400">
              <div class="absolute left-3 top-2.5 text-neutral-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/>
                </svg>
              </div>
            </div>
            @error('username')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="relative">
            <label for="password" class="block text-xs font-semibold text-neutral-700 mb-1">Password</label>
            <div class="relative">
              <input type="password" id="password" name="password" placeholder="Password" required class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-2 pr-10 pl-10 text-sm outline-none ring-0 focus:border-orange-400 focus:ring-2 focus:ring-orange-400">
              <div class="absolute left-3 top-2.5 text-neutral-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path d="M12 5c-4.477 0-8.268 2.943-9.542 7C4.732 16.057 8.523 19 13 19s8.268-2.943 9.542-7C20.268 7.943 16.477 5 12 5Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
              </div>
              <button type="button" id="togglePassword" aria-label="Tampilkan password" class="absolute inset-y-0 right-2 flex items-center text-neutral-500">
                <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.197.625-.435 1.23-.708 1.812M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>
            </div>
            @error('password')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- <div class="flex items-center justify-between">
            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-neutral-600 hover:text-neutral-800 underline">Lupa password?</a>
          </div> -->

          <button type="submit" class=" mb-2 mt-2 w-full rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-lg bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] ring-1 ring-white/30 transition-all hover:shadow-xl hover:scale-105  active:scale-95">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
  const password = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  if (password.type === 'password') {
    password.type = 'text';
    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.958-4.733m3.733-1.86A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-3.567 4.882M3 3l18 18"/>';
  } else {
    password.type = 'password';
    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.197.625-.435 1.23-.708 1.812M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
  }
});
</script>
@endsection
