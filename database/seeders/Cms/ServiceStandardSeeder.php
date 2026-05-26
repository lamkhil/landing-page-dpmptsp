<?php

namespace Database\Seeders\Cms;

use App\Domain\Profil\Models\ServiceStandard;
use App\Domain\Profil\Models\ServiceStandardDocument;
use Illuminate\Database\Seeder;

/**
 * Seeds the Standar Pelayanan service tree (mirrors SSW Alfa: kategori →
 * sub-kategori → layanan) and the yearly official documents. Each leaf layanan
 * carries the per-service sections; admin refines content + uploads PDFs.
 */
class ServiceStandardSeeder extends Seeder
{
    public function run(): void
    {
        ServiceStandard::query()->delete();
        ServiceStandardDocument::query()->delete();

        // Sections broadly shared across services (admin can override per layanan).
        $common = [
            'dasar_hukum'    => "UU No. 25 Tahun 2009 tentang Pelayanan Publik;\nUU No. 11 Tahun 2020 tentang Cipta Kerja;\nPP No. 5 Tahun 2021 tentang Perizinan Berusaha Berbasis Risiko;\nPeraturan Walikota Surabaya tentang Penyelenggaraan PTSP.",
            'alur_perizinan' => "1. Pemohon mengajukan permohonan melalui SSW Alfa / OSS RBA.\n2. Verifikasi berkas oleh petugas.\n3. Peninjauan lapangan (bila diperlukan).\n4. Penerbitan izin dengan tanda tangan elektronik dan QR code.",
            'kontak'         => "Mal Pelayanan Publik (MPP) Siola Lt.3, Jl. Tunjungan No. 1-3, Surabaya.\nTelp. (031) 99000000 · Email: dpmptsp@surabaya.go.id\nSSW Alfa: sswalfa.surabaya.go.id",
            'maklumat'       => "Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan, dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku.",
            'visi_misi'      => "Visi: Gotong-royong menuju Surabaya Kota Dunia yang maju, humanis, dan berkelanjutan.\nMisi: Mewujudkan pelayanan publik yang profesional, modern, dan akuntabel dengan ditunjang teknologi informasi yang terintegrasi.",
            'motto'          => "Melayani dengan CEPAT, MUDAH, dan PASTI.",
        ];

        $leaf = fn (array $specific) => array_merge($common, $specific);

        $tree = [
            ['name' => 'Kesehatan', 'children' => [
                ['name' => 'Fasilitas Kesehatan', 'children' => [
                    ['name' => 'Izin Operasional Klinik (Pratama/Utama)', 'fields' => $leaf([
                        'persyaratan' => "NIB;\nDokumen kepemilikan/sewa tempat;\nDaftar tenaga kesehatan & SIP;\nDokumen sarana-prasarana medis.",
                        'durasi'      => "14 hari kerja sejak berkas lengkap dan benar.",
                        'retribusi'   => "Tidak dipungut biaya (gratis).",
                    ])],
                    ['name' => 'Izin Rumah Sakit Kelas C dan D', 'fields' => $leaf([
                        'persyaratan' => "NIB;\nStudi kelayakan & master plan;\nDokumen SDM & peralatan medis;\nSLF bangunan.",
                        'durasi'      => "14 hari kerja sejak berkas lengkap dan benar.",
                        'retribusi'   => "Tidak dipungut biaya (gratis).",
                    ])],
                ]],
                ['name' => 'Tenaga Kesehatan', 'children' => [
                    ['name' => 'Izin Praktik Tenaga Kesehatan', 'fields' => $leaf([
                        'persyaratan' => "STR aktif;\nIjazah & sertifikat kompetensi;\nRekomendasi organisasi profesi;\nSurat keterangan tempat praktik.",
                        'durasi'      => "7 hari kerja sejak berkas lengkap dan benar.",
                        'retribusi'   => "Tidak dipungut biaya (gratis).",
                    ])],
                ]],
            ]],
            ['name' => 'Lingkungan & Kebersihan', 'children' => [
                ['name' => 'Izin Pengelolaan Limbah', 'fields' => $leaf([
                    'persyaratan' => "NIB;\nDokumen lingkungan (AMDAL/UKL-UPL);\nData teknis pengelolaan limbah.",
                    'durasi'      => "Sesuai ketentuan dokumen lingkungan terkait.",
                    'retribusi'   => "Tidak dipungut biaya (gratis).",
                ])],
                ['name' => 'Izin Penebangan/Pemindahan Pohon', 'fields' => $leaf([
                    'persyaratan' => "Surat permohonan;\nIdentitas pemohon;\nDenah lokasi & alasan penebangan.",
                    'durasi'      => "7 hari kerja sejak berkas lengkap.",
                    'retribusi'   => "Sesuai ketentuan/kompensasi penggantian pohon.",
                ])],
            ]],
            ['name' => 'Penanaman Modal', 'children' => [
                ['name' => 'Pendaftaran Penanaman Modal (OSS RBA)', 'fields' => $leaf([
                    'persyaratan' => "Akun OSS;\nNIK/identitas penanggung jawab;\nData rencana usaha & lokasi;\nNPWP.",
                    'durasi'      => "1 hari kerja (risiko rendah); sesuai ketentuan untuk risiko menengah/tinggi.",
                    'retribusi'   => "Tidak dipungut biaya (gratis).",
                ])],
            ]],
            ['name' => 'Pemanfaatan Aset & Fasilitas Umum', 'children' => [
                ['name' => 'Sewa Stadion & Gelanggang Olahraga', 'fields' => $leaf([
                    'persyaratan' => "Surat permohonan;\nIdentitas penanggung jawab kegiatan;\nRencana & jadwal penggunaan.",
                    'durasi'      => "3–7 hari kerja sejak berkas lengkap.",
                    'retribusi'   => "Sesuai tarif retribusi pemakaian yang berlaku.",
                ])],
                ['name' => 'Pemanfaatan Lahan Taman & Balai', 'fields' => $leaf([
                    'persyaratan' => "Surat permohonan;\nIdentitas pemohon;\nRencana kegiatan & jadwal.",
                    'durasi'      => "3–7 hari kerja sejak berkas lengkap.",
                    'retribusi'   => "Sesuai tarif retribusi yang berlaku.",
                ])],
            ]],
            ['name' => 'Perdagangan', 'children' => [
                ['name' => 'Tanda Daftar Gudang (TDG)', 'fields' => $leaf([
                    'persyaratan' => "NIB;\nBukti kepemilikan/sewa gudang;\nData kapasitas & komoditas.",
                    'durasi'      => "3 hari kerja sejak berkas lengkap dan benar.",
                    'retribusi'   => "Tidak dipungut biaya (gratis).",
                ])],
            ]],
        ];

        $insert = function (array $nodes, ?int $parentId) use (&$insert) {
            $sort = 1;
            foreach ($nodes as $node) {
                $record = ServiceStandard::create(array_merge($node['fields'] ?? [], [
                    'name'         => $node['name'],
                    'parent_id'    => $parentId,
                    'sort_order'   => $sort++,
                    'is_published' => true,
                ]));

                if (! empty($node['children'])) {
                    $insert($node['children'], $record->id);
                }
            }
        };
        $insert($tree, null);

        foreach ([2026, 2025, 2024] as $i => $year) {
            ServiceStandardDocument::create([
                'year'         => $year,
                'title'        => 'Standar Pelayanan DPMPTSP Kota Surabaya Tahun '.$year,
                'file_path'    => null,
                'sort_order'   => $i + 1,
                'is_published' => true,
            ]);
        }
    }
}
