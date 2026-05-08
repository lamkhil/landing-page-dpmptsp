<?php

namespace Database\Seeders\Cms;

use App\Domain\Seo\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        $orgJsonLd = [
            '@context' => 'https://schema.org',
            '@type'    => 'GovernmentOrganization',
            'name'     => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surabaya',
            'alternateName' => 'DPMPTSP Surabaya',
            'url'      => config('app.url'),
            'logo'     => config('app.url') . '/brand/favicon.svg',
            'address'  => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'Jl. Tunjungan No. 1-3',
                'addressLocality' => 'Surabaya',
                'addressRegion'   => 'Jawa Timur',
                'postalCode'      => '60275',
                'addressCountry'  => 'ID',
            ],
            'sameAs' => [
                'https://facebook.com/dpmptspsurabaya',
                'https://instagram.com/dpmptspsurabaya',
                'https://twitter.com/dpmptspsby',
            ],
        ];

        $defaults = [
            ['page_key' => 'home', 'meta_title' => 'DPMPTSP Surabaya — Layanan Perizinan Modern, Transparan & Akuntabel',
             'meta_description' => 'Portal resmi Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Surabaya. Pelayanan perizinan, investasi, dan informasi publik berbasis digital.',
             'keywords' => 'DPMPTSP Surabaya, perizinan, investasi, OSS, SIPEBA, Surabaya', 'structured_data' => $orgJsonLd],
            ['page_key' => 'profil', 'meta_title' => 'Profil — DPMPTSP Surabaya',
             'meta_description' => 'Profil, visi-misi, struktur organisasi, tugas dan fungsi DPMPTSP Kota Surabaya.', 'structured_data' => null],
            ['page_key' => 'layanan', 'meta_title' => 'Layanan Perizinan — DPMPTSP Surabaya',
             'meta_description' => 'Layanan perizinan berusaha, non-perizinan, OSS, tracking, dan konsultasi online.', 'structured_data' => null],
            ['page_key' => 'aplikasi', 'meta_title' => 'Aplikasi Publik — DPMPTSP Surabaya',
             'meta_description' => 'Akses cepat ke aplikasi publik DPMPTSP: SIPEBA, OSS, E-Perizinan, Tracking Izin, dan lainnya.', 'structured_data' => null],
            ['page_key' => 'statistik', 'meta_title' => 'Dashboard Statistik — DPMPTSP Surabaya',
             'meta_description' => 'Statistik investasi PMA & PMDN, perizinan diterbitkan, SLA pelayanan, dan IKM.', 'structured_data' => null],
            ['page_key' => 'informasi', 'meta_title' => 'Informasi Publik — DPMPTSP Surabaya',
             'meta_description' => 'Berita, pengumuman, agenda, regulasi, dokumen publik, dan infografis DPMPTSP.', 'structured_data' => null],
            ['page_key' => 'pengaduan', 'meta_title' => 'Pengaduan & Aspirasi — DPMPTSP Surabaya',
             'meta_description' => 'Sampaikan pengaduan dan aspirasi melalui SP4N LAPOR, Whistleblowing System, dan layanan online.', 'structured_data' => null],
            ['page_key' => 'kontak', 'meta_title' => 'Kontak Kami — DPMPTSP Surabaya',
             'meta_description' => 'Alamat, jam pelayanan, kontak, dan lokasi kantor DPMPTSP Kota Surabaya.', 'structured_data' => null],
        ];

        foreach ($defaults as $d) {
            SeoSetting::updateOrCreate(['page_key' => $d['page_key']], $d + ['robots' => 'index,follow']);
        }
    }
}
