<?php

namespace Database\Seeders;

use Database\Seeders\Cms\AgendaSeeder;
use Database\Seeders\Cms\ApplicationSeeder;
use Database\Seeders\Cms\BeritaImportSeeder;
use Database\Seeders\Cms\FaqSeeder;
use Database\Seeders\Cms\FooterSeeder;
use Database\Seeders\Cms\HeroSeeder;
use Database\Seeders\Cms\InfografisSeeder;
use Database\Seeders\Cms\InovasiSeeder;
use Database\Seeders\Cms\MenuSeeder;
use Database\Seeders\Cms\ProfilContentSeeder;
use Database\Seeders\Cms\ProfilStructuredSeeder;
use Database\Seeders\Cms\RegulationSeeder;
use Database\Seeders\Cms\ServiceStandardSeeder;
use Database\Seeders\Cms\SopSeeder;
use Database\Seeders\Cms\SeoDefaultsSeeder;
use Database\Seeders\Cms\StatisticSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            // Foundation
            RolePermissionSeeder::class,
            DefaultUserSeeder::class,

            // Layout & navigation
            MenuSeeder::class,
            HeroSeeder::class,
            FooterSeeder::class,
            SeoDefaultsSeeder::class,

            // Content from official source (dpm-ptsp.surabaya.go.id)
            ProfilContentSeeder::class,
            InovasiSeeder::class,
            ApplicationSeeder::class,
            StatisticSeeder::class,
            FaqSeeder::class,
            // Arsip resmi DPM-PTSP Surabaya → berita + pengumuman + artikel,
            // plus agenda untuk acara berjadwal (menggantikan data dummy).
            BeritaImportSeeder::class,
            InfografisSeeder::class,
            AgendaSeeder::class,
            RegulationSeeder::class,
            ProfilStructuredSeeder::class,
            SopSeeder::class,
            ServiceStandardSeeder::class,
        ]);
    }
}
