<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{

    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => ' NOCE - نوتش ',
            ],
            [
                'key' => 'promotional_title',
                'value' => 'نظام إدارة الجداول والمواعيد',
            ],
            [
                'key' => 'description',
                'value' => 'جدولها هو نظام متطور لإدارة الجداول والمواعيد، مصمم لتبسيط عملية تنظيم الوقت وإدارة المواعيد بكفاءة عالية.',
            ],

            // Social Media Settings
            [
                'key' => 'facebook',
                'value' => 'https://facebook.com',
            ],
            [
                'key' => 'x',
                'value' => 'https://x.com',
            ],
            [
                'key' => 'instagram',
                'value' => 'https://instagram.com',
            ],
            [
                'key' => 'snapchat',
                'value' => 'https://snapchat.com',
            ],
            [
                'key' => 'whatsapp',
                'value' => '5xxxxxxxx',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
