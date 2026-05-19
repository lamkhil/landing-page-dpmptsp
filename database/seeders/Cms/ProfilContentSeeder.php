<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Post;
use Database\Seeders\Cms\Support\RemoteImageDownloader;
use Illuminate\Database\Seeder;

/**
 * Seeds Post (type=profil) records consumed by ProfilController and
 * LayananController. The slugs here MUST match ProfilController::SLUG_MAP
 * exactly — otherwise the routed pages render the "Konten sedang disiapkan"
 * placeholder.
 *
 * Content is sourced from dpm-ptsp.surabaya.go.id/tentang.php — most of
 * that page consists of infographic images, so we download the source
 * graphics (visi-misi, maklumat, struktur, motto, budaya layanan,
 * kompensasi) into storage/app/public/seed/profil/ and embed them inline
 * via <img> tags. Admin can replace per-section via Filament Media Library.
 */
class ProfilContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('  ↻ downloading 9 profil/tentangfile images (visi-misi, maklumat, struktur, motto, dll)');
        $now = now();
        $author = \App\Models\User::query()->first();
        $dl = new RemoteImageDownloader();

        $base = 'https://dpm-ptsp.surabaya.go.id/';

        // Download all infographics + photos up front so we can embed paths
        // by reference in the body HTML below.
        $img = [
            'siola'       => $dl->fetch($base.'tentangfile/siola.jpeg',                            'profil', 'siola.jpeg'),
            'visi_misi'   => $dl->fetch($base.'tentangfile/visi_misi_2025.jpg',                    'profil', 'visi-misi.jpg'),
            'maklumat'    => $dl->fetch($base.'tentangfile/maklumat_pelayanan_2025_terbaru.jpeg',  'profil', 'maklumat.jpeg'),
            'struktur'    => $dl->fetch($base.'tentangfile/Struktur Organisasi-2026-03-12(Struktur Organisasi)_0.jpg', 'profil', 'struktur-organisasi.jpg'),
            'motto'       => $dl->fetch($base.'tentangfile/motto-2025.png',                        'profil', 'motto.png'),
            'budaya'      => $dl->fetch($base.'tentangfile/budaya-layanan-2025.jpeg',              'profil', 'budaya-layanan.jpeg'),
            'kompensasi'  => $dl->fetch($base.'tentangfile/Kompensasi layanan-2025-07-08(Kompensasi Layanan Organisasi)_0.jpeg', 'profil', 'kompensasi-layanan.jpeg'),
            'perwali'     => $dl->fetch($base.'tentangfile/Peraturan_Walikota.jpg',                'profil', 'peraturan-walikota.jpg'),
        ];

        // Helper: build a /storage URL for embedding in body HTML, or empty
        // string if the download failed (keeps body still renderable).
        $url = fn (?string $path) => $path ? '/storage/'.$path : '';

        $items = [
            // ─────────────── PROFIL ───────────────
            [
                'slug'  => 'profil-dpmptsp-kota-surabaya',
                'title' => 'Profil DPMPTSP Kota Surabaya',
                'cover' => $img['siola'],
                'excerpt' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPM-PTSP) Kota Surabaya — lembaga strategis di bidang penanaman modal dan pelayanan perizinan terpadu satu pintu.',
                'body' => <<<HTML
<p><strong>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPM-PTSP) Kota Surabaya</strong> adalah perangkat daerah yang memegang peranan dan fungsi strategis di bidang penyelenggaraan pelayanan perizinan terpadu satu pintu serta pengembangan iklim investasi di Kota Surabaya.</p>

<figure><img src="{$url($img['siola'])}" alt="Mal Pelayanan Publik Siola — Kantor DPMPTSP Surabaya" loading="lazy" /><figcaption>Mal Pelayanan Publik (Siola) Lt.3 — Kantor DPMPTSP Kota Surabaya</figcaption></figure>

<p>Tiga pilar utama yang menjadi fokus organisasi:</p>
<ol>
    <li><strong>Mendorong Investasi & Penanaman Modal</strong> — menciptakan iklim investasi yang sehat, kondusif, dan berkelanjutan di Kota Surabaya.</li>
    <li><strong>Pelayanan Perizinan Prima</strong> — menyelenggarakan pelayanan perizinan yang cepat, mudah, transparan, dan akuntabel melalui mekanisme satu pintu (PTSP).</li>
    <li><strong>Pemanfaatan Teknologi Informasi</strong> — memanfaatkan TIK sebagai akselerator transformasi pelayanan publik, dengan sistem seperti SSW Alfa Surabaya, SIPINTAR, dan integrasi OSS RBA.</li>
</ol>

<h2>Lokasi Kantor</h2>
<p>DPM-PTSP berkantor di <strong>Mal Pelayanan Publik (MPP) Siola, Lantai 3, Jl. Tunjungan No. 1-3 Genteng, Surabaya 60275</strong> — bagian dari pusat layanan publik terintegrasi Pemerintah Kota Surabaya yang menyatukan berbagai instansi dalam satu lokasi.</p>

<h2>Jam Pelayanan</h2>
<ul>
    <li>Senin – Jumat: 08.00 – 16.00 WIB (layanan tatap muka di MPP)</li>
    <li>Sabtu, Minggu, dan hari libur nasional: tutup</li>
    <li>Layanan online 24 jam melalui <a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a> dan <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a></li>
