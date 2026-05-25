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
        </div>

        @if(session('error'))
          <div class="mt-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            {{ session('error') }}
          </div>
        @endif
      </div>

      <div class="px-6 pb-6 pt-4">
        @if(session('success'))
          <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
            {{ session('success') }}
          </div>
        @endif

        <div class="space-y-4">
          <div class="relative">
            <label for="no_hp" class="block text-xs font-semibold text-neutral-700 mb-1">Nomor HP</label>
            <div class="relative">
              <div class="absolute left-3 top-2.5 text-neutral-500 text-sm font-medium z-10">62</div>
              <input type="text" id="no_hp" name="no_hp" placeholder="8xxxxxxxxxx" value="{{ old('no_hp', session('otp_phone_display')) }}" required autofocus class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-2 pl-12 text-sm outline-none ring-0 focus:border-orange-400 focus:ring-2 focus:ring-orange-400">
            </div>
            @error('no_hp')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="relative">
            <label class="block text-xs font-semibold text-neutral-700 mb-2">Pilih Metode Pengiriman OTP</label>
            <div class="grid grid-cols-3 gap-2">
           
              <label class="flex items-center p-3 rounded-xl border border-neutral-300 cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                <input type="radio" name="otp_type" value="whatsapp" class="sr-only peer" {{ (!old('otp_type') && !session('otp_type')) || old('otp_type', session('otp_type')) == 'whatsapp' ? 'checked' : '' }}>
                <div class="flex items-center gap-2 w-full">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="currentColor" class="w-2.5 h-2.5 shrink-0 text-green-600">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                  </svg>
                  <span class="text-xs font-medium text-neutral-700 peer-checked:text-green-600">WhatsApp</span>
                </div>
              </label>
              <label class="flex items-center p-3 rounded-xl border border-neutral-300 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                <input type="radio" name="otp_type" value="telegram" class="sr-only peer" {{ old('otp_type', session('otp_type')) == 'telegram' ? 'checked' : '' }}>
                <div class="flex items-center gap-2 w-full">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="currentColor" class="w-3 h-3 shrink-0 text-blue-600">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.178 1.994-1.067 6.82-1.502 9.04-.178.89-.528 1.187-.867 1.216-.723.058-1.27-.477-1.97-.935-1.095-.72-1.714-1.168-2.776-1.87-1.227-.88-.432-1.365.267-2.155.183-.203 3.243-2.977 3.302-3.23.007-.032.014-.15-.056-.212-.07-.062-.173-.041-.248-.024-.106.024-1.793 1.14-5.062 3.345-.479.334-.913.497-1.302.49-.428-.008-1.252-.241-1.865-.44-.752-.244-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.14.118.095.151.223.167.313.011.06.025.198.015.305z"/>
                  </svg>
                  <span class="text-xs font-medium text-neutral-700 peer-checked:text-blue-600">Telegram</span>
                </div>
              </label>
              <label class="flex items-center p-3 rounded-xl border border-neutral-300 cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                <input type="radio" name="otp_type" value="emailphone" class="sr-only peer" {{ old('otp_type', session('otp_type')) == 'emailphone' ? 'checked' : '' }}>
                <div class="flex items-center gap-2 w-full">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="currentColor" class="w-5 h-5 shrink-0 text-neutral-600 peer-checked:text-orange-500">
                    <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z"/>
                    <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z"/>
                  </svg>
                  <span class="text-xs font-medium text-neutral-700 peer-checked:text-orange-600">Email</span>
                </div>
              </label>
            </div>
          </div>

          <form method="POST" action="{{ route('login.send-otp') }}" id="sendOtpForm" class="mb-4">
            @csrf
            <input type="hidden" name="no_hp" id="no_hp_hidden" value="">
            <input type="hidden" name="otp_type" id="otp_type_hidden" value="">
            <button type="submit" id="sendOtpBtn" class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-lg bg-gradient-to-r from-blue-500 to-blue-600 ring-1 ring-white/30 transition-all hover:shadow-xl hover:scale-105 active:scale-95">Kirim OTP</button>
          </form>
          
          @if($errors->has('no_hp') || $errors->has('otp_type'))
          <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
          @endif

          @if(session('otp_redirect_url') && (session('otp_type') == 'whatsapp' || session('otp_type') == 'telegram'))
          <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 mb-4">
            <p class="text-xs text-blue-700 mb-2">OTP telah dikirim via {{ session('otp_type') == 'whatsapp' ? 'WhatsApp' : 'Telegram' }}. Membuka aplikasi...</p>
          </div>
          @endif

          <form method="POST" action="{{ route('login.post') }}" class="space-y-4" id="loginForm">
            @csrf
            <input type="hidden" name="no_hp" value="{{ old('no_hp', session('otp_phone_display')) }}">

            <div class="relative">
              <label for="otp" class="block text-xs font-semibold text-neutral-700 mb-1">Kode OTP</label>
              <div class="relative">
                <input type="text" id="otp" name="otp" placeholder="Masukkan 6 digit OTP" maxlength="6" pattern="[0-9]{6}" value="{{ old('otp') }}" required class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-2 pl-10 text-sm outline-none ring-0 focus:border-orange-400 focus:ring-2 focus:ring-orange-400">
                <div class="absolute left-3 top-2.5 text-neutral-400">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              @error('otp')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <button type="submit" class="mb-2 mt-2 w-full rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-lg bg-gradient-to-r from-[#FF3B30] via-[#FF6B2C] to-[#FF9F0A] ring-1 ring-white/30 transition-all hover:shadow-xl hover:scale-105 active:scale-95">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@php
  $shouldAutoRedirect = session('otp_redirect_url') && (session('otp_type') == 'whatsapp' || session('otp_type') == 'telegram');
  $redirectUrl = session('otp_redirect_url', '');
