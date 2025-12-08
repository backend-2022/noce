<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $seoSettings = [
            [
                'key' => 'meta_title',
                'value' => null,
            ],
            [
                'key' => 'meta_description',
                'value' => null,
            ],
            [
                'key' => 'meta_keywords',
                'value' => null,
            ],
        ];

        foreach ($seoSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['meta_title', 'meta_description', 'meta_keywords'])->delete();
    }
};
