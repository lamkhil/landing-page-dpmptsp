<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('region_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('category', 64)->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('popup_html')->nullable();
            $table->string('icon', 64)->nullable();
            $table->boolean('is_visible')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_markers');
    }
};
