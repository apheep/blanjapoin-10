<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $merchants = [
            // ================== denpasar ==================
            [
                'daerah'        => 'denpasar',
                'nama_merchant' => 'CLIP N CLIMB BALI',
                'kategori'      => 'liburan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'denpasar',
                'nama_merchant' => 'ETERNA AESTHETIC & ANTI AGING CLINIC',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'denpasar',
                'nama_merchant' => 'LABORATORIM PRODIA SINGARAJA BALI',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'denpasar',
                'nama_merchant' => 'LABORATORIM PRODIA TABANAN BALI',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'denpasar',
                'nama_merchant' => 'MIRACLE ULTIMATE DENPASAR',
                'kategori'      => 'Kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // ================== kupang ==================
            [
                'daerah'        => 'kupang',
                'nama_merchant' => 'Masterpiece',
                'kategori'      => 'hiburan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'kupang',
                'nama_merchant' => 'la Moringa',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'kupang',
                'nama_merchant' => 'Nakamura Kupang',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'kupang',
                'nama_merchant' => 'Relaxology Kupang',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'kupang',
                'nama_merchant' => 'Dapur Solokoe',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // ================== mataram ==================
            [
                'daerah'        => 'mataram',
                'nama_merchant' => 'JELAJAH COFFEE',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'mataram',
                'nama_merchant' => 'B CLINIC',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'mataram',
                'nama_merchant' => 'LOMBOK WILDLIFE PARK',
                'kategori'      => 'liburan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'mataram',
                'nama_merchant' => 'DAPUR SAYUR',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'mataram',
                'nama_merchant' => 'SALOME HAIR & BEAUTY',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // ================== flores ==================
            [
                'daerah'        => 'flores',
                'nama_merchant' => 'KOPI DENG SAPA',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'flores',
                'nama_merchant' => 'CAFE HM CAFE & RESTO',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'flores',
                'nama_merchant' => 'SIBAKLOANG GALLERY COFFE',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'flores',
                'nama_merchant' => 'CAFE TERAS LANGIT',
                'kategori'      => 'kuliner',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'daerah'        => 'flores',
                'nama_merchant' => 'APOTIK TIARA FARM',
                'kategori'      => 'kecantikan',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        DB::table('merchants')->insert($merchants);
    }
}
