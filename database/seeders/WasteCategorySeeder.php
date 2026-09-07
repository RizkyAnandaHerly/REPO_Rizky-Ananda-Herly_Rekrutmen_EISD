<?php

namespace Database\Seeders;

use App\Models\WasteCategory;
use Illuminate\Database\Seeder;

class WasteCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'PET Plastic',
                'unit' => 'kg',
                'points_per_unit' => 2000,
                'description' => 'Botol dan kemasan plastik PET',
            ],
            [
                'name' => 'Kardus',
                'unit' => 'kg',
                'points_per_unit' => 1500,
                'description' => 'Kardus dan kertas karton',
            ],
            [
                'name' => 'Kaleng Aluminium',
                'unit' => 'kg',
                'points_per_unit' => 8000,
                'description' => 'Kaleng minuman dan aluminium daur ulang',
            ],
            [
                'name' => 'Minyak Jelantah',
                'unit' => 'liter',
                'points_per_unit' => 3000,
                'description' => 'Minyak goreng bekas pakai',
            ],
        ];

        foreach ($categories as $category) {
            WasteCategory::create($category);
        }
    }
}
