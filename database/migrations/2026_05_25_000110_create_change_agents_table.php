<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_agents', function (Blueprint $table) {
            $table->id();
            // The ZI area perubahan this agent serves. Nullable so an agent may
            // exist unassigned; detach (not delete) the agent if the area is removed.
            $table->foreignId('profil_point_id')->nullable()
                ->constrained('profil_points')->nullOnDelete();
            $table->string('name');                   // nama
            $table->string('nip', 32)->nullable();    // NIK / NIP
            $table->string('position')->nullable();   // jabatan
            $table->string('role', 64)->nullable();   // peran tim ZI (Ketua/Koordinator/Anggota)
            $table->string('photo_path')->nullable(); // foto
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_agents');
    }
};
