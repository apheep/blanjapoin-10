<?php

if (!function_exists('extractKabupatenKota')) {
    /**
     * Ekstrak kabupaten/kota dari string daerah (bukan kecamatan)
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
            
            // Jika ada lebih dari 1 bagian (ada koma)
            if (count($parts) >= 2) {
                $firstPart = $parts[0];
                $secondPart = $parts[1];
                
                // Cek apakah bagian pertama adalah kecamatan
                if (preg_match('/^Kecamatan\s+/i', $firstPart)) {
                    // Jika bagian pertama adalah kecamatan, ambil bagian kedua (kabupaten/kota)
                    $kabupatenKota = $secondPart;
                } else {
                    // Jika bagian pertama bukan kecamatan, ambil bagian pertama (kabupaten/kota)
                    $kabupatenKota = $firstPart;
                }
                
                // Hapus kata "Kota" atau "Kabupaten" jika ada di awal
                $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', trim($kabupatenKota));
                
                return $kabupatenKota ?: $secondPart; // Fallback ke bagian kedua jika setelah hapus jadi kosong
            }
            
            // Fallback: jika hanya 1 koma, ambil bagian pertama
            $kabupatenKota = trim($parts[0]);
            $kabupatenKota = preg_replace('/^(Kota|Kabupaten)\s+/i', '', $kabupatenKota);
            return $kabupatenKota ?: trim($parts[0]);
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

