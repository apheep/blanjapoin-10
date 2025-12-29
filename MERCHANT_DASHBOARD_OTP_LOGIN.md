# Merchant Dashboard OTP Login

## Overview

Sistem login dashboard merchant telah diubah dari **Google OAuth** menjadi **OTP (One-Time Password)** yang dikirim ke nomor WhatsApp PIC merchant.

## Perubahan Utama

### 1. Logika Login Baru

**Sebelumnya:**
- Login menggunakan Google OAuth
- Merchant harus input `email_pic` saat upload
- Login harus menggunakan email Google yang sesuai dengan `email_pic`
- Jika ada logo diamond, wajib login dengan Google

**Sekarang:**
- Login menggunakan OTP yang dikirim ke nomor WhatsApp
- Menggunakan field `wa_pic` (nomor WhatsApp PIC) yang sudah ada di tabel merchants
- OTP dapat dikirim via WhatsApp, Telegram, atau Email
- Sistem OTP sama dengan yang digunakan untuk login admin

### 2. Akses Admin

Admin dengan `can_approve = 1` tetap dapat mengakses link dashboard tanpa perlu login OTP.

### 3. Flow Login Merchant

1. Merchant mengakses link dashboard: `/dash/{code}`
2. Jika merchant memiliki `wa_pic`:
   - Redirect ke halaman login merchant: `/merchant-login`
   - Merchant memasukkan nomor WhatsApp PIC
   - Merchant memilih metode pengiriman OTP (WhatsApp/Telegram/Email)
   - Klik "Kirim OTP"
   - Masukkan kode OTP 6 digit
   - Klik "Login"
   - Redirect kembali ke link dashboard
3. Jika merchant tidak memiliki `wa_pic`:
   - Langsung akses dashboard tanpa login

## File yang Diubah

### Controllers

**`app/Http/Controllers/PortalAuthController.php`**
- Menghapus semua method Google OAuth (`redirectToGoogle`, `handleGoogleCallback`, `debugGoogleCallback`)
- Menambahkan method `sendOtp()` - untuk mengirim OTP ke nomor WA PIC
- Menambahkan method `authenticate()` - untuk verifikasi OTP dan login
- Menggunakan API yang sama dengan login admin: `https://mynami.id/obc/api/user/code`

### Models

**`app/Models/PortalUser.php`**
- Menambahkan field `wa_pic` ke `$fillable`
- Menambahkan field `merchant_id` ke `$fillable`
- Menambahkan relasi `merchant()` ke model Merchant
- Field `email` sekarang nullable

### Middleware

**`app/Http/Middleware/EnsureMerchantEmailAuth.php`**
- Mengubah validasi dari `email_pic` menjadi `wa_pic`
- Mengubah pengecekan autentikasi dari email ke wa_pic
- Admin dengan `can_approve = 1` tetap bisa bypass authentication

### Views

**`resources/views/portal-login.blade.php`**
- Menghapus tombol "Login dengan Google"
- Menambahkan form input nomor WhatsApp PIC
- Menambahkan pilihan metode pengiriman OTP (WhatsApp/Telegram/Email)
- Menambahkan form input OTP 6 digit
- UI/UX sama dengan halaman login admin

### Routes

**`routes/web.php`**
- Menghapus route Google OAuth:
  - `/auth/redirect`
  - `/auth-google-callback`
  - `/debug/google-callback`
- Menambahkan route OTP:
  - `POST /merchant-send-otp` → `portal.send-otp`
  - `POST /merchant-authenticate` → `portal.authenticate`

### Database

**Migration: `2025_12_29_092452_add_wa_pic_and_merchant_id_to_portal_users_table.php`**
- Menambahkan kolom `wa_pic` (string, nullable, unique)
- Menambahkan kolom `merchant_id` (foreign key ke merchants)
- Mengubah kolom `email` menjadi nullable

## File yang Dihapus

