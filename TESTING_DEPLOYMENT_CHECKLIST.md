# ✅ Testing & Deployment Checklist

## Pre-Deployment Testing

### 1. Database Migration
- [ ] Backup database (recommended)
- [ ] Run: `php artisan migrate`
- [ ] Verify kolom `link_gmaps` ada di tabel `merchants`
- [ ] Verify data lama ter-migrate ke format baru
  ```bash
  # Check migration result
  php artisan tinker
  > Merchant::find(1)->link_gmaps
  > Merchant::find(1)->getGmapsLocations()
  ```

### 2. Model Testing
- [ ] Test method `getGmapsLocations()` mengembalikan array
- [ ] Test method `addGmapsLocation()` menambah lokasi
- [ ] Test method `updateGmapsLocation()` mengubah lokasi
- [ ] Test method `removeGmapsLocation()` menghapus lokasi
- [ ] Test method `isUserWithinAnyRadius()` dengan berbagai latitude/longitude

```php
# Tinker testing
$merchant = Merchant::find(1);

# Add location
$merchant->addGmapsLocation('https://www.google.com/maps?q=-6.123,106.456', 500);
$merchant->getGmapsLocations(); // verify

# Update location
$merchant->updateGmapsLocation(0, 'https://www.google.com/maps?q=-6.234,106.567', 1000);
$merchant->getGmapsLocations(); // verify

# Check radius
$result = $merchant->isUserWithinAnyRadius(-6.125, 106.458);
// Should return: ['isWithinRadius' => bool, 'matchedLocation' => array]
```

### 3. API Endpoints Testing

#### Using Postman or CURL:

```bash
# 1. Get all locations
curl -H "Authorization: Bearer TOKEN" \
  http://localhost/api/merchants/1/gmaps-locations

# Expected response: 200 OK with locations array

# 2. Add location
curl -X POST http://localhost/api/merchants/1/gmaps-locations \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "link_gmap": "https://www.google.com/maps?q=-6.123,106.456",
    "radius": 500
  }'

# Expected response: 200 OK with updated locations

# 3. Update location
curl -X PUT http://localhost/api/merchants/1/gmaps-locations/0 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "link_gmap": "https://www.google.com/maps?q=-6.234,106.567",
    "radius": 1000
  }'

# 4. Delete location
curl -X DELETE http://localhost/api/merchants/1/gmaps-locations/0 \
  -H "Authorization: Bearer TOKEN"

# 5. Check radius (mobile endpoint - no auth needed for public access)
curl -X POST http://localhost/api/merchants/1/check-radius \
  -H "Content-Type: application/json" \
  -d '{
    "latitude": -6.123456,
    "longitude": 106.456789
  }'
```

### 4. UI Testing - Admin

**Edit Merchant Modal:**
- [ ] Component `gmaps-locations-manager` muncul
- [ ] Button "Tambah Lokasi" berfungsi
- [ ] Form input untuk link & radius muncul
- [ ] Button "Edit" dan "Hapus" berfungsi
- [ ] Menambah lokasi via form submit ke database
- [ ] Locations list update otomatis

**Merchant Table:**
- [ ] Desktop view: Multiple links ditampilkan sebagai "1", "2", "3" badges
- [ ] Mobile view: Multiple links ditampilkan dengan "Lok 1", "Lok 2" format
- [ ] Tooltip menampilkan radius masing-masing lokasi
- [ ] Links clickable menuju Google Maps

### 5. UI Testing - Customer

**Link Pelanggan Page:**
- [ ] Page load tanpa error
- [ ] Merchant data ter-load dengan benar
- [ ] JavaScript `merchant-radius-validator.js` ter-load
- [ ] Browser meminta GPS permission
- [ ] Setelah permission granted:
  - [ ] Coordinates ter-extract dari semua locations
  - [ ] Distance calculation akurat
  - [ ] Button state berubah sesuai radius
  - [ ] Tooltip message muncul dengan benar
- [ ] Jika permission denied:
  - [ ] Button disabled dengan message
  - [ ] Error modal menjelaskan situasi

**Redeem Flow:**
- [ ] User dalam radius: Redeem button ENABLED ✅
- [ ] User click redeem: Link terbuka di tab baru
- [ ] User di luar radius: Redeem button DISABLED ❌
- [ ] User di luar radius click disabled button: Error modal muncul
- [ ] Error modal menampilkan:
  - [ ] Jarak user ke lokasi terdekat
  - [ ] Radius maksimal
  - [ ] Link ke Google Maps

### 6. Backward Compatibility Testing

