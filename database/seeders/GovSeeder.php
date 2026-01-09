<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gov;

class GovSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $govs = [
            'شركة الكهرباء',
            'مؤسسة المياه',
            'البلدية',
            'وزارة الأشغال',
            'وزارة الصحة',
            'وزارة التجارة',
            'الشرطة',
            'شركة الاتصالات',
        ];
         foreach ($govs as $gov) {
            Gov::firstOrCreate(['name' => $gov]);
        }
    }
}