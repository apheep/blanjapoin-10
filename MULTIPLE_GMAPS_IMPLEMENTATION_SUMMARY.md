# 🗺️ Multiple Google Maps Locations Feature

## Ringkasan Implementasi

Fitur baru untuk support **multiple lokasi merchant dengan radius validation masing-masing**.

## Yang Berubah

### 1️⃣ Database
- **New Column**: `merchants.link_gmaps` (JSON array)
- **Format**: `[{"link": "url", "radius": 500}, ...]`
- **Migration**: `2025_12_29_000001_modify_link_gmap_to_support_multiple_locations.php`

### 2️⃣ Model
**File**: `app/Models/Merchant.php`

**Methods Baru:**
- `getGmapsLocations()` - Get all locations
- `addGmapsLocation($link, $radius)` - Add location
- `updateGmapsLocation($index, $link, $radius)` - Edit location
- `removeGmapsLocation($index)` - Delete location
- `isUserWithinAnyRadius($lat, $lng)` - Check radius (returns matched location)

### 3️⃣ Controller
**File**: `app/Http/Controllers/MerchantController.php`

**New API Routes:**
```
GET    /api/merchants/{id}/gmaps-locations
POST   /api/merchants/{id}/gmaps-locations
PUT    /api/merchants/{id}/gmaps-locations/{index}
DELETE /api/merchants/{id}/gmaps-locations/{index}
POST   /api/merchants/{id}/check-radius
```

### 4️⃣ Frontend

**JavaScript**: `public/js/merchant-radius-validator.js`
- Class `MerchantRadiusValidator` untuk handle multiple locations
- Support backward compatibility dengan single link_gmap

**Views Updated**:
- `resources/views/partials/gmaps-locations-manager.blade.php` (baru)
- `resources/views/partials/edit-modal-merchant.blade.php` (include manager)
- `resources/views/link-pelanggan.blade.php` (gunakan validator class)
- `resources/views/partials/table-merchant.blade.php` (display multiple links)

### 5️⃣ Routes
**File**: `routes/web.php`
```php
Route::get('/api/merchants/{id}/gmaps-locations', ...)->name('merchants.gmaps.list');
Route::post('/api/merchants/{id}/gmaps-locations', ...)->name('merchants.gmaps.add');
Route::put('/api/merchants/{id}/gmaps-locations/{locationIndex}', ...)->name('merchants.gmaps.update');
Route::delete('/api/merchants/{id}/gmaps-locations/{locationIndex}', ...)->name('merchants.gmaps.remove');
Route::post('/api/merchants/{id}/check-radius', ...)->name('merchants.check-radius');
```

## Cara Kerja

### Untuk Admin (Menambah Lokasi)

1. Buka edit merchant
2. Scroll ke "Kelola Lokasi Google Maps"
3. Klik "Tambah Lokasi"
4. Input link & radius
5. Save

### Untuk Customer (Redeem)

1. Buka halaman merchant
2. Browser minta GPS permission → Allow
3. Sistem hitung distance ke **SEMUA lokasi**
4. Jika user dalam radius **ADA SATU SAJA** lokasi → Redeem ✅
5. Jika user di luar radius **SEMUA** lokasi → Tolak ❌

## Backward Compatibility

- ✅ Sistem masih support data lama (single `link_gmap`)
- ✅ Query `getGmapsLocations()` mengembalikan array untuk kedua format
- ✅ Migration otomatis convert data lama ke format baru
- ✅ Tidak perlu ubah existing code jika pakai helper methods

## Testing Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Test add multiple locations di admin UI
- [ ] Test edit location
- [ ] Test delete location
- [ ] Test GPS validation di customer page
- [ ] Test dengan 0 locations (backward compat)
- [ ] Test dengan 1 location (backward compat)
- [ ] Test dengan 3+ locations

## Files List

### Created:
- `database/migrations/2025_12_29_000001_modify_link_gmap_to_support_multiple_locations.php`
- `public/js/merchant-radius-validator.js`
- `resources/views/partials/gmaps-locations-manager.blade.php`
- `MULTIPLE_GMAPS_LOCATIONS_GUIDE.md` (dokumentasi lengkap)

### Modified:
- `app/Models/Merchant.php` (+ methods & casts)
- `app/Http/Controllers/MerchantController.php` (+ 5 endpoint)
- `routes/web.php` (+ 5 routes)
- `resources/views/partials/edit-modal-merchant.blade.php` (+ component)
- `resources/views/link-pelanggan.blade.php` (ganti script ke validator class)
- `resources/views/partials/table-merchant.blade.php` (display multiple links)

---

**Created**: 2025-12-29
**Status**: ✅ Ready for Testing
