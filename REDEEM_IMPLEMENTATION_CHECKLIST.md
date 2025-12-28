# ✅ IMPLEMENTATION CHECKLIST & SUMMARY

## 🎯 Tujuan Implementasi

Menambahkan merchant matching ke tabel `tokodigi_tselpoin_redeem` dengan:
- ✅ Kolom `merchant_id` (dari click yang di-match)
- ✅ Kolom `clicked_date` (tanggal klik yang di-match)
- ✅ Kolom `diff_click` (selisih waktu klik-redeem)
- ✅ SQL Functions untuk populate data
- ✅ SQL Triggers untuk auto-update real-time
- ✅ Artisan Command untuk batch populate
- ✅ Model + Analytics Controller
- ✅ Full dokumentasi

---

## 📁 FILE CHECKLIST

### ✅ Database (Migrations)
- [x] `database/migrations/2025_12_28_000000_add_merchant_columns_to_tselpoin_redeem.php`
  - Tambah 3 kolom + indexes
- [x] `database/migrations/2025_12_28_000001_create_populate_redeem_merchant_function.php`
  - Buat MySQL FUNCTION untuk populate
- [x] `database/migrations/2025_12_28_000002_create_update_redeem_on_click_trigger.php`
  - Buat 2 MySQL TRIGGERS untuk auto-update

### ✅ Console Commands
- [x] `app/Console/Commands/PopulateRedeemMerchantData.php`
  - Artisan command untuk batch populate dengan progress

### ✅ Models
- [x] `app/Models/TselepoinRedeem.php` (NEW)
  - Model untuk `tokodigi_tselpoin_redeem` table
  - Relationships, scopes, helper methods
  - Analytics methods

### ✅ Controllers
- [x] `app/Http/Controllers/RedeemAnalyticsController.php` (NEW)
  - Controller untuk analytics endpoints
  - Dashboard, merchant stats, keyword performance, etc

### ✅ Documentation
- [x] `REDEEM_MERCHANT_DATA_GUIDE.md`
  - Dokumentasi lengkap (implementation, usage, testing)
- [x] `REDEEM_MERCHANT_DATA_IMPLEMENTATION.md`
  - Summary implementasi & file descriptions
- [x] `REDEEM_MERCHANT_DATA_SQL.sql`
  - SQL queries ready-to-copy
- [x] `REDEEM_ANALYTICS_ROUTES_EXAMPLE.php`
  - Example routes & endpoint documentation

---

## 🚀 QUICK START (5 STEPS)

### Step 1: Run Migrations
```bash
cd d:\laragon\www\blanjapoin-10
php artisan migrate
```

**Expected Output:**
```
Migrating: 2025_12_28_000000_add_merchant_columns_to_tselpoin_redeem
Migrated:  2025_12_28_000000_add_merchant_columns_to_tselpoin_redeem (0.15s)
Migrating: 2025_12_28_000001_create_populate_redeem_merchant_function
Migrated:  2025_12_28_000001_create_populate_redeem_merchant_function (0.08s)
Migrating: 2025_12_28_000002_create_update_redeem_on_click_trigger
Migrated:  2025_12_28_000002_create_update_redeem_on_click_trigger (0.12s)
```

### Step 2: Populate Existing Data
```bash
php artisan redeem:populate-merchant-data
```

**Expected Output:**
```
🔄 Mulai populate data redemption dengan merchant matching...
📊 Batch size: 10000 records

[... batch processing ...]

✨ Selesai!
📈 Total: 45230 records updated dalam 5 batch

┌──────────────────────────────────┬──────────┐
│ Metric                           │ Value    │
├──────────────────────────────────┼──────────┤
│ Total Redemptions (BLANJAPOIN)   │ 50000    │
│ Redemptions dengan Merchant Match│ 45230    │
│ Match Percentage                 │ 90.46%   │
└──────────────────────────────────┴──────────┘
```

### Step 3: Verify Data
```bash
php artisan tinker

>>> DB::table('tokodigi_tselpoin_redeem')
    ->where('program', 'BLANJAPOIN')
    ->whereNotNull('merchant_id')
    ->limit(5)
    ->get(['id', 'msisdn', 'coupon', 'merchant_id', 'diff_click']);

# Should show data dengan merchant_id filled
```

### Step 4: Setup Analytics Routes (Optional)
Tambah routes ke `routes/api.php` atau `routes/web.php`:
```php
include base_path('REDEEM_ANALYTICS_ROUTES_EXAMPLE.php');
```

### Step 5: Use Model in Your Code
```php
use App\Models\TselepoinRedeem;

// Get redemptions untuk merchant tertentu
$redemptions = TselepoinRedeem::blanjapoin()
    ->forMerchant(1)
    ->matched()
    ->latest('created_date')
    ->get();

// Check match percentage
$percentage = TselepoinRedeem::getMatchPercentageForKeyword('PROMO100');
```

