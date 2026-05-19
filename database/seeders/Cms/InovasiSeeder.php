<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use Database\Seeders\Cms\Support\RemoteImageDownloader;
use Illuminate\Database\Seeder;

/**
 * Seeds Post records of type=inovasi — one record per DPMPTSP innovation,
 * shown as a card grid at /profil/inovasi and as a detail page per slug.
 *
 * Categories live in `categories` with type='inovasi'. Per-innovation
 * metadata (year launched, external app URL) is encoded in a JSON-free
 * way using the title/excerpt/body fields the Post model already exposes,
 * to avoid a migration just for this section.
 */
class InovasiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('  ↻ downloading 7 inovasi cover images');
        $author = \App\Models\User::query()->first();
        $dl     = new RemoteImageDownloader();
        $now    = now();
        $base   = 'https://dpm-ptsp.surabaya.go.id/';

        // ─── Categories ──────────────────────────────────────────────
        $catData = [
            ['name' => 'Sistem Perizinan', 'slug' => 'sistem-perizinan', 'color' => 'primary',  'icon' => 'document-text',  'sort_order' => 0],
            ['name' => 'Sistem Internal',  'slug' => 'sistem-internal',  'color' => 'cyan',     'icon' => 'cog-6-tooth',     'sort_order' => 1],
            ['name' => 'Konsultasi',       'slug' => 'konsultasi',       'color' => 'amber',    'icon' => 'chat-bubble-left','sort_order' => 2],
            ['name' => 'Mobile / Chatbot', 'slug' => 'mobile-chatbot',   'color' => 'emerald',  'icon' => 'device-phone',    'sort_order' => 3],
            ['name' => 'Pengakuan & HAKI', 'slug' => 'pengakuan-haki',   'color' => 'rose',     'icon' => 'trophy',          'sort_order' => 4],
        ];
        foreach ($catData as $c) {
            Category::updateOrCreate(['type' => 'inovasi', 'slug' => $c['slug']], $c + ['type' => 'inovasi']);
        }
        $cat = fn (string $slug) => Category::where(['type' => 'inovasi', 'slug' => $slug])->first();

        // ─── Reuse images already downloaded by ApplicationSeeder & ProfilSeeder ─
        // The `public` disk paths are stable across re-runs (idempotent), so we
        // just reference them rather than re-downloading. Anything we still need
        // (e.g. SSW logo for the inovasi page hero) we fetch fresh here.
        $sswCover     = $dl->fetch($base.'ssw.jpg',                                        'inovasi', 'ssw-alfa-cover.jpg');
        $ossCover     = $dl->fetch('https://s3.oss.go.id/oss/cms/OSS-LOGO-NEW-2024-ID-c39d5a64d376bdcb60bae5f61ce15848.svg', 'inovasi', 'oss-cover.svg');
        $haki         = $dl->fetch($base.'tentangfile/Piagam Penghargaan-2025-01-10(Motto Pelayanan)_0.jpeg', 'inovasi', 'piagam-penghargaan.jpeg');
        $haki2        = $dl->fetch($base.'tentangfile/Piagam Penghargaan-2025-01-10(Motto Pelayanan)_1.jpeg', 'inovasi', 'piagam-penghargaan-2.jpeg');
        $perwali      = $dl->fetch($base.'tentangfile/Peraturan_Walikota.jpg',             'inovasi', 'peraturan-walikota.jpg');
        $siola        = $dl->fetch($base.'tentangfile/siola.jpeg',                         'inovasi', 'siola.jpeg');
        $motto        = $dl->fetch($base.'tentangfile/motto-2025.png',                     'inovasi', 'motto.png');

        $items = [
            [
                'slug'      => 'ssw-alfa-surabaya',
                'title'     => 'SSW Alfa Surabaya',
                'category'  => 'sistem-perizinan',
                'year'      => 2023,
                'app_url'   => 'https://sswalfa.surabaya.go.id/',
                'cover'     => $sswCover,
                'excerpt'   => 'Surabaya Single Window Alfa — sistem perizinan berbasis risiko khusus kewenangan Kota Surabaya yang terintegrasi dengan OSS RBA Nasional.',
                'body'      => <<<'HTML'
<p><strong>SSW Alfa</strong> adalah generasi terbaru dari Sistem Perijinan Surabaya Single Window. Sistem ini dirancang ulang untuk mengikuti pendekatan <strong>Risk-Based Approach (RBA)</strong> sesuai PP No. 5 Tahun 2021 — sehingga seluruh perizinan kewenangan Kota Surabaya dapat diproses melalui satu kanal yang terintegrasi dengan OSS RBA Nasional.</p>

<h2>Keunggulan</h2>
<ul>
    <li>Single sign-on dengan OSS RBA — pelaku usaha cukup satu akun untuk perizinan nasional & daerah.</li>
    <li>Klasifikasi otomatis berdasarkan tingkat risiko KBLI.</li>
    <li>Pelacakan status pengajuan real-time dengan notifikasi setiap perubahan tahap.</li>
    <li>Dokumentasi persyaratan teknis lengkap per sektor usaha.</li>
</ul>

<h2>Pengguna Wajib</h2>
<ul>
    <li>Pelaku Usaha (perseorangan & badan usaha) yang melakukan kegiatan di wilayah Kota Surabaya.</li>
    <li>Administrator Kawasan Ekonomi Khusus di wilayah Kota Surabaya.</li>
</ul>

<p>Akses sistem: <a href="https://sswalfa.surabaya.go.id/" target="_blank" rel="noopener">sswalfa.surabaya.go.id</a></p>
HTML,
            ],

            [
                'slug'      => 'sipintar',
                'title'     => 'SIPINTAR',
                'category'  => 'sistem-internal',
                'year'      => 2024,
                'app_url'   => 'https://dpm-ptsp.surabaya.go.id/sipintar/',
                'cover'     => $perwali,
                'excerpt'   => 'Sistem Informasi Pintar DPMPTSP — dashboard terintegrasi data perizinan, investasi, SLA, dan pengaduan dalam satu antarmuka.',
                'body'      => <<<'HTML'
<p><strong>SIPINTAR</strong> (Sistem Informasi Pintar DPMPTSP) adalah dashboard internal yang menyatukan data dari berbagai sistem perizinan dan layanan publik DPMPTSP — sehingga manajemen dan auditor dapat memantau kinerja organisasi secara real-time.</p>

<h2>Modul Utama</h2>
<ol>
    <li><strong>Dashboard Perizinan</strong> — volume pengajuan per kategori risiko, sebaran sektor, dan tren bulanan.</li>
    <li><strong>Monitoring SLA</strong> — kepatuhan jangka waktu penyelesaian per jenis layanan.</li>
    <li><strong>Tracking Pengaduan</strong> — distribusi pengaduan masuk, status, dan SLA tindak lanjut.</li>
    <li><strong>Data Investasi</strong> — realisasi PMA / PMDN per sektor dan periode.</li>
    <li><strong>Konsultasi</strong> — penjadwalan slot Klinik Investasi.</li>
</ol>

<h2>Manfaat</h2>
<ul>
    <li>Pengambilan keputusan berbasis data, bukan asumsi.</li>
    <li>Transparansi kinerja unit kerja kepada pimpinan.</li>
    <li>Akselerasi penanganan kasus dengan prioritas otomatis.</li>
</ul>
HTML,
            ],

            [
                'slug'      => 'simpedal',
                'title'     => 'SIMPEDAL',
                'category'  => 'sistem-internal',
                'year'      => 2024,
                'app_url'   => null,
                'cover'     => $haki,
                'excerpt'   => 'Sistem layanan pendampingan dan pelaporan terintegrasi yang telah mendapat Hak Kekayaan Intelektual (HAKI) — diakui sebagai karya intelektual resmi DPMPTSP.',
                'body'      => <<<'HTML'
<p><strong>SIMPEDAL</strong> adalah sistem layanan pendampingan dan pelaporan terintegrasi DPMPTSP Surabaya. SIMPEDAL telah mendapat pengakuan formal berupa <strong>Hak Kekayaan Intelektual (HAKI)</strong> dari Kementerian Hukum & HAM — menjadi salah satu inovasi DPM-PTSP yang resmi terdaftar sebagai karya intelektual.</p>

<h2>Fokus Layanan</h2>
<ul>
    <li>Pendampingan pelaporan LKPM (Laporan Kegiatan Penanaman Modal) bagi pelaku usaha.</li>
    <li>Monitoring kepatuhan pasca penerbitan izin.</li>
    <li>Notifikasi proaktif tenggat pelaporan.</li>
    <li>Integrasi dengan sistem OSS Nasional & SSW Alfa.</li>
</ul>

<h2>Pengakuan HAKI</h2>
<p>HAKI atas SIMPEDAL menjadi bukti bahwa transformasi digital pelayanan publik DPMPTSP Surabaya bukan hanya inisiatif administratif, melainkan kontribusi intelektual yang diakui secara nasional.</p>
HTML,
            ],

            [
                'slug'      => 'takon-sobat',
                'title'     => 'TAKON SOBAT',
                'category'  => 'mobile-chatbot',
                'year'      => 2025,
                'app_url'   => null,
                'cover'     => $siola,
                'excerpt'   => 'TAnya KONsultasi via SOBAT WhatsApp — kanal konsultasi perizinan berbasis WhatsApp yang memudahkan pelaku usaha bertanya tanpa harus datang ke kantor.',
                'body'      => <<<'HTML'
<p><strong>TAKON SOBAT</strong> adalah singkatan dari <em>TAnya KONsultasi via SOBAT</em> — layanan konsultasi perizinan terbaru DPMPTSP Surabaya yang dapat diakses langsung lewat aplikasi <strong>WhatsApp</strong>.</p>

<h2>Latar Belakang</h2>
<p>Banyak pelaku usaha — terutama UMKM — terhambat untuk berkonsultasi karena harus datang ke MPP atau menunggu respon email. TAKON SOBAT menghilangkan hambatan ini dengan menyediakan konsultasi melalui kanal yang sudah familiar bagi masyarakat.</p>

<h2>Topik Konsultasi</h2>
<ul>
    <li>Cara pengajuan NIB dan izin berusaha.</li>
    <li>Persyaratan teknis sektor usaha tertentu.</li>
    <li>Pelaporan LKPM.</li>
    <li>Status pengajuan izin.</li>
    <li>Pengaduan ringan / informasi MPP.</li>
</ul>

<h2>Jam Operasional</h2>
<p>TAKON SOBAT dioperasikan oleh operator Klinik Investasi DPMPTSP pada jam kerja (Senin–Jumat, 08.00–16.00 WIB). Pesan di luar jam operasional akan dibalas pada hari kerja berikutnya.</p>

<p><em>Peluncuran resmi: Desember 2025.</em></p>
HTML,
            ],

            [
                'slug'      => 'klinik-investasi',
                'title'     => 'Klinik Investasi',
                'category'  => 'konsultasi',
                'year'      => 2022,
                'app_url'   => null,
                'cover'     => $siola,
                'excerpt'   => 'Layanan konsultasi pra-perizinan dan informasi peluang investasi — tatap muka di MPP Lt.3 atau daring.',
                'body'      => <<<'HTML'
<p><strong>Klinik Investasi</strong> adalah layanan konsultasi pra-perizinan yang membantu calon investor dan pelaku usaha memahami persyaratan dan peluang investasi di Kota Surabaya — sebelum mereka mengajukan perizinan formal.</p>

<h2>Layanan</h2>
<ul>
    <li>Konsultasi NIB & perizinan berusaha (OSS RBA).</li>
    <li>Penjelasan persyaratan teknis per sektor usaha.</li>
    <li>Pendampingan pelaporan LKPM.</li>
    <li>Informasi peluang investasi sektor unggulan Surabaya.</li>
    <li>Klarifikasi regulasi dan kebijakan Pemkot Surabaya.</li>
</ul>

<h2>Kanal Akses</h2>
<ul>
    <li><strong>Tatap muka</strong>: Mal Pelayanan Publik (MPP) Siola Lt.3, Jl. Tunjungan No. 1-3, Surabaya.</li>
    <li><strong>Daring</strong>: email <a href="mailto:dpm-ptsp@surabaya.go.id">dpm-ptsp@surabaya.go.id</a> atau via TAKON SOBAT.</li>
    <li><strong>Telepon</strong>: +62 (031) 99243924.</li>
</ul>

<p>Klinik Investasi telah membantu ribuan pelaku usaha — dari UMKM hingga investor korporasi — memahami alur perizinan Surabaya.</p>
HTML,
            ],

            [
                'slug'      => 'kalkulator-investasi',
                'title'     => 'Kalkulator Investasi',
                'category'  => 'konsultasi',
                'year'      => 2024,
                'app_url'   => 'https://dpm-ptsp.surabaya.go.id/kalkulator_investasi/',
                'cover'     => $motto,
                'excerpt'   => 'Alat simulasi rencana investasi — hitung proyeksi biaya, kebutuhan modal, dan estimasi waktu perizinan sebelum mengajukan permohonan.',
                'body'      => <<<'HTML'
<p><strong>Kalkulator Investasi</strong> adalah alat simulasi berbasis web yang membantu calon investor merencanakan investasinya di Kota Surabaya — termasuk perkiraan biaya perizinan, estimasi waktu pengurusan, dan kebutuhan modal awal.</p>

<h2>Manfaat</h2>
<ul>
    <li>Estimasi biaya perizinan berdasarkan KBLI & tingkat risiko.</li>
    <li>Estimasi jangka waktu perizinan sesuai SLA per jenis izin.</li>
    <li>Daftar persyaratan teknis yang perlu disiapkan.</li>
    <li>Referensi insentif fiskal & non-fiskal (jika applicable).</li>
</ul>

<p>Akses: <a href="https://dpm-ptsp.surabaya.go.id/kalkulator_investasi/" target="_blank" rel="noopener">dpm-ptsp.surabaya.go.id/kalkulator_investasi</a></p>
HTML,
            ],

            [
                'slug'      => 'siwastib',
                'title'     => 'SIWASTIB',
                'category'  => 'sistem-internal',
                'year'      => 2026,
                'app_url'   => null,
                'cover'     => $perwali,
                'excerpt'   => 'Sistem Pengawasan dan Penertiban — perkuat digitalisasi pengawasan kepatuhan pelaku usaha pasca penerbitan izin.',
                'body'      => <<<'HTML'
<p><strong>SIWASTIB</strong> (Sistem Pengawasan dan Penertiban) adalah sistem digital terbaru DPMPTSP Surabaya yang diluncurkan pada Mei 2026 untuk memperkuat akuntabilitas pelaksanaan perizinan setelah izin diterbitkan.</p>

<h2>Latar Belakang</h2>
<p>Sebagaimana diatur dalam PP No. 5 Tahun 2021, perizinan berbasis risiko menempatkan <strong>pengawasan pasca-izin</strong> sebagai komponen wajib. SIWASTIB memungkinkan DPMPTSP melakukan pemantauan kepatuhan pelaku usaha secara sistematis dan transparan.</p>

<h2>Fitur Utama</h2>
<ul>
    <li>Penjadwalan pengawasan rutin & insidentil.</li>
    <li>Inspeksi berbasis form digital (paperless).</li>
    <li>Workflow tindak lanjut: peringatan → pembinaan → sanksi.</li>
    <li>Integrasi dengan SIPINTAR untuk dashboard manajerial.</li>
    <li>Laporan kepatuhan per sektor & per wilayah kecamatan.</li>
</ul>

<p>Bagi pelaku usaha, SIWASTIB juga memberi kanal transparan untuk mengetahui status kepatuhan izinnya.</p>
HTML,
            ],

            [
                'slug'      => 'integrasi-oss-rba',
                'title'     => 'Integrasi OSS RBA',
                'category'  => 'sistem-perizinan',
                'year'      => 2021,
                'app_url'   => 'https://oss.go.id/',
                'cover'     => $ossCover,
                'excerpt'   => 'Integrasi penuh sistem perizinan Kota Surabaya dengan OSS Berbasis Risiko nasional — satu akun, semua perizinan.',
                'body'      => <<<'HTML'
<p>Sejak <strong>UU Cipta Kerja</strong> dan <strong>PP No. 5 Tahun 2021</strong>, perizinan berusaha menggunakan pendekatan berbasis risiko via <strong>OSS Berbasis Risiko</strong> nasional. DPMPTSP Surabaya telah mengintegrasikan seluruh sistem perizinannya dengan OSS RBA — sehingga pelaku usaha cukup menggunakan <strong>satu akun</strong> untuk seluruh perizinan, baik kewenangan nasional maupun Kota Surabaya.</p>

<h2>Yang Diintegrasikan</h2>
<ul>
    <li>Pendaftaran pelaku usaha — sinkron via OSS RBA.</li>
    <li>NIB (Nomor Induk Berusaha) — diterbitkan oleh OSS, diakui penuh oleh Pemkot.</li>
    <li>Sertifikat Standar & Izin — verifikasi via SSW Alfa, status sinkron ke OSS.</li>
    <li>Pelaporan LKPM — submit via OSS, dashboard di SIPINTAR.</li>
</ul>

<h2>Dampak</h2>
<ul>
    <li>Pengurangan duplikasi pengajuan dokumen.</li>
    <li>Konsistensi data perizinan antar level pemerintahan.</li>
    <li>Waktu penerbitan NIB hanya hitungan menit untuk risiko rendah.</li>
</ul>
HTML,
            ],

            [
                'slug'      => 'iso-9001-2015',
                'title'     => 'Sertifikasi ISO 9001:2015',
                'category'  => 'pengakuan-haki',
                'year'      => 2024,
                'app_url'   => null,
                'cover'     => $haki2,
                'excerpt'   => 'Sertifikat ISO 9001:2015 Sistem Manajemen Mutu — pengakuan independen atas konsistensi mutu pelayanan DPMPTSP Surabaya.',
                'body'      => <<<'HTML'
<p>DPMPTSP Kota Surabaya telah meraih sertifikasi <strong>ISO 9001:2015 Sistem Manajemen Mutu</strong> — standar internasional yang menegaskan komitmen terhadap konsistensi, peningkatan berkelanjutan, dan kepuasan pelanggan.</p>

<h2>Lingkup Sertifikasi</h2>
<ul>
    <li>Pelayanan perizinan berusaha (OSS RBA & SSW Alfa).</li>
    <li>Layanan non-perizinan & rekomendasi penanaman modal.</li>
    <li>Klinik Investasi & konsultasi pra-perizinan.</li>
    <li>Penanganan pengaduan pelayanan publik.</li>
</ul>

<h2>Implikasi</h2>
<ul>
    <li>Audit eksternal tahunan untuk memastikan kepatuhan standar mutu.</li>
    <li>Kebijakan mutu yang ditetapkan & dimonitor secara berkala.</li>
    <li>Pengelolaan risiko & peluang yang sistematis.</li>
    <li>Komitmen perbaikan berkelanjutan (continuous improvement).</li>
</ul>

<p>Sertifikasi ini menjadi salah satu pilar pendukung pencapaian predikat <a href="/profil/wbk-wbbm">WBK / WBBM</a>.</p>
HTML,
            ],
        ];

        // Clean slate per run — same reason as ProfilContentSeeder: Spatie's
        // slug auto-derivation makes updateOrCreate by slug unreliable, and
        // soft-deleted records collide with the unique(slug) index.
        Post::withTrashed()->ofType(Post::TYPE_INOVASI)->forceDelete();
        $this->command?->info(sprintf('  ↻ writing %d inovasi posts', count($items)));

        foreach ($items as $i => $it) {
            // Pass slug explicitly — see ProfilContentSeeder for why we
            // bypass Spatie HasSlug here (shield:generate in an earlier
            // seeder can flush event listeners).
            Post::create([
                'type'         => Post::TYPE_INOVASI,
                'category_id'  => $cat($it['category'])?->id,
                'title'        => $it['title'],
                'slug'         => $it['slug'],
                'excerpt'      => $it['excerpt'],
                // Prepend a visible meta strip (year + launch button) — kept
                // in body so admins can edit it in Filament without needing
                // extra columns or a custom DTO.
                'body'         => $this->withMetaStrip($it['body'], $it['year'], $it['app_url']),
                'cover_path'   => $it['cover'] ?? null,
                'status'       => Post::STATUS_PUBLISHED,
                'is_featured'  => $i < 3,
                'author_id'    => $author?->id,
                'published_at' => $now->copy()->subDays($i),
            ]);
        }
    }

    /**
     * Render the "Diluncurkan: 2024 · [Buka Aplikasi →]" strip that appears
     * above the body on the inovasi detail page. Uses `not-prose` so Tailwind
     * Typography doesn't restyle the chips.
     */
    private function withMetaStrip(string $body, int $year, ?string $appUrl): string
    {
        $appBtn = $appUrl
            ? sprintf('<a href="%s" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-[13px] font-semibold bg-primary-700 text-white hover:bg-primary-800 transition shadow-sm">Buka Aplikasi <span aria-hidden="true">↗</span></a>', htmlspecialchars($appUrl, ENT_QUOTES))
            : '';

        $strip = sprintf(
            '<div class="not-prose flex flex-wrap items-center gap-3 mb-8 pb-6 border-b border-slate-200">'.
            '<span class="inline-flex items-center gap-2 text-sm text-muted"><span class="font-semibold text-ink">Diluncurkan</span><span>%d</span></span>'.
            '%s</div>',
            $year,
            $appBtn,
        );

        return $strip.$body;
    }
}
