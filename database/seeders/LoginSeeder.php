<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [

            [
                'username' => 'Wildan',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'wildanwhat@gmail.com',
                'no_hp' => '628814370080'
            ],
            [
                'username' => 'WAHYU SANTOSO',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'wahyu.santoso@gmail.com',
                'no_hp' => '6281262225575'
            ],
            [
                'username' => 'Faiz',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'faizdaf123@gmail.com',
                'no_hp' => '6281335202427'
            ],
            [
                'username' => 'Fabi',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'fabianuspriambodo66@gmail.com',
                'no_hp' => '62895410614178'
            ],
            [
                'username' => 'DIMAS NOVIANTO',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'Dimas_Novianto@telkomsel.co.id',
                'no_hp' => '628113400040'
            ],
            [
                'username' => 'YUNIAR ANGGRAHENI',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'anggraheniyuniar@gmail.com',
                'no_hp' => '628112500066'
            ],
            [
                'username' => 'AFIF KAROMI',
                'password' => bcrypt('namiku'),
                'role' => 'admin',
                'can_approve' => '1',
                'email' => 'afifkaromi264@gmail.com',
                'no_hp' => '6281234826786'
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
