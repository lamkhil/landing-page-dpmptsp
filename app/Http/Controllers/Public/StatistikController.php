<?php

namespace App\Http\Controllers\Public;

use App\Domain\Seo\Services\SeoService;
use App\Domain\Statistic\Services\StatisticService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class StatistikController extends Controller
{
    public function __construct(
        private readonly StatisticService $statistic,
        private readonly SeoService $seo,
    ) {}

    public function index(): View
    {
        return view('pages.statistik.index', [
            'pageTitle' => 'Dashboard Statistik',
            'seo'       => $this->seo->for('statistik'),
            'counters'  => $this->statistic->counters(),
            'series'    => [
                'pma'  => $this->statistic->yearlyTrend('pma',  5),
                'pmdn' => $this->statistic->yearlyTrend('pmdn', 5),
                'izin' => $this->statistic->yearlyTrend('izin', 5),
                'ikm'  => $this->statistic->yearlyTrend('ikm',  5),
                'sla'  => $this->statistic->yearlyTrend('sla',  5),
            ],
            'groups'    => config('dpmptsp.statistic_groups'),
        ]);
    }

    public function investasi(): View    { return $this->subPage('Dashboard Investasi', ['pma', 'pmdn']); }
    public function perizinan(): View    { return $this->subPage('Dashboard Perizinan', ['izin', 'sla']); }
    public function pmaPmdn(): View      { return $this->subPage('Statistik PMA / PMDN', ['pma', 'pmdn']); }
    public function kepuasan(): View     { return $this->subPage('Statistik Kepuasan', ['ikm']); }
    public function sla(): View          { return $this->subPage('SLA Pelayanan', ['sla']); }
    public function openData(): View     { return $this->subPage('Open Data Statistik', ['pma', 'pmdn', 'izin', 'sla', 'ikm']); }

    private function subPage(string $title, array $keys): View
    {
        $series = [];
        foreach ($keys as $k) {
            $series[$k] = $this->statistic->yearlyTrend($k, 5);
        }
        return view('pages.statistik.dashboard', [
            'pageTitle' => $title,
            'seo'       => $this->seo->for('statistik'),
            'counters'  => $this->statistic->counters(),
            'series'    => $series,
            'groups'    => collect(config('dpmptsp.statistic_groups'))->only($keys)->all(),
        ]);
    }
}
