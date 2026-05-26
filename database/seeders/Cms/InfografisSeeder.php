<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * 25 infografis publik DPMPTSP Kota Surabaya.
 *
 * Tiap infografis memiliki: ringkasan (intro), daftar poin yang DIJELASKAN
 * satu per satu, dan catatan visualisasi. Body dirender sebagai HTML
 * terstruktur (<ol> poin) yang ditampilkan sebagai kartu langkah bernomor di
 * halaman detail. cover_path null — admin dapat mengganti dengan gambar
 * infografis asli via Filament.
 */
class InfografisSeeder extends Seeder
{
    public function run(): void
    {
        $author = \App\Models\User::query()->first();

        $cats = [
            'perizinan'      => ['name' => 'Perizinan',       'sort' => 3, 'color' => '#0E4DA4'],
            'investasi'      => ['name' => 'Investasi',       'sort' => 0, 'color' => '#0891b2'],
            'pelayanan'      => ['name' => 'Pelayanan',       'sort' => 1, 'color' => '#059669'],
            'zona-integritas'=> ['name' => 'Zona Integritas', 'sort' => 4, 'color' => '#7c3aed'],
            'smart-city'     => ['name' => 'Smart City',      'sort' => 5, 'color' => '#d97706'],
        ];
        foreach ($cats as $slug => $c) {
            Category::updateOrCreate(['type' => 'post', 'slug' => $slug], ['name' => $c['name'], 'sort_order' => $c['sort'], 'color' => $c['color']]);
        }
        $catId = fn (string $slug) => Category::where(['type' => 'post', 'slug' => $slug])->value('id');

        $items = $this->items();

        $i = 0;
        foreach ($items as $it) {
            $cover = $this->poster($it['slug'], $it['title'], $cats[$it['cat']]['name'], $cats[$it['cat']]['color'], $it['cat']);

            Post::updateOrCreate(
                ['slug' => $it['slug']],
                [
                    'type'         => Post::TYPE_INFOGRAFIS,
                    'category_id'  => $catId($it['cat']),
                    'title'        => $it['title'],
                    'slug'         => $it['slug'],
                    'excerpt'      => $it['intro'],
                    'body'         => $this->body($it['intro'], $it['points']),
                    'cover_path'   => $cover,
                    'status'       => Post::STATUS_PUBLISHED,
                    'is_featured'  => $it['featured'] ?? false,
                    'author_id'    => $author?->id,
                    'view_count'   => 300 - ($i * 9),
                    'published_at' => now()->subDays($i),
                ]
            );
            $i++;
        }

        $this->command?->info('  ✓ seeded '.count($items).' infografis (detail + poster)');
    }

    /** Susun body HTML: intro + daftar poin berpenjelasan. */
    private function body(string $intro, array $points): string
    {
        $li = collect($points)
            ->map(fn ($p) => '<li><strong>'.e($p[0]).'.</strong> '.e($p[1]).'</li>')
            ->implode('');

        return '<p>'.e($intro).'</p>'
            .'<h3>Poin Utama</h3>'
            .'<ol>'.$li.'</ol>';
    }

    /**
     * Bangun poster cover SVG bermerek (gradien kategori + ikon + judul) lalu
     * simpan ke disk public. Mengembalikan path relatif, atau null bila gagal.
     */
    private function poster(string $slug, string $title, string $catName, string $color, string $iconKey): ?string
    {
        $path = "seed/infografis/{$slug}.svg";

        $dark = $this->darken($color);
        $icon = $this->iconPath($iconKey);

        // Bungkus judul ke maksimal 4 baris (~20 karakter per baris).
        $words = preg_split('/\s+/', $title);
        $lines = [];
        $cur = '';
        foreach ($words as $w) {
            $try = $cur === '' ? $w : $cur.' '.$w;
            if (mb_strlen($try) > 20 && $cur !== '') {
                $lines[] = $cur;
                $cur = $w;
            } else {
                $cur = $try;
            }
        }
        if ($cur !== '') {
            $lines[] = $cur;
        }
        $lines = array_slice($lines, 0, 4);

        $tspans = '';
        foreach ($lines as $idx => $line) {
            $tspans .= '<tspan x="80" dy="'.($idx === 0 ? 0 : 74).'">'.e($line).'</tspan>';
        }

        $font = "font-family='Plus Jakarta Sans, Segoe UI, Arial, sans-serif'";

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 675" width="1200" height="675" role="img" aria-label="{$this->attr($title)}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$color}"/>
      <stop offset="1" stop-color="{$dark}"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="675" fill="url(#bg)"/>
  <circle cx="1050" cy="120" r="250" fill="#ffffff" opacity="0.06"/>
  <circle cx="1140" cy="600" r="170" fill="#ffffff" opacity="0.05"/>
  <circle cx="120" cy="610" r="120" fill="#ffffff" opacity="0.04"/>
  <g transform="translate(800 150) scale(13)" fill="none" stroke="#ffffff" stroke-opacity="0.92" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="{$icon}"/>
  </g>
  <text x="80" y="112" {$font} font-size="26" font-weight="700" letter-spacing="3" fill="#ffffff" fill-opacity="0.85">{$this->attr(strtoupper($catName))} &#183; INFOGRAFIS</text>
  <text x="80" y="320" {$font} font-size="62" font-weight="800" fill="#ffffff">{$tspans}</text>
  <rect x="80" y="600" width="60" height="6" rx="3" fill="#ffffff" opacity="0.9"/>
  <text x="80" y="640" {$font} font-size="24" font-weight="600" fill="#ffffff" fill-opacity="0.85">DPMPTSP KOTA SURABAYA</text>
</svg>
SVG;