---

## 🔧 Configuration Options

### Custom Batch Size (Saat Populate)
```bash
# Default: 10,000 records per batch
php artisan redeem:populate-merchant-data --limit=10000

# Smaller batch (lebih aman untuk performance)
php artisan redeem:populate-merchant-data --limit=5000

# Larger batch (lebih cepat)
php artisan redeem:populate-merchant-data --limit=20000
```

### Minimum Click-to-Redeem Time
Di SQL/migration, saat ini minimum adalah **3 detik**. Bisa diubah di:
- `app/Models/Keyword.php` - `whereRaw("TIMESTAMPDIFF(SECOND...") > 3`
- Atau di SQL function/trigger

---

## 📊 Data Structure

### Before Migration
```
tokodigi_tselpoin_redeem
┌────┬──────────┬──────────┬──────────────────┐
│ id │ msisdn   │ coupon   │ created_date     │
├────┼──────────┼──────────┼──────────────────┤
│ 1  │ 081234.. │ PROMO100 │ 2025-12-28 10:02 │
│ 2  │ 081567.. │ PROMO100 │ 2025-12-28 10:07 │
└────┴──────────┴──────────┴──────────────────┘
```

### After Migration
```
tokodigi_tselpoin_redeem
┌────┬──────────┬──────────┬──────────────────┬─────────────┬──────────────┬──────────┐
│ id │ msisdn   │ coupon   │ created_date     │ merchant_id │ clicked_date │ diff_... │
├────┼──────────┼──────────┼──────────────────┼─────────────┼──────────────┼──────────┤
│ 1  │ 081234.. │ PROMO100 │ 2025-12-28 10:02 │ 1           │ 2025-12-28.. │ 120      │
│ 2  │ 081567.. │ PROMO100 │ 2025-12-28 10:07 │ 2           │ 2025-12-28.. │ 125      │
└────┴──────────┴──────────┴──────────────────┴─────────────┴──────────────┴──────────┘
```

---

## 🧪 Testing

### Test 1: Basic Migration
```bash
# Check migrations ran
php artisan migrate:status | grep "2025_12_28"

# Should show all 3 migrations as "Ran"
```

### Test 2: Data Populated
```bash
mysql> SELECT 
    COUNT(*) as total,
    COUNT(merchant_id) as with_merchant,
    ROUND(COUNT(merchant_id) * 100 / COUNT(*), 2) as match_pct
FROM tokodigi_tselpoin_redeem 
WHERE program = 'BLANJAPOIN';

# Example output:
# total | with_merchant | match_pct
# 50000 | 45230         | 90.46
```

### Test 3: New Redeem Auto-Match
```sql
-- Insert test click
INSERT INTO click_history (merchant_id, keyword_id, clicked_at, ip_address, device_id, user_agent)
VALUES (1, 'TEST_KEYWORD', NOW(), '127.0.0.1', 'test-device', 'test-agent');

-- Insert test redeem
INSERT INTO tokodigi_tselpoin_redeem (msisdn, coupon, created_date, program, poin_redeem)
VALUES ('081234567890', 'TEST_KEYWORD', DATE_ADD(NOW(), INTERVAL 10 SECOND), 'BLANJAPOIN', 1000);

-- Check result - merchant_id should be auto-filled
SELECT merchant_id, clicked_date, diff_click 
FROM tokodigi_tselpoin_redeem 
WHERE coupon = 'TEST_KEYWORD' 
AND msisdn = '081234567890';

# Should show:
# merchant_id | clicked_date | diff_click
# 1           | <timestamp>  | 10
```

### Test 4: Model Usage
```php
php artisan tinker

>>> use App\Models\TselepoinRedeem;

>>> TselepoinRedeem::blanjapoin()->matched()->count();
// Should return matched count

>>> TselepoinRedeem::getClickToRedeemDistribution();
// Should return array dengan distribution

>>> TselepoinRedeem::getMatchPercentageForKeyword('PROMO100');
// Should return percentage
```

---

## 🎯 Usage Examples

### Update Keyword Model (Optional)
```php
// In app/Models/Keyword.php - updateTrxAndSisaStock()

// OLD (inefficient - count SEMUA redemption)
$trxCount = DB::table('tokodigi_tselpoin_redeem')
    ->where('coupon', $this->keyword_id)
    ->count();

// NEW (efficient - count hanya merchant ini)
$trxCount = DB::table('tokodigi_tselpoin_redeem')
    ->where('coupon', $this->keyword_id)
    ->where('merchant_id', $this->merchant_key)
    ->distinct('msisdn')
    ->count();
```

