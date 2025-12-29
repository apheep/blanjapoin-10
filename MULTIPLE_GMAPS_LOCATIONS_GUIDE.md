# Multiple Google Maps Locations dengan Radius Validation

## Deskripsi Fitur

Fitur ini memungkinkan merchant untuk menambahkan **multiple (lebih dari satu) lokasi Google Maps** dengan **radius validasi masing-masing**. Ketika customer melakukan redeem, sistem akan:

1. ✅ Mengecek lokasi customer menggunakan GPS
2. ✅ Membandingkan lokasi dengan **semua lokasi merchant yang terdaftar**
3. ✅ Jika customer berada dalam radius **salah satu lokasi**, redeem **diizinkan** ✔️
4. ❌ Jika customer di luar radius **semua lokasi**, redeem **ditolak** ❌

## Arsitektur Implementasi

### 1. Database Schema

#### Migration: `2025_12_29_000001_modify_link_gmap_to_support_multiple_locations.php`

- **Kolom baru**: `link_gmaps` (JSON)
- **Format data**:
  ```json
  [
    {
      "link": "https://www.google.com/maps?q=-6.123,106.456",
      "radius": 500
    },
    {
      "link": "https://www.google.com/maps?q=-6.234,106.567",
      "radius": 1000
    }
  ]
  ```

- **Backward compatibility**: 
  - Field `link_gmap` (lama) tetap ada
  - Data lama akan dimigrasikan ke format baru secara otomatis
  - Sistem mendukung kedua format (untuk transisi bertahap)

### 2. Model Layer

#### Class: `App\Models\Merchant`

**Properties:**
```php
protected $fillable = [..., 'link_gmaps'];
protected $casts = ['link_gmaps' => 'array'];
```

**Methods:**

| Method | Deskripsi |
|--------|-----------|
| `getGmapsLocations()` | Get semua lokasi dengan radius (mendukung format lama & baru) |
| `addGmapsLocation($link, $radius)` | Tambah lokasi baru |
| `updateGmapsLocation($index, $link, $radius)` | Update lokasi tertentu |
| `removeGmapsLocation($index)` | Hapus lokasi tertentu |
| `isUserWithinAnyRadius($lat, $lng)` | Check apakah user dalam radius **salah satu** lokasi |
| `extractCoordinatesFromGmapsLink($link)` | Extract lat/lng dari Google Maps URL |
| `calculateDistance($lat1, $lng1, $lat2, $lng2)` | Hitung distance (Haversine formula) |

### 3. Controller Layer

#### Class: `App\Http\Controllers\MerchantController`

**New Endpoints:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/merchants/{id}/gmaps-locations` | Get semua lokasi |
| POST | `/api/merchants/{id}/gmaps-locations` | Add lokasi baru |
| PUT | `/api/merchants/{id}/gmaps-locations/{index}` | Update lokasi |
| DELETE | `/api/merchants/{id}/gmaps-locations/{index}` | Hapus lokasi |
| POST | `/api/merchants/{id}/check-radius` | Check user location (mobile endpoint) |

### 4. Frontend Layer

#### JavaScript Class: `MerchantRadiusValidator`

**File**: `public/js/merchant-radius-validator.js`

**Key Methods:**
```javascript
// Initialize validator
initRadiusValidator(merchantData);

// Check if user within any location's radius
merchantValidator.checkIfWithinAnyRadius(lat, lng);

// Get formatted distance
merchantValidator.getFormattedDistance(distance);

// Get all locations sorted by distance
merchantValidator.getLocationsSortedByDistance();

// Get closest location within radius
merchantValidator.getClosestLocationWithinRadius();
```

**Features:**
- ✅ Support multiple locations
- ✅ Automatic coordinate extraction dari berbagai format Google Maps URL
- ✅ Haversine formula untuk akurat distance calculation
- ✅ Error handling & user-friendly messages

#### Blade Components

**File**: `resources/views/partials/gmaps-locations-manager.blade.php`

- UI untuk manage multiple locations di admin form
- Add/Edit/Delete locations dengan real-time UI update
- Form integration untuk save ke database

### 5. View Integration

#### Page: `link-pelanggan.blade.php` (Customer-facing)

- Load script: `merchant-radius-validator.js`
- Initialize validator saat page load
- Check user GPS location
- Enable/disable redeem button berdasarkan radius validation
- Show user-friendly error messages

#### Table: `table-merchant.blade.php` (Admin)

- Display semua locations sebagai "Lokasi 1", "Lokasi 2", dst
- Tooltip menampilkan radius masing-masing
- Support responsive design

#### Form: `edit-modal-merchant.blade.php`

- Include `gmaps-locations-manager` component
- Manage locations dengan UI yang intuitif

## Flow Diagram

```
Customer membuka link pelanggan
    ↓
Browser meminta GPS permission
    ↓
Jika allowed:
    ├─ Get user coordinates (lat, lng)
    ├─ Extract koordinat dari ALL merchant locations
    ├─ Hitung distance ke masing-masing lokasi
    ├─ CEK: Apakah ada lokasi yang user dalam radius-nya?
    │   ├─ YES → isWithinRadius = true → ENABLE redeem button ✅
    │   └─ NO → isWithinRadius = false → DISABLE redeem button ❌
    ↓
