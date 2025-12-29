# 🚀 QUICK START - Multiple Google Maps Locations

## Cara Pakai (Admin)

### 1️⃣ Add Location Baru
```
Merchant → Edit → Scroll ke "Kelola Lokasi Google Maps"
  ↓
Klik "Tambah Lokasi"
  ↓
Input: Google Maps Link + Radius (opsional)
  ↓
Klik "Simpan"
  ✅ Lokasi tertambah
```

### 2️⃣ Edit Location
```
Card lokasi → Klik "Edit"
  ↓
Ubah link dan/atau radius
  ↓
Klik "Simpan"
  ✅ Lokasi terupdate
```

### 3️⃣ Delete Location
```
Card lokasi → Klik "Hapus"
  ↓
Confirm
  ✅ Lokasi terhapus
```

---

## Cara Pakai (Customer)

### Redeem Flow
```
1. Buka halaman merchant voucher
2. Klik "Redeem" button
3. Browser: Allow GPS permission
4. System: Check jarak ke semua lokasi
5. Hasil:
   ✅ Dalam radius salah satu lokasi → REDEEM ALLOWED
   ❌ Luar radius semua lokasi → REDEEM BLOCKED
```

---

## Database Migration

```bash
# 1. Backup database (RECOMMENDED!)
mysqldump -u root -p blanjapoin_db > backup_2025.sql

# 2. Run migration
php artisan migrate

# 3. Verify
php artisan migrate:status
```

---

## API Reference

### Get All Locations
```
GET /api/merchants/1/gmaps-locations
Response: [{"link": "...", "radius": 500}, ...]
```

### Add Location
```
POST /api/merchants/1/gmaps-locations
Body: {"link_gmap": "...", "radius": 500}
Response: {"success": true, "data": [...]}
```

### Update Location
```
PUT /api/merchants/1/gmaps-locations/0
Body: {"link_gmap": "...", "radius": 600}
Response: {"success": true, "data": [...]}
```

### Delete Location
```
DELETE /api/merchants/1/gmaps-locations/0
Response: {"success": true, "message": "..."}
```

### Check Radius (Mobile)
```
POST /api/merchants/1/check-radius
Body: {"latitude": -6.123, "longitude": 106.456}
Response: {
  "success": true,
  "data": {
    "isWithinRadius": true,
    "matchedLocation": {...}
  }
}
```

---

## Files Changed

### ✨ New Files
- `database/migrations/2025_12_29_000001_modify_link_gmap_to_support_multiple_locations.php`
- `public/js/merchant-radius-validator.js`
- `resources/views/partials/gmaps-locations-manager.blade.php`
- `MULTIPLE_GMAPS_LOCATIONS_GUIDE.md`
- `MULTIPLE_GMAPS_IMPLEMENTATION_SUMMARY.md`
- `TESTING_DEPLOYMENT_CHECKLIST.md`
- `IMPLEMENTATION_COMPLETE.md`

### 📝 Modified Files
- `app/Models/Merchant.php` (+ 7 methods)
- `app/Http/Controllers/MerchantController.php` (+ 5 endpoints)
- `routes/web.php` (+ 5 routes)
- `resources/views/link-pelanggan.blade.php`
- `resources/views/partials/edit-modal-merchant.blade.php`
- `resources/views/partials/table-merchant.blade.php`

---

## Test Checklist

### 1. Database
- [ ] Migration ran successfully
- [ ] `merchants.link_gmaps` column exists
- [ ] Data migrated to new format

### 2. Admin UI
- [ ] Can add location
- [ ] Can edit location
- [ ] Can delete location
- [ ] Locations display in table with badges

### 3. Customer
- [ ] GPS permission request works
- [ ] Button enables/disables based on distance
- [ ] Redeem works when within radius
- [ ] Error message shows when outside radius

### 4. API
- [ ] GET /api/merchants/{id}/gmaps-locations returns data
- [ ] POST adds location successfully
- [ ] PUT updates location successfully
- [ ] DELETE removes location successfully
- [ ] POST check-radius calculates correctly

---

## Backward Compatibility

✅ Old merchants dengan single `link_gmap` tetap berfungsi
✅ System auto-convert ke format baru
✅ No manual data migration needed

---

## Troubleshooting

### Q: Button masih disabled padahal user dalam radius?
A: 
1. Hard refresh browser (Ctrl+Shift+R)
2. Check browser console for errors
3. Verify GPS coordinates accurate

### Q: Koordinat tidak ter-extract?
A:
1. Pastikan link dari Google Maps official
2. Jika short URL (goo.gl), auto-convert akan follow redirects
3. Manual extract jika tetap gagal

### Q: Migration gagal?
A:
1. Backup database terlebih dahulu
2. Check laravel.log untuk error detail
3. Rollback jika ada issue: `php artisan migrate:rollback`

---

## Performance Notes

- GPS check: Client-side (tidak load server)
- Distance calc: Haversine formula (akurat hingga ~5m)
- Cache: 30 detik untuk GPS hasil
- API response: Target < 100ms

---

## Important Links

- **Full Guide**: MULTIPLE_GMAPS_LOCATIONS_GUIDE.md
- **Implementation Details**: MULTIPLE_GMAPS_IMPLEMENTATION_SUMMARY.md
- **Testing Guide**: TESTING_DEPLOYMENT_CHECKLIST.md
- **Status**: IMPLEMENTATION_COMPLETE.md

---

## Commands

```bash
# Show all routes
php artisan route:list | grep gmaps

# Test in tinker
php artisan tinker
> $m = Merchant::find(1)
> $m->getGmapsLocations()
> $m->addGmapsLocation('https://...', 500)
> $m->isUserWithinAnyRadius(-6.123, 106.456)

# Clear cache
php artisan cache:clear && php artisan view:clear

# Deploy
php artisan migrate && npm run build
```

---

**Status**: ✅ Ready
**Date**: 2025-12-29
**Version**: 1.0