### Analytics Query
```php
use App\Models\TselepoinRedeem;

// Get redemptions by merchant
$stats = TselepoinRedeem::blanjapoin()
    ->forMerchant(1)
    ->matched()
    ->get()
    ->groupBy('coupon')
    ->map(function ($group) {
        return [
            'keyword' => $group->first()->coupon,
            'count' => $group->count(),
            'avg_click_to_redeem' => $group->avg('diff_click'),
        ];
    });
```

### Dashboard Widget
```php
$dashboard = [
    'total_redemptions' => TselepoinRedeem::blanjapoin()->count(),
    'matched' => TselepoinRedeem::blanjapoin()->matched()->count(),
    'unmatched' => TselepoinRedeem::blanjapoin()->unmatched()->count(),
    'avg_click_to_redeem_sec' => TselepoinRedeem::blanjapoin()
        ->matched()
        ->avg('diff_click'),
];
```

---

## ⚠️ Important Notes

### Performance Considerations
1. **Trigger Performance**: Insert banyak data ke click_history bisa slow
   - Solution: Batch insert dengan interval
   - Atau: Disable trigger saat bulk insert, then run populate command

2. **Index Fragmentation**: Monitor regularly
   ```sql
   ANALYZE TABLE tokodigi_tselpoin_redeem;
   OPTIMIZE TABLE tokodigi_tselpoin_redeem;
   ```

3. **Disk Space**: Database akan tumbuh dengan kolom baru
   - Estimate: 3 kolom × ~50K records ≈ 3-5 MB

### Data Consistency
1. **Minimum 3 Detik**: Click dan redeem harus minimal 3 detik
   - Untuk proses loading, validation, etc
   
2. **MSISDN + Keyword Distinct**: Satu user satu keyword = 1 count
   - Prevent double counting jika ada duplicate entry

3. **Closest Match Wins**: Jika ada 2+ click, diambil yang paling dekat
   - Logical untuk multi-merchant scenarios

---

## 🔍 Monitoring & Maintenance

### Check Trigger Status
```sql
SHOW TRIGGERS FROM blanjapoin WHERE `Table` = 'tokodigi_tselpoin_redeem';
SHOW TRIGGERS FROM blanjapoin WHERE `Table` = 'click_history';
```

### Check Function Status
```sql
SHOW FUNCTION STATUS WHERE db = 'blanjapoin' AND name LIKE '%populate%';
```

### View Slow Queries
```sql
SHOW PROCESSLIST;
SELECT * FROM mysql.slow_log LIMIT 10;
```

### Re-run Populate (If Needed)
```bash
# Process hanya unmatched records
php artisan redeem:populate-merchant-data --limit=5000
```

---

## 📞 Troubleshooting

### Migration Failed
1. Check database connection
2. Check disk space
3. Check MySQL version (need 5.7+)
```bash
mysql --version
```

### Populate Command Stalled
1. Check memory usage
2. Kill long-running process
3. Re-run dengan smaller batch size
```bash
php artisan redeem:populate-merchant-data --limit=1000
```

### Trigger Not Working
1. Check MySQL version (triggers in 5.7+)
2. Check trigger created
```sql
SHOW TRIGGERS;
```
3. Check SQL mode (strict mode bisa interfere)

### No Merchant Match Found
1. Check click_history punya data untuk keyword
2. Check clicked_at < created_date (click HARUS sebelum redeem)
3. Check time diff > 3 detik
4. Manual check:
```sql
SELECT * FROM click_history 
WHERE keyword_id = 'YOUR_KEYWORD'
LIMIT 5;
```

---

## 📚 Reference Files

| File | Purpose | Notes |
|------|---------|-------|
| `REDEEM_MERCHANT_DATA_GUIDE.md` | Full documentation | Read this first |
| `REDEEM_MERCHANT_DATA_IMPLEMENTATION.md` | Implementation summary | Overview & checklist |
| `REDEEM_MERCHANT_DATA_SQL.sql` | SQL queries | Ready-to-copy SQL |
| `REDEEM_ANALYTICS_ROUTES_EXAMPLE.php` | API endpoints | Example routes |
| `app/Models/TselepoinRedeem.php` | Model | Eloquent model & scopes |
| `app/Http/Controllers/RedeemAnalyticsController.php` | Controller | Analytics endpoints |

---

## ✅ Implementation Complete!

Semua file sudah dibuat dan siap digunakan. Follow langkah "Quick Start" di atas untuk deployment.

Untuk pertanyaan lebih detail, lihat dokumentasi di file-file reference.

---

**Last Updated:** 2025-12-28
**Version:** 1.0
**Status:** Ready for Production
