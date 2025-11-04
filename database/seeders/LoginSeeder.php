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
                'username' => 'admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin'
            ],

            [
                'username' => 'user',
                'password' => bcrypt('user123'),
                'role' => 'user'
            ]
        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
