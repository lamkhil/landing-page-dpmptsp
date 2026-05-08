<?php

/*
|--------------------------------------------------------------------------
| DPMPTSP system constants
|--------------------------------------------------------------------------
| Non-CMS-editable values: brand identity, palette tokens, navbar route
| whitelist, content limits. Per CLAUDE.md: structure/layout = STATIC,
| content/data = CMS. Anything in this file requires a deploy to change.
*/

return [
    'brand' => [
        'name'      => 'DPMPTSP Surabaya',
        'long_name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surabaya',
        'short'     => 'DPMPTSP',
        'tagline'   => 'Layanan Perizinan Modern, Transparan & Akuntabel',
        'gov'       => 'Pemerintah Kota Surabaya',
    ],

    /*
    | Whitelist of named routes admins may bind menu items to.
    | The Filament Menu Resource exposes a Select bound to this list — admins
    | cannot type arbitrary route names or URLs in the route_name field.
    */
    'menu_routes' => [
        'home'                          => 'Beranda',
        'profil.index'                  => 'Profil DPMPTSP',
        'profil.visi-misi'              => 'Visi & Misi',
        'profil.struktur'               => 'Struktur Organisasi',
        'profil.tugas-fungsi'           => 'Tugas & Fungsi',
        'profil.maklumat'               => 'Maklumat Pelayanan',
        'profil.sop'                    => 'SOP Pelayanan',
        'profil.standar'                => 'Standar Pelayanan',
        'profil.reformasi'              => 'Reformasi Birokrasi',
        'profil.zi'                     => 'Zona Integritas',
        'profil.wbk'                    => 'WBK / WBBM',
        'profil.mengapa'                => 'Mengapa Investasi di Surabaya',
        'profil.faq'                    => 'FAQ',

        'layanan.index'                 => 'Layanan',
        'layanan.perizinan'             => 'Perizinan Berusaha',
        'layanan.non-perizinan'         => 'Non Perizinan',
        'layanan.oss'                   => 'OSS',
        'layanan.tracking'              => 'Tracking Perizinan',
        'layanan.konsultasi'            => 'Konsultasi Online',
        'layanan.antrian'               => 'Antrian Online',
        'layanan.persyaratan'           => 'Persyaratan Perizinan',
        'layanan.formulir'              => 'Download Formulir',
        'layanan.sla'                   => 'SLA Pelayanan',

        'aplikasi.index'                => 'Aplikasi Publik',

        'statistik.index'               => 'Statistik',
        'statistik.investasi'           => 'Dashboard Investasi',
        'statistik.perizinan'           => 'Dashboard Perizinan',
        'statistik.pma-pmdn'            => 'Statistik PMA / PMDN',
        'statistik.kepuasan'            => 'Statistik Kepuasan',
        'statistik.sla'                 => 'SLA Pelayanan',
        'statistik.open-data'           => 'Open Data Statistik',

        'informasi.index'               => 'Informasi Publik',
        'informasi.berita.index'        => 'Berita',
        'informasi.pengumuman.index'    => 'Pengumuman',
        'informasi.agenda.index'        => 'Agenda',
        'informasi.artikel.index'       => 'Artikel',
        'informasi.regulasi.index'      => 'Regulasi',
        'informasi.dokumen.index'       => 'Dokumen Publik',
        'informasi.infografis.index'    => 'Infografis',
        'informasi.lkjip'               => 'LKjIP',
        'informasi.renstra'             => 'Renstra',
        'informasi.laporan-tahunan'     => 'Laporan Tahunan',
        'informasi.download'            => 'Download Center',

        'pengaduan.index'               => 'Pengaduan',
        'pengaduan.lapor'               => 'Lapor Pengaduan',
        'pengaduan.tracking'            => 'Tracking Pengaduan',
        'pengaduan.sp4n'                => 'SP4N LAPOR',
        'pengaduan.wbs'                 => 'Whistleblowing System',
        'pengaduan.konsultasi'          => 'Konsultasi Masyarakat',

        'kontak.index'                  => 'Kontak Kami',
        'kontak.lokasi'                 => 'Lokasi Kantor',
    ],

    /*
    | Statistic groups expected by the dashboard. Used by seeders + service
    | layer guards. Adding a new group here is a code change (intentional).
    */
    'statistic_groups' => [
        'pma'  => ['label' => 'Investasi PMA',  'unit' => 'USD juta'],
        'pmdn' => ['label' => 'Investasi PMDN', 'unit' => 'IDR miliar'],
        'izin' => ['label' => 'Izin Diterbitkan', 'unit' => 'izin'],
        'sla'  => ['label' => 'SLA Pelayanan', 'unit' => 'hari'],
        'ikm'  => ['label' => 'Indeks Kepuasan Masyarakat', 'unit' => 'skor'],
    ],

    /*
    | RBAC roles. Adding/removing a role requires the seeder + policy update;
    | renaming requires a migration. Kept hardcoded to prevent permission
    | drift from CMS edits.
    */
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin'       => 'Admin',
        'editor'      => 'Editor',
        'operator'    => 'Operator',
        'viewer'      => 'Viewer',
    ],

    /*
    | Cache TTLs (seconds). All public-facing reads go through services that
    | wrap repository calls in Cache::tags()->remember() with these TTLs.
    */
    'cache_ttl' => [
        'hero'        => 600,
        'menu'        => 1800,
        'application' => 600,
        'statistic'   => 300,
        'footer'      => 1800,
        'seo'         => 1800,
        'post'        => 300,
        'faq'         => 1800,
    ],

    /*
    | Limits for public submission endpoints (rate-limit middleware uses these).
    */
    'limits' => [
        'complaint_per_minute' => 5,
        'contact_per_minute'   => 5,
        'survey_per_minute'    => 10,
    ],
];
