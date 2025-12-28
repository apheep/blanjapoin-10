# Panduan Ukuran Gambar untuk Upload

Dokumen ini menjelaskan ukuran gambar yang optimal untuk setiap jenis upload di aplikasi BlanjaPoin.

## 📋 Ringkasan Cepat

| Jenis Gambar | Ukuran Optimal | Format | Max File Size | Aspect Ratio |
|-------------|----------------|--------|---------------|--------------|
| **Banner Promo (Iklan)** | 1920x640px | JPG, PNG, WebP | 2 MB | 3:1 (Landscape Wide) |
| **Logo Merchant** | 512x512px | PNG (transparan), JPG, WebP | 2 MB | 1:1 (Square) |
| **Promo Keywords** | 1200x600px | JPG, PNG, WebP | 2 MB | 2:1 (Landscape) |

---

## 1. 🎨 Banner Promo (Iklan)

### Detail Teknis
- **Ukuran Optimal**: **1920x640px** (atau 1440x480px untuk lebih ringan)
- **Format**: JPG, PNG, WebP
- **Max File Size**: 2 MB (2048 KB)
- **Aspect Ratio**: **3:1** (Landscape Wide) - **Rasio yang tepat untuk banner promo**
- **Background**: Bisa transparan (PNG) atau solid color

### Dimensi Tampilan
- Mobile: 224px tinggi (h-56) - Width: ~672px (rasio 3:1)
- Small: 256px tinggi (h-64) - Width: ~768px (rasio 3:1)
- Medium: 320px tinggi (h-80) - Width: ~960px (rasio 3:1)
- Large: 400px tinggi (h-100) - Width: ~1200px (rasio 3:1)
- Max Width: 1152px (max-w-6xl) dengan object-cover

### Tips Desain
- ✅ Gunakan resolusi tinggi untuk tampilan tajam di semua device
- ✅ Pastikan teks dan elemen penting berada di tengah (akan di-crop)
- ✅ Hindari elemen penting di pinggir kiri/kanan (akan terpotong di mobile)
- ✅ Gunakan warna kontras tinggi untuk teks
- ✅ Optimalkan file size dengan kompresi (target < 500KB untuk loading cepat)
- ✅ **Rasio 3:1** adalah standar untuk banner promo web

### Contoh Ukuran Alternatif
- **1920x640px** (Recommended) - Balance antara kualitas dan file size, rasio 3:1
- **1440x480px** - Lebih ringan, tetap rasio 3:1
- **1200x400px** - Alternatif untuk device lebih kecil, rasio 3:1
- **1600x533px** - Alternatif dengan rasio mendekati 3:1

---

## 2. 🏢 Logo Merchant

### Detail Teknis
- **Ukuran Optimal**: **512x512px** (atau 256x256px minimum)
- **Format**: **PNG dengan background transparan** (Recommended), JPG, WebP
- **Max File Size**: 2 MB (2048 KB)
- **Aspect Ratio**: **1:1** (Square)
- **Background**: Transparan (PNG) atau solid color

### Dimensi Tampilan
- Mobile: 48x48px (w-12 h-12)
- Desktop: 64x64px (w-16 h-16)
- Display: object-contain (mempertahankan aspect ratio)

### Tips Desain
- ✅ Gunakan PNG dengan background transparan untuk fleksibilitas
- ✅ Pastikan logo jelas dan terbaca di ukuran kecil (48px)
- ✅ Hindari teks terlalu kecil yang tidak terbaca saat di-resize
- ✅ Gunakan warna kontras tinggi
- ✅ Simpan logo dalam format vector (SVG) jika memungkinkan, lalu export ke PNG 512x512px
- ✅ Pastikan logo berada di tengah canvas dengan padding yang cukup

### Contoh Ukuran Alternatif
- **512x512px** (Recommended) - Kualitas tinggi untuk semua penggunaan
- **256x256px** - Minimum untuk kualitas baik
- **1024x1024px** - Untuk logo dengan detail halus (file size lebih besar)

---

## 3. 🎁 Promo Keywords (Gambar Produk/Promo)

### Detail Teknis
- **Ukuran Optimal**: **1200x600px** (Desktop) atau **800x600px** (Mobile)
- **Format**: JPG, PNG, WebP
- **Max File Size**: 2 MB (2048 KB)
- **Aspect Ratio**: **2:1** (Desktop) atau **4:3** (Mobile)
- **Background**: Bisa transparan atau solid color

