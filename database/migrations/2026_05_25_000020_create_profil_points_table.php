<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_points', function (Blueprint $table) {
            $table->id();
            // Discriminator: visi | misi | fokus | tugas_pokok | fungsi
            $table->string('group', 32)->index();
            $table->string('title')->nullable();
            $table->text('body');
            $table->string('icon', 64)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_points');
    }
};