1. `app/Services/UserGoogle.php` - Service Google OAuth
2. `GOOGLE_OAUTH_SETUP.md` - Dokumentasi setup Google OAuth
3. `GOOGLE_OAUTH_FIX.md` - Dokumentasi fix Google OAuth

## API Endpoint yang Digunakan

### Send OTP
```
POST https://mynami.id/obc/api/user/code/{type}
```
- `{type}`: `whatsapp`, `telegram`, atau `emailphone`
- Body: `{ "phone": "628xxxxxxxxxx", "name": "BLANJAPOIN MERCHANT" }`

### Verify OTP
```
POST https://mynami.id/obc/api/user/checkcode
```
- Body: `{ "method": "WA|TELE|EMAIL", "phone": "628xxxxxxxxxx", "otp": "123456" }`

## Cara Testing

### 1. Login Merchant dengan wa_pic

1. Pastikan merchant memiliki `wa_pic` di database
2. Akses link dashboard: `http://localhost/dash/{merchant_code}`
3. Akan redirect ke `/merchant-login`
4. Masukkan nomor WA PIC (format: 8xxxxxxxxxx, tanpa 62)
5. Pilih metode pengiriman OTP
6. Klik "Kirim OTP"
7. Masukkan kode OTP yang diterima
8. Klik "Login"
9. Akan redirect kembali ke dashboard

### 2. Login Merchant tanpa wa_pic

1. Merchant tanpa `wa_pic` dapat langsung akses dashboard tanpa login
2. Akses link dashboard: `http://localhost/dash/{merchant_code}`
3. Langsung masuk ke dashboard

### 3. Login sebagai Admin

1. Login sebagai admin dengan `can_approve = 1`
2. Akses link dashboard merchant: `http://localhost/dash/{merchant_code}`
3. Langsung masuk tanpa perlu login portal

## Session Variables

Portal OTP menggunakan session variables dengan prefix `portal_`:
- `portal_otp_phone` - Nomor HP yang diformat (62xxx)
- `portal_otp_phone_display` - Nomor HP untuk display
- `portal_otp_type` - Tipe OTP (whatsapp/telegram/emailphone)
- `portal_otp_requested_at` - Timestamp request OTP
- `portal_otp_redirect_url` - URL redirect untuk WA/Telegram
- `portal.intended` - URL tujuan setelah login

## Guard Authentication

Portal merchant menggunakan guard `portal`:
- Model: `App\Models\PortalUser`
- Table: `portal_users`
- Login: `Auth::guard('portal')->login($user)`
- Check: `Auth::guard('portal')->check()`
- Logout: `Auth::guard('portal')->logout()`

## Catatan Penting

1. **Backward Compatibility**: Field `email_pic` masih ada di tabel merchants, tapi tidak digunakan untuk autentikasi
2. **OTP Expiry**: OTP berlaku selama 10 menit
3. **OTP Format**: 6 digit angka
4. **Phone Format**: Nomor HP harus format 62xxx (tanpa +)
5. **Admin Access**: Admin dengan `can_approve = 1` tetap bisa akses semua dashboard merchant tanpa login

## Troubleshooting

### OTP tidak terkirim
- Cek koneksi ke API `https://mynami.id/obc/api/user/code`
- Cek format nomor HP (harus 62xxx)
- Cek log di `storage/logs/laravel.log`

### Login gagal setelah OTP valid
- Cek apakah merchant dengan `wa_pic` tersebut ada di database
- Cek session `portal_otp_requested_at` belum expired
- Cek log di `storage/logs/laravel.log`

### Admin tidak bisa bypass login
- Pastikan admin sudah login dengan guard default (bukan portal)
- Pastikan admin memiliki `role = 'admin'` dan `can_approve = 1`

## Migration

Untuk apply perubahan database:
```bash
php artisan migrate
```

Untuk rollback:
```bash
php artisan migrate:rollback
```

