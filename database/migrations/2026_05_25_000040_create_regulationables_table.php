<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Polymorphic pivot: attach Regulation records (Perwali, SK, …) to any
        // content (OrgUnit, ProfilPoint, …) as the "Dasar Hukum".
        Schema::create('regulationables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')->constrained()->cascadeOnDelete();
            $table->morphs('regulationable');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['regulation_id', 'regulationable_id', 'regulationable_type'], 'regulationables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulationables');
    }
};
