<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

            /*User::create([
        'name' => 'Super Admin',
        'email' => 'admin@admin.com',
        'phone' => '0934580181',
        'password' => Hash::make('admin123'),
        'NID' => '1234567890',
        'role_id' => 1,          // admin
        'department_id' => null, // الأدمن لا يتبع جهة حكومية
    ]);*/
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            GovSeeder::class
        ]);

}
}
