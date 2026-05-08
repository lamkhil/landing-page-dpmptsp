<?php

namespace Database\Seeders\Cms;

use App\Domain\Statistic\Models\StatisticCounter;
use App\Domain\Statistic\Models\StatisticGroup;
use App\Domain\Statistic\Models\StatisticPeriod;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    public function run(): void
    {
        // Groups
        foreach (config('dpmptsp.statistic_groups') as $key => $info) {
            StatisticGroup::updateOrCreate(
                ['key' => $key],
                ['label' => $info['label'], 'unit' => $info['unit']]
            );
        }

        // Yearly placeholder data (3 years) for chart rendering on /statistik
        $year = (int) now()->year;
        $sample = [
            'pma'  => [$year - 2 => 6240, $year - 1 => 6890, $year => 7340],   // USD juta
            'pmdn' => [$year - 2 => 7250, $year - 1 => 8100, $year => 8920],   // IDR miliar
            'izin' => [$year - 2 => 9870, $year - 1 => 11250, $year => 12450],
            'sla'  => [$year - 2 => 7,    $year - 1 => 6,     $year => 5],     // hari
            'ikm'  => [$year - 2 => 92,   $year - 1 => 95,    $year => 98],
        ];

        foreach ($sample as $key => $rows) {
            $group = StatisticGroup::where('key', $key)->first();
            if (! $group) continue;
            foreach ($rows as $y => $value) {
                StatisticPeriod::updateOrCreate(
                    ['statistic_group_id' => $group->id, 'period_type' => StatisticPeriod::PERIOD_YEARLY, 'year' => $y],
                    ['value' => $value, 'label' => (string) $y]
                );
            }
        }

        // Counter cards on home/dashboard
        $counters = [
            ['key' => 'izin_diterbitkan', 'label' => 'Izin Diterbitkan',       'value' => 12450, 'unit' => 'izin',     'icon' => 'document-check', 'sort_order' => 0],
            ['key' => 'ikm',              'label' => 'IKM (skor)',             'value' => 98,    'unit' => 'skor',     'icon' => 'star',           'sort_order' => 1],
            ['key' => 'pma',              'label' => 'Investasi PMA',          'value' => 7340,  'unit' => 'USD juta', 'icon' => 'banknotes',      'sort_order' => 2],
            ['key' => 'pmdn',             'label' => 'Investasi PMDN',         'value' => 8920,  'unit' => 'IDR mly',  'icon' => 'banknotes',      'sort_order' => 3],
        ];
        foreach ($counters as $c) {
            StatisticCounter::updateOrCreate(['key' => $c['key']], $c + ['is_visible' => true]);
        }
    }
}
