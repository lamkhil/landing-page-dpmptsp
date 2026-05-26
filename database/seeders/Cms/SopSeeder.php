<?php

namespace Database\Seeders\Cms;

use App\Domain\Profil\Models\Sop;
use App\Domain\Profil\Models\SopCategory;
use App\Domain\Profil\Models\SopFile;
use Illuminate\Database\Seeder;

/**
 * Seeds SOP categories + example SOP entries for /profil/sop-pelayanan.
 * Files are left empty (file_path = null) — the page shows "segera tersedia"
 * until the admin uploads the actual PDFs via Filament (Profil → SOP Pelayanan).
 * Categories are fully manageable via the Kategori SOP resource.
 */
class SopSeeder extends Seeder
{
    public function run(): void
    {
        SopFile::query()->delete();
        Sop::query()->delete();
        SopCategory::query()->delete();

        $data = [
            ['SOP MPP', 'Standar prosedur layanan di Mal Pelayanan Publik (MPP) Siola.', [
                'SOP Pelayanan di MPP Siola Lantai 3',
                'SOP Antrian dan Pendaftaran Layanan MPP',
            ]],
            ['SOP Pelayanan', 'Prosedur pelayanan perizinan dan non-perizinan terpadu satu pintu.', [
                'SOP Penerbitan Izin Berbasis Risiko (OSS RBA)',
                'SOP Pelayanan Non-Perizinan',
                'SOP Konsultasi dan Pendampingan Perizinan',
            ]],
            ['SOP Difabel', 'Prosedur pelayanan ramah kelompok rentan, lansia, dan difabel.', [
                'SOP Layanan Prioritas Difabel dan Lansia',
            ]],
            ['SOP Pengaduan', 'Prosedur penanganan dan tindak lanjut pengaduan masyarakat.', [
                'SOP Penanganan Pengaduan Masyarakat',
                'SOP Tindak Lanjut Pengaduan SP4N-LAPOR',
            ]],
        ];

        $catSort = 1;
        foreach ($data as [$name, $desc, $titles]) {
            $category = SopCategory::create([
                'name'        => $name,
                'description' => $desc,
                'sort_order'  => $catSort++,
                'is_published'=> true,
            ]);

            $sort = 1;
            foreach ($titles as $title) {
                $sop = Sop::create([
                    'sop_category_id' => $category->id,
                    'title'           => $title,
                    'sort_order'      => $sort++,
                    'is_published'    => true,
                ]);

                // Seed per-year version rows (2024–2026). Files left null —
                // admin uploads the actual PDFs per year via Filament.
                foreach ([2026, 2025, 2024] as $year) {
                    SopFile::create([
                        'sop_id'       => $sop->id,
                        'year'         => $year,
                        'file_path'    => null,
                        'is_published' => true,
                    ]);
                }
            }
        }
    }
}
