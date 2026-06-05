# Top Banner — Redesign & Flow Baru

## Ringkasan Perubahan

| # | Fitur | Status |
|---|-------|--------|
| 1 | Halaman iklan jadi list link | Baru |
| 2 | Flow add banner: pilih teritorial/program dulu | Baru |
| 3 | "All Program" dropdown untuk user regional | Baru |
| 4 | Fallback inheritance hierarchy | Baru |
| 5 | Setting top banner saat add merchant | Baru |

---

## 1. Halaman Iklan — Tampilan Daftar Link

### Kondisi Sekarang
Halaman `/iklan` langsung menampilkan form tambah + tabel flat semua banner dari semua lokasi.

### Yang Akan Dibuat

Halaman `/iklan` menjadi **daftar link/lokasi** yang sudah memiliki top banner terkonfigurasi.

**Layout halaman iklan (index):**
```
[+ Tambah Top Banner]

DAFTAR LINK YANG SUDAH DIKONFIGURASI
┌──────────────────────────────────────────────────────────┐
│ Lokasi/Link            │ Tipe       │ Jumlah  │ Aksi     │
├──────────────────────────────────────────────────────────┤
│ poin-tsel/surabaya     │ Territorial│ 3 banner│ [Kelola] │
│ poin-tsel/jatim        │ Regional   │ 2 banner│ [Kelola] │
│ /u/tokobaju            │ Program    │ 1 banner│ [Kelola] │
│ General (Welcome)      │ General    │ 5 banner│ [Kelola] │
└──────────────────────────────────────────────────────────┘
```

**Klik [Kelola] → halaman detail link:**
- Tampil semua top banner untuk link tersebut
- Drag-reorder posisi/urutan (sama seperti fitur reorder yang sekarang)
- Tombol tambah banner baru ke link ini
- Tombol edit/hapus per banner
- Preview carousel

**Perubahan file:**
- `resources/views/iklan.blade.php` — ubah layout menjadi list link
- `app/Http/Controllers/IklanController.php` → method `index()` — group iklans by lokasi
- Route baru: `GET /iklan/{type}/{slug}` → halaman detail per link

---

## 2. Flow Add Top Banner — Pilih Teritorial/Program Dulu

### Kondisi Sekarang
User langsung upload gambar atau pilih keyword, baru kemudian pilih lokasi penempatan di bagian bawah form.

### Yang Akan Dibuat

Flow menjadi **wizard 3 langkah**:

```
Step 1: Pilih Penempatan
        [Teritorial ▼]  [Program/Merchant ▼]

Step 2: Pilih Sumber Banner
        (Keyword-keyword yang tersedia di teritorial/program yang dipilih muncul di sini)
        ○ Dari Keyword   ○ Upload Manual

Step 3: Konfigurasi & Simpan
        - Preview banner
        - Setting aktif/nonaktif
        - Simpan
```

### Detail Step 1 — Pilih Penempatan

**Opsi Teritorial:**
```
Tipe Lokasi: [General] [Territorial] [Regional] [Branch] [Cluster]
              ↓ (sesuai user level, sama seperti sekarang)
Pilih Lokasi: [dropdown lokasi spesifik]
```

**Opsi Program/Merchant:**
```
Pilih Merchant: [dropdown merchant]
```

**Dengan tambahan "All Program" (lihat section 3).**

### Detail Step 2 — Pilih Keyword dari Lokasi Terpilih

Setelah Step 1 dipilih, sistem query keyword yang terdaftar di lokasi tersebut:

```php
// Contoh: user pilih Territorial = Surabaya
// Query keyword dari merchant-merchant yang ada di Surabaya
$keywords = Keyword::whereHas('merchant', function($q) use ($locationSlug) {
    $q->whereRaw("LOWER(teritorial_slug) = ?", [$locationSlug]);
})
->where('status', 'approve')
->where('is_active', 1)
->get();
```

Tampil daftar keyword tersebut → user pilih → otomatis ambil image dan link dari keyword.

