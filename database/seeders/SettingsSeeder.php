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
                'value' => 'NOCE - نوتش',
            ],
            [
                'key' => 'promotional_title',
                'value' => 'صمم مساحتك معانا',
            ],
            [
                'key' => 'description',
                'value' => 'نحول أحلامك إلى واقع من خلال تصميمات داخلية مبتكرة وعصرية',
            ],
            [
                'key' => 'logo',
                'value' => null,
            ],
            [
                'key' => 'home_banner',
                'value' => '4b0065eb-0c8d-4724-bb21-964abeca1e30.webp',
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
            [
                'key' => 'keep_backups',
                'value' => '0',
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