Jika denied:
    ├─ isWithinRadius = false
    ├─ DISABLE redeem button ❌
    └─ Show error modal
```

## API Response Format

### GET `/api/merchants/{id}/gmaps-locations`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "link": "https://www.google.com/maps?q=-6.123,106.456",
      "radius": 500
    },
    {
      "link": "https://www.google.com/maps?q=-6.234,106.567",
      "radius": 1000
    }
  ]
}
```

### POST `/api/merchants/{id}/check-radius`

**Request:**
```json
{
  "latitude": -6.123456,
  "longitude": 106.456789
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "isWithinRadius": true,
    "matchedLocation": {
      "link": "https://www.google.com/maps?q=-6.123,106.456",
      "radius": 500,
      "index": 0,
      "distance": 234.5,
      "withinRadius": true
    },
    "allLocations": [
      {
        "index": 0,
        "link": "https://www.google.com/maps?q=-6.123,106.456",
        "radius": 500,
        "distance": 234.5,
        "withinRadius": true
      },
      {
        "index": 1,
        "link": "https://www.google.com/maps?q=-6.234,106.567",
        "radius": 1000,
        "distance": 1234.5,
        "withinRadius": false
      }
    ]
  }
}
```

## Admin UI Usage

### Menambah Lokasi Baru

1. Buka edit merchant modal
2. Scroll ke section "Kelola Lokasi Google Maps"
3. Klik "Tambah Lokasi"
4. Input link Google Maps atau pilih dari peta
5. Input radius validasi (opsional)
6. Klik "Simpan" button
7. Location akan langsung ditambahkan ke list

### Mengedit Lokasi

1. Hover atau klik "Edit" di location card
2. Ubah link dan/atau radius
3. Klik "Simpan"

### Menghapus Lokasi

1. Klik "Hapus" di location card
2. Confirm dialog
3. Location akan dihapus

## Migration Steps

Untuk merchant yang **sudah punya data lama** (single `link_gmap`):

1. Run migration:
   ```bash
   php artisan migrate
   ```

2. Data lama akan otomatis dikonversi:
   ```
   link_gmap: "https://..."
   radius: 500
   
   ↓ (becomes)
   
   link_gmaps: [
     {
       "link": "https://...",
       "radius": 500
     }
   ]
   ```

## Testing

### Local Testing dengan GPS

**Di Desktop Browser (Chrome DevTools):**
1. Open DevTools → Sensors tab
2. Override location dengan koordinat tertentu
3. Test radius validation

**Di Mobile:**
1. Ensure GPS enabled
2. Open link pelanggan page
3. Grant location permission
4. Button state berubah berdasarkan radius

### Manual Testing Endpoints

```bash
# Get locations
curl http://localhost/api/merchants/1/gmaps-locations

# Add location
curl -X POST http://localhost/api/merchants/1/gmaps-locations \
  -H "Content-Type: application/json" \
  -d '{"link_gmap":"https://maps.app.goo.gl/...", "radius":500}'

# Check radius
curl -X POST http://localhost/api/merchants/1/check-radius \
  -H "Content-Type: application/json" \
  -d '{"latitude":-6.123,"longitude":106.456}'
```

## Troubleshooting

### Issue: Koordinat tidak ter-extract

**Causes:**
- Google Maps URL format tidak standard
- URL sudah shortened (goo.gl)

**Solution:**
- Converter akan follow redirects otomatis
- Jika tetap gagal, extract coordinates manually dari Google Maps

### Issue: Distance calculation tidak akurat

**Causes:**
- GPS accuracy variation
- User location cache (30 detik)

**Solution:**
- Nilai maxAge: 30000ms dapat disesuaikan di code
- Tolerance untuk radius validation: ±5m

### Issue: Permission denied untuk GPS

**Causes:**
- User reject permission
- HTTPS required (production)
- Privacy settings browser

**Solution:**
- Tampilkan helpful error message
- Guide user untuk enable GPS
- Provide link ke lokasi sebagai fallback

## Performance Considerations

### Optimization

- **Caching**: User location cache 30 detik (configurable)
- **Lazy loading**: Validator hanya run saat needed
- **Minimal API calls**: Check radius done client-side (GPS local)

### Database

- `link_gmaps` stored as JSON (indexed untuk quick access)
- No additional tables needed
- Backward compatible dengan existing data

## Security

- ✅ Authorization check pada controller endpoints
- ✅ Coordinate extraction tidak allow arbitrary code
- ✅ Distance validation server-side available untuk API users
- ✅ No sensitive data exposed (GPS points stored client-side)

## Future Enhancements

- [ ] Multiple radius per location (dalam/luar peak hours)
- [ ] Geofencing dengan map visualization
- [ ] Location history tracking
- [ ] SMS/notification saat user near merchant
- [ ] Analytics dashboard untuk radius violations

---

**Created**: 2025-12-29
**Version**: 1.0
**Status**: Production Ready
