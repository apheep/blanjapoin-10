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

if (!function_exists('territorialSlug')) {
    /**
     * Generate slug untuk URL teritorial
     * Mengubah nama kota/kabupaten menjadi format URL-friendly
     * 
     * Contoh:
     * - "Kota Surabaya" -> "surabaya"
     * - "Kota Depok" -> "depok"
     * - "Kabupaten Bandung" -> "bandung"
     * - "Surabaya" -> "surabaya"
     * 
     * @param string|null $location
     * @return string
     */
    function territorialSlug($location) {
        if (empty($location)) return '';
        
        $location = trim($location);
        
        // Hapus prefix "Kota" atau "Kabupaten"
        $location = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $location);
        
        // Convert ke lowercase
        $location = strtolower($location);
        
        // Replace spasi dengan dash
        $location = str_replace(' ', '-', $location);
        
        // Remove special characters, keep only alphanumeric and dash
        $location = preg_replace('/[^a-z0-9\-]/', '', $location);
        
        // Remove multiple dashes
        $location = preg_replace('/-+/', '-', $location);
        
        // Trim dashes from start and end
        $location = trim($location, '-');
        
        return $location;
    }
}

if (!function_exists('territorialName')) {
    /**
     * Convert slug kembali ke nama teritorial yang readable
     * 
     * Contoh:
     * - "surabaya" -> "Surabaya"
     * - "kota-depok" -> "Kota Depok"
     * 
     * @param string|null $slug
     * @return string
     */
    function territorialName($slug) {
        if (empty($slug)) return '';
        
        // Replace dash dengan spasi
        $name = str_replace('-', ' ', $slug);
        
        // Capitalize first letter of each word
        $name = ucwords($name);
        
        return $name;
    }
}

if (!function_exists('territorialSlugGeneric')) {
    /**
     * Generate slug untuk URL teritorial (generic untuk cluster, branch, regional)
     * Mengubah nama menjadi format URL-friendly
     * 
     * @param string|null $location
     * @return string
     */
    function territorialSlugGeneric($location) {
        if (empty($location)) return '';
        
        $location = trim($location);
        
        // Convert ke lowercase
        $location = strtolower($location);
        
        // Replace spasi dengan dash
        $location = str_replace(' ', '-', $location);
        
        // Remove special characters, keep only alphanumeric and dash
        $location = preg_replace('/[^a-z0-9\-]/', '', $location);
        
        // Remove multiple dashes
        $location = preg_replace('/-+/', '-', $location);
        
        // Trim dashes from start and end
        $location = trim($location, '-');
        
        return $location;
    }
}

if (!function_exists('territorialNameGeneric')) {
    /**
     * Convert slug kembali ke nama teritorial yang readable (generic)
     * 
     * @param string|null $slug
     * @return string
     */
    function territorialNameGeneric($slug) {
        if (empty($slug)) return '';
        
        // Replace dash dengan spasi
        $name = str_replace('-', ' ', $slug);
        
        // Capitalize first letter of each word
        $name = ucwords($name);
        
        return $name;
    }
}

if (!function_exists('normalizeCityName')) {
    /**
     * Normalisasi nama kota untuk matching (toleransi prefix Kota/Kabupaten)
     * Menghapus prefix "Kota" atau "Kabupaten" dan trim untuk matching yang lebih toleran
     * 
     * Contoh:
     * - "Kota Blitar" -> "Blitar"
     * - "Kabupaten Blitar" -> "Blitar"
     * - "Blitar" -> "Blitar"
     * 
     * @param string|null $cityName
     * @return string
     */
    function normalizeCityName($cityName) {
        if (empty($cityName)) return '';
        
        $cityName = trim($cityName);
        
        // Hapus prefix "Kota" atau "Kabupaten" jika ada
        $cityName = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $cityName);
        
        return trim($cityName);
    }
}

if (!function_exists('getRegionalNameFromAlias')) {
    /**
     * Convert alias regional ke nama regional yang sebenarnya
     * Mapping alias untuk URL yang lebih pendek
     * 
     * Contoh:
     * - "balnus" atau "bali-nusra" -> "Bali Nusra"
     * - "jatengdiy" atau "jateng-diy" -> "Jateng DIY"
     * - "jatim" -> "Jatim"
     * 
     * @param string|null $alias
     * @return string
     */
    function getRegionalNameFromAlias($alias) {
        if (empty($alias)) return '';
        
        $alias = strtolower(trim($alias));
        
        // Mapping alias ke nama regional
        $regionalAliases = [
            // Bali Nusra
            'balnus' => 'Bali Nusra',
            'bali-nusra' => 'Bali Nusra',
            'balinusra' => 'Bali Nusra',
            'bali nusra' => 'Bali Nusra',
            
            // Jateng DIY
            'jatengdiy' => 'Jateng DIY',
            'jateng-diy' => 'Jateng DIY',
            'jateng diy' => 'Jateng DIY',
            
            // Jatim (tetap sama)
            'jatim' => 'Jatim',
            'jawa timur' => 'Jatim',
        ];
        
        // Cek apakah ada mapping untuk alias ini
        if (isset($regionalAliases[$alias])) {
            return $regionalAliases[$alias];
        }
        
        // Jika tidak ada mapping, convert slug ke readable name (untuk backward compatibility)
        return territorialNameGeneric($alias);
    }
}