**Perubahan file:**
- `resources/views/iklan.blade.php` — form menjadi multi-step / wizard
- `app/Http/Controllers/IklanController.php` → tambah method `getKeywordsByLocation()` (AJAX)
- Route baru: `GET /iklan/keywords-by-location` → return JSON keywords

---

## 3. "All Program" — Apply ke Semua Link di Region

### Kondisi Sekarang
Tidak ada opsi bulk apply untuk semua link dalam satu region.

### Yang Akan Dibuat

Di dropdown penempatan, tambah opsi **"All [Nama Region]"**:

```
Regional: [Jatim ▼]
           ├─ All Jatim  ← BARU
           ├─ Branch Surabaya
           ├─ Branch Malang
           └─ ...
```

**Ketika "All Jatim" dipilih:**
1. Banner disimpan dengan flag `apply_all = true` + `regional = 'jatim'`
2. Semua link yang ber-regional Jatim (branch, city, program) akan menampilkan banner ini
3. **Tetap bisa di-override per link** — kalau link spesifik punya banner sendiri, banner spesifik itu yang tampil (sesuai hierarchy fallback di section 4)

**Skema database — tambah kolom di tabel `iklans`:**
```sql
ALTER TABLE iklans ADD COLUMN apply_scope ENUM('specific', 'all_regional', 'all_branch') DEFAULT 'specific';
```

**Perubahan file:**
- `database/migrations/` → migration baru untuk kolom `apply_scope`
- `app/Models/Iklan.php` → tambah `apply_scope` ke `$fillable`
- `app/Http/Controllers/IklanController.php` → handle `apply_scope` di `store()`
- `app/Http/Controllers/MerchantController.php` → update logika query banner di `showByTerritorial()`, `showByRegional()`, `showByBranch()`, `showByCluster()` untuk mempertimbangkan `apply_scope`

---

## 4. Fallback Inheritance Hierarchy

### Kondisi Sekarang
```
Link tanpa banner → General (welcome)
```

### Yang Akan Dibuat

**Hierarki fallback lengkap:**
```
Link spesifik (/u/programdigital)
    ↓ (tidak ada)
City/Territorial (poin-tsel/surabaya)
    ↓ (tidak ada)
Branch (poin-tsel/surabaya-branch)
    ↓ (tidak ada)
Regional (poin-tsel/jatim)
    ↓ (tidak ada)
General (welcome)
```

**Contoh kasus:**
- `/u/programdigital` → lokasinya di Surabaya
- Belum diset banner spesifik
- Sistem cek → apakah ada banner untuk `territorial = surabaya`? → Ya → pakai itu
- Kalau tidak ada territorial Surabaya → cek branch Surabaya → cek regional Jatim → fallback general

**Implementasi di MerchantController — showByProgram / showByMerchant:**

```php
// Helper baru: resolveIklansWithFallback($merchantOrProgram)
private function resolveIklansWithFallback($merchant)
{
    // 1. Banner spesifik untuk merchant ini
    $iklans = Iklan::where('merchant_key', $merchant->id)->where('is_active', 1)->get();
    if ($iklans->isNotEmpty()) return $iklans;

    // 2. Banner territorial (city) merchant ini
    $citySlug = territorialSlug(extractKabupatenKota($merchant->daerah));
    $iklans = Iklan::whereNotNull('territorial')->whereNull('regional')
        ->where('is_active', 1)->get()
        ->filter(fn($i) => territorialSlug($i->territorial) === $citySlug);
    if ($iklans->isNotEmpty()) return $iklans;

    // 3. Banner branch merchant ini
    $branchSlug = territorialSlugGeneric($merchant->branch ?? '');
    if ($branchSlug) {
        $iklans = Iklan::whereNotNull('branch')->where('is_active', 1)->get()
            ->filter(fn($i) => territorialSlugGeneric($i->branch) === $branchSlug);
        if ($iklans->isNotEmpty()) return $iklans;
    }

    // 4. Banner regional merchant ini
    $regionalSlug = territorialSlugGeneric($merchant->regional ?? '');
    if ($regionalSlug) {
        $iklans = Iklan::whereNotNull('regional')->whereNull('branch')->where('is_active', 1)->get()
            ->filter(fn($i) => territorialSlugGeneric($i->regional) === $regionalSlug);
        if ($iklans->isNotEmpty()) return $iklans;
    }

    // 5. Fallback: general
    return Iklan::whereNull('territorial')->whereNull('regional')
        ->whereNull('branch')->whereNull('cluster')
        ->whereNull('merchant_key')->whereNull('keyword_id')
        ->where('is_active', 1)->orderBy('order')->get();
}
```

