# Setup Google OAuth untuk Login Portal

## Masalah: "Missing required parameter: redirect_uri"

Error ini terjadi karena redirect_uri tidak dikonfigurasi dengan benar di Google Cloud Console atau di file `.env`.

## Langkah-langkah Setup:

### 1. Buat OAuth 2.0 Client di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih atau buat project baru
3. Buka **APIs & Services** > **Credentials**
4. Klik **Create Credentials** > **OAuth client ID**
5. Pilih **Web application**
6. Isi form:
   - **Name**: BlanjaPoin Portal (atau nama lain)
   - **Authorized redirect URIs**: 
     - Untuk development dengan port: `http://localhost:8000/auth-google-callback`
     - Untuk development tanpa port: `http://localhost/auth-google-callback`
     - Untuk production: `https://yourdomain.com/auth-google-callback`
   
   **PENTING:** Tambahkan SEMUA variasi URL yang mungkin digunakan:
   - `http://localhost:8000/auth-google-callback` (jika menggunakan `php artisan serve`)
   - `http://localhost/auth-google-callback` (jika menggunakan web server seperti Laragon/Apache)
   - `http://127.0.0.1:8000/auth-google-callback` (alternatif localhost)
7. Klik **Create**
8. Copy **Client ID** dan **Client Secret**

### 2. Konfigurasi di file `.env`

Tambahkan atau update konfigurasi berikut di file `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
APP_URL=http://localhost:8000
```

**Catatan:** 
- `GOOGLE_REDIRECT_URI` tidak perlu diisi di .env karena akan otomatis menggunakan `url('/auth-google-callback')` yang include port
- `APP_URL` harus sesuai dengan URL aplikasi Anda (termasuk port jika ada)

**Penting:**
- `GOOGLE_REDIRECT_URI` harus sama persis dengan yang di Google Cloud Console
- `APP_URL` harus sesuai dengan URL aplikasi Anda
- Untuk production, ganti `http://localhost` dengan domain Anda

### 3. Verifikasi Konfigurasi

Pastikan:
- ✅ `GOOGLE_CLIENT_ID` sudah diisi
- ✅ `GOOGLE_CLIENT_SECRET` sudah diisi
- ✅ `GOOGLE_REDIRECT_URI` sudah diisi dan sama dengan yang di Google Cloud Console
- ✅ `APP_URL` sudah sesuai dengan URL aplikasi

### 4. Clear Cache (jika perlu)

```bash
php artisan config:clear
php artisan cache:clear
```

## Troubleshooting

### Error: "redirect_uri_mismatch"
- Pastikan `GOOGLE_REDIRECT_URI` di `.env` sama persis dengan yang di Google Cloud Console
- Pastikan tidak ada trailing slash yang berbeda
- Pastikan protocol (http/https) sesuai

### Error: "invalid_client"
- Pastikan `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` benar
- Pastikan credentials sudah di-copy dengan benar (tidak ada spasi)

### Error: "access_denied"
- Pastikan OAuth consent screen sudah dikonfigurasi
- Pastikan user yang login sudah ditambahkan sebagai test user (jika app masih dalam testing mode)

