# 📋 REDEEM MERCHANT DATA POPULATION GUIDE

## 🎯 Overview

Dokumentasi lengkap untuk menambahkan kolom `merchant_id`, `clicked_date`, dan `diff_click` ke tabel `tokodigi_tselpoin_redeem` serta fungsi-fungsi SQL untuk mengotomatisasi pengisian data.

---

## 📊 Struktur Database

### Tabel: `tokodigi_tselpoin_redeem`

**Kolom Baru yang Ditambahkan:**

```
┌─────────────────┬──────────────┬──────────────────────────────────────────┐
│ Column          │ Type         │ Deskripsi                                │
├─────────────────┼──────────────┼──────────────────────────────────────────┤
│ merchant_id     │ BIGINT       │ ID merchant dari click yang di-match     │
│ clicked_date    │ DATETIME     │ Tanggal/waktu klik yang di-match        │
│ diff_click      │ INT          │ Selisih waktu (detik) antara klik & redeem│
└─────────────────┴──────────────┴──────────────────────────────────────────┘
```

---

## 🚀 Cara Implementasi

### Step 1: Run Migrations

```bash
# Jalankan semua migration baru
php artisan migrate

# Atau jalankan satu per satu:
php artisan migrate --path=database/migrations/2025_12_28_000000_add_merchant_columns_to_tselpoin_redeem.php
php artisan migrate --path=database/migrations/2025_12_28_000001_create_populate_redeem_merchant_function.php
php artisan migrate --path=database/migrations/2025_12_28_000002_create_update_redeem_on_click_trigger.php
```

### Step 2: Populate Existing Data

Untuk mengisi data redemption yang sudah ada sebelum trigger aktif:

```bash
# Default: process 10,000 records per batch
php artisan redeem:populate-merchant-data

# Atau dengan custom batch size
php artisan redeem:populate-merchant-data --limit=5000
```

**Output Example:**
```
🔄 Mulai populate data redemption dengan merchant matching...
📊 Batch size: 10000 records

⏳ Batch #1: Processing 45230 pending records...
✅ Batch #1: 10000 records updated
⏳ Batch #2: Processing 35230 pending records...
✅ Batch #2: 10000 records updated
⏳ Batch #3: Processing 25230 pending records...
✅ Batch #3: 10000 records updated
⏳ Batch #4: Processing 15230 pending records...
✅ Batch #4: 10000 records updated
⏳ Batch #5: Processing 5230 pending records...
✅ Batch #5: 5230 records updated

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

---

## 🔄 Cara Kerja (Logic Flow)

### Proses Manual Populate

```
FOR EACH redemption WHERE program = 'BLANJAPOIN' AND merchant_id IS NULL:
    
    1. Ambil: coupon (keyword_id), created_date (redeem time), msisdn
    
    2. Cari matching click:
       - keyword_id = redemption.coupon
       - clicked_at < created_date (click HARUS sebelum redeem)
       - TIMESTAMPDIFF(SECOND, clicked_at, created_date) > 3 (minimal 3 detik)
       - Order by time_diff ASC (ambil yang paling dekat)
    
    3. Jika ketemu matching click:
       - merchant_id = matching_click.merchant_id
       - clicked_date = matching_click.clicked_at
       - diff_click = TIMESTAMPDIFF(SECOND, clicked_at, created_date)
       - UPDATE redemption dengan 3 nilai tersebut
```

### Automatic via Trigger (Real-time)

**Trigger 1: `tr_update_redeem_on_create`**
```
WHEN: INSERT ke tokodigi_tselpoin_redeem
ACTION: Auto-populate merchant_id, clicked_date, diff_click dari click_history
BENEFIT: Setiap redeem baru langsung matched dengan click terdekat
```

**Trigger 2: `tr_update_redemptions_on_new_click`**
```
WHEN: INSERT ke click_history (merchant klik keyword)
ACTION: Find redemptions dalam 1 jam setelah klik, check apakah ada match lebih baik
BENEFIT: Jika ada click lebih dekat, update redemption dengan click lebih baik tersebut
```

---

## 📝 SQL Query Reference

### Hitung Total Redemption per Merchant

```sql
SELECT 
    m.id as merchant_id,
    m.nama_merchant,
    COUNT(*) as total_redemptions,
    COUNT(DISTINCT tr.msisdn) as unique_users
