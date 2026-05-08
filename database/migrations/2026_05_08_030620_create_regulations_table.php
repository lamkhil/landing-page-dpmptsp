<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('doc_number', 64);
            $table->unsignedSmallInteger('doc_year')->index();
            $table->string('doc_type', 64)->index();   // perwali|perda|kepwali|sk|sop
            $table->string('file_path');
            $table->date('signed_at')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};
