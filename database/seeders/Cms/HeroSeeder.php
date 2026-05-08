<?php

namespace Database\Seeders\Cms;

use App\Domain\Hero\Models\HeroHighlight;
use App\Domain\Hero\Models\HeroSection;
use Illuminate\Database\Seeder;

/**
 * Seeds 3 hero slides for the homepage carousel — each highlights a
 * different angle (perizinan / investasi / komitmen integritas).
 */
class HeroSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title'    => 'Layanan Perizinan Modern, Transparan & Akuntabel',
                'subtitle' => 'DPMPTSP Kota Surabaya',
                'description' => 'Pengajuan, penerbitan, dan tracking perizinan berbasis risiko (OSS RBA) — cepat, mudah, dan dapat dipertanggungjawabkan.',
                'cta_label'           => 'Ajukan Perizinan',
                'cta_url'             => '/layanan/perizinan-berusaha',
                'secondary_cta_label' => 'Tracking Izin',
                'secondary_cta_url'   => '/layanan/tracking',
                'running_text'        => 'Mal Pelayanan Publik Lt.3, Jl. Tunjungan No. 1-3 Genteng, Surabaya · Senin–Jumat 08.00–16.00 WIB · Layanan online 24 jam',
                'sort_order'          => 0,
            ],
            [
                'title'    => 'Klinik Investasi · Konsultasi Perizinan di Kota Surabaya',
                'subtitle' => 'DPM-PTSP Surabaya',
                'description' => 'Konsultasi pra-perizinan, pelaporan LKPM, hingga peluang investasi di kota metropolitan terbesar kedua di Indonesia.',
                'cta_label'           => 'Konsultasi Online',
                'cta_url'             => '/layanan/konsultasi-online',
                'secondary_cta_label' => 'Mengapa Surabaya',
                'secondary_cta_url'   => '/profil/mengapa-surabaya',
                'running_text'        => 'Surabaya — Kota Metropolitan ke-2 Indonesia · Bandara & Pelabuhan Internasional · Pendidikan & Kesehatan Bertaraf Internasional',
                'sort_order'          => 1,
            ],
            [
                'title'    => 'Komitmen WBK & WBBM — Pelayanan Publik Bersih, Bebas Pungli',
                'subtitle' => 'Zona Integritas DPMPTSP',
                'description' => 'Reformasi Birokrasi Pemerintah Kota Surabaya — membangun pelayanan yang akuntabel, transparan, dan bebas dari korupsi.',
                'cta_label'           => 'Lapor Pengaduan',
                'cta_url'             => '/pengaduan/lapor',
                'secondary_cta_label' => 'Zona Integritas',
                'secondary_cta_url'   => '/profil/zona-integritas',
                'running_text'        => 'Wilayah Bebas Korupsi · Wilayah Birokrasi Bersih Melayani · Reformasi Birokrasi Pemerintah Kota Surabaya',
                'sort_order'          => 2,
            ],
        ];

        foreach ($slides as $slide) {
            $hero = HeroSection::updateOrCreate(
                ['title' => $slide['title']],
                $slide + ['is_active' => true, 'published_at' => now()]
            );

            // Highlights only for the first slide (used in dedicated highlight section).
            if ($slide['sort_order'] === 0) {
                $highlights = [
                    ['title' => 'Pelayanan Cepat',  'description' => 'Pengajuan & penerbitan sesuai SLA.', 'icon' => 'clock'],
                    ['title' => 'Transparan',       'description' => 'Status pengajuan real-time.',       'icon' => 'eye'],
                    ['title' => 'Akuntabel',        'description' => 'Audit trail seluruh proses.',       'icon' => 'shield-check'],
                    ['title' => 'Bebas Pungli',     'description' => 'Komitmen ZI WBK/WBBM.',             'icon' => 'badge-check'],
                ];
                foreach ($highlights as $i => $h) {
                    HeroHighlight::updateOrCreate(
                        ['hero_section_id' => $hero->id, 'title' => $h['title']],
                        ['description' => $h['description'], 'icon' => $h['icon'], 'sort_order' => $i]
                    );
                }
            }
        }
    }
}