### Dimensi Tampilan
- **Mobile**: 
  - Aspect ratio: 4:3 (aspect-[4/3])
  - Height: ~280px (min-h-[280px])
- **Desktop**: 
  - Aspect ratio: 2:1 (aspect-[10/5])
  - Height: ~160px (h-40)
- Display: object-cover (akan di-crop untuk fit container)

### Tips Desain
- ✅ Gunakan resolusi tinggi untuk tampilan tajam
- ✅ Pastikan elemen penting (produk, teks) berada di tengah
- ✅ Hindari elemen penting di pinggir (akan di-crop)
- ✅ Gunakan warna kontras tinggi
- ✅ Optimalkan file size dengan kompresi (target < 500KB)
- ✅ Pertimbangkan tampilan mobile (4:3) dan desktop (2:1)

### Contoh Ukuran Alternatif
- **1200x600px** (Recommended Desktop) - Balance kualitas dan file size
- **800x600px** (Recommended Mobile) - Cocok untuk tampilan mobile
- **1600x800px** - Untuk gambar dengan detail tinggi
- **1000x500px** - Alternatif lebih ringan

### Catatan Penting
- Gambar akan di-crop secara otomatis untuk fit aspect ratio
- Pastikan elemen penting berada di area tengah gambar
- Test tampilan di mobile dan desktop sebelum upload

---

## 📐 Rekomendasi Umum

### Kompresi Gambar
Sebelum upload, kompres gambar untuk mengurangi file size:
- **JPG**: Gunakan quality 80-85% (balance antara kualitas dan size)
- **PNG**: Gunakan tool seperti TinyPNG atau ImageOptim
- **WebP**: Format modern dengan kompresi lebih baik (Recommended jika browser support)

### Tools yang Direkomendasikan
1. **Image Compression**:
   - [TinyPNG](https://tinypng.com/) - Kompres PNG/JPG
   - [Squoosh](https://squoosh.app/) - Kompres dengan kontrol penuh
   - [ImageOptim](https://imageoptim.com/) - Desktop app untuk Mac

2. **Image Resizing**:
   - [Canva](https://www.canva.com/) - Online design tool
   - [Photopea](https://www.photopea.com/) - Free Photoshop alternative
   - [GIMP](https://www.gimp.org/) - Free image editor

### Best Practices
1. ✅ **Selalu optimalkan gambar** sebelum upload untuk loading cepat
2. ✅ **Gunakan format yang tepat**: PNG untuk logo (transparan), JPG untuk foto
3. ✅ **Test tampilan** di berbagai device sebelum publish
4. ✅ **Gunakan WebP** jika memungkinkan (format modern, lebih kecil)
5. ✅ **Hindari gambar terlalu besar** (max 2MB, target < 500KB)

---

## 🔍 Validasi di Backend

Saat ini, validasi yang diterapkan di aplikasi:

### Banner Promo (Iklan)
```php
'image' => ['required', 'image', 'max:2048']
```

### Logo Merchant
```php
'logo_merchant' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
```

### Promo Keywords
```php
'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
```

**Catatan**: Validasi dimensi (width/height) belum diterapkan. Disarankan untuk menambahkan validasi dimensi di masa depan untuk memastikan kualitas gambar konsisten.

---

## 📝 Checklist Sebelum Upload

- [ ] Gambar sudah di-resize ke ukuran optimal
- [ ] File size < 2MB (target < 500KB)
- [ ] Format file sesuai (JPG, PNG, atau WebP)
- [ ] Aspect ratio sesuai dengan rekomendasi
- [ ] Gambar sudah di-kompres untuk optimasi
- [ ] Test tampilan di mobile dan desktop
- [ ] Elemen penting berada di tengah gambar

---

## 🆘 Troubleshooting

### Masalah: Gambar terlihat blur
**Solusi**: 
- Pastikan menggunakan resolusi tinggi (minimal sesuai rekomendasi)
- Hindari upscale gambar kecil ke ukuran besar
- Gunakan format lossless (PNG) untuk logo

### Masalah: File size terlalu besar
**Solusi**:
- Kompres gambar menggunakan tool seperti TinyPNG
- Kurangi quality JPG ke 80-85%
- Pertimbangkan menggunakan WebP format

### Masalah: Gambar ter-crop tidak sesuai
**Solusi**:
- Pastikan aspect ratio sesuai rekomendasi
- Letakkan elemen penting di tengah gambar
- Test tampilan sebelum publish

---

**Terakhir Diupdate**: Desember 2025

