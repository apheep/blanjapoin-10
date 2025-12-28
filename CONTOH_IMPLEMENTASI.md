# 🚀 Contoh Implementasi Click Tracking - READY TO USE

## Quick Start (3 Langkah Mudah)

---

## 📝 Step 1: Include JavaScript di Layout

Tambahkan di `resources/views/partials/head.blade.php` atau layout utama:

```blade
{{-- CSRF Token untuk AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Click Tracker Script --}}
<script src="{{ asset('js/click-tracker.js') }}" defer></script>
```

---

## 📝 Step 2: Tambahkan Tracking ke Merchant/Keyword Cards

### Cara PALING MUDAH - Gunakan Data Attributes:

```blade
{{-- Di welcome.blade.php atau view apapun --}}

@foreach($keywords as $keyword)
    <a href="{{ $keyword->merchant->link_blanjapoin }}" 
       class="keyword-card"
       data-track-merchant="{{ $keyword->merchant_key }}"
       data-track-keyword="{{ $keyword->keyword_id }}">
        
        <h3>{{ $keyword->nama_produk }}</h3>
        <p>{{ $keyword->merchant->nama_merchant }}</p>
        <p>{{ $keyword->point_redeem }} Poin</p>
    </a>
@endforeach

{{-- SELESAI! Script otomatis detect dan track click --}}
```

### ✨ Magic Attributes:
- `data-track-merchant` = ID merchant (WAJIB)
- `data-track-keyword` = ID keyword (optional)
- Script akan otomatis:
  1. Detect click
  2. Track ke database
  3. Redirect ke link

---

## 📝 Step 3 (Optional): Manual Tracking untuk Kontrol Lebih

Jika butuh kontrol lebih, gunakan JavaScript manual:

```blade
<div class="merchant-card" onclick="handleMerchantClick({{ $merchant->id }}, '{{ $merchant->link_blanjapoin }}')">
    <h3>{{ $merchant->nama_merchant }}</h3>
    <p>{{ $merchant->daerah }}</p>
</div>

<script>
function handleMerchantClick(merchantId, url) {
    // Track dan redirect otomatis
    trackAndRedirect(merchantId, null, url);
}
</script>
```

---

## 🎯 Contoh Lengkap Welcome Page

```blade
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BlanjaPoin</title>
    
    {{-- Include Click Tracker --}}
    <script src="{{ asset('js/click-tracker.js') }}" defer></script>
    
    {{-- Your CSS --}}
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
    <div class="container">
        <h1>Pilih Merchant & Keyword</h1>
        
        <div class="keywords-grid">
            @foreach($keywords as $keyword)
                {{-- AUTO TRACKING dengan data attributes --}}
                <a href="{{ $keyword->merchant->link_blanjapoin }}" 
                   class="keyword-card"
                   data-track-merchant="{{ $keyword->merchant_key }}"
                   data-track-keyword="{{ $keyword->keyword_id }}">
                    
                    <div class="card-image">
                        <img src="{{ $keyword->merchant->foto }}" alt="{{ $keyword->nama_produk }}">
                    </div>
                    
                    <div class="card-content">
                        <h3>{{ $keyword->nama_produk }}</h3>
                        <p class="merchant-name">{{ $keyword->merchant->nama_merchant }}</p>
                        <p class="location">📍 {{ $keyword->merchant->daerah }}</p>
                        
                        <div class="points">
                            <span class="point-badge">{{ number_format($keyword->point_redeem) }} Poin</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</body>
</html>
```

---

## 🎨 Contoh dengan Button Click

```blade
{{-- Button dengan tracking --}}
<button class="btn-primary" 
        data-track-merchant="{{ $merchant->id }}"
        data-track-keyword="{{ $keyword->keyword_id }}"
        data-track-url="{{ $merchant->link_blanjapoin }}">
    Klik untuk Redeem
</button>

{{-- Card dengan tracking --}}
<div class="merchant-card" 
     data-track-merchant="{{ $merchant->id }}"
     data-track-url="/merchant/{{ $merchant->id }}/detail">
    <img src="{{ $merchant->foto }}">
    <h3>{{ $merchant->nama_merchant }}</h3>
    <p>{{ $merchant->daerah }}</p>
</div>
```

---

## 🔧 Advanced: Tracking Tanpa Redirect

Jika hanya ingin track tanpa redirect (misal: open new tab):

```blade
<a href="{{ $url }}" 
   target="_blank"
   data-track-merchant="{{ $merchant->id }}"
   data-track-keyword="{{ $keyword->keyword_id }}"
   data-track-only>
    {{-- data-track-only = track saja, tidak redirect --}}
    Buka di Tab Baru
</a>
```

---

## 🎯 Tracking dari Backend (Alternative)

Jika lebih suka tracking dari controller PHP:

### Di MerchantController.php:

```php
use App\Http\Controllers\ClickHistoryController;

public function linkPelanggan($code, Request $request)
{
    // ... find merchant logic ...
    
    $merchant = Merchant::where('link_blanjapoin', 'LIKE', "%/u/{$code}%")->first();
    
    if ($merchant) {
        // TRACK CLICK
        ClickHistoryController::recordClick(
            $merchant->id,
            null, // keyword_id jika ada
            $request
        );
    }
    
    // ... continue your logic ...
    return view('merchant.page', compact('merchant'));
}
```

---

## ✅ Checklist Implementasi

- [ ] Include `click-tracker.js` di head
- [ ] Tambahkan `<meta name="csrf-token">` di head
- [ ] Tambahkan `data-track-merchant` ke element yang ingin ditrack
- [ ] (Optional) Tambahkan `data-track-keyword` jika ada keyword
- [ ] Test klik → check database `click_history` table
- [ ] Test di `/click-history` untuk lihat data

---

## 🧪 Testing

### 1. Test Manual:
```javascript
// Di browser console:
trackClick(1, 'KEYWORD123');  // Track merchant ID 1 dengan keyword
```

### 2. Check Database:
```sql
SELECT * FROM click_history ORDER BY clicked_at DESC LIMIT 10;
```

### 3. Check di Admin Panel:
- Buka: `http://your-domain.com/click-history`
- Filter by merchant/date
- Lihat matched vs suspicious

---

## 🎊 SELESAI!

Sekarang setiap klik user akan tercatat dan bisa dianalisa untuk deteksi cheating!

### Yang Tercatat:
✅ Merchant ID
✅ Keyword ID
✅ IP Address (real IP, bukan proxy)
✅ Device ID (unique per device via cookie)
✅ Timestamp klik
✅ User Agent
✅ Referer URL

### Auto-Matching:
Sistem otomatis akan match klik dengan redeem berdasarkan:
- Keyword sama
- Waktu redeem setelah klik
- Selisih waktu paling dekat
- IP & Device ID match = legit
- IP & Device ID tidak match = suspicious (cheating)

---

## 💡 Tips Pro:

1. **Test di Incognito** untuk simulate user baru
2. **Check Console** untuk debug tracking
3. **Monitor Click Rate** untuk detect anomaly
4. **Set Alert** untuk suspicious clicks > threshold

Selamat menggunakan! 🚀