        return Storage::disk('public')->put($path, $svg) ? $path : null;
    }

    private function attr(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function darken(string $hex, float $f = 0.60): string
    {
        $hex = ltrim($hex, '#');
        $r = (int) round(hexdec(substr($hex, 0, 2)) * $f);
        $g = (int) round(hexdec(substr($hex, 2, 2)) * $f);
        $b = (int) round(hexdec(substr($hex, 4, 2)) * $f);

        return sprintf('#%02x%02x%02x', min(255, $r), min(255, $g), min(255, $b));
    }

    /** Ikon heroicon-style (viewBox 24) per kategori untuk poster. */
    private function iconPath(string $key): string
    {
        return [
            'perizinan'       => 'M9 12l2 2 4-4M7.5 4h9A1.5 1.5 0 0118 5.5v14L12 17l-6 2.5v-14A1.5 1.5 0 017.5 4z',
            'investasi'       => 'M3 3v18h18M7 14l3-3 3 3 5-6',
            'pelayanan'       => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0',
            'zona-integritas' => 'M12 3l8 3v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3zM9 12l2 2 4-4',
            'smart-city'      => 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M13 9h.01M13 13h.01',
        ][$key] ?? 'M4 5h16v14H4zM4 15l4-4 3 3 4-5 5 6';
    }

    /** @return array<int,array{cat:string,slug:string,title:string,intro:string,points:array<int,array{0:string,1:string}>,visual:string,featured?:bool}> */
    private function items(): array
    {
        return [
            ['cat' => 'perizinan', 'slug' => 'alur-perizinan-berusaha-oss-rba', 'title' => 'Alur Perizinan Berusaha OSS RBA', 'featured' => true,
                'intro' => 'Perizinan berusaha kini ditempuh melalui sistem OSS Berbasis Risiko (RBA). Berikut alur lengkap dari pendaftaran hingga izin terbit.',
                'visual' => 'Flowchart modern + ikon bisnis',
                'points' => [
                    ['Daftar akun OSS', 'Buat akun di oss.go.id memakai NIK (perseorangan) atau data badan usaha; akun ini menjadi identitas tunggal seluruh proses perizinan.'],
                    ['Isi data usaha', 'Lengkapi profil usaha: identitas, permodalan, lokasi, dan rencana kegiatan sesuai kondisi sebenarnya.'],
                    ['Pilih KBLI', 'Tentukan Klasifikasi Baku Lapangan Usaha Indonesia (KBLI) yang sesuai; KBLI menentukan tingkat risiko dan jenis izin yang dibutuhkan.'],
                    ['Terbit NIB', 'Sistem menerbitkan Nomor Induk Berusaha (NIB) sebagai identitas berusaha sekaligus legalitas dasar.'],
                    ['Pemenuhan persyaratan', 'Untuk usaha risiko menengah/tinggi, penuhi Sertifikat Standar dan/atau Izin sesuai ketentuan sektor.'],
                    ['Izin terbit', 'Setelah verifikasi disetujui, izin berusaha terbit dan dapat diunduh langsung dari sistem.'],
                ]],
            ['cat' => 'perizinan', 'slug' => 'cara-mengurus-nib', 'title' => 'Cara Mengurus NIB', 'featured' => true,
                'intro' => 'NIB adalah identitas wajib bagi setiap pelaku usaha. Simak persyaratan dan tipsnya agar tidak gagal verifikasi.',
                'visual' => 'Step-by-step carousel',
                'points' => [
                    ['Persyaratan', 'Akun OSS aktif, NIK atau identitas badan usaha, serta data KBLI dan lokasi usaha yang valid.'],
                    ['Dokumen dibutuhkan', 'NPWP, data legalitas badan usaha (akta/SK), dan bukti kesesuaian lokasi untuk skala tertentu.'],
                    ['Estimasi waktu', 'NIB untuk usaha risiko rendah dapat terbit seketika setelah data lengkap dan terverifikasi.'],
                    ['Tips menghindari gagal verifikasi', 'Pastikan KBLI sesuai kegiatan riil, data alamat konsisten, dan seluruh dokumen terbaca jelas.'],
                ]],
            ['cat' => 'perizinan', 'slug' => 'jenis-perizinan-populer', 'title' => 'Jenis Perizinan Populer',
                'intro' => 'Beberapa izin paling banyak diajukan masyarakat dan pelaku usaha di Kota Surabaya.',
                'visual' => 'Grid ikon layanan',
                'points' => [
                    ['NIB', 'Nomor Induk Berusaha — legalitas dasar bagi seluruh kegiatan usaha.'],
                    ['PBG', 'Persetujuan Bangunan Gedung — pengganti IMB untuk pendirian atau perubahan bangunan.'],
                    ['Sertifikat Standar', 'Pernyataan atau verifikasi pemenuhan standar usaha sesuai tingkat risiko.'],
                    ['Izin Klinik', 'Izin operasional bagi fasilitas pelayanan kesehatan.'],
                    ['Izin Restoran', 'Izin dan sertifikat laik bagi usaha rumah makan serta restoran.'],
                    ['Izin Reklame', 'Izin pemasangan reklame atau papan iklan di wilayah kota.'],
                ]],
            ['cat' => 'investasi', 'slug' => 'statistik-investasi-surabaya', 'title' => 'Statistik Investasi Surabaya', 'featured' => true,
                'intro' => 'Ringkasan capaian realisasi investasi Kota Surabaya pada tahun berjalan.',
                'visual' => 'Bar chart + peta Surabaya',
                'points' => [
                    ['Total investasi tahun berjalan', 'Akumulasi nilai PMA dan PMDN yang direalisasikan sepanjang tahun.'],
                    ['PMDN', 'Penanaman Modal Dalam Negeri yang berasal dari investor nasional.'],
                    ['PMA', 'Penanaman Modal Asing yang berasal dari investor luar negeri.'],
                    ['Sektor terbesar', 'Perdagangan, industri pengolahan, dan jasa menjadi kontributor utama.'],
                    ['Pertumbuhan investasi', 'Tren realisasi dibanding periode sebelumnya menunjukkan arah pertumbuhan.'],
                ]],
            ['cat' => 'investasi', 'slug' => 'target-investasi-kota-surabaya', 'title' => 'Target Investasi Kota Surabaya',
                'intro' => 'Target dan capaian realisasi investasi yang dipantau secara berkala.',
                'visual' => 'Progress infographic',
                'points' => [
                    ['Target tahunan', 'Sasaran nilai investasi yang ditetapkan untuk satu tahun anggaran.'],
                    ['Realisasi triwulan', 'Capaian aktual yang dilaporkan setiap triwulan melalui LKPM.'],
                    ['Persentase capaian', 'Rasio realisasi terhadap target sebagai indikator kinerja.'],
                ]],
            ['cat' => 'investasi', 'slug' => 'mengapa-investasi-di-surabaya-infografis', 'title' => 'Mengapa Investasi di Surabaya', 'featured' => true,
                'intro' => 'Sejumlah keunggulan menjadikan Surabaya tujuan investasi yang strategis.',
                'visual' => 'City skyline futuristik',
                'points' => [
                    ['Kota terbesar kedua', 'Pusat ekonomi terbesar kedua di Indonesia dengan pasar yang luas.'],
                    ['Pelabuhan internasional', 'Pelabuhan Tanjung Perak menjadi gerbang logistik Indonesia Timur.'],
                    ['Infrastruktur lengkap', 'Jaringan jalan, utilitas, dan konektivitas yang sudah matang.'],
                    ['SDM berkualitas', 'Didukung banyak perguruan tinggi dan tenaga kerja terampil.'],
                    ['Smart city', 'Tata kelola kota berbasis teknologi yang memudahkan berusaha.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'sop-pelayanan-perizinan', 'title' => 'SOP Pelayanan Perizinan',
                'intro' => 'Standar operasional menjamin layanan perizinan yang terukur dan transparan.',
                'visual' => 'Timeline horizontal',
                'points' => [
                    ['Waktu layanan', 'Setiap jenis izin memiliki jangka waktu penyelesaian yang ditetapkan.'],
                    ['Tahapan verifikasi', 'Permohonan melewati pemeriksaan administrasi dan teknis secara berjenjang.'],
                    ['SLA layanan', 'Service Level Agreement memberi kepastian waktu bagi pemohon.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'maklumat-pelayanan-infografis', 'title' => 'Maklumat Pelayanan',
                'intro' => 'Maklumat pelayanan adalah janji DPMPTSP Kota Surabaya kepada masyarakat.',
                'visual' => 'Poster formal modern',
                'points' => [
                    ['Komitmen pelayanan cepat', 'Menyelesaikan layanan sesuai standar waktu yang dijanjikan.'],
                    ['Transparan', 'Prosedur, biaya, dan persyaratan terbuka untuk publik.'],
                    ['Anti pungli', 'Tidak memungut biaya apa pun di luar ketentuan resmi.'],
                    ['Profesional', 'Dilayani petugas yang kompeten, ramah, dan santun.'],
                ]],
            ['cat' => 'zona-integritas', 'slug' => 'zona-integritas-dpmptsp', 'title' => 'Zona Integritas DPMPTSP', 'featured' => true,
                'intro' => 'DPMPTSP membangun Zona Integritas menuju birokrasi yang bersih dan melayani.',
                'visual' => 'Shield + integrity concept',
                'points' => [
                    ['WBK', 'Wilayah Bebas dari Korupsi sebagai tahap awal predikat Zona Integritas.'],
                    ['WBBM', 'Wilayah Birokrasi Bersih dan Melayani, tingkat lanjutan dari WBK.'],
                    ['Reformasi birokrasi', 'Perbaikan tata kelola, SDM, dan pelayanan secara berkelanjutan.'],
                    ['Anti korupsi', 'Penerapan nilai integritas dan pencegahan korupsi di setiap lini.'],
                ]],
            ['cat' => 'zona-integritas', 'slug' => 'anti-gratifikasi', 'title' => 'Anti Gratifikasi',
                'intro' => 'Seluruh layanan DPMPTSP Kota Surabaya bebas dari gratifikasi.',
                'visual' => 'Flat illustration anti corruption',
                'points' => [
                    ['Larangan pemberian hadiah', 'Pegawai dilarang menerima hadiah yang berkaitan dengan jabatan atau layanan.'],
                    ['Kanal pelaporan', 'Dugaan gratifikasi dapat dilaporkan melalui kanal resmi yang tersedia.'],
                    ['Whistleblowing', 'Identitas pelapor dilindungi melalui sistem whistleblowing (WBS).'],
                ]],
            ['cat' => 'investasi', 'slug' => 'klinik-investasi-infografis', 'title' => 'Klinik Investasi',
                'intro' => 'Layanan konsultasi untuk memudahkan calon investor dan pelaku usaha.',
                'visual' => 'Customer service illustration',
                'points' => [
                    ['Konsultasi gratis', 'Tanpa biaya untuk seluruh topik perizinan dan investasi.'],
                    ['Pendampingan OSS', 'Dibantu memahami alur dan mengisi permohonan pada sistem OSS.'],
                    ['Lokasi layanan', 'Tersedia tatap muka di MPP Siola maupun melalui kanal daring.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'jam-pelayanan', 'title' => 'Jam Pelayanan',
                'intro' => 'Informasi waktu dan lokasi layanan tatap muka DPMPTSP Kota Surabaya.',
                'visual' => 'Clock + map card',
                'points' => [
                    ['Hari kerja', 'Senin sampai Jumat, kecuali hari libur nasional.'],
                    ['Jam operasional', 'Pukul 08.00–16.00 WIB; layanan daring tersedia 24 jam.'],
                    ['Lokasi MPP Siola', 'Mal Pelayanan Publik Siola, pusat layanan publik terintegrasi Kota Surabaya.'],
                ]],
            ['cat' => 'perizinan', 'slug' => 'persyaratan-pbg', 'title' => 'Persyaratan PBG',
                'intro' => 'Persetujuan Bangunan Gedung (PBG) membutuhkan kelengkapan dokumen teknis.',
                'visual' => 'Blueprint style',
                'points' => [
                    ['Dokumen teknis', 'Data teknis bangunan dan perhitungan struktur sesuai standar.'],
                    ['Gambar bangunan', 'Gambar arsitektur, struktur, dan utilitas bangunan.'],
                    ['Persetujuan lingkungan', 'Dokumen lingkungan sesuai skala dan dampak bangunan.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'cara-tracking-permohonan', 'title' => 'Cara Tracking Permohonan',
                'intro' => 'Pantau status permohonan perizinan Anda secara mandiri dan real-time.',
                'visual' => 'UI dashboard mockup',
                'points' => [
                    ['Input nomor registrasi', 'Masukkan nomor permohonan yang diperoleh saat pengajuan.'],
                    ['Cek status', 'Sistem menampilkan posisi permohonan secara real-time.'],
                    ['Tahap verifikasi', 'Ketahui tahap yang sedang berjalan dan tindak lanjut yang diperlukan.'],
                ]],
            ['cat' => 'smart-city', 'slug' => 'digitalisasi-pelayanan', 'title' => 'Digitalisasi Pelayanan', 'featured' => true,
                'intro' => 'Layanan perizinan Kota Surabaya bergerak menuju serba digital.',
                'visual' => 'Digital transformation concept',
                'points' => [
                    ['Online service', 'Permohonan diajukan secara daring tanpa harus datang langsung.'],
                    ['Paperless', 'Dokumen elektronik mengurangi penggunaan kertas.'],
                    ['Tracking realtime', 'Status permohonan dapat dipantau kapan saja.'],
                    ['Tandatangan elektronik', 'Dokumen disahkan dengan tanda tangan elektronik tersertifikasi.'],
                ]],
            ['cat' => 'smart-city', 'slug' => 'inovasi-drive-thru-perizinan', 'title' => 'Inovasi Drive Thru Perizinan',
                'intro' => 'Inovasi layanan pengambilan dokumen perizinan tanpa turun dari kendaraan.',
                'visual' => 'Drive thru government service',
                'points' => [
                    ['Ambil dokumen tanpa turun kendaraan', 'Dokumen diserahkan langsung di jalur drive thru.'],
                    ['Cepat', 'Memangkas waktu antre dan kontak tatap muka.'],
                    ['Praktis', 'Cocok bagi pemohon dengan mobilitas tinggi.'],
                ]],
            ['cat' => 'perizinan', 'slug' => 'data-perizinan-terbit', 'title' => 'Data Perizinan Terbit',
                'intro' => 'Tren penerbitan izin menggambarkan kinerja pelayanan perizinan.',
                'visual' => 'Dashboard analytics',
                'points' => [
                    ['Jumlah izin per bulan', 'Volume izin yang diterbitkan pada setiap bulan.'],
                    ['Kategori izin', 'Komposisi izin berdasarkan jenis dan sektor usaha.'],
                    ['Grafik tren', 'Pola naik-turun penerbitan izin dari waktu ke waktu.'],
                ]],
            ['cat' => 'perizinan', 'slug' => 'faq-perizinan', 'title' => 'FAQ Perizinan',
                'intro' => 'Jawaban atas pertanyaan yang paling sering diajukan seputar perizinan.',
                'visual' => 'Accordion infographic',
                'points' => [
                    ['Berapa lama izin terbit?', 'Bergantung tingkat risiko; izin risiko rendah dapat terbit seketika.'],
                    ['Apakah gratis?', 'Layanan perizinan tidak dipungut biaya di luar ketentuan resmi.'],
                    ['Bisa online?', 'Ya, seluruh permohonan dapat diajukan melalui OSS dan SSW Alfa.'],
                ]],
            ['cat' => 'investasi', 'slug' => 'investasi-sektor-unggulan', 'title' => 'Investasi Sektor Unggulan', 'featured' => true,
                'intro' => 'Sektor-sektor unggulan yang menjadi magnet investasi Kota Surabaya.',
                'visual' => 'Sector card infographic',
                'points' => [
                    ['Properti', 'Permintaan hunian dan ruang komersial yang terus tumbuh.'],
                    ['Kuliner', 'Ekosistem kuliner yang dinamis dan bernilai tambah tinggi.'],
                    ['Industri', 'Industri pengolahan dengan dukungan logistik pelabuhan.'],
                    ['Logistik', 'Posisi strategis sebagai hub distribusi Indonesia Timur.'],
                    ['Pariwisata', 'Wisata kota, MICE, dan heritage yang terus berkembang.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'tata-cara-pengaduan', 'title' => 'Tata Cara Pengaduan',
                'intro' => 'Sampaikan pengaduan layanan melalui kanal resmi yang tersedia.',
                'visual' => 'Complaint flow diagram',
                'points' => [
                    ['SP4N LAPOR', 'Kanal pengaduan nasional yang terintegrasi lintas instansi.'],
                    ['Hotline', 'Saluran telepon resmi untuk pengaduan cepat.'],
                    ['Email', 'Pengaduan tertulis melalui alamat email resmi instansi.'],
                    ['Alur tindak lanjut', 'Setiap laporan ditindaklanjuti dan dipantau statusnya.'],
                ]],
            ['cat' => 'smart-city', 'slug' => 'surabaya-smart-investment-city', 'title' => 'Surabaya Smart Investment City', 'featured' => true,
                'intro' => 'Surabaya sebagai kota cerdas dengan ekosistem investasi modern.',
                'visual' => 'Isometric smart city',
                'points' => [
                    ['Smart city', 'Tata kelola kota berbasis data dan teknologi.'],
                    ['Infrastruktur digital', 'Konektivitas dan layanan publik digital yang luas.'],
                    ['Transportasi', 'Jaringan transportasi yang mendukung mobilitas usaha.'],
                    ['Peluang usaha', 'Beragam peluang investasi lintas sektor bagi investor.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'pelayanan-ramah-disabilitas', 'title' => 'Pelayanan Ramah Disabilitas',
                'intro' => 'Layanan yang inklusif dan setara bagi penyandang disabilitas.',
                'visual' => 'Accessibility illustration',
                'points' => [
                    ['Jalur khusus', 'Jalur dan loket prioritas yang mudah diakses.'],
                    ['Lift', 'Akses vertikal yang ramah bagi pengguna kursi roda.'],
                    ['Pendampingan', 'Petugas siap mendampingi seluruh proses layanan.'],
                ]],
            ['cat' => 'pelayanan', 'slug' => 'nilai-kepuasan-masyarakat', 'title' => 'Nilai Kepuasan Masyarakat',
                'intro' => 'Indeks Kepuasan Masyarakat (IKM) menjadi tolok ukur mutu layanan.',
                'visual' => 'Gauge chart modern',
                'points' => [
                    ['Indeks kepuasan', 'Skor IKM dari survei berkala terhadap pengguna layanan.'],
                    ['Persentase puas', 'Proporsi responden yang menyatakan puas atau sangat puas.'],
                    ['Feedback masyarakat', 'Masukan masyarakat menjadi dasar perbaikan layanan.'],
                ]],
            ['cat' => 'perizinan', 'slug' => 'timeline-pengurusan-izin', 'title' => 'Timeline Pengurusan Izin',
                'intro' => 'Gambaran tahapan harian pengurusan izin (ilustratif).',
                'visual' => 'Minimal timeline',
                'points' => [
                    ['Hari 1: Verifikasi', 'Pemeriksaan administrasi atas kelengkapan berkas.'],
                    ['Hari 2: Validasi', 'Validasi teknis sesuai ketentuan sektor terkait.'],
                    ['Hari 3: Penerbitan', 'Izin diterbitkan dan dapat diunduh oleh pemohon.'],
                ]],
            ['cat' => 'investasi', 'slug' => 'peta-potensi-investasi-surabaya', 'title' => 'Peta Potensi Investasi Surabaya', 'featured' => true,
                'intro' => 'Sebaran kawasan potensial untuk berinvestasi di Kota Surabaya.',
                'visual' => 'Map infographic',
                'points' => [
                    ['Kawasan industri', 'Zona industri dan pergudangan dengan dukungan logistik.'],
                    ['Kawasan perdagangan', 'Pusat perdagangan dan komersial bernilai tinggi.'],
                    ['Kawasan jasa', 'Kawasan jasa, keuangan, dan perkantoran.'],
                ]],
        ];
    }
}
