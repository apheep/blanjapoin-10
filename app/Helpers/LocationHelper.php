<?php

if (!function_exists('extractKabupatenKota')) {
    /**
     * Ekstrak kabupaten/kota dari string daerah (bukan kecamatan)
     * 
     * Format yang didukung:
     * - "Beji, Kota Depok, Jawa Barat" -> "Depok"
     * - "Kecamatan Beji, Kota Depok, Jawa Barat" -> "Depok"
     * - "Kota Depok, Jawa Barat" -> "Depok"
     * - "Depok, Jawa Barat" -> "Depok"
     * 
     * @param string|null $daerah
     * @return string
     */
    function extractKabupatenKota($daerah) {
        if (empty($daerah)) return '';
        
        $daerah = trim($daerah);
        
        // Jika ada koma, parse bagian-bagiannya
        if (strpos($daerah, ',') !== false) {
            $parts = array_map('trim', explode(',', $daerah));
            $partsCount = count($parts);
            
            // Jika ada 3 bagian atau lebih: format biasanya "Kecamatan, Kabupaten/Kota, Provinsi"
            // Ambil bagian kedua (index 1) yang biasanya adalah kabupaten/kota
            if ($partsCount >= 3) {
                $kabupatenKota = $parts[1]; // Ambil bagian kedua
            }
            // Jika ada 2 bagian
            else if ($partsCount == 2) {
                $firstPart = $parts[0];
                $secondPart = $parts[1];
                
                // Cek apakah bagian pertama atau kedua mengandung "Kota" atau "Kabupaten"
                $firstHasKotaKabupaten = preg_match('/^(Kota|Kabupaten)\s+/i', $firstPart);
                $secondHasKotaKabupaten = preg_match('/^(Kota|Kabupaten)\s+/i', $secondPart);
                
                if ($firstHasKotaKabupaten) {
                    // Jika bagian pertama mengandung "Kota" atau "Kabupaten", ambil bagian pertama
                    $kabupatenKota = $firstPart;
                } else if ($secondHasKotaKabupaten) {
                    // Jika bagian kedua mengandung "Kota" atau "Kabupaten", ambil bagian kedua
                    // (bagian pertama kemungkinan besar adalah kecamatan)
                    $kabupatenKota = $secondPart;
                } else if (preg_match('/^Kecamatan\s+/i', $firstPart)) {
                    // Jika bagian pertama dimulai dengan "Kecamatan", ambil bagian kedua
                    $kabupatenKota = $secondPart;
                } else {
                    // Jika tidak ada yang jelas, asumsikan bagian pertama adalah kecamatan
                    // Ambil bagian kedua sebagai kabupaten/kota
                    $kabupatenKota = $secondPart;
                }
            }
            // Jika hanya 1 bagian (tidak mungkin, tapi untuk safety)
            else {
                $kabupatenKota = trim($parts[0]);
            }
            
            // Hapus kata "Kota" atau "Kabupaten" jika ada di awal
            $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($kabupatenKota));
            
            return $kabupatenKota ?: '';
        }
        
        // Jika tidak ada koma, cek apakah ada kata "Kota" atau "Kabupaten"
        if (preg_match('/^(?:Kota|Kabupaten)\s+(.+)$/i', $daerah, $matches)) {
            return trim($matches[1]);
        }
        
        // Cek apakah dimulai dengan "Kecamatan", jika ya skip (karena kita tidak mau kecamatan)
        if (preg_match('/^Kecamatan\s+/i', $daerah)) {
            return ''; // Skip kecamatan
        }
        
        // Jika tidak ada format khusus, gunakan seluruhnya
        return $daerah;
    }
}

if (!function_exists('formatDiskon')) {
    /**
     * Format diskon untuk ditampilkan di UI
     * Jika diskon adalah "100%" atau "FREE", tampilkan sebagai "FREE"
     * 
     * @param string|null $diskon
     * @return string
     */
    function formatDiskon($diskon) {
        if (empty($diskon)) return '';
        
        $diskon = trim($diskon);
        
        // Jika diskon adalah "100%" atau "FREE", tampilkan sebagai "FREE"
        if ($diskon === '100%' || $diskon === 'FREE' || $diskon === 'free') {
            return 'FREE';
        }
        
        // Jika tidak, tampilkan sesuai aslinya
        return $diskon;
    }
}

