<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistic_groups', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // pma, pmdn, izin, sla, ikm
            $table->string('label');
            $table->string('unit', 32)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistic_groups');
    }
};
