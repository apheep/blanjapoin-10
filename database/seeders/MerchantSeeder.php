<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('merchants')->insert([
            // Tetap mempertahankan 3 merchant pertama
            [
                'no' => 1,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Telkomsel',
                'link_kv_google' => 'https://maps.google.com/example-telkomsel-jakarta',
                'kategori' => 'Telkomsel',
                'poin' => 75000,
                'promo' => 'Paket Data 10GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 2,
                'daerah' => 'Bandung',
                'nama_merchant' => 'Toko Makmur Abadi',
                'link_kv_google' => 'https://maps.google.com/example-toko-makmur-bandung',
                'kategori' => 'Shop',
                'poin' => 50000,
                'promo' => 'Cashback 10% semua produk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 3,
                'daerah' => 'Surabaya',
                'nama_merchant' => 'Restoran Ayam Pedas',
                'link_kv_google' => 'https://maps.google.com/example-ayam-pedas-surabaya',
                'kategori' => 'Food',
                'poin' => 40000,
                'promo' => 'Beli 1 Gratis 1 paket ayam',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Food (sesuai views/merchant/food.blade.php)
            [
                'no' => 4,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Wingstop',
                'link_kv_google' => 'https://maps.google.com/example-wingstop',
                'kategori' => 'Food',
                'poin' => 50000,
                'promo' => 'Diskon 15% menu pilihan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 5,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Pizza Hut',
                'link_kv_google' => 'https://maps.google.com/example-pizzahut',
                'kategori' => 'Food',
                'poin' => 100000,
                'promo' => 'Diskon Rp 100.000 untuk paket keluarga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 6,
                'daerah' => 'Surabaya',
                'nama_merchant' => 'KFC',
                'link_kv_google' => 'https://maps.google.com/example-kfc',
                'kategori' => 'Food',
                'poin' => 60000,
                'promo' => 'Paket hemat ayam goreng',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Beauty & Care (sesuai views/merchant/beautyncare.blade.php)
            [
                'no' => 7,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Sociolla',
                'link_kv_google' => 'https://maps.google.com/example-sociolla',
                'kategori' => 'Beauty & Care',
                'poin' => 85000,
                'promo' => 'Voucher Sociolla Rp 85.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 8,
                'daerah' => 'Bandung',
                'nama_merchant' => 'Guardian',
                'link_kv_google' => 'https://maps.google.com/example-guardian',
                'kategori' => 'Beauty & Care',
                'poin' => 50000,
                'promo' => 'Voucher Guardian Rp 50.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Entertain (sesuai views/merchant/entertain.blade.php)
            [
                'no' => 9,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'CGV Cinema',
                'link_kv_google' => 'https://maps.google.com/example-cgv',
                'kategori' => 'Entertain',
                'poin' => 50000,
                'promo' => 'Voucher nonton CGV Rp 50.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 10,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Timezone',
                'link_kv_google' => 'https://maps.google.com/example-timezone',
                'kategori' => 'Entertain',
                'poin' => 40000,
                'promo' => 'Bonus saldo permainan tambahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Vacation (sesuai views/merchant/vacation.blade.php)
            [
                'no' => 11,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Traveloka',
                'link_kv_google' => 'https://maps.google.com/example-traveloka',
                'kategori' => 'Vacation',
                'poin' => 80000,
                'promo' => 'Voucher Hotel Traveloka Rp 80.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Shop / Retail umum (untuk section Shop)
            [
                'no' => 12,
                'daerah' => 'Jakarta',
                'nama_merchant' => 'Indomaret',
                'link_kv_google' => 'https://maps.google.com/example-indomaret',
                'kategori' => 'Shop',
                'poin' => 30000,
                'promo' => 'Voucher belanja Indomaret Rp 30.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no' => 13,
                'daerah' => 'Bandung',
                'nama_merchant' => 'Alfamart',
                'link_kv_google' => 'https://maps.google.com/example-alfamart',
                'kategori' => 'Shop',
                'poin' => 30000,
                'promo' => 'Voucher belanja Alfamart Rp 30.000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
