<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'home_banner'],
            ['value' => '4b0065eb-0c8d-4724-bb21-964abeca1e30.webp', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'home_banner')->delete();
    }
};
