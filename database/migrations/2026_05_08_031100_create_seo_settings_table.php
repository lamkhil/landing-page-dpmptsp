<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();    // home, profil, layanan, aplikasi, ...
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('keywords')->nullable();
            $table->string('og_image_path')->nullable();
            $table->json('structured_data')->nullable();   // JSON-LD payload (typed via DTO)
            $table->string('robots', 64)->default('index,follow');
            $table->string('canonical_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
