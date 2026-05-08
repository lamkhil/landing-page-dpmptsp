<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // ikm-2026, layanan-perizinan-q1, ...
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('schema')->nullable();   // typed via DTO at app layer; defines questions
            $table->boolean('is_active')->default(false)->index();
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
