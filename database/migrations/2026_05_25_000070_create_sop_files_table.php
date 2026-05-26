<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Per-year version of a SOP document. One SOP can have 2024/2025/2026…
        Schema::create('sop_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sop_id')->constrained('sops')->cascadeOnDelete();
            $table->unsignedSmallInteger('year')->index();
            $table->string('file_path')->nullable(); // uploaded later via CMS
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        // SOP versions now live in sop_files; drop the single-file column.
        Schema::table('sops', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('sops', function (Blueprint $table) {
            $table->string('file_path')->nullable();
        });
        Schema::dropIfExists('sop_files');
    }
};
