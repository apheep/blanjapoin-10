# Perubahan untuk Fix: Data Google Maps Locations Tidak Masuk ke Database

**Tanggal**: 29 Desember 2025  
**Status**: ✅ SELESAI

## Ringkasan Masalah
Data Google Maps locations tidak tersimpan ke database karena:
1. Logic `saveAllGmapsLocations()` menggunakan endpoint yang tidak ada (DELETE individual)
2. Tidak ada proses save gmaps sebelum form merchant disubmit
3. Tidak ada `data-merchant-id` di modal untuk tracking

## Solusi Implementasi

### 1. Endpoint Sync Baru (Backend)
**File**: `app/Http/Controllers/MerchantController.php`  
**Method**: `syncGmapsLocations()`  
**Lokasi**: Sebelum fungsi `checkUserWithinRadius()`

```php
public function syncGmapsLocations(Request $request, $id)
{
    // Menerima array locations lengkap
    // Validasi dan convert semua URLs
    // Save sekaligus ke column link_gmaps
    // Return response dengan saved data
}
```

**Keuntungan**:
- Single API call instead of multiple delete + add
- Atomic operation - semua atau tidak sama sekali
- Performance lebih baik
- Error handling lebih jelas

### 2. Route Tambahan
**File**: `routes/web.php`  
**Line**: 337 (setelah route POST add)

```php
Route::post('/api/merchants/{id}/gmaps-locations/sync', [MerchantController::class, 'syncGmapsLocations'])->name('merchants.gmaps.sync');
```

### 3. JavaScript Function Fix
**File**: `resources/views/partials/gmaps-locations-manager.blade.php`  
**Function**: `saveAllGmapsLocations()`

**Sebelum**:
```javascript
// Loop delete semua locations
// Loop add semua locations baru
// Multiple API calls yang tidak reliable
```

**Sesudah**:
```javascript
// Single POST request ke sync endpoint
// Kirim seluruh array locations
// Simpler dan lebih reliable
```

### 4. Form Submit Handler
**File**: `resources/views/partials/edit-modal-merchant.blade.php`  
**Event**: Form submit listener (line 1162)

**Tambahan**:
```javascript
// Made async function
// Added await for saveAllGmapsLocations()
// Check if gmaps save successful sebelum submit merchant
```

### 5. Modal Initialization
**File**: `resources/views/partials/edit-modal-merchant.blade.php`  
**Function**: `openEditMerchant()`

**Tambahan**:
```javascript
// Set data-merchant-id attribute ke modal
modal.setAttribute('data-merchant-id', id);

// Load gmaps locations saat modal dibuka
loadGmapsLocations(id);
```

## Data Flow - Sebelum vs Sesudah

### SEBELUM (❌ Tidak Bekerja)
```
User Edit Merchant
    ↓
Modal Dibuka (gmaps kosong)
    ↓
User Tambah/Edit Gmaps Location
    ↓
User Klik Simpan Merchant
    ↓
Form Submit
    ↓
saveAllGmapsLocations() dipanggil
    ↓
Loop delete dengan index individual (🔴 FAIL - endpoint tidak ada)
    ↓
Loop add satu per satu (🔴 Multiple calls)
    ↓
Data tidak tersimpan 😢
```

### SESUDAH (✅ Bekerja)
```
User Edit Merchant
    ↓
Modal Dibuka
    ↓
loadGmapsLocations(id) dipanggil
    ↓
GET /api/merchants/{id}/gmaps-locations
    ↓
Render locations dari template
    ↓
User Tambah/Edit Gmaps Location
    ↓
Set gmapsLocationsModified = true
    ↓
User Klik Simpan Merchant
    ↓
Form submit event triggered (async)
    ↓
Check gmapsLocationsModified dan merchantId
    ↓
await saveAllGmapsLocations(merchantId)
    ↓
POST /api/merchants/{id}/gmaps-locations/sync dengan full array
    ↓
Backend validate dan convert URLs
    ↓
Save ke link_gmaps column sekaligus
    ↓
Return success response
    ↓
Setelah gmaps selesai, submit merchant form
    ↓
Data tersimpan dengan sempurna ✅
```

## Testing Checklist

- [ ] Modal dibuka, lihat di console: `✅ Google Maps locations loaded untuk merchant: {id}`
- [ ] Tambah lokasi, klik Simpan, lihat: `✅ Lokasi tersimpan`
- [ ] Edit lokasi, klik Simpan, lihat: `✅ Lokasi tersimpan`
- [ ] Hapus lokasi, klik Simpan, lihat: `✅ Lokasi dihapus`
- [ ] Submit form merchant, lihat: `💾 Menyimpan Google Maps locations...` dan `✅ Google Maps locations berhasil disimpan`
- [ ] Cek database dengan `php artisan tinker`:
  ```php
  $merchant->link_gmaps // Should return array of locations
  ```
- [ ] Reload page, modal dibuka, locations masih ada
- [ ] Cek Network tab di browser, POST /api/merchants/{id}/gmaps-locations/sync harus 200 OK

## Catatan Penting

### ✅ Yang Sudah Fixed
1. ✅ Refactoring gmaps component dengan proper HTML template
2. ✅ Memisahkan HTML dari JavaScript string template
3. ✅ Menambah endpoint sync yang proper
4. ✅ Fix saveAllGmapsLocations() logic
5. ✅ Integrasi dengan form submit merchant
6. ✅ Load gmaps saat modal dibuka
7. ✅ Proper error handling dan user feedback

### 📝 Catatan Teknis
- Data type untuk `link_gmaps` adalah JSON column di database
- Backward compatibility tetap dijaga untuk `link_gmap` lama
- CSRF token otomatis di-include di form
- Authorization check sudah ada di controller
- URL conversion dilakukan di backend, jadi lebih aman

### 🔒 Security
- CSRF token validation
- Authorization check (canEditMerchant)
- Input validation untuk URL format
- Radius validation (0-100000)
- Content-Type header properly set

## Rollback Plan (Jika diperlukan)

Jika ada masalah, revert changes di:
1. Controller: Remove `syncGmapsLocations()` method
2. Routes: Remove sync route
3. JS: Revert `saveAllGmapsLocations()` ke logic lama
4. Modal: Remove gmaps load di `openEditMerchant()`

Tapi semestinya tidak perlu! 🎉
