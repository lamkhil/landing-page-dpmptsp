<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per layanan, with the 14 standard service components
        // (UU 25/2009 / PermenpanRB) as dedicated columns.
        Schema::create('service_standards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable()->index();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();

            $table->text('dasar_hukum')->nullable();
            $table->text('persyaratan')->nullable();
            $table->text('prosedur')->nullable();          // Sistem, mekanisme, dan prosedur
            $table->text('jangka_waktu')->nullable();
            $table->text('biaya')->nullable();              // Biaya / tarif
            $table->text('produk')->nullable();             // Produk pelayanan
            $table->text('sarana')->nullable();             // Sarana, prasarana, dan fasilitas
            $table->text('kompetensi')->nullable();         // Kompetensi pelaksana
            $table->text('pengawasan')->nullable();         // Pengawasan internal
            $table->text('pengaduan')->nullable();          // Penanganan pengaduan, saran, dan masukan
            $table->text('jumlah_pelaksana')->nullable();
            $table->text('jaminan_pelayanan')->nullable();
            $table->text('jaminan_keamanan')->nullable();   // Jaminan keamanan & keselamatan
            $table->text('evaluasi')->nullable();           // Evaluasi kinerja pelaksana

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_standards');
    }
};
