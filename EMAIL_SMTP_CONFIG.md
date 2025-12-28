# Konfigurasi Email SMTP untuk Login OTP

## Cara Konfigurasi

Tambahkan konfigurasi berikut ke file `.env` di root project:

### 1. Gmail SMTP (Recommended untuk Development)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan untuk Gmail:**
- Anda perlu membuat **App Password** (bukan password biasa)
- Cara membuat App Password:
  1. Buka https://myaccount.google.com/security
  2. Aktifkan 2-Step Verification
  3. Buat App Password: https://myaccount.google.com/apppasswords
  4. Pilih "Mail" dan "Other (Custom name)"
  5. Copy password yang dihasilkan (16 karakter tanpa spasi)

### 2. Outlook/Hotmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@outlook.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Yahoo Mail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@yahoo.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Mailtrap (Untuk Testing/Development)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@blanjapoin.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Cara mendapatkan Mailtrap:**
1. Daftar di https://mailtrap.io
2. Pilih "Email Testing" > "Inboxes"
3. Copy SMTP credentials

### 5. SMTP Server Sendiri (cPanel/WHM)

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Contoh Konfigurasi Lengkap di .env

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@blanjapoin.com
MAIL_PASSWORD=your-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@blanjapoin.com
MAIL_FROM_NAME="BlanjaPoin"
```

## Testing Konfigurasi

Setelah mengkonfigurasi, test dengan:

1. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

2. **Test kirim email:**
   - Coba login dengan nomor HP yang sudah terdaftar
   - Klik "Kirim OTP"
   - Cek email inbox (atau spam folder)

## Troubleshooting

### Email tidak terkirim?

1. **Cek log Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Pastikan konfigurasi benar:**
   - Username dan password benar
   - Port sesuai (587 untuk TLS, 465 untuk SSL)
   - Encryption sesuai (tls atau ssl)

3. **Untuk Gmail:**
   - Pastikan menggunakan App Password, bukan password biasa
   - Pastikan 2-Step Verification sudah aktif

4. **Cek firewall/antivirus:**
   - Beberapa firewall memblokir koneksi SMTP

### Port yang Umum Digunakan:

- **587** - TLS (Recommended)
- **465** - SSL
- **25** - Non-encrypted (tidak disarankan)

### Encryption:

- **tls** - Untuk port 587
- **ssl** - Untuk port 465
- **null** - Untuk port 25 (tidak disarankan)

## Alternatif: Menggunakan Mail Driver Lain

Jika SMTP bermasalah, bisa menggunakan:

### 1. Log Driver (untuk testing)
```env
MAIL_MAILER=log
```
Email akan disimpan di `storage/logs/laravel.log`

### 2. Array Driver (untuk testing)
```env
MAIL_MAILER=array
```
Email tidak benar-benar dikirim, hanya disimpan di memory

