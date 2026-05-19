<?php

namespace Database\Seeders\Cms;

use App\Domain\Menu\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Seeds CMS-managed sub-menu items per navbar group.
 *
 * The 8 top-level navbar buttons (Beranda, Profil, Layanan, …) are STATIC
 * and rendered directly from routes in the navbar component — they are
 * NOT seeded here. This table only contains the SUB-MENU items shown in
 * dropdowns / mobile drawer.
 *
 * Sections that have no real sub-pages (Beranda, Aplikasi Publik) get an
 * empty array — admin can add later via Filament if a section grows.
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Sections that route to their root only — no submenu, no dropdown.
        $emptyGroups = ['beranda', 'aplikasi'];

        $tree = [
            'profil' => [
                ['label' => 'Visi & Misi',          'route_name' => 'profil.visi-misi'],
                ['label' => 'Struktur Organisasi',  'route_name' => 'profil.struktur'],
                ['label' => 'Tugas & Fungsi',       'route_name' => 'profil.tugas-fungsi'],
                ['label' => 'Maklumat Pelayanan',   'route_name' => 'profil.maklumat'],
                ['label' => 'SOP Pelayanan',        'route_name' => 'profil.sop'],
                ['label' => 'Standar Pelayanan',    'route_name' => 'profil.standar'],
                ['label' => 'Reformasi Birokrasi',  'route_name' => 'profil.reformasi'],
                ['label' => 'Zona Integritas',      'route_name' => 'profil.zi'],
                ['label' => 'WBK / WBBM',           'route_name' => 'profil.wbk'],
                ['label' => 'Mengapa Surabaya',     'route_name' => 'profil.mengapa'],
                ['label' => 'Inovasi',              'route_name' => 'profil.inovasi.index'],
                ['label' => 'FAQ',                  'route_name' => 'profil.faq'],
            ],
            'layanan' => [
                ['label' => 'Perizinan Berusaha',   'route_name' => 'layanan.perizinan'],
                ['label' => 'Non Perizinan',        'route_name' => 'layanan.non-perizinan'],
                ['label' => 'OSS RBA',              'route_name' => 'layanan.oss'],
                ['label' => 'Tracking Perizinan',   'route_name' => 'layanan.tracking'],
                ['label' => 'Konsultasi Online',    'route_name' => 'layanan.konsultasi'],
                ['label' => 'Antrian Online',       'route_name' => 'layanan.antrian'],
                ['label' => 'Persyaratan Perizinan','route_name' => 'layanan.persyaratan'],
                ['label' => 'Download Formulir',    'route_name' => 'layanan.formulir'],
                ['label' => 'SLA Pelayanan',        'route_name' => 'layanan.sla'],
            ],
            'statistik' => [
                ['label' => 'Dashboard Investasi',  'route_name' => 'statistik.investasi'],
                ['label' => 'Dashboard Perizinan',  'route_name' => 'statistik.perizinan'],
                ['label' => 'Statistik PMA / PMDN', 'route_name' => 'statistik.pma-pmdn'],
                ['label' => 'Statistik Kepuasan',   'route_name' => 'statistik.kepuasan'],
                ['label' => 'SLA Pelayanan',        'route_name' => 'statistik.sla'],
                ['label' => 'Open Data Statistik',  'route_name' => 'statistik.open-data'],
            ],
            'informasi' => [
                ['label' => 'Berita',               'route_name' => 'informasi.berita.index'],
                ['label' => 'Pengumuman',           'route_name' => 'informasi.pengumuman.index'],
                ['label' => 'Agenda',               'route_name' => 'informasi.agenda.index'],
                ['label' => 'Artikel',              'route_name' => 'informasi.artikel.index'],
                ['label' => 'Regulasi',             'route_name' => 'informasi.regulasi.index'],
                ['label' => 'Dokumen Publik',       'route_name' => 'informasi.dokumen.index'],
                ['label' => 'Infografis',           'route_name' => 'informasi.infografis.index'],
                ['label' => 'LKjIP',                'route_name' => 'informasi.lkjip'],
                ['label' => 'Renstra',              'route_name' => 'informasi.renstra'],
                ['label' => 'Laporan Tahunan',      'route_name' => 'informasi.laporan-tahunan'],
                ['label' => 'Download Center',      'route_name' => 'informasi.download'],
            ],
            'pengaduan' => [
                ['label' => 'Lapor Pengaduan',      'route_name' => 'pengaduan.lapor'],
                ['label' => 'Tracking Pengaduan',   'route_name' => 'pengaduan.tracking'],
                ['label' => 'SP4N LAPOR',           'route_name' => 'pengaduan.sp4n'],
                ['label' => 'Whistleblowing',       'route_name' => 'pengaduan.wbs'],
                ['label' => 'Konsultasi Masyarakat','route_name' => 'pengaduan.konsultasi'],
            ],
            'kontak' => [
                ['label' => 'Kontak Kami',          'route_name' => 'kontak.index'],
                ['label' => 'Lokasi Kantor',        'route_name' => 'kontak.lokasi'],
            ],
        ];

        // Cleanup: remove self-referential entries from previous seed runs.
        Menu::query()->where('group', 'beranda')->where('route_name', 'home')->delete();
        Menu::query()->where('group', 'aplikasi')->where('route_name', 'aplikasi.index')->delete();
        foreach (['profil.index', 'layanan.index', 'statistik.index', 'informasi.index', 'pengaduan.index'] as $selfRef) {
            $group = explode('.', $selfRef)[0];
            Menu::query()->where('group', $group)->where('route_name', $selfRef)->delete();
        }

        foreach ($tree as $group => $items) {
            foreach ($items as $i => $item) {
                Menu::updateOrCreate(
                    ['group' => $group, 'route_name' => $item['route_name']],
                    [
                        'label'      => $item['label'],
                        'is_visible' => true,
                        'sort_order' => $i,
                    ]
                );
            }

            // Drop orphan menu records that aren't in the current tree for this
            // group — happens when a route gets renamed (e.g. profil.inovasi →
            // profil.inovasi.index). Without this, the navbar shows both old
            // and new entries; the old one's route doesn't resolve and renders
            // as an unclickable "#" link.
            $keepRoutes = array_column($items, 'route_name');
            Menu::query()
                ->where('group', $group)
                ->whereNotIn('route_name', $keepRoutes)
                ->delete();
        }

        // Make explicit the sections that purposely have no submenu.
        // (no records to insert; just documented in $emptyGroups above.)
        unset($emptyGroups);
    }
}
