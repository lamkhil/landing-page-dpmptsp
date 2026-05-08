<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistic_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statistic_group_id')->constrained()->cascadeOnDelete();
            $table->string('period_type', 16)->index();          // yearly|quarterly|monthly
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedTinyInteger('month')->nullable();    // 1-12 when monthly
            $table->unsignedTinyInteger('quarter')->nullable();  // 1-4 when quarterly
            $table->decimal('value', 18, 4);
            $table->string('label')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['statistic_group_id', 'period_type', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistic_periods');
    }
};
