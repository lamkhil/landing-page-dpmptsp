<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Restructure service_standards to mirror SSW Alfa: a multi-level service tree
 * (parent_id) where each layanan carries its own sections — Persyaratan, Alur
 * Perizinan, Dasar Hukum, Durasi, Kontak, Retribusi, Maklumat, Visi & Misi, Motto.
 * Replaces the earlier flat 14-component layout.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_standards', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'prosedur', 'jangka_waktu', 'biaya', 'produk', 'sarana',
                'kompetensi', 'pengawasan', 'pengaduan', 'jumlah_pelaksana',
                'jaminan_pelayanan', 'jaminan_keamanan', 'evaluasi',
            ]);
        });

        Schema::table('service_standards', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('service_standards')->nullOnDelete();
            $table->text('alur_perizinan')->nullable();
            $table->text('durasi')->nullable();
            $table->text('kontak')->nullable();
            $table->text('retribusi')->nullable();
            $table->text('maklumat')->nullable();
            $table->text('visi_misi')->nullable();
            $table->text('motto')->nullable();
            // persyaratan + dasar_hukum kept from the original table.
        });
    }

    public function down(): void
    {
        Schema::table('service_standards', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn(['alur_perizinan', 'durasi', 'kontak', 'retribusi', 'maklumat', 'visi_misi', 'motto']);
            $table->string('category')->nullable();
            $table->text('prosedur')->nullable();
            $table->text('jangka_waktu')->nullable();
            $table->text('biaya')->nullable();
            $table->text('produk')->nullable();
            $table->text('sarana')->nullable();
            $table->text('kompetensi')->nullable();
            $table->text('pengawasan')->nullable();
            $table->text('pengaduan')->nullable();
            $table->text('jumlah_pelaksana')->nullable();
            $table->text('jaminan_pelayanan')->nullable();
            $table->text('jaminan_keamanan')->nullable();
            $table->text('evaluasi')->nullable();
        });
    }
};
