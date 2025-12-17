<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utms', function (Blueprint $table) {
            $table->id();
            $table->string('utm_source', 255)->nullable();
            $table->string('utm_medium', 255)->nullable();
            $table->string('utm_campaign', 255)->nullable();
            $table->string('utm_id', 255)->nullable();
            $table->string('utm_ads_set_id', 255)->nullable();
            $table->string('utm_ads_set_name', 255)->nullable();
            $table->string('ad_name', 255)->nullable();
            $table->string('ad_id', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utms');
    }
};
