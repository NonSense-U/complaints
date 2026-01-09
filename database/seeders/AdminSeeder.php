<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::Create(
            [
                'email' => 'admin@example.com',
                'name' => 'System Admin',
                'phone' => '0934580181',
                'NID' => '123456789',
                'password' => 'Admin123',
                'role_id' => 1,
                'gov_id' => null,
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
