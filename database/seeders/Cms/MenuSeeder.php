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
                // Zona Integritas = induk (link ke halaman reformasi); Reformasi
                // Birokrasi / WBK / WBBM jadi anak submenu (deep-link via hash).
                ['label' => 'Zona Integritas', 'route_name' => 'profil.zi', 'external_url' => '/profil/reformasi-birokrasi', 'children' => [
                    ['label' => 'Reformasi Birokrasi', 'route_name' => 'profil.reformasi'],
                    ['label' => 'WBK',                 'route_name' => 'profil.wbk',  'external_url' => '/profil/reformasi-birokrasi#wbk'],
                    ['label' => 'WBBM',                'route_name' => 'profil.wbbm', 'external_url' => '/profil/reformasi-birokrasi#wbbm'],
                ]],
                ['label' => 'Inovasi',              'route_name' => 'profil.inovasi.index'],
                ['label' => 'FAQ',                  'route_name' => 'profil.faq'],
            ],
            // Diselaraskan dengan pola DPMPTSP kota lain (Badung/Medan/Bandung).
            // SOP & Standar Pelayanan dipindah dari menu Profil ke sini
            // (route/halaman tetap profil.sop & profil.standar). Item lama yang
            // tak dipakai (OSS/Konsultasi/Antrian/Formulir/SLA/Persyaratan/Alur)
            // route-nya tetap ada, hanya tidak ditampilkan di menu.
            'layanan' => [
                ['label' => 'Perizinan Berusaha',     'route_name' => 'layanan.perizinan'],
                ['label' => 'Perizinan Non-Berusaha', 'route_name' => 'layanan.non-perizinan'],
                ['label' => 'Pelayanan Non-Perizinan','route_name' => 'layanan.pelayanan-non-perizinan'],
                ['label' => 'Kamus KBLI',            'route_name' => 'layanan.kbli', 'external_url' => 'https://oss.go.id/id/kbli', 'new_tab' => true],
                ['label' => 'SOP Pelayanan',        'route_name' => 'profil.sop'],
                ['label' => 'Standar Pelayanan',    'route_name' => 'profil.standar'],
                ['label' => 'Tracking Permohonan',  'route_name' => 'layanan.tracking', 'external_url' => 'https://sswalfa.surabaya.go.id/cek/lacak', 'new_tab' => true],
            ],
            'statistik' => [
                ['label' => 'Dashboard Investasi',  'route_name' => 'statistik.investasi'],
                ['label' => 'Dashboard Perizinan',  'route_name' => 'statistik.perizinan'],
                ['label' => 'Statistik PMA / PMDN', 'route_name' => 'statistik.pma-pmdn'],
                ['label' => 'Statistik Kepuasan',   'route_name' => 'statistik.kepuasan'],
                ['label' => 'SLA Pelayanan',        'route_name' => 'statistik.sla'],
                ['label' => 'Open Data Statistik',  'route_name' => 'statistik.open-data'],
            ],
            // Informasi = konten editorial. Arsip dokumen dipisah ke grup
            // 'dokumen' di bawah agar tampil sebagai menu top-level sendiri.
            'informasi' => [
                ['label' => 'Berita',               'route_name' => 'informasi.berita.index'],
                ['label' => 'Pengumuman',           'route_name' => 'informasi.pengumuman.index'],
                ['label' => 'Agenda',               'route_name' => 'informasi.agenda.index'],
                ['label' => 'Artikel',              'route_name' => 'informasi.artikel.index'],
                ['label' => 'Infografis',           'route_name' => 'informasi.infografis.index'],
            ],
            // Dokumen Publik = menu top-level baru (navbar key 'dokumen').
            'dokumen' => [
                ['label' => 'Regulasi',             'route_name' => 'informasi.regulasi.index'],
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
            $keepRoutes = [];
            foreach ($items as $i => $item) {
                $parent = Menu::updateOrCreate(
                    ['group' => $group, 'route_name' => $item['route_name']],
                    [
                        'label'           => $item['label'],
                        'external_url'    => $item['external_url'] ?? null,
                        'open_in_new_tab' => $item['new_tab'] ?? false,
                        'parent_id'       => null,
                        'is_visible'      => true,
                        'sort_order'      => $i,
                    ]
                );
                $keepRoutes[] = $item['route_name'];

                // Nested submenu items (one level), e.g. Profil → Zona Integritas.
                foreach ($item['children'] ?? [] as $ci => $child) {
                    Menu::updateOrCreate(
                        ['group' => $group, 'route_name' => $child['route_name']],
                        [
                            'label'        => $child['label'],
                            'external_url' => $child['external_url'] ?? null,
                            'parent_id'    => $parent->id,
                            'is_visible'   => true,
                            'sort_order'   => $ci,
                        ]
                    );
                    $keepRoutes[] = $child['route_name'];
                }
            }

            // Drop orphan menu records (incl. nested children) that aren't in the
            // current tree for this group — happens when a route gets renamed or
            // removed. Without this, the navbar shows stale "#" links.
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
