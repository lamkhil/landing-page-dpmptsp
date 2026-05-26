<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sop_category_id')->nullable()->constrained('sop_categories')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('doc_number', 100)->nullable();
            $table->string('file_path')->nullable(); // downloadable PDF; admin uploads later
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sops');
    }
};