**Single Location (Data Lama):**
- [ ] Merchant dengan hanya `link_gmap` (tanpa `link_gmaps`)
- [ ] Method `getGmapsLocations()` tetap mengembalikan array
- [ ] Radius validation tetap berfungsi
- [ ] Table view menampilkan "1" badge
- [ ] Customer page radius check berfungsi

**No Location:**
- [ ] Merchant tanpa `link_gmap` maupun `link_gmaps`
- [ ] Admin UI: Empty state message
- [ ] Customer page: Redeem button ENABLED (no validation)

### 7. Mobile Testing

**On Real Device:**
- [ ] GPS permission dialog muncul
- [ ] Grant permission: Location accuracy check ✅
- [ ] Deny permission: Error handling ✅
- [ ] Redeem button state responsive
- [ ] Error modal readable di mobile screen
- [ ] Google Maps link opens di mobile browser

### 8. Performance Testing

- [ ] Page load time < 3 seconds
- [ ] GPS check tidak freeze UI
- [ ] Multiple locations (5+) calculation smooth
- [ ] Database query optimized (use indexing)

### 9. Error Handling Testing

**Invalid Inputs:**
- [ ] Add location dengan invalid URL
- [ ] Add location dengan negative radius
- [ ] Update non-existent location index
- [ ] Delete non-existent location index

**Network Issues:**
- [ ] Timeout handling untuk GPS
- [ ] Timeout handling untuk API calls
- [ ] Offline mode (graceful degradation)

### 10. Browser/Device Compatibility

- [ ] Chrome (Desktop & Mobile)
- [ ] Firefox (Desktop & Mobile)
- [ ] Safari (Desktop & Mobile)
- [ ] Edge (Desktop)

---

## Deployment Steps

### 1. Pre-Production
```bash
# Backup database
mysqldump -u root -p blanjapoin_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Pull latest code
git pull origin main

# Install/update dependencies
composer install

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Compile JS (if using build)
npm run build
```

### 2. Verification
```bash
# Check migration status
php artisan migrate:status

# Verify Merchant model loaded correctly
php artisan tinker
> Merchant::first()->getGmapsLocations()

# Test routes
php artisan route:list | grep gmaps
```

### 3. Post-Deployment
- [ ] Verify all routes accessible: `/api/merchants/{id}/gmaps-locations`
- [ ] Test admin UI dengan existing merchant
- [ ] Test customer page dengan existing merchant
- [ ] Monitor logs untuk errors: `tail -f storage/logs/laravel.log`

### 4. Rollback (jika diperlukan)
```bash
# Undo migration
php artisan migrate:rollback

# Restore database dari backup
mysql -u root -p blanjapoin_db < backup_YYYYMMDD_HHMMSS.sql
```

---

## Known Issues & Solutions

### Issue 1: Coordinate tidak ter-extract dari URL
**Cause**: URL format tidak standard
**Solution**: 
- Pastikan link dari Google Maps official
- Try to convert URL di `convertGmapUrl()` method

### Issue 2: Distance calculation jauh dari actual
**Cause**: GPS inaccuracy atau calculation error
**Solution**:
- GPS accuracy biasanya ±5-10 meter di urban areas
- Set radius dengan buffer: 100m lebih besar dari actual

### Issue 3: Button tetap disabled meski user dalam radius
**Cause**: Browser cache atau geolocation stale
**Solution**:
- Hard refresh: Ctrl+Shift+R (Windows) atau Cmd+Shift+R (Mac)
- Check browser console untuk error messages
- Verify coordinates di browser DevTools

### Issue 4: Permission dialog tidak muncul
**Cause**: HTTPS required (production)
**Solution**:
- Geolocation API hanya work di HTTPS
- Localhost exception for HTTP (development)
- Enable HTTPS di production

---

## Monitoring

### Key Metrics to Monitor

1. **GPS Permission Acceptance Rate**
   - Target: > 70% dari users grant permission

2. **Redeem Success Rate**
   - Track users dalam vs di luar radius
   - Alert if outside-radius ratio terlalu tinggi

3. **API Response Time**
   - `/api/merchants/{id}/check-radius` target: < 100ms
   - `/api/merchants/{id}/gmaps-locations` target: < 50ms

4. **Error Rate**
   - Monitor coordinate extraction failures
   - Monitor API failures

---

## Success Criteria

- ✅ All migration tests pass
- ✅ All API endpoints return correct responses
- ✅ Admin UI functional dan intuitive
- ✅ Customer radius validation working correctly
- ✅ Backward compatibility maintained
- ✅ Mobile GPS permission flow smooth
- ✅ Error handling graceful
- ✅ Performance acceptable

---

**Testing Date**: [Your Date]
**Tested By**: [Your Name]
**Status**: Ready for Production ✅
