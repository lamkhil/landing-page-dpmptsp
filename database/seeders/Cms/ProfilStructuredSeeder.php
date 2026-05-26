<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\Regulation;
use App\Domain\Profil\Models\ChangeAgent;
use App\Domain\Profil\Models\OrgUnit;
use App\Domain\Profil\Models\ProfilPoint;
use App\Domain\Profil\Models\ProfilPointDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the STRUCTURED profil content (org_units + profil_points) consumed by
 * ProfilController for the visi-misi, struktur, and tugas-fungsi pages, and
 * links each to the relevant Regulation / Document records.
 *
 * Runs AFTER RegulationSeeder so the Perwali / Renstra / RPJMD records exist.
 * Everything here is fully CRUD-able afterwards via Filament (Profil group +
 * Dokumen & Regulasi group).
 */
class ProfilStructuredSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent reset. Child rows of profil_points first (FK order).
        DB::table('documentables')->delete();
        DB::table('regulationables')->delete();
        ChangeAgent::query()->delete();
        ProfilPointDetail::query()->delete();
        OrgUnit::query()->delete();
        ProfilPoint::query()->delete();

        $perwali = Regulation::query()->where('doc_type', 'perwali')->first();
        $renstra = Document::query()->where('title', 'like', 'Rencana Strategis%')->first();
        $rpjmd   = Document::query()->where('title', 'like', 'RPJMD%')->first();
        $planDocs = array_values(array_filter([$renstra?->id, $rpjmd?->id]));

        $this->command?->info('  ↻ seeding structured profil content (visi/misi/fokus, tugas/fungsi, struktur)');

        // ─────────────── VISI ───────────────
        $visi = ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_VISI,
            'body'       => 'Gotong-royong menuju Surabaya Kota Dunia yang maju, humanis, dan berkelanjutan.',
            'sort_order' => 1,
        ]);
        $visi->documents()->sync($planDocs);

        // ─────────────── MISI ───────────────
        $misi = [
            'Menciptakan lapangan pekerjaan seluas-luasnya, perlindungan pekerja, mengembangkan UMKM, koperasi, ekonomi kreatif dan pemberdayaan pelaku usaha ekonomi lokal yang berdaya saing global.',
            'Mewujudkan pelayanan publik yang profesional, modern, dan akuntabel dengan ditunjang teknologi informasi yang terintegrasi.',
            'Memantapkan penyelenggaraan pemerintahan kolaboratif yang efektif, transparan, dan bertanggung jawab untuk mempercepat pencapaian kesejahteraan masyarakat.',
        ];
        foreach ($misi as $i => $body) {
            $p = ProfilPoint::create([
                'group'      => ProfilPoint::GROUP_MISI,
                'body'       => $body,
                'sort_order' => $i + 1,
            ]);
            $p->documents()->sync($planDocs);
        }

        // ─────────────── FOKUS STRATEGIS ───────────────
        $fokus = [
            ['Mendorong Investasi', 'Menciptakan iklim investasi yang sehat, kondusif, dan berkelanjutan.'],
            ['Pelayanan Perizinan Prima', 'Pelayanan yang cepat, akurat, transparan, dan akuntabel melalui sistem satu pintu (PTSP).'],
            ['Transformasi Digital', 'Memanfaatkan teknologi informasi untuk mempermudah akses, mempercepat proses, dan memperluas jangkauan pelayanan kepada masyarakat dan pelaku usaha.'],
        ];
        foreach ($fokus as $i => [$title, $body]) {
            $p = ProfilPoint::create([
                'group'      => ProfilPoint::GROUP_FOKUS,
                'title'      => $title,
                'body'       => $body,
                'sort_order' => $i + 1,
            ]);
            $p->documents()->sync($planDocs);
        }

        // ─────────────── TUGAS POKOK ───────────────
        $tugas = ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_TUGAS_POKOK,
            'body'       => 'DPM-PTSP Kota Surabaya mempunyai tugas membantu Walikota dalam melaksanakan urusan pemerintahan bidang penanaman modal serta penyelenggaraan pelayanan perizinan terpadu satu pintu (PTSP) yang menjadi kewenangan daerah.',
            'sort_order' => 1,
        ]);
        if ($perwali) {
            $tugas->regulations()->sync([$perwali->id]);
        }

        // ─────────────── FUNGSI ───────────────
        $fungsi = [
            'Perumusan kebijakan teknis di bidang penanaman modal dan pelayanan perizinan.',
            'Pelaksanaan kebijakan promosi, kerja sama, dan pengembangan investasi.',
            'Penyelenggaraan pelayanan perizinan dan non-perizinan secara terpadu satu pintu (PTSP) sesuai mekanisme OSS RBA.',
            'Pengendalian dan pengawasan pelaksanaan penanaman modal serta tindak lanjut atas perizinan yang diterbitkan.',
            'Pengelolaan data dan sistem informasi penanaman modal serta perizinan.',
            'Penanganan pengaduan masyarakat terhadap pelayanan perizinan.',
            'Pelaksanaan administrasi Dinas dan tugas lain yang diberikan Walikota sesuai dengan tugas dan fungsinya.',
        ];
        foreach ($fungsi as $i => $body) {
            $p = ProfilPoint::create([
                'group'      => ProfilPoint::GROUP_FUNGSI,
                'body'       => $body,
                'sort_order' => $i + 1,
            ]);
            if ($perwali) {
                $p->regulations()->sync([$perwali->id]);
            }
        }

        // ─────────────── MAKLUMAT PELAYANAN ───────────────
        ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_MAKLUMAT,
            'body'       => 'Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan, dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku.',
            'sort_order' => 1,
        ]);

        $komitmen = [
            'Pelayanan yang cepat, mudah, transparan, dan akuntabel.',
            'Bebas pungutan liar (pungli) dan gratifikasi sesuai prinsip Zona Integritas.',
            'Memenuhi Standar Pelayanan Minimum (SPM) sesuai peraturan yang berlaku.',
            'Menerima dan menindaklanjuti pengaduan masyarakat sesuai SOP yang telah ditetapkan.',
            'Mengutamakan kepuasan masyarakat sebagai indikator utama keberhasilan pelayanan.',
        ];
        foreach ($komitmen as $i => $body) {
            ProfilPoint::create([
                'group'      => ProfilPoint::GROUP_KOMITMEN,
                'body'       => $body,
                'sort_order' => $i + 1,
            ]);
        }

        // ─────────────── REFORMASI BIROKRASI — 6 AREA PERUBAHAN ───────────────
        // Each area: title, deskripsi/tujuan (body), sasaran[], indikator[].
        // Disusun mengacu kerangka 6 area pengungkit ZI (PermenPAN-RB 90/2021)
        // dan LKE ZI DPMPTSP Kota Surabaya.
        $areaRb = [
            [
                'title'     => 'Area Manajemen Perubahan',
                'body'      => 'Membangun komitmen seluruh jajaran serta mengubah pola pikir dan budaya kerja aparatur agar berintegritas, sehingga menurunkan risiko kegagalan pembangunan Zona Integritas.',
                'sasaran'   => [
                    'Meningkatnya komitmen pimpinan dan seluruh pegawai dalam membangun Zona Integritas menuju WBK/WBBM.',
                    'Terjadinya perubahan pola pikir dan budaya kerja yang berintegritas dan melayani.',
                    'Menurunnya risiko kegagalan pembangunan Zona Integritas.',
                ],
                'indikator' => [
                    'Tim Kerja Pembangunan ZI dibentuk melalui SK Kepala Dinas dengan mekanisme yang jelas.',
                    'Dokumen rencana kerja pembangunan ZI tersusun dan disosialisasikan ke seluruh unit.',
                    'Pemantauan dan evaluasi pembangunan ZI dilakukan secara berkala.',
                    'Agen Perubahan / role model ditetapkan dan aktif menggerakkan budaya kerja berAKHLAK.',
                ],
            ],
            [
                'title'     => 'Area Penataan Tata Laksana',
                'body'      => 'Meningkatkan efisiensi dan efektivitas proses bisnis melalui penyederhanaan prosedur dan pemanfaatan teknologi informasi dalam penyelenggaraan pelayanan.',
                'sasaran'   => [
                    'Meningkatnya penggunaan teknologi informasi dalam penyelenggaraan manajemen pemerintahan dan pelayanan.',
                    'Meningkatnya efisiensi dan efektivitas proses bisnis dan ketatalaksanaan.',
                ],
                'indikator' => [
                    'Peta proses bisnis dan SOP kegiatan utama tersedia, diterapkan, dan dievaluasi.',
                    'Penerapan Sistem Pemerintahan Berbasis Elektronik (SPBE) — e-Performance, e-Monev.',
                    'Keterbukaan informasi publik dilaksanakan melalui PPID.',
                ],
            ],
            [
                'title'     => 'Area Penataan Sistem Manajemen SDM',
                'body'      => 'Meningkatkan ketaatan, transparansi, disiplin, dan profesionalisme pengelolaan Sumber Daya Manusia aparatur.',
                'sasaran'   => [
                    'Meningkatnya ketaatan, transparansi, dan profesionalisme pengelolaan SDM aparatur.',
                ],
                'indikator' => [
                    'Perencanaan kebutuhan pegawai disusun sesuai kebutuhan organisasi.',
                    'Pengembangan pegawai berbasis kompetensi (minimal 20 JP/pegawai/tahun).',
                    'Penetapan kinerja individu yang selaras dengan kinerja organisasi.',
                    'Penegakan aturan disiplin/kode etik serta sistem informasi kepegawaian termutakhir.',
                ],
            ],
            [
                'title'     => 'Area Penguatan Akuntabilitas',
                'body'      => 'Meningkatkan akuntabilitas kinerja instansi melalui keterlibatan pimpinan dan pengelolaan kinerja yang berkualitas.',
                'sasaran'   => [
                    'Meningkatnya kinerja dan akuntabilitas instansi pemerintah.',
                ],
                'indikator' => [
                    'Keterlibatan pimpinan dalam perencanaan, penetapan, dan pemantauan capaian kinerja.',
                    'Penetapan Indikator Kinerja Utama (IKU) dan penyusunan dokumen SAKIP.',
                    'Pelaporan kinerja dilakukan tepat waktu dan berkualitas.',
                ],
            ],
            [
                'title'     => 'Area Penguatan Pengawasan',
                'body'      => 'Memperkuat pengendalian internal dan sistem integritas untuk menurunkan penyalahgunaan wewenang.',
                'sasaran'   => [
                    'Meningkatnya kepatuhan dan efektivitas pengendalian internal.',
                    'Menurunnya tingkat penyalahgunaan wewenang.',
                ],
                'indikator' => [
                    'Pengendalian gratifikasi dan penanganan benturan kepentingan.',
                    'Penerapan Sistem Pengendalian Intern Pemerintah (SPIP).',
                    'Pengelolaan pengaduan masyarakat dan Whistle-Blowing System (WBS).',
                ],
            ],
            [
                'title'     => 'Area Peningkatan Kualitas Pelayanan Publik',
                'body'      => 'Meningkatkan kualitas dan kepuasan masyarakat terhadap pelayanan publik.',
                'sasaran'   => [
                    'Meningkatnya kualitas pelayanan publik (cepat, mudah, terjangkau, terukur).',
                    'Meningkatnya kepuasan masyarakat terhadap pelayanan.',
                ],
                'indikator' => [
                    'Penetapan dan penerapan standar pelayanan serta maklumat pelayanan.',
                    'Budaya pelayanan prima diterapkan kepada seluruh petugas.',
                    'Pengelolaan pengaduan pelayanan dilaksanakan dan ditindaklanjuti.',
                    'Survei Kepuasan Masyarakat (SKM) dilakukan secara berkala.',
                    'Pemanfaatan teknologi informasi dalam pemberian pelayanan.',
                ],
            ],
        ];

        $areaModels = [];
        foreach ($areaRb as $i => $area) {
            $point = ProfilPoint::create([
                'group'      => ProfilPoint::GROUP_AREA_RB,
                'title'      => $area['title'],
                'body'       => $area['body'],
                'sort_order' => $i + 1,
            ]);
            $areaModels[$i] = $point;

            foreach ($area['sasaran'] as $si => $body) {
                ProfilPointDetail::create([
                    'profil_point_id' => $point->id,
                    'kind'            => ProfilPointDetail::KIND_SASARAN,
                    'body'            => $body,
                    'sort_order'      => $si + 1,
                ]);
            }
            foreach ($area['indikator'] as $ii => $body) {
                ProfilPointDetail::create([
                    'profil_point_id' => $point->id,
                    'kind'            => ProfilPointDetail::KIND_INDIKATOR,
                    'body'            => $body,
                    'sort_order'      => $ii + 1,
                ]);
            }
        }

        // Renja ZI — link points to an INTERNAL system Document (admin uploads
        // the actual PDF via the Dokumen module), not an external URL.
        $renjaZiDoc = Document::updateOrCreate(
            ['title' => 'Rencana Kerja Pembangunan Zona Integritas (Renja ZI)'],
            [
                'description'  => 'Dokumen Renja ZI memuat program kerja, sasaran, dan target tiap area perubahan menuju predikat WBK/WBBM.',
                'file_path'    => 'documents/placeholder.pdf',  // admin replaces via CMS
                'mime'         => 'application/pdf',
                'size_bytes'   => 0,
                'is_published' => true,
            ]
        );
        $renja = ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_RENJA_ZI,
            'body'       => 'Dokumen Renja ZI memuat program kerja, sasaran, dan target tiap area perubahan menuju WBK/WBBM.',
            'sort_order' => 1,
        ]);
        $renja->documents()->sync([$renjaZiDoc->id]);

        // SK ZI — Tim Pembentukan Zona Integritas menuju WBBM (rujukan tim).
        $skZi = ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_SK_ZI,
            'title'      => 'SK Kepala Dinas No. 100.3.12/131/436.7.15/2026 tentang Tim Pembentukan Zona Integritas Menuju WBBM',
            'body'       => 'https://drive.google.com/file/d/1-FdgrAh49_EC-UKGquCPbg8XwggxA6Rl/view',
            'sort_order' => 1,
        ]);

        // WBK & Menuju WBBM — section media dokumentasi (pelaksanaan & penilaian).
        // Lampirkan foto/dokumen via CMS (ReformasiResource → entri WBK / WBBM →
        // "Dokumen / Media"); foto tampil sebagai galeri di halaman.
        ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_WBK,
            'title'      => 'WBK',
            'body'       => 'Dokumentasi pelaksanaan dan penilaian pembangunan Zona Integritas menuju Wilayah Bebas dari Korupsi (WBK).',
            'sort_order' => 1,
        ]);
        ProfilPoint::create([
            'group'      => ProfilPoint::GROUP_WBBM,
            'title'      => 'Menuju WBBM',
            'body'       => 'Dokumentasi pelaksanaan dan penilaian menuju predikat Wilayah Birokrasi Bersih dan Melayani (WBBM).',
            'sort_order' => 1,
        ]);

        // ── Pimpinan Tim ZI (level tim, bukan per-area) → dilekatkan ke entri SK ZI.
        //    Tampil di section "Tim Pembangunan ZI" pada halaman reformasi.
        $pimpinan = [
            ['Ir. Lasidi ST., M.T', 'Ketua'],
            ['Herdayana Wistianingrum, S.Sos', 'Sekretaris'],
        ];
        foreach ($pimpinan as $pi => [$name, $role]) {
            ChangeAgent::create([
                'profil_point_id' => $skZi->id,
                'name'            => $name,
                'role'            => $role,
                'sort_order'      => $pi + 1,
            ]);
        }

        // ── Kelompok Kerja (Pokja) per area = Agen Perubahan sesuai area.
        //    Koordinator + Anggota, dilekatkan ke ProfilPoint area (urutan = $areaRb).
        //    NIK/NIP & foto dilengkapi via CMS. Sumber: Lampiran SK ZI 2026.
        $pokja = [
            0 => ['Yohanes Franklin, S.H., M.H.', [
                'Ria Dwi Rafita Sari, S.E.', 'Advin Mariyono, S.ST.', 'Arif Firmansyah, S.E.',
                'Bagaskara Andhita Dewa Noraga', 'Anton Budi Satria, S.E.',
                'A.R Bagas Danang Haditio, S.Kom.', 'Juztitazya Ratna Larasutami',
            ]],
            1 => ['Hefli Syarifuddin Madjid, S.E., M.Si.', [
                'Adi Yustiawan, S.Tr.Kom.', 'Haristya Eka Farma, S.Kom.', 'Rio Saputra, S.Kom.',
                'Yuli Rahmawati, S.E.', 'Brayen Prastika Dwiputra, S.M.', 'Robby Suryagara, S.Kom.',
                'Mega Fadhilah',
            ]],
            2 => ['Indah Mayasari, A.Md., S.T.', [
                'Muhammad Lamkhil Bashor, S.Kom.', 'Zahwanur Farikh, S.E.', 'Septiyan Firdaus Gigih Armadani',
                'Firman Santoso', 'La Ari', 'Kurnia Hijriah Agustin', 'Nur Wardah Siahaan', 'Naura Darrin Hasan',
            ]],
            3 => ['Taufiq Ervantri, S.E.', [
                'Novita Theresyana Erawati', 'Layl Ahcmad Suryana, S.Kom.', 'Antok Slamet Prasetyo',
                'Mada Bimantoro', 'Zhafirah Ramadhani', 'Anastasia Aurelia Andani Putri',
            ]],
            4 => ['Nur Ulfatur Roiha, S.Kom., M.T.', [
                'Windy Puspita Sari, S.E.', 'Piery Patria Huda', 'Triyudha Ria Wijayanto, S.Kom.',
                'Harnang Febri Saputro, S.Kom.', 'Arum Kusuma Wardhani, S.Psi.', 'Junaedi', 'Galan Pradana',
                'Anang Arifin', 'Dra. Retno Enny Rahayu',
            ]],
            5 => ['Chotijah, A.Md.', [
                'Ulvia Zulvia, S.T.', 'Wijang Widyati, A.Md., S.A.P.', 'Agung Satrio, S.M.', 'Dwi Harihadi, S.A.P.',
                'Siti Rape`a, S.A.P.', 'Fitri Chandrawati', 'Moch. Rosul Zein, S.Kom.',
                'Tri Sapta Nugroho Slamet Harianto, S.E.', 'Mitory Ditya Rantika Elentina, S.Psi.', 'Deva Ivanka Ridwan',
            ]],
        ];
        foreach ($pokja as $idx => [$koordinator, $anggota]) {
            $area = $areaModels[$idx] ?? null;
            if (! $area) {
                continue;
            }
            $sort = 1;
            ChangeAgent::create([
                'profil_point_id' => $area->id,
                'name'            => $koordinator,
                'role'            => 'Koordinator',
                'sort_order'      => $sort++,
            ]);
            foreach ($anggota as $name) {
                ChangeAgent::create([
                    'profil_point_id' => $area->id,
                    'name'            => $name,
                    'role'            => 'Anggota',
                    'sort_order'      => $sort++,
                ]);
            }
        }

        // ─────────────── STRUKTUR ORGANISASI ───────────────
        $attachPerwali = function (OrgUnit $u) use ($perwali) {
            if ($perwali) {
                $u->regulations()->sync([$perwali->id]);
            }
        };

        $kadis = OrgUnit::create([
            'name'        => 'Kepala Dinas',
            'category'    => OrgUnit::CAT_PIMPINAN,
            'description' => 'Memimpin penyelenggaraan urusan penanaman modal dan pelayanan perizinan terpadu satu pintu di Kota Surabaya.',
            'sort_order'  => 1,
        ]);
        $attachPerwali($kadis);

        $sekretariat = OrgUnit::create([
            'name'        => 'Sekretariat',
            'category'    => OrgUnit::CAT_SEKRETARIAT,
            'description' => 'Mengoordinasikan perencanaan, keuangan, kepegawaian, dan ketatausahaan dinas.',
            'sort_order'  => 2,
        ]);
        $attachPerwali($sekretariat);
        foreach (['Sub Bagian Umum dan Kepegawaian', 'Sub Bagian Keuangan', 'Sub Bagian Perencanaan dan Evaluasi'] as $i => $sub) {
            OrgUnit::create([
                'name'       => $sub,
                'category'   => OrgUnit::CAT_TIM_KERJA,
                'parent_id'  => $sekretariat->id,
                'sort_order' => $i + 1,
            ]);
        }

        $penanamanModal = OrgUnit::create([
            'name'        => 'Bidang Penanaman Modal',
            'category'    => OrgUnit::CAT_BIDANG,
            'description' => 'Menangani promosi, kerja sama, pengembangan, pengendalian, dan pengawasan investasi di Kota Surabaya.',
            'sort_order'  => 3,
        ]);
        $attachPerwali($penanamanModal);
        OrgUnit::create([
            'name'        => 'Pengelolaan Data dan Sistem Informasi',
            'category'    => OrgUnit::CAT_TIM_KERJA,
            'parent_id'   => $penanamanModal->id,
            'description' => 'Pengelolaan data investasi & perizinan, pengembangan dan integrasi sistem informasi (SSW Alfa, SIPINTAR).',
            'sort_order'  => 1,
        ]);

        $pelayanan = OrgUnit::create([
            'name'        => 'Bidang Pelayanan Perizinan',
            'category'    => OrgUnit::CAT_BIDANG,
            'description' => 'Penerbitan izin berbasis risiko (OSS RBA), pengawasan penyelenggaraan perizinan, dan penanganan pengaduan perizinan.',
            'sort_order'  => 4,
        ]);
        $attachPerwali($pelayanan);

        $fungsional = OrgUnit::create([
            'name'        => 'Kelompok Jabatan Fungsional',
            'category'    => OrgUnit::CAT_FUNGSIONAL,
            'description' => 'Melaksanakan tugas fungsional sesuai keahlian dan jenjang jabatan masing-masing dalam mendukung tugas dinas.',
            'sort_order'  => 5,
        ]);
        $attachPerwali($fungsional);
    }
}
