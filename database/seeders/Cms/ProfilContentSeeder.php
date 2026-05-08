<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Post;
use Illuminate\Database\Seeder;

/**
 * Seeds Post (type=profil) records keyed by the slugs that
 * ProfilController + LayananController look up. Content sourced from the
 * official DPMPTSP Surabaya site (dpm-ptsp.surabaya.go.id) — sections that
 * exist as text are filled verbatim/lightly-rewritten; sections that exist
 * only as images on the source (visi-misi, struktur, SOP) get a placeholder
 * body referring admin to the upload area in CMS.
 */
class ProfilContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $author = \App\Models\User::query()->first();

        $items = [
            // ---------------- PROFIL ----------------
            [
                'slug'  => 'profil-dpmptsp',
                'title' => 'Profil DPMPTSP Kota Surabaya',
                'excerpt' => 'Lembaga yang memegang peranan dan fungsi strategis di bidang penyelenggaraan pelayanan perizinan terpadu satu pintu di Kota Surabaya.',
                'body'  => <<<'HTML'
<p>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPM-PTSP) Kota Surabaya adalah perangkat daerah yang memegang peranan dan fungsi strategis di bidang penyelenggaraan pelayanan perizinan terpadu satu pintu serta pengembangan iklim investasi di Kota Surabaya.</p>

<p>Berdasarkan visi DPM-PTSP Kota Surabaya, terdapat tiga poin pokok yang menjadi fokus organisasi:</p>
<ol>
    <li><strong>Penanaman Modal & Investasi</strong> — mendorong tumbuhnya iklim investasi yang sehat dan berkelanjutan di Kota Surabaya.</li>
    <li><strong>Pelayanan Perizinan Prima</strong> — menyelenggarakan pelayanan perizinan yang cepat, mudah, transparan, dan akuntabel melalui mekanisme satu pintu.</li>
    <li><strong>Teknologi Informasi</strong> — memanfaatkan teknologi informasi sebagai akselerator transformasi pelayanan publik.</li>
</ol>

<p>DPM-PTSP berkantor di Mal Pelayanan Publik (MPP) Lt.3, Jl. Tunjungan No. 1-3 Genteng, Surabaya 60275 — bagian dari pusat layanan publik terintegrasi Kota Surabaya.</p>

<p><em>Sumber: <a href="https://dpm-ptsp.surabaya.go.id/tentang.php" rel="noopener" target="_blank">dpm-ptsp.surabaya.go.id/tentang.php</a></em></p>
HTML,
            ],

            // ---------------- VISI MISI ----------------
            [
                'slug'  => 'visi-misi',
                'title' => 'Visi & Misi DPMPTSP Kota Surabaya',
                'excerpt' => 'Visi dan Misi DPMPTSP Surabaya selaras dengan RPJMD Kota Surabaya 2021–2026.',
                'body'  => <<<'HTML'
<p>Visi dan Misi DPM-PTSP Kota Surabaya selaras dengan visi dan misi Walikota Surabaya yang dituangkan dalam <strong>RPJMD Kota Surabaya 2021–2026</strong>, dengan tiga fokus utama: penanaman modal, pelayanan perizinan prima, dan transformasi digital pelayanan publik.</p>

<blockquote class="border-l-4 border-primary-700 pl-4 italic">Dokumen Visi-Misi resmi DPM-PTSP Surabaya tersedia dalam bentuk infografis dan dapat diunduh pada <a href="/informasi/dokumen">Download Center</a>.</blockquote>

<h2>Tiga Fokus Strategis</h2>
<ol>
    <li><strong>Mendorong Investasi</strong> — menciptakan iklim investasi yang sehat, kondusif, dan berkelanjutan di Kota Surabaya.</li>
    <li><strong>Pelayanan Perizinan Prima</strong> — menyelenggarakan pelayanan perizinan yang cepat, akurat, transparan, dan akuntabel melalui sistem satu pintu (PTSP).</li>
    <li><strong>Transformasi Digital</strong> — memanfaatkan teknologi informasi untuk mempermudah akses, mempercepat proses, dan memperluas jangkauan pelayanan kepada masyarakat dan pelaku usaha.</li>
</ol>

<p>Dokumen lengkap Renstra 2021-2026 yang berisi visi-misi, tujuan, sasaran, strategi, dan program DPM-PTSP dapat diakses pada <a href="/informasi/dokumen">Download Center</a>.</p>
HTML,
            ],

            // ---------------- STRUKTUR ----------------
            [
                'slug'  => 'struktur-organisasi',
                'title' => 'Struktur Organisasi',
                'excerpt' => 'Struktur Organisasi DPMPTSP Kota Surabaya per tahun 2026.',
                'body'  => <<<'HTML'
<p>Struktur Organisasi DPM-PTSP Kota Surabaya disusun untuk mendukung pelaksanaan tugas pokok di bidang penanaman modal dan pelayanan perizinan terpadu satu pintu.</p>

<p>Susunan organisasi terdiri dari:</p>
<ul>
    <li><strong>Kepala Dinas</strong></li>
    <li><strong>Sekretariat</strong>, terdiri dari sub-bagian umum & kepegawaian, keuangan, serta perencanaan & evaluasi.</li>
    <li><strong>Bidang Penanaman Modal</strong> — menangani promosi, kerja sama, dan pengendalian investasi.</li>
    <li><strong>Bidang Pelayanan Perizinan</strong> — penerbitan, pengawasan, dan pengaduan perizinan.</li>
    <li><strong>Bidang Pengelolaan Data & Sistem Informasi</strong> — pengelolaan data investasi & perizinan, pengembangan sistem.</li>
    <li><strong>Kelompok Jabatan Fungsional</strong></li>
</ul>

<blockquote class="border-l-4 border-primary-700 pl-4 italic">Bagan struktur organisasi resmi tersedia dalam bentuk infografis pada <a href="/informasi/dokumen">Download Center</a>.</blockquote>
HTML,
            ],

            // ---------------- TUGAS & FUNGSI ----------------
            [
                'slug'  => 'tugas-dan-fungsi',
                'title' => 'Tugas & Fungsi',
                'excerpt' => 'Pelaksanaan urusan pemerintahan bidang penanaman modal serta penyelenggaraan pelayanan terpadu satu pintu di Kota Surabaya.',
                'body'  => <<<'HTML'
<h2>Tugas Pokok</h2>
<p>DPM-PTSP Kota Surabaya mempunyai tugas membantu Walikota dalam melaksanakan urusan pemerintahan bidang <strong>penanaman modal</strong> serta penyelenggaraan <strong>pelayanan perizinan terpadu satu pintu</strong> yang menjadi kewenangan daerah.</p>

<h2>Fungsi</h2>
<ol>
    <li>Perumusan kebijakan teknis di bidang penanaman modal dan pelayanan perizinan.</li>
    <li>Pelaksanaan kebijakan promosi, kerja sama, dan pengembangan investasi.</li>
    <li>Penyelenggaraan pelayanan perizinan dan non-perizinan secara terpadu satu pintu (PTSP).</li>
    <li>Pengendalian dan pengawasan pelaksanaan penanaman modal serta tindak lanjut atas perizinan yang diterbitkan.</li>
    <li>Pengelolaan data dan sistem informasi penanaman modal serta perizinan.</li>
    <li>Penanganan pengaduan masyarakat terhadap pelayanan perizinan.</li>
    <li>Pelaksanaan administrasi Dinas dan tugas lain yang diberikan Walikota sesuai dengan tugas dan fungsinya.</li>
</ol>
HTML,
            ],

            // ---------------- MAKLUMAT ----------------
            [
                'slug'  => 'maklumat-pelayanan',
                'title' => 'Maklumat Pelayanan',
                'excerpt' => 'Komitmen DPMPTSP Surabaya kepada masyarakat untuk pelayanan yang prima.',
                'body'  => <<<'HTML'
<p>Dengan ini kami, segenap aparatur DPM-PTSP Kota Surabaya, menyatakan <strong>sanggup menyelenggarakan pelayanan publik sesuai standar pelayanan</strong> yang telah ditetapkan, dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundangan yang berlaku.</p>

<p>Komitmen kami:</p>
<ul>
    <li>Pelayanan yang <strong>cepat, mudah, transparan, dan akuntabel</strong>.</li>
    <li>Bebas pungutan liar (pungli) dan gratifikasi sesuai prinsip Zona Integritas.</li>
    <li>Memenuhi Standar Pelayanan Minimum (SPM) sesuai peraturan yang berlaku.</li>
    <li>Menerima dan menindaklanjuti pengaduan masyarakat sesuai SOP.</li>
</ul>

<blockquote class="border-l-4 border-primary-700 pl-4 italic">Naskah maklumat resmi yang ditandatangani Kepala Dinas dapat diunduh pada <a href="/informasi/dokumen">Download Center</a>.</blockquote>
HTML,
            ],

            // ---------------- SOP ----------------
            [
                'slug'  => 'sop-pelayanan',
                'title' => 'SOP Pelayanan',
                'excerpt' => 'Standar Operasional Prosedur pelayanan perizinan dan non-perizinan.',
                'body'  => <<<'HTML'
<p>Setiap jenis layanan di DPM-PTSP Surabaya memiliki <strong>Standar Operasional Prosedur (SOP)</strong> yang mengatur alur, persyaratan, jangka waktu, dan unit kerja yang menangani.</p>

<p>SOP pelayanan tersedia untuk:</p>
<ul>
    <li>Perizinan Berusaha berbasis risiko (OSS RBA).</li>
    <li>Penerbitan dan perubahan NIB (Nomor Induk Berusaha).</li>
    <li>Layanan non-perizinan (rekomendasi, surat keterangan).</li>
    <li>Pengaduan dan tindak lanjut pelayanan.</li>
    <li>Konsultasi pra-perizinan (Klinik Investasi).</li>
</ul>

<p>Dokumen SOP lengkap dapat diunduh pada <a href="/informasi/dokumen">Download Center</a> atau <a href="/informasi/regulasi">menu Regulasi</a>.</p>
HTML,
            ],

            // ---------------- STANDAR ----------------
            [
                'slug'  => 'standar-pelayanan',
                'title' => 'Standar Pelayanan',
                'excerpt' => 'Daftar jenis layanan, persyaratan, jangka waktu, biaya, dan saluran pengaduan.',
                'body'  => <<<'HTML'
<p>Standar Pelayanan DPM-PTSP Kota Surabaya disusun sesuai amanat <strong>UU No. 25 Tahun 2009 tentang Pelayanan Publik</strong> dan peraturan turunannya, yang memuat 14 komponen meliputi:</p>
<ol>
    <li>Dasar hukum</li>
    <li>Persyaratan pelayanan</li>
    <li>Sistem, mekanisme, dan prosedur</li>
    <li>Jangka waktu penyelesaian</li>
    <li>Biaya / tarif</li>
    <li>Produk pelayanan</li>
    <li>Sarana, prasarana, atau fasilitas</li>
    <li>Kompetensi pelaksana</li>
    <li>Pengawasan internal</li>
    <li>Penanganan pengaduan, saran, dan masukan</li>
    <li>Jumlah pelaksana</li>
    <li>Jaminan pelayanan</li>
    <li>Jaminan keamanan dan keselamatan pelayanan</li>
    <li>Evaluasi kinerja pelaksana</li>
</ol>
<p>Detail per-jenis layanan dapat dilihat di Aplikasi Sistem Perizinan (SSW) atau <a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a>.</p>
HTML,
            ],

            // ---------------- REFORMASI ----------------
            [
                'slug'  => 'reformasi-birokrasi',
                'title' => 'Reformasi Birokrasi',
                'excerpt' => 'Komitmen DPMPTSP dalam pelaksanaan Reformasi Birokrasi Pemerintah Kota Surabaya.',
                'body'  => <<<'HTML'
<p>DPM-PTSP Kota Surabaya melaksanakan program <strong>Reformasi Birokrasi</strong> sebagai bagian dari komitmen Pemerintah Kota Surabaya untuk membangun aparatur pemerintahan yang profesional, akuntabel, bersih, dan melayani.</p>

<p>Delapan area perubahan Reformasi Birokrasi:</p>
<ol>
    <li>Manajemen Perubahan</li>
    <li>Deregulasi Kebijakan</li>
    <li>Penataan Organisasi</li>
    <li>Penataan Tata Laksana</li>
    <li>Penataan SDM Aparatur</li>
    <li>Penguatan Akuntabilitas</li>
    <li>Penguatan Pengawasan</li>
    <li>Peningkatan Kualitas Pelayanan Publik</li>
</ol>
HTML,
            ],

            // ---------------- ZONA INTEGRITAS ----------------
            [
                'slug'  => 'zona-integritas',
                'title' => 'Zona Integritas',
                'excerpt' => 'Komitmen pencegahan korupsi dan peningkatan kualitas pelayanan publik.',
                'body'  => <<<'HTML'
<p><strong>Zona Integritas (ZI)</strong> adalah predikat yang diberikan kepada instansi pemerintah yang pimpinan dan jajarannya mempunyai komitmen untuk mewujudkan WBK/WBBM melalui upaya pencegahan korupsi, reformasi birokrasi, dan peningkatan kualitas pelayanan publik.</p>

<h2>Komponen Pengungkit</h2>
<ol>
    <li>Manajemen Perubahan</li>
    <li>Penataan Tatalaksana</li>
    <li>Penataan Sistem Manajemen SDM</li>
    <li>Penguatan Akuntabilitas</li>
    <li>Penguatan Pengawasan</li>
    <li>Peningkatan Kualitas Pelayanan Publik</li>
</ol>

<h2>Komponen Hasil</h2>
<ul>
    <li>Pemerintahan yang bersih dan bebas KKN.</li>
    <li>Kualitas pelayanan publik kepada masyarakat.</li>
</ul>
HTML,
            ],

            // ---------------- WBK/WBBM ----------------
            [
                'slug'  => 'wbk-wbbm',
                'title' => 'WBK / WBBM',
                'excerpt' => 'Wilayah Bebas Korupsi & Wilayah Birokrasi Bersih Melayani.',
                'body'  => <<<'HTML'
<h2>Wilayah Bebas Korupsi (WBK)</h2>
<p>Predikat yang diberikan kepada unit kerja yang memenuhi sebagian besar manajemen perubahan, penataan tatalaksana, penataan sistem manajemen SDM, penguatan pengawasan, dan penguatan akuntabilitas kinerja.</p>

<h2>Wilayah Birokrasi Bersih Melayani (WBBM)</h2>
<p>Predikat lanjutan dari WBK yang diberikan kepada unit kerja yang memenuhi sebagian besar manajemen perubahan, penataan tatalaksana, penataan sistem manajemen SDM, penguatan pengawasan, penguatan akuntabilitas kinerja, dan <strong>peningkatan kualitas pelayanan publik</strong>.</p>

<p>DPM-PTSP Kota Surabaya berkomitmen membangun zona pelayanan yang bersih dan melayani sesuai amanat <strong>Permenpan-RB Nomor 90 Tahun 2021</strong> tentang Pembangunan dan Evaluasi Zona Integritas.</p>
HTML,
            ],

            // ---------------- LAYANAN ROOT ----------------
            [
                'slug'  => 'layanan',
                'title' => 'Layanan DPMPTSP Kota Surabaya',
                'excerpt' => 'Pelayanan perizinan, non-perizinan, dan konsultasi investasi DPMPTSP Surabaya.',
                'body'  => <<<'HTML'
<p>DPM-PTSP Kota Surabaya menyelenggarakan layanan publik di bidang perizinan dan penanaman modal yang dapat diakses melalui <strong>Mal Pelayanan Publik (MPP)</strong> di Jl. Tunjungan No. 1-3 atau melalui kanal digital terintegrasi.</p>

<h2>Kategori Layanan</h2>
<ul>
    <li><a href="/layanan/perizinan-berusaha">Perizinan Berusaha</a> — berbasis risiko via OSS RBA.</li>
    <li><a href="/layanan/non-perizinan">Non-Perizinan</a> — rekomendasi, surat keterangan.</li>
    <li><a href="/layanan/oss">OSS RBA</a> — sistem perizinan berusaha terintegrasi nasional.</li>
    <li><a href="/layanan/tracking">Tracking Perizinan</a> — pantau status izin Anda.</li>
    <li><a href="/layanan/konsultasi-online">Konsultasi Online</a> — Klinik Investasi.</li>
    <li><a href="/layanan/antrian-online">Antrian Online</a> — pendaftaran tatap muka.</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'perizinan-berusaha',
                'title' => 'Perizinan Berusaha (OSS RBA)',
                'excerpt' => 'Pengajuan izin usaha berbasis risiko via Online Single Submission Risk Based Approach.',
                'body'  => <<<'HTML'
<p>Sejak diberlakukannya <strong>UU Cipta Kerja</strong> dan <strong>PP No. 5 Tahun 2021</strong>, perizinan berusaha menggunakan pendekatan berbasis risiko (Risk-Based Approach / RBA) melalui sistem <strong>OSS Berbasis Risiko</strong> yang dikelola Kementerian Investasi/BKPM RI.</p>

<h2>Kategori Risiko</h2>
<ul>
    <li><strong>Risiko Rendah (R)</strong> — cukup NIB.</li>
    <li><strong>Risiko Menengah Rendah (MR)</strong> — NIB + Sertifikat Standar pernyataan mandiri.</li>
    <li><strong>Risiko Menengah Tinggi (MT)</strong> — NIB + Sertifikat Standar terverifikasi.</li>
    <li><strong>Risiko Tinggi (T)</strong> — NIB + Izin & Sertifikat Standar.</li>
</ul>

<p>Pengajuan dilakukan melalui:</p>
<ul>
    <li><a href="https://oss.go.id" target="_blank" rel="noopener">oss.go.id</a> — OSS RBA Nasional</li>
    <li><a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">sswalfa.surabaya.go.id</a> — Sistem Perizinan Surabaya (SSW)</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'oss',
                'title' => 'Online Single Submission (OSS RBA)',
                'excerpt' => 'Sistem perizinan berusaha terintegrasi yang dikelola Kementerian Investasi/BKPM.',
                'body'  => <<<'HTML'
<p><strong>Online Single Submission (OSS) Berbasis Risiko</strong> adalah sistem perizinan berusaha terintegrasi secara elektronik yang wajib digunakan oleh:</p>
<ul>
    <li>Pelaku usaha (perseorangan, badan usaha, kantor perwakilan, badan usaha luar negeri).</li>
    <li>Kementerian, Lembaga, dan Pemerintah Daerah.</li>
    <li>Administrator Kawasan Ekonomi Khusus.</li>
    <li>Badan Pengusahaan Kawasan Perdagangan Bebas dan Pelabuhan Bebas.</li>
</ul>

<p>Akses sistem: <a href="https://oss.go.id" target="_blank" rel="noopener">https://oss.go.id</a></p>
HTML,
            ],

            [
                'slug'  => 'tracking-perizinan',
                'title' => 'Tracking Perizinan',
                'excerpt' => 'Pantau status pengajuan izin Anda secara real-time.',
                'body'  => <<<'HTML'
<p>Pemantauan status pengajuan izin dapat dilakukan melalui:</p>
<ul>
    <li><a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a> — untuk perizinan nasional.</li>
    <li><a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a> — untuk perizinan kewenangan Kota Surabaya.</li>
    <li><a href="https://dpm-ptsp.surabaya.go.id/sipintar/" target="_blank" rel="noopener">SIPINTAR</a> — sistem informasi pintar DPMPTSP.</li>
</ul>

<p>Tracking memerlukan <strong>nomor permohonan</strong> atau <strong>NIB</strong> Anda.</p>
HTML,
            ],

            [
                'slug'  => 'konsultasi-online',
                'title' => 'Konsultasi Online — Klinik Investasi',
                'excerpt' => 'Konsultasi perizinan dan investasi tatap muka maupun daring.',
                'body'  => <<<'HTML'
<p><strong>Klinik Investasi DPM-PTSP Surabaya</strong> melayani konsultasi pra-perizinan dan informasi peluang investasi di Kota Surabaya, baik secara tatap muka di Mal Pelayanan Publik maupun secara daring melalui kanal resmi.</p>

<p>Topik konsultasi meliputi:</p>
<ul>
    <li>Tata cara pengajuan NIB dan izin berusaha.</li>
    <li>Persyaratan teknis sektor usaha tertentu.</li>
    <li>Pelaporan LKPM (Laporan Kegiatan Penanaman Modal).</li>
    <li>Peluang investasi dan kemudahan usaha di Kota Surabaya.</li>
</ul>

<h2>Kanal Konsultasi</h2>
<ul>
    <li>Tatap muka: Mal Pelayanan Publik Lt.3, Jl. Tunjungan No. 1-3, Surabaya.</li>
    <li>Email: <a href="mailto:dpm-ptsp@surabaya.go.id">dpm-ptsp@surabaya.go.id</a></li>
    <li>Telepon: +62 (031) 99243924</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'mengapa-surabaya',
                'title' => 'Mengapa Investasi di Surabaya',
                'excerpt' => 'Enam alasan strategis untuk berinvestasi di Kota Surabaya.',
                'body'  => <<<'HTML'
<ol>
    <li><strong>Iklim Investasi Kondusif</strong> — Pembangunan Kota Surabaya tumbuh sangat cepat dan didukung kemudahan perizinan terpadu satu pintu.</li>
    <li><strong>Kota Metropolitan Terbesar Kedua</strong> — Surabaya merupakan kota metropolitan terbesar kedua di Indonesia setelah Jakarta.</li>
    <li><strong>Infrastruktur Transportasi Bertaraf Internasional</strong> — Bandara Internasional Juanda dan Pelabuhan Tanjung Perak berstandar dan bertaraf internasional.</li>
    <li><strong>Fasilitas Kesehatan Bertaraf Internasional</strong> — Standar pelayanan kesehatan bertaraf internasional dengan dukungan rumah sakit pendidikan terkemuka.</li>
    <li><strong>Fasilitas Umum Terjaga</strong> — Surabaya dikenal sebagai "kota 1000 taman" dengan fasilitas umum yang terawat sangat baik.</li>
    <li><strong>Fasilitas Pendidikan Bertaraf Internasional</strong> — Universitas negeri dan swasta yang terkemuka secara nasional dan internasional.</li>
</ol>
<p><em>Sumber: <a href="https://dpm-ptsp.surabaya.go.id/mengapa.php" target="_blank" rel="noopener">dpm-ptsp.surabaya.go.id/mengapa.php</a></em></p>
HTML,
            ],
        ];

        foreach ($items as $item) {
            Post::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'type'         => Post::TYPE_PROFIL,
                    'title'        => $item['title'],
                    'slug'         => $item['slug'],
                    'excerpt'      => $item['excerpt'],
                    'body'         => $item['body'],
                    'status'       => Post::STATUS_PUBLISHED,
                    'author_id'    => $author?->id,
                    'published_at' => $now,
                ]
            );
        }
    }
}
