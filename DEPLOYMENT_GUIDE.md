# Panduan Deployment ke Production (https://blanjapoin.id)

## Perubahan yang Diperlukan

### 1. Perubahan di File `.env` (di Server)

Ubah konfigurasi berikut di file `.env` di server production:

```env
# Sebelum (Development)
APP_URL=http://127.0.0.1:8000
# atau
APP_URL=http://localhost:8000

# Sesudah (Production)
APP_URL=https://blanjapoin.id

# Opsional: Jika menggunakan Sanctum untuk SPA, tambahkan:
SANCTUM_STATEFUL_DOMAINS=blanjapoin.id,www.blanjapoin.id

# Opsional: Jika perlu set session domain (untuk subdomain)
# SESSION_DOMAIN=.blanjapoin.id
```

**Catatan Penting:**
- Pastikan menggunakan `https://` bukan `http://`
- Jangan sertakan port (kecuali jika menggunakan port khusus)
- Setelah mengubah `.env`, jalankan:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  ```

### 2. Perubahan di Google Cloud Console

Berdasarkan gambar yang Anda sertakan, berikut yang perlu diubah:

#### A. Client ID for Web application (URIs)

**Hapus atau pertahankan (opsional):**
- `http://127.0.0.1:8000` (bisa dihapus jika tidak digunakan lagi)
- `https://blanjapoin.id` ✅ (sudah benar, pertahankan)

**Rekomendasi untuk Production:**
- Hapus semua URL localhost/127.0.0.1 jika tidak digunakan
- Pastikan hanya ada: `https://blanjapoin.id`

#### B. Authorized redirect URIs

**Perbaiki dan sesuaikan:**

1. **Hapus URL development:**
   - ❌ `https://localhost:8000/auth-google-callback` (hapus)
   - ❌ `http://127.0.0.1:8000/auth-google-callback` (hapus)
   - ❌ `http://localhost/auth-google-callback` (hapus)

2. **Perbaiki typo:**
   - ❌ `https://blanjapoin.id/auth-ggogle-callback` (ada typo "ggogle")
   - ✅ `https://blanjapoin.id/auth-google-callback` (benar)

3. **Pastikan hanya ada URL production:**
   - ✅ `https://blanjapoin.id/auth-google-callback`

**Langkah-langkah di Google Cloud Console:**

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda
3. Buka **APIs & Services** > **Credentials**
4. Klik pada **OAuth 2.0 Client ID** yang sedang digunakan
5. Di bagian **Authorized redirect URIs**:
   - Hapus semua URL yang mengandung `localhost` atau `127.0.0.1`
   - Edit URL yang ada typo: `auth-ggogle-callback` → `auth-google-callback`
   - Pastikan hanya ada: `https://blanjapoin.id/auth-google-callback`
6. Di bagian **Client ID for Web application (URIs)**:
   - Hapus `http://127.0.0.1:8000` (opsional, bisa dihapus)
   - Pastikan ada: `https://blanjapoin.id`
7. Klik **SAVE**

### 3. Checklist Sebelum Deploy

- [ ] File `.env` di server sudah diubah `APP_URL=https://blanjapoin.id`
- [ ] Google Cloud Console sudah diupdate:
  - [ ] Authorized redirect URIs hanya berisi `https://blanjapoin.id/auth-google-callback`
  - [ ] Typo "auth-ggogle-callback" sudah diperbaiki menjadi "auth-google-callback"
  - [ ] Semua URL localhost sudah dihapus (atau dipertahankan jika masih digunakan untuk development)
- [ ] Jalankan `php artisan config:clear` dan `php artisan cache:clear` di server
- [ ] Pastikan SSL certificate sudah terpasang dengan benar di server
- [ ] Test login dengan Google OAuth di production

### 4. Setelah Deploy

Setelah semua perubahan dilakukan, test dengan:

