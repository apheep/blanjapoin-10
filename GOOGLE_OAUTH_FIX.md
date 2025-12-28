# Fix: Register the redirect URI in Google Cloud Console

## Error yang muncul:
```
If you're the app developer, register the redirect URI in the Google Cloud Console.
Request details: redirect_uri=http://127.0.0.1:8000/auth-google-callback
```

## Solusi Cepat:

### Langkah 1: Buka Google Cloud Console
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda
3. Buka **APIs & Services** > **Credentials**
4. Klik pada **OAuth 2.0 Client ID** yang sudah dibuat

### Langkah 2: Tambahkan Redirect URI
Di bagian **Authorized redirect URIs**, klik **+ ADD URI** dan tambahkan **SEMUA** variasi berikut:

```
http://127.0.0.1:8000/auth-google-callback
http://localhost:8000/auth-google-callback
http://127.0.0.1/auth-google-callback
http://localhost/auth-google-callback
```

**PENTING:** 
- Tambahkan **SEMUA** variasi di atas untuk menghindari masalah
- Pastikan tidak ada spasi atau karakter tambahan
- Pastikan menggunakan `http://` bukan `https://` untuk development

### Langkah 3: Save
Klik **SAVE** di bagian bawah halaman

### Langkah 4: Clear Cache Laravel
```bash
php artisan config:clear
php artisan cache:clear
```

### Langkah 5: Test Kembali
Coba login dengan Google lagi. Seharusnya sudah berfungsi.

## Catatan:
- Redirect URI yang digunakan aplikasi adalah: `http://127.0.0.1:8000/auth-google-callback`
- Pastikan redirect URI di Google Cloud Console **SAMA PERSIS** dengan yang digunakan aplikasi
- Jika masih error, cek log di `storage/logs/laravel.log` untuk melihat redirect URI yang sebenarnya digunakan