FROM merchants m
LEFT JOIN tokodigi_tselpoin_redeem tr ON m.id = tr.merchant_id
WHERE tr.program = 'BLANJAPOIN'
GROUP BY m.id, m.nama_merchant
ORDER BY total_redemptions DESC;
```

### Lihat Redemptions yang Belum Ter-Match

```sql
SELECT 
    id,
    msisdn,
    coupon,
    created_date,
    merchant_id,
    clicked_date,
    diff_click
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN'
AND (merchant_id IS NULL OR clicked_date IS NULL OR diff_click IS NULL)
LIMIT 100;
```

### Lihat Distribution Click-to-Redeem Time

```sql
SELECT 
    CASE 
        WHEN diff_click BETWEEN 3 AND 10 THEN '3-10 sec'
        WHEN diff_click BETWEEN 11 AND 30 THEN '11-30 sec'
        WHEN diff_click BETWEEN 31 AND 60 THEN '31-60 sec'
        WHEN diff_click BETWEEN 61 AND 300 THEN '1-5 min'
        WHEN diff_click > 300 THEN '> 5 min'
        ELSE 'NULL'
    END as time_range,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM tokodigi_tselpoin_redeem WHERE program = 'BLANJAPOIN'), 2) as percentage
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN'
GROUP BY time_range
ORDER BY FIELD(time_range, '3-10 sec', '11-30 sec', '31-60 sec', '1-5 min', '> 5 min', 'NULL');
```

### Update Keyword.trx dengan Data dari Tokodigi (lebih akurat sekarang)

```sql
UPDATE keywords k
SET k.trx = (
    SELECT COALESCE(COUNT(DISTINCT CONCAT(tr.msisdn, '_', tr.coupon)), 0)
    FROM tokodigi_tselpoin_redeem tr
    WHERE tr.coupon = k.keyword_id
    AND tr.merchant_id = k.merchant_key
    AND tr.program = 'BLANJAPOIN'
)
WHERE k.keyword_id IS NOT NULL;
```

---

## 🔗 Integration dengan Keyword Model

Setelah implement ini, bisa update `Keyword.php` untuk lebih efficient:

```php
// In Keyword model updateTrxAndSisaStock()
public function updateTrxAndSisaStock()
{
    if (!$this->keyword_id) {
        return false;
    }

    // Sekarang bisa hitung langsung dari tokodigi_tselpoin_redeem
    // karena merchant_id sudah ter-fill dari database
    $trxCount = DB::table('tokodigi_tselpoin_redeem')
        ->where('coupon', $this->keyword_id)
        ->where('merchant_id', $this->merchant_key)  // ← Direct match!
        ->where('program', 'BLANJAPOIN')
        ->distinct('msisdn', 'coupon')  // Prevent double counting
        ->count();

    // ... rest of the logic
}
```

---

## 🧪 Testing

### Test Scenario 1: Basic Population

```bash
# Setup: Insert 100 clicks dan 50 redeems
php artisan tinker

# Verify columns ada
>>> DB::table('tokodigi_tselpoin_redeem')->where('program', 'BLANJAPOIN')->limit(1)->get();

# Run populate
>>> php artisan redeem:populate-merchant-data --limit=100

# Check hasil
>>> DB::table('tokodigi_tselpoin_redeem')->where('program', 'BLANJAPOIN')
    ->whereNotNull('merchant_id')->count();
```

### Test Scenario 2: Trigger on New Redeem

```php
// Insert new click
DB::table('click_history')->insert([
    'merchant_id' => 1,
    'keyword_id' => 'TEST123',
    'clicked_at' => now(),
    'ip_address' => '127.0.0.1',
    'device_id' => 'device-test',
    'user_agent' => 'test-agent'
]);

// Insert new redeem (trigger harus otomatis matching)
DB::table('tokodigi_tselpoin_redeem')->insert([
    'coupon' => 'TEST123',
    'created_date' => now()->addSeconds(10),
    'msisdn' => '081234567890',
    'program' => 'BLANJAPOIN',
    'poin_redeem' => 1000
]);

// Verify: merchant_id, clicked_date, diff_click should be auto-filled
DB::table('tokodigi_tselpoin_redeem')
    ->where('coupon', 'TEST123')
    ->where('msisdn', '081234567890')
    ->first();
```

---

## ⚠️ Important Notes

1. **Minimum 3 Detik:** Click dan redeem harus minimal berjarak 3 detik (untuk proses loading, etc)
2. **Closest Match Wins:** Jika ada 2+ click untuk keyword yang sama, diambil yang paling dekat waktunya
3. **Distinct by MSISDN + Keyword:** Satu user (MSISDN) dengan satu keyword dihitung hanya 1x
4. **Program Filter:** Hanya BLANJAPOIN yang diproses (bisa extend untuk program lain)

---

## 📈 Performance Tips

1. Pastikan ada index di:
   - `click_history(keyword_id, clicked_at)`
   - `tokodigi_tselpoin_redeem(coupon, merchant_id, program)`

2. Untuk dataset besar (> 1 juta rows):
   - Use batch processing (population command sudah handle ini)
   - Schedule population command di off-peak hours

3. Monitor trigger performance:
   ```sql
   -- Check slow queries
   SHOW PROCESSLIST;
   ```

---

## 🔄 Maintenance Commands

```bash
# Verify data consistency
php artisan redeem:populate-merchant-data

# Re-run specific batch size jika ada yang failed
php artisan redeem:populate-merchant-data --limit=5000

# Check migration status
php artisan migrate:status
```

---

## 📞 Support

Jika ada issue:
1. Check apakah migration sudah running: `php artisan migrate:status`
2. Verify database structure: `DESCRIBE tokodigi_tselpoin_redeem;`
3. Check trigger status: `SHOW TRIGGERS;`
4. Check function status: `SHOW FUNCTION STATUS;`
