<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Polymorphic pivot: attach Document records to any content
        // (OrgUnit, ProfilPoint, …) for the "Dokumen Terkait" links.
        Schema::create('documentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->morphs('documentable');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['document_id', 'documentable_id', 'documentable_type'], 'documentables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentables');
    }
};
