<?php

namespace Database\Seeders\Cms;

use App\Domain\Application\Models\Application;
use App\Domain\Application\Models\ApplicationCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds the 10 official applications listed at
 * dpm-ptsp.surabaya.go.id/aplikasi.php — including OSS RBA, SSW Alfa Surabaya,
 * SIPINTAR, Kalkulator Investasi, Data Investasi, MPP Surabaya, SKM, LAPOR!,
 * SIPPN MENPAN-RB, and WarGaku.
 */
class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Perizinan',       'slug' => 'perizinan',       'icon' => 'document-text', 'sort_order' => 0],
            ['name' => 'Investasi',       'slug' => 'investasi',       'icon' => 'chart-bar',     'sort_order' => 1],
            ['name' => 'Pengaduan',       'slug' => 'pengaduan',       'icon' => 'megaphone',     'sort_order' => 2],
            ['name' => 'Pelayanan Publik','slug' => 'pelayanan-publik','icon' => 'building-office', 'sort_order' => 3],
        ];
        foreach ($categories as $c) {
            ApplicationCategory::updateOrCreate(['slug' => $c['slug']], $c);
        }

        $perizinan = ApplicationCategory::where('slug', 'perizinan')->first();
        $investasi = ApplicationCategory::where('slug', 'investasi')->first();
        $pengaduan = ApplicationCategory::where('slug', 'pengaduan')->first();
        $publik    = ApplicationCategory::where('slug', 'pelayanan-publik')->first();

        $apps = [
            [
                'name' => 'OSS RBA',
                'slug' => 'oss',
                'description' => 'Online Single Submission Risk-Based Approach — wajib digunakan oleh Pelaku Usaha, Kementerian/Lembaga, dan Pemerintah Daerah untuk perizinan berusaha berbasis risiko.',
                'url'  => 'https://oss.go.id/',
                'category' => $perizinan, 'is_featured' => true, 'sort_order' => 0,
            ],
            [
                'name' => 'SSW Alfa Surabaya',
                'slug' => 'ssw',
                'description' => 'Sistem Surabaya Single Window Berbasis Risiko — wajib digunakan oleh Pelaku Usaha dan Administrator Kawasan Ekonomi Khusus di wilayah Kota Surabaya.',
                'url'  => 'https://sswalfa.surabaya.go.id/',
                'category' => $perizinan, 'is_featured' => true, 'sort_order' => 1,
            ],
            [
                'name' => 'SIPINTAR',
                'slug' => 'sipintar',
                'description' => 'Sistem Informasi Pintar DPMPTSP Surabaya — informasi perizinan dan layanan investasi terintegrasi.',
                'url'  => 'https://dpm-ptsp.surabaya.go.id/sipintar/',
                'category' => $perizinan, 'is_featured' => true, 'sort_order' => 2,
            ],
            [
                'name' => 'Kalkulator Investasi',
                'slug' => 'kalkulator-investasi',
                'description' => 'Alat bantu simulasi dan kalkulasi rencana investasi untuk pelaku usaha di Kota Surabaya.',
                'url'  => 'https://dpm-ptsp.surabaya.go.id/kalkulator_investasi/',
                'category' => $investasi, 'is_featured' => true, 'sort_order' => 3,
            ],
            [
                'name' => 'Data Investasi Surabaya',
                'slug' => 'data-investasi',
                'description' => 'Portal data realisasi investasi PMA dan PMDN Kota Surabaya — diperbarui berkala oleh DPMPTSP.',
                'url'  => 'https://dpm-ptsp.surabaya.go.id/data-investasi/',
                'category' => $investasi, 'is_featured' => true, 'sort_order' => 4,
            ],
            [
                'name' => 'Mal Pelayanan Publik Surabaya',
                'slug' => 'mpp-surabaya',
                'description' => 'Pusat layanan publik terintegrasi Pemerintah Kota Surabaya — DPMPTSP berlokasi di MPP Lt.3.',
                'url'  => 'https://mpp.surabaya.go.id/',
                'category' => $publik, 'is_featured' => false, 'sort_order' => 5,
            ],
            [
                'name' => 'SKM Surabaya',
                'slug' => 'skm',
                'description' => 'Survei Kepuasan Masyarakat — pengukuran indeks kepuasan terhadap pelayanan publik Pemkot Surabaya.',
                'url'  => 'https://dinassosial.surabaya.go.id/daftar',
                'category' => $publik, 'is_featured' => false, 'sort_order' => 6,
            ],
            [
                'name' => 'SP4N LAPOR!',
                'slug' => 'lapor',
                'description' => 'Sistem Pengelolaan Pengaduan Pelayanan Publik Nasional — kanal pengaduan terintegrasi nasional.',
                'url'  => 'https://www.lapor.go.id/',
                'category' => $pengaduan, 'is_featured' => true, 'sort_order' => 7,
            ],
            [
                'name' => 'SIPPN MENPAN-RB',
                'slug' => 'sippn-menpan',
                'description' => 'Sistem Informasi Pelayanan Publik Nasional Kementerian PAN-RB.',
                'url'  => 'https://sippn.menpan.go.id/beranda',
                'category' => $pengaduan, 'is_featured' => false, 'sort_order' => 8,
            ],
            [
                'name' => 'WarGaku',
                'slug' => 'wargaku',
                'description' => 'Aplikasi mobile resmi Pemerintah Kota Surabaya untuk layanan dan informasi warga.',
                'url'  => 'https://play.google.com/store/apps/details?id=com.surabaya.go.id.wargaku',
                'category' => $publik, 'is_featured' => false, 'sort_order' => 9,
            ],
        ];

        foreach ($apps as $a) {
            $cat = $a['category'];
            unset($a['category']);
            $a['application_category_id'] = $cat?->id;
            $a['link_type']     = Application::LINK_EXTERNAL;
            $a['status']        = Application::STATUS_ACTIVE;
            $a['published_at']  = now();
            Application::updateOrCreate(['slug' => $a['slug']], $a);
        }
    }
}
