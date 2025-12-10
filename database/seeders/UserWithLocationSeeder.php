<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserWithLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data dari tabel users
        DB::table('users')->delete();

        $userData = [
            [
                'username' => 'Wildan',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'wildanwhat@gmail.com',
                'no_hp' => '628814370080',
                'user_level' => 'AREA',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'AREA 3',
            ],
            [
                'username' => 'WAHYU SANTOSO',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'wahyu.santoso@gmail.com',
                'no_hp' => '6281262225575',
                'user_level' => 'NATIONAL',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'NATIONAL',
            ],
            [
                'username' => 'Faiz',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'faizdaf123@gmail.com',
                'no_hp' => '6281335202427',
                'user_level' => 'AREA',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'AREA 3',
                'area_level' => 'AREA 3',
            ],
            [
                'username' => 'Fabi',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'fabianuspriambodo66@gmail.com',
                'no_hp' => '62895410614178',
                'user_level' => 'AREA',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'AREA 3',
            ],
            [
                'username' => 'DIMAS NOVIANTO',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'Dimas_Novianto@telkomsel.co.id',
                'no_hp' => '628113400040',
                'user_level' => 'NATIONAL',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'NATIONAL',
            ],
            [
                'username' => 'YUNIAR ANGGRAHENI',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'anggraheniyuniar@gmail.com',
                'no_hp' => '628112500066',
                'user_level' => 'AREA',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'AREA 3',
            ],
            [
                'username' => 'AFIF KAROMI',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'afifkaromi264@gmail.com',
                'no_hp' => '6281234826786',
                'user_level' => 'AREA',
                'area' => 'JAWA - BALI - NUSRA',
                'regional' => 'JATIM',
                'branch' => 'SURABAYA',
                'sub_branch' => 'KOTA SURABAYA',
                'cluster' => 'KOTA SURABAYA',
                'city' => 'KOTA SURABAYA',
                'area_level' => 'AREA 3',
            ],
        ];

        foreach ($userData as $key => $val) {
            User::updateOrCreate(
                ['username' => $val['username']],
                $val
            );
        }
    }
}