</ul>

<p><em>Sumber: <a href="https://dpm-ptsp.surabaya.go.id/tentang.php" rel="noopener" target="_blank">dpm-ptsp.surabaya.go.id/tentang.php</a></em></p>
HTML,
            ],

            // ─────────────── VISI & MISI ───────────────
            [
                'slug'  => 'visi-misi-dpmptsp-kota-surabaya',
                'title' => 'Visi & Misi DPMPTSP Kota Surabaya',
                'cover' => $img['visi_misi'],
                'excerpt' => 'Visi dan Misi DPMPTSP Surabaya selaras dengan RPJMD Kota Surabaya 2021–2026 — gotong royong menuju Surabaya kota dunia yang maju, humanis, dan berkelanjutan.',
                'body' => <<<HTML
<p>Visi dan Misi DPM-PTSP Kota Surabaya selaras dengan visi dan misi Walikota Surabaya sebagaimana dituangkan dalam <strong>Rencana Pembangunan Jangka Menengah Daerah (RPJMD) Kota Surabaya Tahun 2021–2026</strong>.</p>

<figure><img src="{$url($img['visi_misi'])}" alt="Infografis Visi & Misi DPMPTSP Surabaya 2025" loading="lazy" /><figcaption>Visi & Misi DPMPTSP Kota Surabaya — sumber resmi: dpm-ptsp.surabaya.go.id</figcaption></figure>

<h2>Visi</h2>
<blockquote><p><em>"Gotong-royong menuju Surabaya Kota Dunia yang maju, humanis, dan berkelanjutan."</em></p></blockquote>

<h2>Misi</h2>
<ol>
    <li>Menciptakan lapangan pekerjaan seluas-luasnya, perlindungan pekerja, mengembangkan UMKM, koperasi, ekonomi kreatif dan pemberdayaan pelaku usaha ekonomi lokal yang berdaya saing global.</li>
    <li>Mewujudkan pelayanan publik yang profesional, modern, dan akuntabel dengan ditunjang teknologi informasi yang terintegrasi.</li>
    <li>Memantapkan penyelenggaraan pemerintahan kolaboratif yang efektif, transparan, dan bertanggung jawab untuk mempercepat pencapaian kesejahteraan masyarakat.</li>
</ol>

<h2>Tiga Fokus Strategis DPM-PTSP</h2>
<ol>
    <li><strong>Mendorong Investasi</strong> — menciptakan iklim investasi yang sehat, kondusif, dan berkelanjutan.</li>
    <li><strong>Pelayanan Perizinan Prima</strong> — pelayanan yang cepat, akurat, transparan, dan akuntabel melalui sistem satu pintu (PTSP).</li>
    <li><strong>Transformasi Digital</strong> — memanfaatkan teknologi informasi untuk mempermudah akses, mempercepat proses, dan memperluas jangkauan pelayanan kepada masyarakat dan pelaku usaha.</li>
</ol>

<p>Dokumen lengkap Renstra DPMPTSP 2021-2026 dan RPJMD Kota Surabaya 2021-2026 tersedia pada <a href="/informasi/dokumen">Download Center</a>.</p>
HTML,
            ],

            // ─────────────── STRUKTUR ───────────────
            [
                'slug'  => 'struktur-organisasi',
                'title' => 'Struktur Organisasi',
                'cover' => $img['struktur'],
                'excerpt' => 'Susunan organisasi DPMPTSP Kota Surabaya — Kepala Dinas, Sekretariat, dan bidang-bidang pelaksana tugas pelayanan perizinan dan penanaman modal.',
                'body' => <<<HTML
<p>Struktur Organisasi DPM-PTSP Kota Surabaya disusun berdasarkan <strong>Peraturan Walikota Surabaya Nomor 52 Tahun 2023</strong> tentang Kedudukan, Susunan Organisasi, Tugas, Fungsi dan Tata Kerja DPM-PTSP, untuk mendukung pelaksanaan tugas pokok di bidang penanaman modal dan pelayanan perizinan terpadu satu pintu.</p>

<figure><img src="{$url($img['struktur'])}" alt="Bagan Struktur Organisasi DPMPTSP Kota Surabaya 2026" loading="lazy" /><figcaption>Struktur Organisasi DPMPTSP Kota Surabaya — diperbarui Maret 2026</figcaption></figure>

<h2>Susunan Organisasi</h2>
<ol>
    <li><strong>Kepala Dinas</strong></li>
    <li><strong>Sekretariat</strong>, membawahi:
        <ul>
            <li>Sub Bagian Umum dan Kepegawaian</li>
            <li>Sub Bagian Keuangan</li>
            <li>Sub Bagian Perencanaan dan Evaluasi</li>
        </ul>
    </li>
    <li><strong>Bidang Penanaman Modal</strong> — menangani promosi, kerja sama, pengembangan, pengendalian, dan pengawasan investasi di Kota Surabaya.</li>
    <li><strong>Bidang Pelayanan Perizinan</strong> — penerbitan izin berbasis risiko (OSS RBA), pengawasan penyelenggaraan perizinan, dan penanganan pengaduan perizinan.</li>
    <li><strong>Bidang Pengelolaan Data dan Sistem Informasi</strong> — pengelolaan data investasi & perizinan, pengembangan dan integrasi sistem informasi (SSW Alfa, SIPINTAR).</li>
    <li><strong>Kelompok Jabatan Fungsional</strong></li>
</ol>

<p><em>Dasar hukum: Peraturan Walikota Surabaya Nomor 52 Tahun 2023.</em></p>
HTML,
            ],

            // ─────────────── TUGAS & FUNGSI ───────────────
            [
                'slug'  => 'tugas-fungsi',
                'title' => 'Tugas & Fungsi',
                'cover' => $img['perwali'],
                'excerpt' => 'Pelaksanaan urusan pemerintahan bidang penanaman modal serta penyelenggaraan pelayanan perizinan terpadu satu pintu di Kota Surabaya.',
                'body' => <<<HTML
<h2>Tugas Pokok</h2>
<p>DPM-PTSP Kota Surabaya mempunyai tugas membantu Walikota dalam melaksanakan urusan pemerintahan bidang <strong>penanaman modal</strong> serta penyelenggaraan <strong>pelayanan perizinan terpadu satu pintu (PTSP)</strong> yang menjadi kewenangan daerah.</p>

<figure><img src="{$url($img['perwali'])}" alt="Peraturan Walikota Surabaya tentang DPMPTSP" loading="lazy" /><figcaption>Peraturan Walikota Surabaya Nomor 52 Tahun 2023 — dasar hukum tugas & fungsi DPMPTSP</figcaption></figure>

<h2>Fungsi</h2>
<ol>
    <li>Perumusan kebijakan teknis di bidang penanaman modal dan pelayanan perizinan.</li>
    <li>Pelaksanaan kebijakan promosi, kerja sama, dan pengembangan investasi.</li>
    <li>Penyelenggaraan pelayanan perizinan dan non-perizinan secara terpadu satu pintu (PTSP) sesuai mekanisme OSS RBA.</li>
    <li>Pengendalian dan pengawasan pelaksanaan penanaman modal serta tindak lanjut atas perizinan yang diterbitkan.</li>
    <li>Pengelolaan data dan sistem informasi penanaman modal serta perizinan.</li>
    <li>Penanganan pengaduan masyarakat terhadap pelayanan perizinan.</li>
    <li>Pelaksanaan administrasi Dinas dan tugas lain yang diberikan Walikota sesuai dengan tugas dan fungsinya.</li>
</ol>

<p>Penjabaran tugas dan fungsi per-bidang termuat dalam Peraturan Walikota Surabaya Nomor 52 Tahun 2023.</p>
HTML,
            ],

            // ─────────────── MAKLUMAT ───────────────
            [
                'slug'  => 'maklumat-pelayanan',
                'title' => 'Maklumat Pelayanan',
                'cover' => $img['maklumat'],
                'excerpt' => 'Komitmen aparatur DPMPTSP Kota Surabaya untuk menyelenggarakan pelayanan publik sesuai standar yang telah ditetapkan.',
                'body' => <<<HTML
<p>Maklumat Pelayanan adalah pernyataan tertulis dari penyelenggara layanan publik tentang kesanggupan dan kewajibannya untuk menyelenggarakan pelayanan sesuai standar pelayanan, sebagaimana diamanatkan <strong>Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik</strong>.</p>

<figure><img src="{$url($img['maklumat'])}" alt="Naskah Maklumat Pelayanan DPMPTSP Kota Surabaya 2025" loading="lazy" /><figcaption>Maklumat Pelayanan DPMPTSP Kota Surabaya — naskah resmi 2025</figcaption></figure>

<h2>Naskah Maklumat</h2>
<blockquote><p><em>"Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan, dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."</em></p></blockquote>

<h2>Komitmen Pelayanan</h2>
<ul>
    <li>Pelayanan yang <strong>cepat, mudah, transparan, dan akuntabel</strong>.</li>
    <li><strong>Bebas pungutan liar (pungli) dan gratifikasi</strong> sesuai prinsip Zona Integritas.</li>
    <li>Memenuhi <strong>Standar Pelayanan Minimum (SPM)</strong> sesuai peraturan yang berlaku.</li>
    <li>Menerima dan menindaklanjuti pengaduan masyarakat sesuai SOP yang telah ditetapkan.</li>
    <li>Mengutamakan kepuasan masyarakat sebagai indikator utama keberhasilan pelayanan.</li>
</ul>

<h2>Budaya Pelayanan</h2>
<figure><img src="{$url($img['budaya'])}" alt="Budaya Pelayanan DPMPTSP Surabaya" loading="lazy" /></figure>

<h2>Motto Pelayanan</h2>
<figure><img src="{$url($img['motto'])}" alt="Motto Pelayanan DPMPTSP Surabaya" loading="lazy" /></figure>

<h2>Kompensasi Layanan</h2>
<p>Bagi pengguna layanan yang tidak terlayani sesuai standar pelayanan, tersedia <strong>kompensasi layanan</strong> sebagai bentuk pertanggungjawaban DPMPTSP:</p>
<figure><img src="{$url($img['kompensasi'])}" alt="Kompensasi Layanan DPMPTSP Surabaya" loading="lazy" /></figure>
HTML,
            ],

            // ─────────────── SOP ───────────────
            [
                'slug'  => 'sop-pelayanan',
                'title' => 'SOP Pelayanan',
                'cover' => $img['perwali'],
                'excerpt' => 'Standar Operasional Prosedur pelayanan perizinan dan non-perizinan DPMPTSP Kota Surabaya.',
                'body' => <<<'HTML'
<p>Setiap jenis layanan di DPM-PTSP Surabaya memiliki <strong>Standar Operasional Prosedur (SOP)</strong> yang mengatur alur, persyaratan, jangka waktu, dan unit kerja yang menangani — disusun mengacu pada peraturan perundangan terkait pelayanan publik dan perizinan berbasis risiko.</p>

<h2>Cakupan SOP</h2>
<ul>
    <li><strong>Perizinan Berusaha Berbasis Risiko (OSS RBA)</strong> — penerbitan NIB, Sertifikat Standar, dan Izin sesuai tingkat risiko usaha.</li>
    <li><strong>Penerbitan & Perubahan NIB</strong> (Nomor Induk Berusaha).</li>
    <li><strong>Layanan Non-Perizinan</strong> — rekomendasi, surat keterangan, dan layanan administrasi penanaman modal.</li>
    <li><strong>Pengaduan & Tindak Lanjut Pelayanan</strong> — sesuai SP4N LAPOR! dan kanal internal DPMPTSP.</li>
    <li><strong>Konsultasi Pra-Perizinan</strong> melalui Klinik Investasi.</li>
    <li><strong>Pelaporan LKPM</strong> (Laporan Kegiatan Penanaman Modal) bagi pelaku usaha.</li>
</ul>

<h2>Komponen Setiap SOP</h2>
<ol>
    <li>Dasar hukum</li>
    <li>Definisi & ruang lingkup</li>
    <li>Persyaratan teknis & administratif</li>
    <li>Alur prosedur (flowchart)</li>
    <li>Jangka waktu penyelesaian (SLA)</li>
    <li>Biaya/tarif (jika ada)</li>
    <li>Output / produk layanan</li>
    <li>Penanganan pengaduan</li>
</ol>

<p>Dokumen SOP lengkap dapat diunduh pada <a href="/informasi/dokumen">Download Center</a> atau halaman <a href="/informasi/regulasi">Regulasi</a>.</p>
HTML,
            ],

            // ─────────────── STANDAR ───────────────
            [
                'slug'  => 'standar-pelayanan',
                'title' => 'Standar Pelayanan',
                'cover' => $img['budaya'],
                'excerpt' => 'Standar Pelayanan DPMPTSP Surabaya — 14 komponen sesuai UU 25/2009 tentang Pelayanan Publik.',
                'body' => <<<'HTML'
<p>Standar Pelayanan DPM-PTSP Kota Surabaya disusun sesuai amanat <strong>Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik</strong> dan <strong>Peraturan Menteri PAN-RB Nomor 15 Tahun 2014</strong>.</p>

<h2>14 Komponen Standar Pelayanan</h2>
<ol>
    <li>Dasar hukum</li>
    <li>Persyaratan pelayanan</li>
    <li>Sistem, mekanisme, dan prosedur</li>
    <li>Jangka waktu penyelesaian</li>
    <li>Biaya / tarif</li>
    <li>Produk pelayanan</li>
    <li>Sarana, prasarana, dan fasilitas</li>
    <li>Kompetensi pelaksana</li>
    <li>Pengawasan internal</li>
    <li>Penanganan pengaduan, saran, dan masukan</li>
    <li>Jumlah pelaksana</li>
    <li>Jaminan pelayanan</li>
    <li>Jaminan keamanan dan keselamatan pelayanan</li>
    <li>Evaluasi kinerja pelaksana</li>
</ol>

<h2>Saluran Layanan</h2>
<ul>
    <li><a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a> — perizinan kewenangan nasional.</li>
    <li><a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a> — perizinan kewenangan Kota Surabaya.</li>
    <li><strong>MPP Siola Lt.3</strong> — pelayanan tatap muka, antrian online, klinik investasi.</li>
    <li><strong>SIPINTAR & SIPEBA</strong> — sistem informasi internal DPMPTSP.</li>
</ul>

<p>Detail Standar Pelayanan per-jenis layanan dapat dilihat pada <a href="/layanan">menu Layanan</a>.</p>
HTML,
            ],

            // ─────────────── REFORMASI ───────────────
            [
                'slug'  => 'reformasi-birokrasi',
                'title' => 'Reformasi Birokrasi',
                'cover' => $img['motto'],
                'excerpt' => 'Komitmen DPMPTSP dalam pelaksanaan Reformasi Birokrasi Pemerintah Kota Surabaya — 8 area perubahan menuju birokrasi profesional, akuntabel, dan melayani.',
                'body' => <<<'HTML'
<p>DPM-PTSP Kota Surabaya melaksanakan program <strong>Reformasi Birokrasi (RB)</strong> sebagai bagian dari komitmen Pemerintah Kota Surabaya membangun aparatur pemerintahan yang profesional, akuntabel, bersih, dan melayani — mengacu pada <strong>Permenpan-RB Nomor 25 Tahun 2020</strong> tentang Road Map Reformasi Birokrasi.</p>

<h2>Delapan Area Perubahan</h2>
<ol>
    <li><strong>Manajemen Perubahan</strong> — pola pikir dan budaya kerja aparatur.</li>
    <li><strong>Deregulasi Kebijakan</strong> — harmonisasi dan penyederhanaan regulasi pelayanan.</li>
    <li><strong>Penataan Organisasi</strong> — struktur yang tepat fungsi dan tepat ukuran.</li>
    <li><strong>Penataan Tata Laksana</strong> — bisnis proses dan SOP berbasis digital.</li>
    <li><strong>Penataan SDM Aparatur</strong> — kompetensi, kinerja, dan integritas.</li>
    <li><strong>Penguatan Akuntabilitas</strong> — kinerja yang terukur dan dapat dipertanggungjawabkan.</li>
    <li><strong>Penguatan Pengawasan</strong> — sistem pengendalian internal yang efektif.</li>
    <li><strong>Peningkatan Kualitas Pelayanan Publik</strong> — pelayanan yang cepat, mudah, dan akuntabel.</li>
</ol>

<h2>Hasil yang Diharapkan</h2>
<ul>
    <li>Pemerintahan yang bersih dan bebas KKN.</li>
    <li>Peningkatan kapasitas dan akuntabilitas kinerja birokrasi.</li>
    <li>Peningkatan kualitas pelayanan publik kepada masyarakat dan pelaku usaha.</li>
</ul>
HTML,
            ],

            // ─────────────── ZONA INTEGRITAS ───────────────
            [
                'slug'  => 'zona-integritas',
                'title' => 'Zona Integritas',
                'cover' => $img['motto'],
                'excerpt' => 'Predikat instansi pemerintah yang berkomitmen mewujudkan WBK/WBBM melalui pencegahan korupsi dan peningkatan kualitas pelayanan publik.',
                'body' => <<<'HTML'
<p><strong>Zona Integritas (ZI)</strong> adalah predikat yang diberikan kepada instansi pemerintah yang pimpinan dan jajarannya mempunyai komitmen untuk mewujudkan WBK/WBBM melalui upaya pencegahan korupsi, reformasi birokrasi, dan peningkatan kualitas pelayanan publik — sebagaimana diatur dalam <strong>Permenpan-RB Nomor 90 Tahun 2021</strong>.</p>

<h2>Enam Komponen Pengungkit</h2>
<ol>
    <li>Manajemen Perubahan</li>
    <li>Penataan Tata Laksana</li>
    <li>Penataan Sistem Manajemen SDM</li>
    <li>Penguatan Akuntabilitas</li>
    <li>Penguatan Pengawasan</li>
    <li>Peningkatan Kualitas Pelayanan Publik</li>
</ol>

<h2>Dua Komponen Hasil</h2>
<ul>
    <li>Pemerintahan yang bersih dan bebas KKN (terwujudnya nilai integritas).</li>
    <li>Kualitas pelayanan publik kepada masyarakat dan pelaku usaha.</li>
</ul>

<p>Komitmen DPM-PTSP terhadap Zona Integritas dibuktikan melalui penerapan budaya pelayanan, pakta integritas, kanal pengaduan terbuka, dan transparansi proses perizinan.</p>
HTML,
            ],

            // ─────────────── WBK / WBBM ───────────────
            [
                'slug'  => 'wbk-wbbm',
                'title' => 'WBK / WBBM',
                'cover' => $img['motto'],
                'excerpt' => 'Wilayah Bebas Korupsi (WBK) dan Wilayah Birokrasi Bersih Melayani (WBBM) — predikat lanjutan dari Zona Integritas.',
                'body' => <<<'HTML'
<h2>Wilayah Bebas Korupsi (WBK)</h2>
<p>Predikat yang diberikan kepada unit kerja yang memenuhi sebagian besar komponen pengungkit Zona Integritas: manajemen perubahan, penataan tata laksana, penataan sistem manajemen SDM, penguatan pengawasan, dan penguatan akuntabilitas kinerja — serta menghasilkan kinerja terukur dalam pencegahan korupsi.</p>

<h2>Wilayah Birokrasi Bersih Melayani (WBBM)</h2>
<p>Predikat lanjutan dari WBK yang menambahkan komponen <strong>Peningkatan Kualitas Pelayanan Publik</strong> sebagai prasyarat utama. Unit yang berhasil meraih WBBM telah membuktikan bahwa pencegahan korupsi berdampak nyata pada kualitas pelayanan kepada masyarakat.</p>

<h2>Komitmen DPMPTSP Surabaya</h2>
<p>DPM-PTSP Kota Surabaya berkomitmen membangun zona pelayanan yang bersih dan melayani sesuai amanat <strong>Permenpan-RB Nomor 90 Tahun 2021</strong> tentang Pembangunan dan Evaluasi Zona Integritas Menuju Wilayah Bebas dari Korupsi dan Wilayah Birokrasi Bersih Melayani di Instansi Pemerintah.</p>

<p>Pelaporan dugaan pelanggaran integritas dapat disampaikan melalui:</p>
<ul>
    <li><a href="/pengaduan/lapor">Form Pengaduan</a> di website ini.</li>
    <li><strong>SP4N LAPOR!</strong> di <a href="https://www.lapor.go.id" target="_blank" rel="noopener">lapor.go.id</a>.</li>
    <li><strong>Whistleblowing System</strong> Pemkot Surabaya untuk pelanggaran integritas.</li>
</ul>
HTML,
            ],

            // Note: Inovasi sekarang adalah halaman list + detail tersendiri
            // (lihat InovasiSeeder + ProfilController::inovasi/inovasiShow).
            // Tidak ada Post type=profil dengan slug "inovasi-*" lagi.

            // ─────────────── MENGAPA SURABAYA ───────────────
            [
                'slug'  => 'mengapa-investasi-di-surabaya',
                'title' => 'Mengapa Investasi di Surabaya',
                'cover' => $img['siola'],
                'excerpt' => 'Enam alasan strategis untuk berinvestasi di Surabaya — kota metropolitan terbesar kedua dengan infrastruktur, fasilitas, dan iklim usaha bertaraf internasional.',
                'body' => <<<'HTML'
<p>Surabaya menawarkan kombinasi unik antara skala metropolitan, infrastruktur kelas internasional, dan kemudahan perizinan yang terus disederhanakan. Berikut enam alasan strategis untuk berinvestasi di Kota Pahlawan:</p>

<ol>
    <li><strong>Iklim Investasi Kondusif</strong> — pembangunan Kota Surabaya tumbuh cepat, didukung kemudahan perizinan terpadu satu pintu dan integrasi dengan OSS RBA Nasional.</li>
    <li><strong>Kota Metropolitan Terbesar Kedua</strong> — Surabaya merupakan kota metropolitan terbesar kedua di Indonesia setelah Jakarta, dengan basis konsumen dan tenaga kerja yang besar.</li>
    <li><strong>Infrastruktur Transportasi Bertaraf Internasional</strong> — Bandara Internasional Juanda dan Pelabuhan Tanjung Perak berstandar internasional, memudahkan logistik dalam dan luar negeri.</li>
    <li><strong>Fasilitas Kesehatan Bertaraf Internasional</strong> — standar pelayanan kesehatan bertaraf internasional dengan dukungan rumah sakit pendidikan terkemuka.</li>
    <li><strong>Fasilitas Umum Terjaga</strong> — Surabaya dikenal sebagai <em>"kota seribu taman"</em> dengan fasilitas umum yang terawat sangat baik dan tingkat kenyamanan tinggi.</li>
    <li><strong>Fasilitas Pendidikan Bertaraf Internasional</strong> — universitas negeri dan swasta terkemuka secara nasional dan internasional, menjamin pasokan SDM berkualitas.</li>
</ol>

<p><em>Sumber: <a href="https://dpm-ptsp.surabaya.go.id/mengapa.php" target="_blank" rel="noopener">dpm-ptsp.surabaya.go.id/mengapa.php</a></em></p>
HTML,
            ],

            // ─────────────── LAYANAN ROOT ───────────────
            [
                'slug'  => 'layanan',
                'title' => 'Layanan DPMPTSP Kota Surabaya',
                'cover' => $img['siola'],
                'excerpt' => 'Pelayanan perizinan, non-perizinan, dan konsultasi investasi DPMPTSP Surabaya.',
                'body' => <<<'HTML'
<p>DPM-PTSP Kota Surabaya menyelenggarakan layanan publik di bidang perizinan dan penanaman modal yang dapat diakses melalui <strong>Mal Pelayanan Publik (MPP) Siola Lt.3</strong>, Jl. Tunjungan No. 1-3 atau melalui kanal digital terintegrasi.</p>

<h2>Kategori Layanan</h2>
<ul>
    <li><a href="/layanan/perizinan-berusaha">Perizinan Berusaha</a> — berbasis risiko via OSS RBA.</li>
    <li><a href="/layanan/non-perizinan">Non-Perizinan</a> — rekomendasi, surat keterangan.</li>
    <li><a href="/layanan/oss">OSS RBA</a> — sistem perizinan berusaha terintegrasi nasional.</li>
    <li><a href="/layanan/tracking">Tracking Perizinan</a> — pantau status izin Anda.</li>
    <li><a href="/layanan/konsultasi-online">Konsultasi Online</a> — Klinik Investasi.</li>
    <li><a href="/layanan/antrian-online">Antrian Online</a> — pendaftaran tatap muka.</li>
    <li><a href="/layanan/persyaratan">Persyaratan Perizinan</a> — checklist persyaratan per jenis layanan.</li>
    <li><a href="/layanan/formulir">Download Formulir</a> — template formulir layanan.</li>
    <li><a href="/layanan/sla">SLA Pelayanan</a> — jangka waktu penyelesaian.</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'perizinan-berusaha',
                'title' => 'Perizinan Berusaha (OSS RBA)',
                'cover' => null,
                'excerpt' => 'Pengajuan izin usaha berbasis risiko via Online Single Submission Risk Based Approach.',
                'body' => <<<'HTML'
<p>Sejak diberlakukannya <strong>UU Cipta Kerja</strong> dan <strong>PP No. 5 Tahun 2021</strong>, perizinan berusaha menggunakan pendekatan berbasis risiko (<em>Risk-Based Approach</em> / RBA) melalui sistem <strong>OSS Berbasis Risiko</strong> yang dikelola Kementerian Investasi/BKPM RI.</p>

<h2>Kategori Risiko</h2>
<ul>
    <li><strong>Risiko Rendah (R)</strong> — cukup NIB.</li>
    <li><strong>Risiko Menengah Rendah (MR)</strong> — NIB + Sertifikat Standar pernyataan mandiri.</li>
    <li><strong>Risiko Menengah Tinggi (MT)</strong> — NIB + Sertifikat Standar terverifikasi.</li>
    <li><strong>Risiko Tinggi (T)</strong> — NIB + Izin & Sertifikat Standar.</li>
</ul>

<h2>Saluran Pengajuan</h2>
<ul>
    <li><a href="https://oss.go.id" target="_blank" rel="noopener">oss.go.id</a> — OSS RBA Nasional.</li>
    <li><a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">sswalfa.surabaya.go.id</a> — Sistem Perizinan Surabaya (SSW Alfa).</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'non-perizinan',
                'title' => 'Layanan Non-Perizinan',
                'cover' => null,
                'excerpt' => 'Layanan rekomendasi, surat keterangan, dan layanan administrasi penanaman modal di luar perizinan berusaha.',
                'body' => <<<'HTML'
<p>Selain perizinan berusaha, DPM-PTSP Kota Surabaya menyediakan layanan non-perizinan yang menunjang penyelenggaraan penanaman modal dan administrasi pelaku usaha.</p>

<h2>Jenis Layanan</h2>
<ul>
    <li>Surat Rekomendasi Penanaman Modal.</li>
    <li>Surat Keterangan / Klarifikasi Perizinan.</li>
    <li>Fasilitasi Penyelesaian Permasalahan Penanaman Modal.</li>
    <li>Konfirmasi Status Pelaku Usaha.</li>
    <li>Penerbitan Bukti Pelaporan LKPM.</li>
</ul>

<p>Pengajuan dapat dilakukan tatap muka di MPP Siola atau melalui <a href="/kontak">kanal kontak</a>.</p>
HTML,
            ],

            [
                'slug'  => 'oss',
                'title' => 'Online Single Submission (OSS RBA)',
                'cover' => null,
                'excerpt' => 'Sistem perizinan berusaha terintegrasi yang dikelola Kementerian Investasi/BKPM.',
                'body' => <<<'HTML'
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
                'cover' => null,
                'excerpt' => 'Pantau status pengajuan izin Anda secara real-time melalui OSS RBA, SSW Alfa, atau SIPINTAR.',
                'body' => <<<'HTML'
<p>Pemantauan status pengajuan izin dapat dilakukan melalui beberapa kanal sesuai dengan sistem tempat pengajuan dilakukan:</p>
<ul>
    <li><a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a> — untuk perizinan kewenangan nasional.</li>
    <li><a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a> — untuk perizinan kewenangan Kota Surabaya.</li>
    <li><a href="https://dpm-ptsp.surabaya.go.id/sipintar/" target="_blank" rel="noopener">SIPINTAR</a> — sistem informasi pintar DPMPTSP.</li>
</ul>

<p>Tracking memerlukan <strong>nomor permohonan</strong> atau <strong>NIB</strong> Anda.</p>
HTML,
            ],

            [
                'slug'  => 'konsultasi-online',
                'title' => 'Konsultasi Online — Klinik Investasi',
                'cover' => null,
                'excerpt' => 'Konsultasi perizinan dan investasi tatap muka maupun daring melalui Klinik Investasi DPMPTSP.',
                'body' => <<<'HTML'
<p><strong>Klinik Investasi DPM-PTSP Surabaya</strong> melayani konsultasi pra-perizinan dan informasi peluang investasi di Kota Surabaya, baik tatap muka di MPP maupun daring melalui kanal resmi.</p>

<h2>Topik Konsultasi</h2>
<ul>
    <li>Tata cara pengajuan NIB dan izin berusaha.</li>
    <li>Persyaratan teknis sektor usaha tertentu.</li>
    <li>Pelaporan LKPM (Laporan Kegiatan Penanaman Modal).</li>
    <li>Peluang investasi dan kemudahan usaha di Kota Surabaya.</li>
</ul>

<h2>Kanal Konsultasi</h2>
<ul>
    <li><strong>Tatap muka</strong>: Mal Pelayanan Publik Siola Lt.3, Jl. Tunjungan No. 1-3, Surabaya.</li>
    <li><strong>Email</strong>: <a href="mailto:dpm-ptsp@surabaya.go.id">dpm-ptsp@surabaya.go.id</a></li>
    <li><strong>Telepon</strong>: +62 (031) 99243924</li>
    <li><strong>TAKON SOBAT</strong> via WhatsApp — kanal konsultasi cepat untuk pelaku usaha.</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'antrian-online',
                'title' => 'Antrian Online',
                'cover' => null,
                'excerpt' => 'Pendaftaran antrian tatap muka di Mal Pelayanan Publik secara online — hindari antri di lokasi.',
                'body' => <<<'HTML'
<p>Untuk memberikan layanan tatap muka yang lebih tertib dan efisien, DPMPTSP menyediakan antrian online bagi pemohon yang akan datang ke <strong>Mal Pelayanan Publik (MPP) Siola Lt.3</strong>.</p>

<h2>Cara Mendaftar</h2>
<ol>
    <li>Buka kanal antrian online MPP Surabaya.</li>
    <li>Pilih layanan: konsultasi, pengajuan izin, atau pengambilan dokumen.</li>
    <li>Pilih tanggal dan slot waktu yang tersedia.</li>
    <li>Datang ke MPP sesuai jadwal — bawa nomor antrian dan dokumen pendukung.</li>
</ol>

<p>Layanan tatap muka beroperasi Senin – Jumat, 08.00 – 16.00 WIB.</p>
HTML,
            ],

            [
                'slug'  => 'persyaratan-perizinan',
                'title' => 'Persyaratan Perizinan',
                'cover' => null,
                'excerpt' => 'Checklist persyaratan dan dokumen pendukung untuk setiap jenis perizinan kewenangan Kota Surabaya.',
                'body' => <<<'HTML'
<p>Persyaratan tiap jenis perizinan berbeda tergantung sektor usaha, skala usaha, dan tingkat risiko. Daftar lengkap persyaratan dapat diakses melalui:</p>

<ul>
    <li><a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a> — persyaratan tergantung KBLI dan tingkat risiko.</li>
    <li><a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a> — persyaratan perizinan kewenangan daerah Kota Surabaya.</li>
    <li><a href="/layanan/konsultasi-online">Klinik Investasi</a> — konsultasi spesifik per sektor usaha.</li>
</ul>

<h2>Persyaratan Umum (Wajib)</h2>
<ul>
    <li>NIK / akta pendirian badan usaha.</li>
    <li>NPWP perseorangan / badan.</li>
    <li>Akun OSS / SSW Alfa Surabaya.</li>
    <li>Dokumen pendukung sesuai KBLI usaha.</li>
</ul>
HTML,
            ],

            [
                'slug'  => 'download-formulir',
                'title' => 'Download Formulir',
                'cover' => null,
                'excerpt' => 'Template formulir LKPM, rekomendasi, dan layanan administrasi DPMPTSP Surabaya.',
                'body' => <<<'HTML'
<p>Berikut template formulir yang dapat diunduh sesuai keperluan layanan:</p>

<ul>
    <li><strong>Formulir LKPM</strong> — Laporan Kegiatan Penanaman Modal (wajib bagi pelaku usaha).</li>
    <li><strong>Formulir Permohonan Rekomendasi</strong> — untuk surat rekomendasi penanaman modal.</li>
    <li><strong>Formulir Permohonan Konsultasi</strong> — untuk Klinik Investasi.</li>
    <li><strong>Formulir Surat Keterangan</strong> — untuk klarifikasi atau konfirmasi status perizinan.</li>
</ul>

<p>Formulir lengkap tersedia di <a href="/informasi/dokumen">Download Center</a>.</p>
HTML,
            ],

            [
                'slug'  => 'sla-pelayanan',
                'title' => 'SLA Pelayanan',
                'cover' => null,
                'excerpt' => 'Service Level Agreement — jangka waktu penyelesaian setiap jenis layanan perizinan DPMPTSP.',
                'body' => <<<'HTML'
<p><strong>Service Level Agreement (SLA)</strong> adalah komitmen jangka waktu penyelesaian setiap jenis layanan. SLA DPMPTSP ditetapkan sesuai dengan tingkat kompleksitas layanan dan peraturan perundangan.</p>

<h2>SLA Umum</h2>
<ul>
    <li><strong>NIB (Risiko Rendah)</strong>: terbit otomatis melalui OSS RBA — hitungan menit setelah submit.</li>
    <li><strong>Sertifikat Standar (Risiko Menengah)</strong>: 5–10 hari kerja, tergantung verifikasi.</li>
    <li><strong>Izin Risiko Tinggi</strong>: sesuai komitmen sektor terkait, umumnya 14–30 hari kerja.</li>
    <li><strong>Rekomendasi & Non-Perizinan</strong>: 3–5 hari kerja.</li>
    <li><strong>Konsultasi (Klinik Investasi)</strong>: respon dalam 1 hari kerja.</li>
</ul>

<p>SLA dapat dipantau real-time melalui <a href="https://dpm-ptsp.surabaya.go.id/sipintar/" target="_blank" rel="noopener">SIPINTAR</a> atau kanal tracking OSS/SSW Alfa.</p>
HTML,
            ],
        ];

        // Force-clean any prior profil posts (including soft-deleted ones).
        // We can't reliably updateOrCreate because (a) Spatie HasSlug derives
        // slug from title and may rewrite our explicit value, and (b) soft
        // deletes leak into the unique(slug) constraint. Hard-reset every
        // run gives the cleanest, idempotent state.
        Post::withTrashed()->ofType(Post::TYPE_PROFIL)->forceDelete();
        $this->command?->info(sprintf('  ↻ writing %d profil/layanan posts', count($items)));

        foreach ($items as $item) {
            // Pass slug explicitly. Spatie HasSlug respects pre-set slug
            // values, AND this guards against the edge case where another
            // seeder (e.g. RolePermissionSeeder via Artisan::call) flushes
            // model event listeners — leaving HasSlug's `creating` hook
            // unregistered and slug NULL on insert.
            Post::create([
                'type'         => Post::TYPE_PROFIL,
                'title'        => $item['title'],
                'slug'         => $item['slug'],
                'excerpt'      => $item['excerpt'],
                'body'         => $item['body'],
                'cover_path'   => $item['cover'] ?? null,
                'status'       => Post::STATUS_PUBLISHED,
                'author_id'    => $author?->id,
                'published_at' => $now,
            ]);
        }
    }
}
