<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use Database\Seeders\Cms\Support\RemoteImageDownloader;
use Illuminate\Database\Seeder;

/**
 * Sample news entries — content adapted from public information at
 * dpm-ptsp.surabaya.go.id. The first three items mirror the real headline
 * news on the source site at seed time and download their actual cover
 * images; the rest are evergreen articles with null cover_path that admin
 * can replace via Filament Media Library.
 */
class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('  ↻ downloading 3 news thumbnails');
        $author = \App\Models\User::query()->first();
        $downloader = new RemoteImageDownloader();

        // Categories for berita
        Category::updateOrCreate(['type' => 'post', 'slug' => 'investasi'], ['name' => 'Investasi', 'sort_order' => 0]);
        Category::updateOrCreate(['type' => 'post', 'slug' => 'pelayanan'], ['name' => 'Pelayanan', 'sort_order' => 1]);
        Category::updateOrCreate(['type' => 'post', 'slug' => 'pengumuman'], ['name' => 'Pengumuman', 'sort_order' => 2]);

        $catInvestasi = Category::where(['type' => 'post', 'slug' => 'investasi'])->first();
        $catPelayanan = Category::where(['type' => 'post', 'slug' => 'pelayanan'])->first();
        $catPengumuman = Category::where(['type' => 'post', 'slug' => 'pengumuman'])->first();

        $items = [
            // ─── Headline news (mirror of source site at seed time) ─────
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'kota-batam-pelajari-implementasi-pbg-di-surabaya',
                'title'   => 'Kota Batam Pelajari Implementasi PBG di Surabaya untuk Tingkatkan Layanan Perizinan',
                'excerpt' => 'DPMPTSP Kota Batam berkunjung ke Surabaya untuk mempelajari implementasi Persetujuan Bangunan Gedung (PBG).',
                'body'    => '<p>DPMPTSP Kota Batam melakukan kunjungan ke DPM-PTSP Kota Surabaya untuk mempelajari implementasi <strong>Persetujuan Bangunan Gedung (PBG)</strong>. Studi tiru ini bertujuan meningkatkan kualitas layanan perizinan bangunan di wilayah Kota Batam, dengan mengadopsi praktik baik penyelenggaraan PBG di Surabaya.</p><p>Selain mempelajari alur teknis PBG, delegasi juga berdiskusi tentang sistem informasi pendukung dan strategi sosialisasi kepada pelaku usaha.</p>',
                'featured'=> true,
                'days_ago'=> 6,
                'image_url' => 'https://dpm-ptsp.surabaya.go.id/fileberita/batam-pbg-mei-2026-2026-05-13(BERITA).jpeg',
                'image_filename' => 'batam-pbg.jpg',
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'festival-rujak-uleg-2026-semarakkan-hjks-733',
                'title'   => 'Festival Rujak Uleg 2026 Semarakkan HJKS ke-733, Ribuan Warga Padati SUBEC',
                'excerpt' => 'Festival Rujak Uleg tahunan menyemarakkan Hari Jadi Kota Surabaya ke-733 di kawasan SUBEC.',
                'body'    => '<p>Ribuan warga memadati kawasan <strong>Surabaya Convention & Exhibition Center (SUBEC)</strong> dalam Festival Rujak Uleg 2026, agenda tradisional yang menyemarakkan rangkaian peringatan <strong>Hari Jadi Kota Surabaya (HJKS) ke-733</strong>. Festival ini menjadi sarana promosi kuliner dan budaya khas Surabaya.</p>',
                'featured'=> true,
                'days_ago'=> 7,
                'image_url' => 'https://dpm-ptsp.surabaya.go.id/fileberita/Hjks-rujag-uleg-2026-2026-05-12(BERITA).jpg',
                'image_filename' => 'rujak-uleg.jpg',
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'studi-tiru-dpmptsp-gresik-pelaporan-lkpm',
                'title'   => 'DPMPTSP Surabaya Terima Kunjungan Studi Tiru DPMPTSP Gresik Terkait Pelaporan LKPM',
                'excerpt' => 'DPMPTSP Kabupaten Gresik berkunjung ke Surabaya untuk mempelajari optimalisasi pelaporan LKPM.',
                'body'    => '<p>DPM-PTSP Kabupaten Gresik melakukan kunjungan studi tiru ke DPM-PTSP Kota Surabaya terkait <strong>optimalisasi pelaporan LKPM (Laporan Kegiatan Penanaman Modal)</strong>. Kunjungan ini berfokus pada strategi pendampingan pelaku usaha, monitoring kepatuhan pelaporan, dan integrasi sistem dengan OSS RBA.</p>',
                'featured'=> false,
                'days_ago'=> 11,
                'image_url' => 'https://dpm-ptsp.surabaya.go.id/fileberita/dpmptspgresik-kunjungan-2026-2026-05-08(BERITA).jpeg',
                'image_filename' => 'dpmptsp-gresik.jpg',
            ],

            // ─── Evergreen articles (no source-side thumbnail) ──────────
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catInvestasi,
                'slug'    => 'klinik-investasi-konsultasi-perizinan-surabaya',
                'title'   => 'Klinik Investasi: Konsultasi Perizinan di Kota Surabaya',
                'excerpt' => 'DPMPTSP Surabaya membuka layanan Klinik Investasi sebagai sarana konsultasi pra-perizinan dan pengembangan iklim investasi.',
                'body'    => '<p>DPM-PTSP Kota Surabaya menyelenggarakan <strong>Klinik Investasi</strong> sebagai layanan konsultasi pra-perizinan dan informasi peluang investasi. Layanan ini dapat diakses tatap muka di <strong>Mal Pelayanan Publik Lt.3, Jl. Tunjungan No. 1-3 Genteng, Surabaya</strong>, maupun melalui kanal daring.</p><p>Topik konsultasi meliputi tata cara pengajuan NIB, persyaratan teknis sektor usaha, pelaporan LKPM, hingga peluang dan kemudahan investasi di Kota Surabaya.</p>',
                'featured'=> true,
                'days_ago'=> 25,
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'oss-rba-perizinan-berbasis-risiko',
                'title'   => 'OSS RBA: Perizinan Berusaha Berbasis Risiko Wajib bagi Pelaku Usaha',
                'excerpt' => 'Sistem OSS Berbasis Risiko wajib digunakan untuk seluruh perizinan berusaha sesuai UU Cipta Kerja.',
                'body'    => '<p>Sejak diberlakukannya <strong>UU Cipta Kerja</strong> dan <strong>PP No. 5 Tahun 2021</strong>, perizinan berusaha menggunakan pendekatan berbasis risiko (Risk-Based Approach / RBA) melalui sistem <a href="https://oss.go.id" target="_blank" rel="noopener">OSS Berbasis Risiko</a>.</p><p>OSS RBA wajib digunakan oleh pelaku usaha, kementerian/lembaga, pemerintah daerah, dan administrator KEK. Untuk perizinan kewenangan Kota Surabaya, pelaku usaha dapat menggunakan <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a>.</p>',
                'featured'=> true,
                'days_ago'=> 14,
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'mal-pelayanan-publik-surabaya-lokasi-dpmptsp',
                'title'   => 'DPMPTSP Surabaya Berlokasi di Mal Pelayanan Publik',
                'excerpt' => 'Layanan tatap muka DPMPTSP Surabaya kini terintegrasi dengan Mal Pelayanan Publik Surabaya.',
                'body'    => '<p>Layanan tatap muka <strong>DPM-PTSP Kota Surabaya</strong> dilayani di <strong>Mal Pelayanan Publik (MPP) Lt.3</strong>, Jl. Tunjungan No. 1-3 Genteng, Surabaya 60275. MPP merupakan pusat pelayanan publik terintegrasi Pemerintah Kota Surabaya yang menyatukan berbagai instansi dalam satu lokasi untuk memudahkan masyarakat.</p>',
                'featured'=> false,
                'days_ago'=> 30,
            ],
            [
                'type'    => Post::TYPE_ANNOUNCEMENT,
                'category'=> $catPengumuman,
                'slug'    => 'pelaporan-lkpm-wajib-pelaku-usaha',
                'title'   => 'Pengumuman: Wajib Lapor LKPM bagi Seluruh Pelaku Usaha',
                'excerpt' => 'Setiap pelaku usaha yang telah memperoleh perizinan berusaha wajib menyampaikan LKPM secara berkala.',
                'body'    => '<p>Sesuai ketentuan, setiap pelaku usaha yang telah memperoleh perizinan berusaha <strong>wajib menyampaikan Laporan Kegiatan Penanaman Modal (LKPM)</strong> secara berkala melalui sistem OSS.</p><p>Periodisasi LKPM mengikuti skala usaha:</p><ul><li><strong>Triwulanan</strong> — usaha menengah dan besar.</li><li><strong>Semester</strong> — usaha kecil.</li></ul><p>Pelaku usaha yang menemukan kendala pelaporan dapat menghubungi DPMPTSP Surabaya melalui <a href="/kontak">form Kontak</a> atau email <a href="mailto:dpm-ptsp@surabaya.go.id">dpm-ptsp@surabaya.go.id</a>.</p>',
                'featured'=> true,
                'days_ago'=> 3,
            ],
            [
                'type'    => Post::TYPE_ANNOUNCEMENT,
                'category'=> $catPengumuman,
                'slug'    => 'jadwal-pelayanan-dpmptsp-mpp',
                'title'   => 'Jadwal Pelayanan Tatap Muka DPMPTSP di MPP Surabaya',
                'excerpt' => 'Layanan tatap muka DPMPTSP buka Senin–Jumat 08.00–16.00 WIB. Layanan online 24 jam.',
                'body'    => '<p>Layanan tatap muka DPM-PTSP Kota Surabaya di <strong>Mal Pelayanan Publik Lt.3</strong> beroperasi:</p><ul><li>Senin – Jumat: 08.00 – 16.00 WIB</li><li>Sabtu, Minggu, dan hari libur nasional: tutup.</li></ul><p>Layanan online tersedia 24 jam melalui <a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a>, <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa</a>, dan website ini.</p>',
                'featured'=> false,
                'days_ago'=> 1,
            ],
            // -- Tambahan berita & pengumuman supaya marquee punya konten cukup
            [
                'type'    => Post::TYPE_ANNOUNCEMENT,
                'category'=> $catPengumuman,
                'slug'    => 'maintenance-sistem-perizinan-rutin',
                'title'   => 'Pengumuman: Maintenance Sistem Perizinan Setiap Sabtu Malam',
                'excerpt' => 'Sistem perizinan online dijadwalkan maintenance rutin setiap Sabtu pukul 22.00–24.00 WIB.',
                'body'    => '<p>Untuk menjaga kestabilan layanan, sistem perizinan online dijadwalkan menjalani <strong>pemeliharaan rutin setiap hari Sabtu pukul 22.00–24.00 WIB</strong>. Mohon menyelesaikan transaksi sebelum jadwal tersebut.</p>',
                'featured'=> false,
                'days_ago'=> 4,
            ],
            [
                'type'    => Post::TYPE_ANNOUNCEMENT,
                'category'=> $catPengumuman,
                'slug'    => 'libur-nasional-hari-raya-pelayanan',
                'title'   => 'Pengumuman: Libur Nasional Hari Raya — Layanan Tatap Muka Tutup',
                'excerpt' => 'Pelayanan tatap muka diliburkan pada hari raya nasional. Layanan online tetap 24 jam via OSS & SSW Alfa.',
                'body'    => '<p>Mengacu pada SE Pemkot Surabaya, layanan tatap muka DPMPTSP diliburkan pada hari raya nasional. Layanan online perizinan tetap dapat diakses 24 jam melalui <strong>OSS RBA</strong> dan <strong>SSW Alfa Surabaya</strong>.</p>',
                'featured'=> false,
                'days_ago'=> 6,
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catInvestasi,
                'slug'    => 'realisasi-investasi-surabaya-q1-2026',
                'title'   => 'Realisasi Investasi Surabaya Triwulan I 2026 Tembus Rp 8,9 Triliun',
                'excerpt' => 'Realisasi PMA dan PMDN Kota Surabaya pada Triwulan I 2026 menunjukkan tren positif.',
                'body'    => '<p>Realisasi investasi Kota Surabaya pada Triwulan I 2026 mencapai <strong>Rp 8,9 triliun</strong> (PMDN) dan <strong>USD 7,3 miliar</strong> (PMA), naik dibanding periode yang sama tahun sebelumnya. Sektor pengolahan, perdagangan, dan jasa keuangan menjadi penyumbang terbesar.</p>',
                'featured'=> true,
                'days_ago'=> 10,
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'sertifikat-iso-9001-pelayanan-perizinan',
                'title'   => 'DPMPTSP Surabaya Raih Sertifikasi ISO 9001:2015 Sistem Manajemen Mutu',
                'excerpt' => 'Sertifikasi ISO 9001:2015 menegaskan komitmen DPMPTSP Surabaya terhadap mutu pelayanan publik.',
                'body'    => '<p>DPM-PTSP Kota Surabaya berhasil meraih sertifikasi <strong>ISO 9001:2015</strong> Sistem Manajemen Mutu. Sertifikasi ini menjadi penegasan komitmen instansi terhadap pelayanan publik yang konsisten, transparan, dan akuntabel.</p>',
                'featured'=> false,
                'days_ago'=> 18,
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catInvestasi,
                'slug'    => 'forum-investasi-surabaya-2026',
                'title'   => 'Forum Investasi Surabaya 2026: Peluang & Kemudahan bagi Investor',
                'excerpt' => 'Forum Investasi Surabaya 2026 mempertemukan calon investor dengan Pemerintah Kota Surabaya.',
                'body'    => '<p>Pemerintah Kota Surabaya melalui DPM-PTSP menggelar <strong>Forum Investasi Surabaya 2026</strong> yang mempertemukan calon investor dengan stakeholder. Forum ini membahas peluang sektor unggulan, insentif fiskal, dan kemudahan perizinan.</p>',
                'featured'=> true,
                'days_ago'=> 22,
            ],
            [
                'type'    => Post::TYPE_NEWS,
                'category'=> $catPelayanan,
                'slug'    => 'survey-kepuasan-masyarakat-ikm-2025',
                'title'   => 'Survei Kepuasan Masyarakat 2025 — Indeks Pelayanan DPMPTSP 98 (Sangat Baik)',
                'excerpt' => 'Hasil Survei Kepuasan Masyarakat 2025 menunjukkan IKM DPMPTSP Surabaya sebesar 98 (Sangat Baik).',
                'body'    => '<p>Hasil Survei Kepuasan Masyarakat (SKM) tahun 2025 menempatkan DPM-PTSP Surabaya pada nilai indeks <strong>98 (Sangat Baik)</strong>. Komponen ketepatan waktu pelayanan dan kemudahan prosedur menjadi kontributor utama.</p>',
                'featured'=> false,
                'days_ago'=> 35,
            ],
        ];

        foreach ($items as $it) {
            $cover = null;
            if (! empty($it['image_url']) && ! empty($it['image_filename'])) {
                $cover = $downloader->fetch($it['image_url'], 'news', $it['image_filename']);
            }

            Post::updateOrCreate(
                ['slug' => $it['slug']],
                [
                    'type'         => $it['type'],
                    'category_id'  => $it['category']?->id,
                    'title'        => $it['title'],
                    'slug'         => $it['slug'],
                    'excerpt'      => $it['excerpt'],
                    'body'         => $it['body'],
                    'cover_path'   => $cover,
                    'status'       => Post::STATUS_PUBLISHED,
                    'is_featured'  => $it['featured'],
                    'author_id'    => $author?->id,
                    'published_at' => now()->subDays($it['days_ago']),
                ]
            );
        }
    }
}
