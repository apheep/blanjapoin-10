<?php

namespace Database\Seeders;

use App\Models\PortalUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PortalUser::updateOrCreate(
            ['email' => 'demo@gmail.com'],
            [
                'name' => 'Merchant Demo',
                'password' => bcrypt('demo123'),
            ]
        );
    }
}
