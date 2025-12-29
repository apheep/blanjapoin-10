# Debug Guide: Google Maps Locations Data Tidak Masuk ke Database

## Permasalahan
Data Google Maps locations tidak masuk ke database saat merchant di-update.

## Solusi yang Sudah Diimplementasikan

### 1. **Endpoint Sync Baru Ditambahkan**
   - **Route**: `POST /api/merchants/{id}/gmaps-locations/sync`
   - **Controller**: `MerchantController@syncGmapsLocations`
   - **Fungsi**: Menyimpan semua locations sekaligus dengan single request
   - **File**: `routes/web.php` (line 337)

### 2. **JavaScript Function Update**
   - Function `saveAllGmapsLocations()` diperbaiki menggunakan endpoint sync baru
   - Endpoint lama yang menggunakan loop delete + loop add sudah diganti
   - **File**: `resources/views/partials/gmaps-locations-manager.blade.php`

### 3. **Form Submit Handler**
   - Ditambahkan `async/await` untuk menunggu gmaps save selesai
   - Function `loadGmapsLocations()` dipanggil saat modal dibuka
   - `data-merchant-id` attribute ditambahkan ke modal
   - **File**: `resources/views/partials/edit-modal-merchant.blade.php` (line ~1162)

### 4. **Modal Initialization**
   - Saat modal dibuka, `loadGmapsLocations(id)` dipanggil otomatis
   - Saat form disubmit, `saveAllGmapsLocations()` dipanggil dengan await
   - **File**: `resources/views/partials/edit-modal-merchant.blade.php` (line ~584-800)

## Cara Testing

### Step 1: Buka Developer Console
```javascript
// Di browser console, pastikan tidak ada error
// Cek log yang muncul saat modal dibuka:
// ✅ Google Maps locations loaded untuk merchant: {id}
```

### Step 2: Tambah/Edit Google Maps Location
1. Buka Edit Merchant modal
2. Scroll ke section "Lokasi"
3. Klik "Tambah Lokasi"
4. Isi link Google Maps: `https://www.google.com/maps?q=-6.123,106.456`
5. Isi Radius (opsional): `300`
6. Klik tombol "Simpan" (green button)
7. Di console harus muncul: `✅ Lokasi tersimpan`

### Step 3: Submit Merchant Form
1. Scroll ke atas dan isi data merchant
2. Klik tombol "Update" (orange button di footer)
3. Di console harus muncul logs:
   ```
   💾 Menyimpan Google Maps locations...
   ✅ Google Maps locations berhasil disimpan
   ```
4. Jika ada error, akan muncul:
   ```
   ❌ Gagal menyimpan Google Maps locations
   ```

### Step 4: Verifikasi Database
```bash
# SSH ke server
php artisan tinker

# Cek merchant dan gmaps locations
$merchant = \App\Models\Merchant::find({id});
$merchant->link_gmaps;
```

Harus tampil array dengan struktur:
```php
[
    [
        'link' => 'https://www.google.com/maps?q=-6.123,106.456',
        'radius' => 300
    ],
    ...
]
```

## Struktur Data di Database

### Column: `link_gmaps` (JSON)
```json
[
    {
        "link": "https://www.google.com/maps?q=-6.123,106.456",
        "radius": 300
    },
    {
        "link": "https://www.google.com/maps?q=-6.456,106.123",
        "radius": 500
    }
]
```

## API Endpoints yang Tersedia

### GET - Load Locations
```bash
GET /api/merchants/{id}/gmaps-locations
```
Response:
```json
{
    "success": true,
    "data": [
        {
            "link": "...",
            "radius": 300
        }
    ]
}
```

### POST - Add Single Location (lama, tidak direkomendasikan)
```bash
POST /api/merchants/{id}/gmaps-locations
```

### POST - Sync All Locations (baru, yang digunakan)
```bash
POST /api/merchants/{id}/gmaps-locations/sync
```
Body:
```json
{
    "locations": [
        {
            "link": "https://...",
            "radius": 300
        }
    ]
}
```

### PUT - Update Single Location (lama, tidak direkomendasikan)
```bash
PUT /api/merchants/{id}/gmaps-locations/{index}
```

### DELETE - Remove Single Location (lama, tidak direkomendasikan)
```bash
DELETE /api/merchants/{id}/gmaps-locations/{index}
```

## File yang Dimodifikasi

1. **resources/views/partials/gmaps-locations-manager.blade.php**
   - Perbaikan function `renderGmapsLocations()` menggunakan template cloning
   - Update function `saveAllGmapsLocations()` menggunakan sync endpoint

2. **app/Http/Controllers/MerchantController.php**
   - Tambahan method `syncGmapsLocations()` (line ~3050)

3. **routes/web.php**
   - Tambahan route sync endpoint (line 337)

4. **resources/views/partials/edit-modal-merchant.blade.php**
   - Update form submit handler dengan async/await
   - Tambahan `data-merchant-id` attribute
   - Call `loadGmapsLocations()` saat modal dibuka

## Debugging Tips

### Jika data tidak tersimpan:
1. Cek browser console untuk error messages
2. Buka Network tab dan lihat response dari sync endpoint
3. Pastikan CSRF token valid
4. Cek server logs: `storage/logs/laravel.log`

### Jika form tidak bisa submit:
1. Cek console untuk validation errors
2. Pastikan minimal satu lokasi sudah ditambahkan dan disimpan
3. Cek `gmapsLocationsModified` flag di console

### Test Manual di Console:
```javascript
// Load locations
loadGmapsLocations(1); // Ganti 1 dengan merchant ID

// Lihat data
console.log(gmapsLocationsData);

// Tambah lokasi
addNewGmapsLocation();

// Simpan semua
saveAllGmapsLocations(1); // Ganti 1 dengan merchant ID
```

## Catatan Penting

- Component ini sudah direfactor untuk memisahkan HTML dari JavaScript
- Menggunakan template element untuk rendering yang lebih clean
- Data dimulai kosong dan di-load saat modal dibuka via API
- Modified flag ditrack untuk optimize save operations
- Toast notification menginformasikan user tentang status operasi
