<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistic_counters', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->decimal('value', 18, 2)->default(0);
            $table->string('unit', 32)->nullable();
            $table->string('icon', 64)->nullable();
            $table->string('color', 16)->nullable();
            $table->boolean('is_visible')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistic_counters');
    }
};