@endphp

<script>
var shouldAutoRedirect = {{ $shouldAutoRedirect ? 'true' : 'false' }};
var redirectUrl = {!! json_encode($redirectUrl) !!};

document.addEventListener('DOMContentLoaded', function() {
  // Auto redirect untuk WhatsApp/Telegram
  if (shouldAutoRedirect && redirectUrl) {
    setTimeout(function() {
      window.location.href = redirectUrl;
    }, 1000);
  }

  // Format nomor HP dengan prefix 62
  const noHpInput = document.getElementById('no_hp');
  
  // Format value yang sudah ada jika ada dari session
  if (noHpInput.value) {
    let currentValue = noHpInput.value.replace(/\D/g, '');
    if (currentValue.startsWith('62')) {
      noHpInput.value = currentValue.substring(2);
    } else if (currentValue.startsWith('0')) {
      noHpInput.value = currentValue.substring(1);
    } else {
      noHpInput.value = currentValue;
    }
  }

  // Format input saat user mengetik
  noHpInput.addEventListener('input', function() {
    // Hanya ambil angka
    let value = this.value.replace(/\D/g, '');
    
    // Jika dimulai dengan 0, hapus 0
    if (value.startsWith('0')) {
      value = value.substring(1);
    }
    
    // Jika dimulai dengan 62, hapus 62
    if (value.startsWith('62')) {
      value = value.substring(2);
    }
    
    this.value = value;
  });

  // Format saat blur (ketika user selesai mengetik)
  noHpInput.addEventListener('blur', function() {
    let value = this.value.replace(/\D/g, '');
    
    if (value.startsWith('0')) {
      value = value.substring(1);
    }
    
    if (value.startsWith('62')) {
      value = value.substring(2);
    }
    
    this.value = value;
  });

  const sendOtpForm = document.getElementById('sendOtpForm');
  
  if (!sendOtpForm) {
    console.error('Form sendOtpForm tidak ditemukan!');
    return;
  }

  sendOtpForm.addEventListener('submit', function(e) {
    console.log('Form submit triggered');
    
    let noHp = document.getElementById('no_hp').value.replace(/\D/g, '');
    const otpTypeInput = document.querySelector('input[name="otp_type"]:checked');
    
    if (!noHp) {
      e.preventDefault();
      alert('Silakan masukkan nomor HP terlebih dahulu');
      return false;
    }

    if (!otpTypeInput) {
      e.preventDefault();
      alert('Silakan pilih metode pengiriman OTP');
      return false;
    }

    // Hapus 0 di depan jika ada
    if (noHp.startsWith('0')) {
      noHp = noHp.substring(1);
    }
    
    // Tambahkan prefix 62 jika belum ada
    if (!noHp.startsWith('62')) {
      noHp = '62' + noHp;
    }

    const otpType = otpTypeInput.value;
    
    console.log('Sending OTP:', { noHp, otpType });

    // Set hidden input values dengan format lengkap (62xxxxxxxxxxx)
    document.getElementById('no_hp_hidden').value = noHp;
    document.getElementById('otp_type_hidden').value = otpType;

    // Disable button and show loading
    const btn = document.getElementById('sendOtpBtn');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';
    
    // Form will submit normally and redirect will work
    return true;
  });

  // Auto focus OTP field after sending
  const otpInput = document.getElementById('otp');
  if (otpInput) {
    otpInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  }

  // Update radio button styles on change
  document.querySelectorAll('input[name="otp_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
      // Remove all checked classes
      document.querySelectorAll('label').forEach(label => {
        label.classList.remove('border-orange-500', 'bg-orange-50', 'border-green-500', 'bg-green-50', 'border-blue-500', 'bg-blue-50');
      });
    });
  });
});
</script>
@endsection
