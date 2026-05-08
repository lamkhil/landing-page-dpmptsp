<?php

namespace Database\Seeders;

use Database\Seeders\Cms\AgendaSeeder;
use Database\Seeders\Cms\ApplicationSeeder;
use Database\Seeders\Cms\FaqSeeder;
use Database\Seeders\Cms\FooterSeeder;
use Database\Seeders\Cms\HeroSeeder;
use Database\Seeders\Cms\MenuSeeder;
use Database\Seeders\Cms\NewsSeeder;
use Database\Seeders\Cms\ProfilContentSeeder;
use Database\Seeders\Cms\RegulationSeeder;
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
            ApplicationSeeder::class,
            StatisticSeeder::class,
            FaqSeeder::class,
            NewsSeeder::class,
            AgendaSeeder::class,
            RegulationSeeder::class,
        ]);
    }
}
