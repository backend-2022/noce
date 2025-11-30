<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'تصميم سكني',
                'description' => null,
                'is_active' => true,
            ],
            [
                'name' => 'تصميم تجاري',
                'description' => null,
                'is_active' => true,
            ],
            [
                'name' => 'تصميم مكاتب',
                'description' => null,
                'is_active' => true,
            ],
            [
                'name' => 'استشارة',
                'description' => null,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
