# Quick Test Google Maps Locations Fix

## 1️⃣ Testing via UI (Recommended)

### Setup
1. Buka aplikasi di browser
2. Buka Developer Tools (F12)
3. Go to Console tab

### Test Steps
```
1. Click "Edit Merchant" button di mana saja
   ✓ Cek console: "✅ Google Maps locations loaded untuk merchant: {id}"

2. Scroll ke section "Lokasi"
   ✓ Lihat "Kelola Lokasi Google Maps"

3. Click "Tambah Lokasi" button
   ✓ Baru ada satu card location

4. Fill form:
   - Google Maps Link: https://www.google.com/maps?q=-6.2088,106.8456
   - Radius: 300

5. Click green "Simpan" button
   ✓ Toast muncul: "✅ Lokasi tersimpan"

6. Buka Merchant data di table untuk test edit existing
   ✓ Modal dibuka dengan location sudah terbaca

7. Fill form atas (wajib minimal nama merchant, kategori)

8. Click orange "Update" button di footer
   ✓ Console log: "💾 Menyimpan Google Maps locations..."
   ✓ Toast: "✅ Semua lokasi berhasil disimpan ke database"
   ✓ Form submit continues...

9. Refresh halaman / reload merchant
   ✓ Locations masih ada saat edit dibuka lagi
```

## 2️⃣ Testing via Console Commands

### Load Locations untuk Merchant ID 1
```javascript
loadGmapsLocations(1);
```
**Expected Output**:
```javascript
// Di console log akan muncul
gmapsLocationsData = [
    {
        link: "https://...",
        radius: 300
    }
]
```

### Add New Location
```javascript
addNewGmapsLocation();
```
**Expected**: Toast "➕ Lokasi baru ditambahkan", baru ada empty card

### Check Modified Flag
```javascript
gmapsLocationsModified; // Should be true setelah edit
```

### Save Manually (untuk test endpoint)
```javascript
await saveAllGmapsLocations(1); // Ganti 1 dengan merchant ID
```
**Expected Response**:
```json
{
    "success": true,
    "message": "Semua lokasi berhasil disimpan",
    "data": [...]
}
```

## 3️⃣ Testing Network Request

### Buka Network tab di Developer Tools

### Submit Merchant dengan Google Maps Locations
1. Fill form merchant
2. Click "Update" button
3. Di Network tab, cari request:
   - **URL**: `/api/merchants/{id}/gmaps-locations/sync`
   - **Method**: `POST`
   - **Status**: `200`

### Check Request Body
**Payload**:
```json
{
    "locations": [
        {
            "link": "https://www.google.com/maps?q=-6.2088,106.8456",
            "radius": 300
        }
    ]
}
```

### Check Response Body
**Response**:
```json
{
    "success": true,
    "message": "Semua lokasi berhasil disimpan",
    "data": [
        {
            "link": "https://www.google.com/maps?q=-6.2088,106.8456",
            "radius": 300
        }
    ]
}
```

## 4️⃣ Testing Database

### SSH to Server
```bash
php artisan tinker
```

### Get Merchant Data
```php
$merchant = \App\Models\Merchant::find(1); // Ganti 1 dengan ID

// Lihat locations
$merchant->link_gmaps;
// Output: 
// [
//     [
//         "link" => "https://...",
//         "radius" => 300
//     ]
// ]

// Or use getter method
$merchant->getGmapsLocations();
// Output: Array of locations
```

### Verify Data Structure
```php
// Check type
gettype($merchant->link_gmaps); // Should be 'array'

// Count locations
count($merchant->getGmapsLocations()); // Should be 1, 2, etc

// Get first location
$merchant->getGmapsLocations()[0];
```

## 5️⃣ Error Scenarios (Expected & How to Fix)

### ❌ Error: "Gagal menyimpan lokasi Google Maps"
**Debug**:
```javascript
// Check browser console untuk detail error
// Network tab - cek response dari sync endpoint
// Check server log: tail storage/logs/laravel.log
```

**Possible Causes**:
- Invalid URL format (tidak dimulai dengan https://)
- CSRF token expired
- User tidak punya permission untuk edit merchant
- Database connection error

**Fix**:
- Refresh page untuk reset CSRF token
- Cek authorization di controller
- Cek database connection

### ❌ Form tidak submit setelah gmaps save
**Debug**:
```javascript
// Check console untuk gmaps save response
console.log(gmapsLocationsModified);
```

**Possible Causes**:
- gmaps save return false
- Error di form validation sebelum gmaps save

**Fix**:
- Check validation error messages
- Cek console untuk detail error
- Verifikasi required fields sudah diisi

### ⚠️ Locations hilang setelah refresh
**Debug**:
```php
// Di tinker, check merchant data
$merchant->link_gmaps; // Harus ada data
```

**Possible Causes**:
- Data tidak tersimpan ke database
- Salah merchant ID

**Fix**:
- Check database bahwa data memang tersimpan
- Confirm correct merchant ID digunakan

## 6️⃣ Performance Notes

| Operation | Time | API Calls |
|-----------|------|-----------|
| Load modal | ~100ms | 1 GET |
| Add location | ~50ms | 0 API (local) |
| Save location | ~200ms | 0 API (local) |
| Submit form | ~500-1000ms | 2 API (1 sync + 1 merchant update) |
| Total flow | ~1-2s | 2 API calls |

## 7️⃣ Success Indicators

✅ Semua test di atas passed  
✅ Console tidak ada error  
✅ Network requests semua 200 OK  
✅ Database punya data yang benar  
✅ UI feedback (toast) muncul dengan proper  
✅ Locations persist after refresh  

Kalau semua ini sudah terpenuhi, Fix sudah berhasil! 🎉
