<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => 'جدة',
                'is_active' => true,
            ],
            [
                'name' => 'الرياض',
                'is_active' => true,
            ],
            [
                'name' => 'القصيم',
                'is_active' => true,
            ],
            [
                'name' => 'المدينة المنورة',
                'is_active' => true,
            ],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name' => $city['name']],
                $city
            );
        }
    }
}
