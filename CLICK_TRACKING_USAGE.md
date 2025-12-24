# Click Tracking Usage Guide

## 📖 Cara Menggunakan Click Tracking

Ada 3 cara untuk tracking click user:

---

## 1️⃣ Tracking dari JavaScript (Frontend) - **RECOMMENDED**

Gunakan ini saat user klik merchant card atau keyword di halaman publik.

### Contoh: Track saat klik merchant card

```javascript
// Di file blade atau JavaScript
function trackMerchantClick(merchantId, keywordId = null) {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Send tracking request
    fetch('/api/track-click', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            merchant_id: merchantId,
            keyword_id: keywordId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Click tracked:', data);
    })
    .catch(error => {
        console.error('Error tracking click:', error);
    });
}

// Panggil saat user klik
document.querySelectorAll('.merchant-card').forEach(card => {
    card.addEventListener('click', function() {
        const merchantId = this.getAttribute('data-merchant-id');
        const keywordId = this.getAttribute('data-keyword-id');
        
        // Track click
        trackMerchantClick(merchantId, keywordId);
        
        // Then redirect or open link
        // window.location.href = this.getAttribute('data-link');
    });
});
```

### Tambahkan di blade layout:

```blade
{{-- Di head section --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Di merchant card --}}
<div class="merchant-card" 
     data-merchant-id="{{ $merchant->id }}"
     data-keyword-id="{{ $keyword->keyword_id ?? '' }}"
     data-link="{{ $merchant->link_blanjapoin }}">
    <!-- Card content -->
</div>
```

---

## 2️⃣ Tracking dari Controller (Backend)

Gunakan ini saat user mengakses route yang memerlukan tracking otomatis.

### Cara 1: Menggunakan Static Method

```php
use App\Http\Controllers\ClickHistoryController;

class MerchantController extends Controller
{
    public function linkPelanggan($code, Request $request)
    {
        // ... find merchant logic ...
        
        // Track click
        ClickHistoryController::recordClick(
            $merchant->id, 
            $keyword->keyword_id ?? null, 
            $request
        );
        
        // ... continue with your logic ...
        return view('merchant-page', compact('merchant'));
    }
}
```

### Cara 2: Instantiate Controller

```php
use App\Http\Controllers\ClickHistoryController;

public function showMerchant($id, Request $request)
{
    $merchant = Merchant::findOrFail($id);
    
    // Track click
    $trackingController = new ClickHistoryController();
    $trackingController->trackClick($request->merge([
        'merchant_id' => $merchant->id
    ]));
    
    return view('merchant.show', compact('merchant'));
}
```

---

## 3️⃣ Tracking dengan Middleware (Advanced)

Buat middleware untuk auto-track certain routes.

### Create Middleware:

```bash
php artisan make:middleware TrackClickMiddleware
```

### app/Http/Middleware/TrackClickMiddleware.php:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\ClickHistoryController;

class TrackClickMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Extract merchant_id from route or query
        $merchantId = $request->route('id') ?? $request->query('merchant_id');
        $keywordId = $request->query('keyword_id');
        
        if ($merchantId) {
            ClickHistoryController::recordClick($merchantId, $keywordId, $request);
        }
        
        return $next($request);
    }
}
```

### Register di app/Http/Kernel.php:

```php
protected $routeMiddleware = [
    // ...
    'track.click' => \App\Http\Middleware\TrackClickMiddleware::class,
];
```

### Gunakan di routes:

```php
Route::get('/merchant/{id}', [MerchantController::class, 'show'])
    ->middleware('track.click');
```

---

## 🎯 Implementasi di Welcome Page (Contoh Lengkap)

### Di resources/views/welcome.blade.php:

```blade
@foreach($keywords as $keyword)
    <a href="{{ $keyword->merchant->link_blanjapoin }}" 
       class="keyword-card"
       data-merchant-id="{{ $keyword->merchant_key }}"
       data-keyword-id="{{ $keyword->keyword_id }}"
       onclick="trackClick(event, this)">
        <h3>{{ $keyword->nama_produk }}</h3>
        <p>{{ $keyword->merchant->nama_merchant }}</p>
    </a>
@endforeach

<script>
function trackClick(event, element) {
    // Prevent default untuk tracking dulu
    event.preventDefault();
    
    const merchantId = element.getAttribute('data-merchant-id');
    const keywordId = element.getAttribute('data-keyword-id');
    const targetUrl = element.getAttribute('href');
    
    // Track click
    fetch('/api/track-click', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            merchant_id: merchantId,
            keyword_id: keywordId
        })
    })
    .then(() => {
        // Setelah tracking berhasil, redirect
        window.location.href = targetUrl;
    })
    .catch(() => {
        // Jika tracking gagal, tetap redirect
        window.location.href = targetUrl;
    });
}
</script>
```

---

## 📊 Data Yang Ditrack

Setiap click akan mencatat:
- ✅ **merchant_id** - ID merchant yang diklik
- ✅ **keyword_id** - ID keyword (optional)
- ✅ **ip_address** - IP address user (support proxy/forwarded IP)
- ✅ **device_id** - Unique device ID dari cookie
- ✅ **clicked_at** - Timestamp klik
- ✅ **user_agent** - Browser/device info
- ✅ **referer** - URL asal klik

---

## 🔍 Analisa Data

Setelah data terkumpul, bisa dianalisa di:
- `/click-history` - View semua click dengan detail matching redeem
- `/click-history/analytics` - Dashboard analytics

---

## ⚙️ Tips & Best Practices

1. **Gunakan JavaScript tracking** untuk UX lebih baik (non-blocking)
2. **Fallback**: Jika JavaScript gagal, tetap redirect ke target
3. **Rate limiting**: Consider adding rate limit untuk prevent spam
4. **Privacy**: Inform user tentang tracking di Privacy Policy
5. **Performance**: Track async, jangan block user experience

---

## 🔧 Troubleshooting

### Cookie tidak ter-set?
- Pastikan domain sama (tidak cross-domain)
- Check browser settings (allow cookies)
- Untuk Laravel, pastikan session driver configured

### IP selalu 127.0.0.1?
- Configure nginx/apache untuk forward real IP
- Check `getClientIP()` implementation
- Gunakan `HTTP_X_FORWARDED_FOR` header

### Track tidak ter-record?
- Check log: `storage/logs/laravel.log`
- Verify CSRF token
- Check database migration sudah run
- Verify merchant_id valid

---

## 📞 Support

Jika ada masalah, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console untuk JavaScript errors
3. Network tab untuk API request/response

