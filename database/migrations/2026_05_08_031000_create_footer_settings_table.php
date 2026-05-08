<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Singleton table: footer settings live in row id=1, edited via Filament Settings page.
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('phone', 64)->nullable();
            $table->string('email')->nullable();
            $table->string('office_hours')->nullable();
            $table->text('embed_map_url')->nullable();
            $table->json('social_links')->nullable();   // typed: [{platform, url}]
            $table->text('about_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
