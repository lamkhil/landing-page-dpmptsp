<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\Regulation;
use Illuminate\Database\Seeder;

/**
 * Reference documents listed at dpm-ptsp.surabaya.go.id/regulasi.php — these
 * are administrative/strategic planning docs (Renstra, Renja, LKj, RPJMD,
 * Formulir LKPM). Records are created with placeholder file_path; admin
 * uploads the actual PDFs via Filament Media Library.
 */
class RegulationSeeder extends Seeder
{
    public function run(): void
    {
        // Regulasi entries — formal Perda/Perwali. Placeholder doc_number until
        // admin updates with the actual numbers from the source PDFs.
        $regulations = [
            ['title' => 'Peraturan Walikota tentang Penyelenggaraan PTSP', 'doc_number' => 'PERWALI/SBY/PTSP', 'doc_year' => 2023, 'doc_type' => 'perwali'],
            ['title' => 'SK Kepala DPMPTSP tentang Standar Pelayanan',     'doc_number' => 'SK/DPMPTSP/SP',  'doc_year' => 2024, 'doc_type' => 'sk'],
            ['title' => 'SOP Pelayanan Perizinan Berbasis Risiko',         'doc_number' => 'SOP/PTSP-RBA',   'doc_year' => 2024, 'doc_type' => 'sop'],
        ];

        foreach ($regulations as $r) {
            Regulation::updateOrCreate(
                ['doc_number' => $r['doc_number'], 'doc_year' => $r['doc_year']],
                $r + [
                    'file_path'    => 'regulations/placeholder.pdf',  // admin replaces
                    'is_published' => true,
                ]
            );
        }

        // Strategic / planning documents — listed publicly at /regulasi.php
        $documents = [
            ['title' => 'Rencana Strategis (Renstra) DPMPTSP 2021-2026',
             'description' => 'Dokumen perencanaan jangka menengah DPMPTSP Kota Surabaya periode 2021-2026, memuat visi, misi, tujuan, sasaran, strategi, dan program prioritas.'],
            ['title' => 'RPJMD Kota Surabaya 2021-2026',
             'description' => 'Rencana Pembangunan Jangka Menengah Daerah Kota Surabaya 2021-2026 — landasan strategi DPMPTSP.'],
            ['title' => 'Rencana Kerja (Renja) DPMPTSP Tahun 2022',
             'description' => 'Renja tahunan DPMPTSP yang merinci program/kegiatan, output, dan indikator kinerja tahun 2022.'],
            ['title' => 'Rencana Kerja Murni DPMPTSP Tahun 2023',
             'description' => 'Renja murni tahun 2023 sebelum perubahan APBD.'],
            ['title' => 'Perubahan Renja DPMPTSP Tahun 2023',
             'description' => 'Perubahan Renja DPMPTSP Tahun 2023 yang disesuaikan dengan APBD perubahan.'],
            ['title' => 'Laporan Kinerja (LKj) DPMPTSP Tahun 2021',
             'description' => 'Laporan Kinerja Instansi Pemerintah DPMPTSP tahun 2021 — bagian dari akuntabilitas kinerja birokrasi.'],
            ['title' => 'Formulir Laporan Kegiatan Penanaman Modal (LKPM)',
             'description' => 'Template formulir LKPM untuk pelaku usaha yang wajib melaporkan realisasi penanaman modal secara berkala.'],
        ];

        foreach ($documents as $d) {
            Document::updateOrCreate(
                ['title' => $d['title']],
                [
                    'description'    => $d['description'],
                    'file_path'      => 'documents/placeholder.pdf',  // admin replaces
                    'mime'           => 'application/pdf',
                    'size_bytes'     => 0,
                    'is_published'   => true,
                ]
            );
        }
    }
}
