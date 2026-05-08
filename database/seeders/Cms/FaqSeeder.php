<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Category;
use App\Domain\Faq\Models\Faq;
use Illuminate\Database\Seeder;

/**
 * FAQ items adapted from common questions at dpm-ptsp.surabaya.go.id/tentang.php#faq-sec
 * — focus on NIB, LKPM, dan kendala perizinan, plus core service questions.
 */
class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Umum',         'slug' => 'umum'],
            ['name' => 'NIB & OSS',    'slug' => 'nib-oss'],
            ['name' => 'Investasi',    'slug' => 'investasi'],
            ['name' => 'Pengaduan',    'slug' => 'pengaduan'],
        ];
        foreach ($categories as $c) {
            Category::updateOrCreate(['type' => 'faq', 'slug' => $c['slug']], ['name' => $c['name']]);
        }
        $umum       = Category::where(['type' => 'faq', 'slug' => 'umum'])->first();
        $nib        = Category::where(['type' => 'faq', 'slug' => 'nib-oss'])->first();
        $investasi  = Category::where(['type' => 'faq', 'slug' => 'investasi'])->first();
        $pengaduan  = Category::where(['type' => 'faq', 'slug' => 'pengaduan'])->first();

        $faqs = [
            // --- Umum ---
            ['cat' => $umum, 'q' => 'Apa itu DPMPTSP Surabaya?',
             'a' => '<p>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPM-PTSP) Kota Surabaya adalah perangkat daerah yang menyelenggarakan urusan pemerintahan bidang penanaman modal dan pelayanan perizinan terpadu satu pintu di Kota Surabaya.</p>'],
            ['cat' => $umum, 'q' => 'Di mana lokasi kantor DPMPTSP Surabaya?',
             'a' => '<p>Kantor DPM-PTSP berada di <strong>Mal Pelayanan Publik Lt.3, Jl. Tunjungan No. 1-3 Genteng, Surabaya 60275</strong>. Pelayanan tatap muka melalui MPP buka Senin–Jumat pukul 08.00–16.00 WIB. Layanan online tersedia 24 jam melalui OSS dan SSW.</p>'],
            ['cat' => $umum, 'q' => 'Bagaimana cara menghubungi DPMPTSP Surabaya?',
             'a' => '<p>Telepon: <a href="tel:+62319924-3924">+62 (031) 99243924</a><br>Email: <a href="mailto:dpm-ptsp@surabaya.go.id">dpm-ptsp@surabaya.go.id</a><br>Atau melalui form <a href="/kontak">Kontak</a> di website ini.</p>'],

            // --- NIB & OSS ---
            ['cat' => $nib, 'q' => 'Apa itu NIB (Nomor Induk Berusaha)?',
             'a' => '<p>NIB adalah identitas pelaku usaha yang diterbitkan oleh Lembaga OSS setelah pelaku usaha melakukan pendaftaran. NIB berlaku sebagai <strong>Tanda Daftar Perusahaan (TDP)</strong>, <strong>Angka Pengenal Importir (API)</strong>, dan <strong>akses kepabeanan</strong> apabila pelaku usaha akan melakukan kegiatan ekspor/impor.</p>'],
            ['cat' => $nib, 'q' => 'Apa fungsi NIB?',
             'a' => '<p>NIB berfungsi sebagai (1) identitas pelaku usaha; (2) bukti pendaftaran berusaha; (3) syarat untuk memperoleh izin usaha dan izin komersial/operasional; (4) dasar untuk pengurusan perizinan turunan; dan (5) syarat perpajakan terkait kegiatan usaha.</p>'],
            ['cat' => $nib, 'q' => 'Di mana saya bisa mendapat bantuan untuk pengurusan NIB?',
             'a' => '<p>Pelaku usaha dapat memperoleh pendampingan melalui:</p><ul><li><strong>Klinik Investasi DPMPTSP Surabaya</strong> (tatap muka di MPP atau email/telepon).</li><li>Sistem <a href="https://oss.go.id" target="_blank" rel="noopener">OSS RBA</a> atau <a href="https://sswalfa.surabaya.go.id" target="_blank" rel="noopener">SSW Alfa Surabaya</a>.</li><li>SIPINTAR DPMPTSP untuk konsultasi terjadwal.</li></ul>'],
            ['cat' => $nib, 'q' => 'Bagaimana jika menemukan kendala saat mengurus perizinan via OSS?',
             'a' => '<p>Sampaikan kendala melalui:</p><ul><li>Saluran pengaduan resmi DPMPTSP Surabaya (form di <a href="/pengaduan/lapor">/pengaduan/lapor</a>).</li><li><strong>SP4N LAPOR!</strong> di <a href="https://www.lapor.go.id" target="_blank" rel="noopener">lapor.go.id</a>.</li><li>Helpdesk OSS melalui kanal resmi BKPM RI.</li></ul>'],

            // --- Investasi ---
            ['cat' => $investasi, 'q' => 'Apa itu LKPM?',
             'a' => '<p><strong>Laporan Kegiatan Penanaman Modal (LKPM)</strong> adalah laporan berkala mengenai perkembangan realisasi penanaman modal yang wajib disampaikan oleh setiap pelaku usaha yang telah memperoleh perizinan berusaha. LKPM disampaikan via OSS sesuai periodisasi (triwulan / semester / tahunan tergantung skala usaha).</p>'],
            ['cat' => $investasi, 'q' => 'Mengapa berinvestasi di Surabaya?',
             'a' => '<p>Surabaya adalah kota metropolitan terbesar kedua di Indonesia dengan iklim investasi yang kondusif, infrastruktur transportasi (Bandara Juanda, Pelabuhan Tanjung Perak) dan kesehatan bertaraf internasional, serta fasilitas umum dan pendidikan kelas dunia. Selengkapnya: <a href="/profil/zona-integritas">profil DPMPTSP</a>.</p>'],

            // --- Pengaduan ---
            ['cat' => $pengaduan, 'q' => 'Bagaimana cara menyampaikan pengaduan pelayanan?',
             'a' => '<p>Pengaduan dapat disampaikan via:</p><ul><li>Form <a href="/pengaduan/lapor">/pengaduan/lapor</a> di website ini (akan menerbitkan nomor tiket).</li><li><strong>SP4N LAPOR!</strong>.</li><li><strong>Whistleblowing System</strong> Pemkot Surabaya untuk pelanggaran integritas.</li></ul><p>Setiap pengaduan ditindaklanjuti sesuai SLA dan identitas pelapor dijamin kerahasiaannya.</p>'],
            ['cat' => $pengaduan, 'q' => 'Berapa lama pengaduan ditanggapi?',
             'a' => '<p>Pengaduan ditindaklanjuti sesuai Standar Pelayanan Minimum (SPM) DPMPTSP. Tracking dapat dilakukan menggunakan nomor tiket di halaman <a href="/pengaduan/tracking">Tracking Pengaduan</a>.</p>'],
        ];

        foreach ($faqs as $i => $f) {
            Faq::updateOrCreate(
                ['question' => $f['q']],
                [
                    'category_id' => $f['cat']?->id,
                    'body'        => $f['a'],
                    'is_published'=> true,
                    'sort_order'  => $i,
                ]
            );
        }
    }
}