1. Buka `https://blanjapoin.id`
2. Coba login dengan Google OAuth
3. Pastikan redirect berfungsi dengan benar
4. Cek log jika ada error: `storage/logs/laravel.log`

### 5. Troubleshooting

#### Error: "You can't sign in to this app because it doesn't comply with Google's OAuth 2.0 policy"

**Error ini muncul karena redirect URI belum terdaftar di Google Cloud Console.**

**Solusi Langkah demi Langkah:**

1. **Buka Google Cloud Console:**
   - Kunjungi: https://console.cloud.google.com/
   - Pilih project yang sesuai
   - Buka menu **APIs & Services** > **Credentials**

2. **Cari OAuth 2.0 Client ID:**
   - Cari Client ID yang sesuai: `1014177464770-uht9rcvdenhfti51cm0kc9v10ic0eih5.apps.googleusercontent.com`
   - Klik pada Client ID tersebut untuk membuka detail

3. **Tambahkan Authorized redirect URI:**
   - Scroll ke bagian **Authorized redirect URIs**
   - Klik tombol **+ ADD URI**
   - Masukkan URL berikut **TANPA spasi di awal/akhir:**
     ```
     https://blanjapoin.id/auth-google-callback
     ```
   - **PENTING:** 
     - Pastikan menggunakan `https://` (bukan `http://`)
     - Pastikan tidak ada trailing slash di akhir (`/`)
     - Pastikan ejaan benar: `auth-google-callback` (bukan `auth-ggogle-callback`)

4. **Tambahkan Authorized JavaScript origins (jika diperlukan):**
   - Di bagian **Authorized JavaScript origins**
   - Klik **+ ADD URI**
   - Tambahkan: `https://blanjapoin.id`
   - **Jangan** tambahkan path atau trailing slash

5. **Simpan Perubahan:**
   - Scroll ke bawah
   - Klik tombol **SAVE**
   - Tunggu beberapa detik hingga perubahan tersimpan

6. **Verifikasi:**
   - Pastikan di **Authorized redirect URIs** ada:
     - `https://blanjapoin.id/auth-google-callback`
   - Pastikan di **Authorized JavaScript origins** ada:
     - `https://blanjapoin.id`

7. **Clear Cache Laravel (di server):**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

8. **Test Kembali:**
   - Tunggu 1-2 menit (kadang Google perlu waktu untuk update)
   - Coba login dengan Google OAuth lagi
   - Jika masih error, tunggu 5-10 menit dan coba lagi

**Catatan Penting:**
- URL harus **SAMA PERSIS** tanpa spasi, tanpa trailing slash
- Pastikan menggunakan `https://` untuk production
- Perubahan di Google Cloud Console bisa memakan waktu beberapa menit untuk aktif

#### Error: "redirect_uri_mismatch"

**Jika masih error "redirect_uri_mismatch":**
- Pastikan URL di Google Cloud Console **SAMA PERSIS** dengan yang digunakan aplikasi
- Pastikan menggunakan `https://` bukan `http://`
- Pastikan tidak ada trailing slash (`/`) di akhir URL
- Pastikan tidak ada spasi di awal atau akhir URL
- Clear cache Laravel: `php artisan config:clear`
- Cek log Laravel untuk melihat redirect URI yang sebenarnya digunakan:
  ```bash
  tail -f storage/logs/laravel.log
  ```

#### Error: "invalid_client"

**Jika error "invalid_client":**
- Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` di `.env` sudah benar
- Pastikan credentials sudah di-copy tanpa spasi tambahan
- Pastikan Client ID yang digunakan di `.env` sama dengan yang di Google Cloud Console

## Ringkasan Perubahan

### Di Code (`.env`):
```
APP_URL=https://blanjapoin.id
```

### Di Google Cloud Console:
- **Authorized redirect URIs:** Hanya `https://blanjapoin.id/auth-google-callback`
- **Client ID for Web application:** `https://blanjapoin.id`
- **Perbaiki typo:** `auth-ggogle-callback` → `auth-google-callback`

