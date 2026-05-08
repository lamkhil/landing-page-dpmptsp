<?php

namespace Database\Seeders\Cms;

use App\Domain\Content\Models\Agenda;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title'     => 'Sosialisasi OSS RBA untuk Pelaku UMKM Surabaya',
                'location'  => 'Mal Pelayanan Publik Lt.3, Surabaya',
                'organizer' => 'DPM-PTSP Kota Surabaya',
                'starts_at' => now()->addDays(3)->setTime(9, 0),
                'ends_at'   => now()->addDays(3)->setTime(12, 0),
            ],
            [
                'title'     => 'Bimtek Pelaporan LKPM Triwulan II 2026',
                'location'  => 'Ruang Sidang Lt.4 MPP Surabaya',
                'organizer' => 'DPM-PTSP Kota Surabaya',
                'starts_at' => now()->addDays(10)->setTime(13, 0),
                'ends_at'   => now()->addDays(10)->setTime(16, 0),
            ],
            [
                'title'     => 'Klinik Investasi Bulanan — Konsultasi Sektor Pariwisata',
                'location' => 'Ruang Konsultasi DPMPTSP',
                'organizer' => 'DPM-PTSP Kota Surabaya',
                'starts_at' => now()->addDays(17)->setTime(9, 0),
                'ends_at'   => now()->addDays(17)->setTime(11, 30),
            ],
            [
                'title'     => 'Forum Konsultasi Publik: Standar Pelayanan 2026',
                'location'  => 'Aula DPMPTSP Surabaya',
                'organizer' => 'DPM-PTSP Kota Surabaya',
                'starts_at' => now()->addDays(24)->setTime(9, 0),
                'ends_at'   => now()->addDays(24)->setTime(12, 0),
            ],
            [
                'title'     => 'Roadshow Investasi Surabaya — Jakarta',
                'location'  => 'Hotel Mulia, Jakarta',
                'organizer' => 'DPM-PTSP Kota Surabaya',
                'starts_at' => now()->addDays(31)->setTime(8, 30),
                'ends_at'   => now()->addDays(31)->setTime(17, 0),
            ],
        ];

        foreach ($items as $i => $it) {
            Agenda::updateOrCreate(
                ['title' => $it['title']],
                [
                    'location'     => $it['location'],
                    'organizer'    => $it['organizer'],
                    'starts_at'    => $it['starts_at'],
                    'ends_at'      => $it['ends_at'],
                    'is_published' => true,
                ]
            );
        }
    }
}