**Perubahan file:**
- `app/Http/Controllers/MerchantController.php` → tambah `resolveIklansWithFallback()`, update semua method `showBy*` yang sekarang langsung fallback ke general
- `routes/web.php` → tidak ada perubahan route

---

## 5. Add Merchant — Setting Top Banner

### Kondisi Sekarang
Form tambah merchant tidak ada opsi untuk setting top banner.

### Yang Akan Dibuat

Di halaman add/edit merchant, tambah **section "Top Banner"**:

```
TOP BANNER
──────────────────────────────────────────────────
Mode Banner:
○ Ikut General (city atau program di atasnya)
○ Otomatis dari Keywords Merchant (maks 5)

[Jika pilih otomatis]
Keyword tersedia: [checkbox list keyword aktif merchant ini]
                  Pilih maks 5 keyword
                  ☑ Keyword A (gambar preview kecil)
                  ☑ Keyword B
                  ☐ Keyword C
                  ☐ Keyword D
```

**Logika:**
- **Ikut General** = tidak ada iklan spesifik dibuat, sistem pakai fallback hierarchy (section 4)
- **Otomatis dari Keywords** = saat simpan merchant, otomatis buat record `iklans` untuk merchant tersebut dari keyword yang dipilih (max 5), dengan `merchant_key = merchant.id` dan `keyword_id = keyword.id`

**Perubahan file:**
- `resources/views/admin/merchant/` (form add/edit merchant) → tambah section top banner
- `app/Http/Controllers/MerchantController.php` → di `store()` dan `update()`, handle pembuatan iklan otomatis dari keyword terpilih
- `app/Models/Merchant.php` → tambah relasi `iklans()` jika belum ada

---

## Urutan Implementasi yang Disarankan

```
Phase 1 — Foundation
  [1] Migration: kolom apply_scope di tabel iklans
  [2] Model Iklan: tambah apply_scope ke $fillable
  [3] Fallback hierarchy di MerchantController (section 4)
      → Fix bug yang paling terasa langsung oleh user

Phase 2 — Halaman Iklan Redesign
  [4] IklanController index(): group iklans by lokasi
  [5] Blade iklan.blade.php: tampilan list link
  [6] Route + controller method detail per link
  [7] Drag-reorder tetap jalan di halaman detail

Phase 3 — Flow Add Banner Baru
  [8] AJAX endpoint: getKeywordsByLocation()
  [9] Form wizard 3-step di iklan.blade.php
  [10] Handle apply_scope "All Regional/Branch" di store()

Phase 4 — Merchant Setting
  [11] Form merchant: section top banner
  [12] MerchantController store()/update(): auto-create iklans dari keyword
```

---

## File yang Akan Diubah (Summary)

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/IklanController.php` | index() list by link, store() handle apply_scope, tambah getKeywordsByLocation() |
| `app/Http/Controllers/MerchantController.php` | resolveIklansWithFallback(), semua showBy*(), store()/update() auto-create iklan |
| `app/Models/Iklan.php` | tambah apply_scope ke $fillable |
| `app/Models/Merchant.php` | tambah relasi iklans() |
| `resources/views/iklan.blade.php` | redesign layout → list link + form wizard |
| `resources/views/admin/merchant/` | tambah section top banner di form |
| `database/migrations/` | migration baru kolom apply_scope |
| `routes/web.php` | route baru: /iklan/{type}/{slug}, /iklan/keywords-by-location |
