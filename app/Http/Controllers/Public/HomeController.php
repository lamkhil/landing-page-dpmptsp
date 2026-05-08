<?php

namespace App\Http\Controllers\Public;

use App\Domain\Application\Services\ApplicationService;
use App\Domain\Content\Models\Agenda;
use App\Domain\Content\Models\Post;
use App\Domain\Content\Models\Regulation;
use App\Domain\Faq\Models\Faq;
use App\Domain\Hero\Services\HeroService;
use App\Domain\Seo\Services\SeoService;
use App\Domain\Statistic\Services\StatisticService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly HeroService $hero,
        private readonly ApplicationService $applications,
        private readonly StatisticService $statistic,
        private readonly SeoService $seo,
    ) {}

    public function index(): View
    {
        // Build all stat trends for the homepage tab switcher.
        // Alpine reads this as JSON, so URLs must be pre-resolved (no route() calls in Blade-data).
        $statSpecs = [
            'izin' => ['label' => 'Izin Diterbitkan',  'unit' => 'izin',     'color' => '#0E4DA4', 'detail' => 'statistik.perizinan'],
            'pma'  => ['label' => 'Investasi PMA',     'unit' => 'USD juta', 'color' => '#0891b2', 'detail' => 'statistik.pma-pmdn'],
            'pmdn' => ['label' => 'Investasi PMDN',    'unit' => 'IDR mly',  'color' => '#059669', 'detail' => 'statistik.pma-pmdn'],
            'ikm'  => ['label' => 'IKM',               'unit' => 'skor',     'color' => '#d97706', 'detail' => 'statistik.kepuasan'],
            'sla'  => ['label' => 'SLA Pelayanan',     'unit' => 'hari',     'color' => '#7c3aed', 'detail' => 'statistik.sla'],
        ];

        $trendStats = [];
        foreach ($statSpecs as $key => $spec) {
            $series = $this->statistic->yearlyTrend($key, 5);
            $trendStats[$key] = [
                'label'      => $spec['label'],
                'unit'       => $spec['unit'],
                'color'      => $spec['color'],
                'detail_url' => route($spec['detail']),
                'categories' => $series->pluck('year')->all(),
                'values'     => $series->map(fn ($r) => (float) $r->value)->all(),
                'latest'     => $series->last()?->value,
                'latest_year'=> $series->last()?->year,
            ];
        }

        // Ticker items for the running-text strip — pengumuman + berita + agenda merged + sorted.
        $announcementItems = Post::query()->ofType(Post::TYPE_ANNOUNCEMENT)->published()
            ->latest('published_at')->limit(5)->get()
            ->map(fn ($p) => ['label' => 'Pengumuman', 'title' => $p->title, 'date' => $p->published_at, 'url' => route('informasi.pengumuman.show', $p->slug)]);
        $newsItems = Post::query()->ofType(Post::TYPE_NEWS)->published()
            ->latest('published_at')->limit(5)->get()
            ->map(fn ($p) => ['label' => 'Berita', 'title' => $p->title, 'date' => $p->published_at, 'url' => route('informasi.berita.show', $p->slug)]);
        $agendaItems = Agenda::query()->published()
            ->orderBy('starts_at', 'desc')->limit(3)->get()
            ->map(fn ($a) => ['label' => 'Agenda', 'title' => $a->title, 'date' => $a->starts_at, 'url' => route('informasi.agenda.index')]);
        $tickerItems = $announcementItems
            ->concat($newsItems)
            ->concat($agendaItems)
            ->sortByDesc('date')
            ->take(8)
            ->values()
            ->all();

        return view('pages.home', [
            'pageTitle'     => 'Beranda',
            'seo'           => $this->seo->for('home'),
            'slides'        => $this->hero->slides(),
            'counters'      => $this->statistic->counters(),
            'trendStats'    => $trendStats,
            'tickerItems'   => $tickerItems,
            'applications'  => $this->applications->featured(8),
            'profilSnippet' => Post::query()->ofType(Post::TYPE_PROFIL)->where('slug', 'profil-dpmptsp-kota-surabaya')->published()->first(),
            'mengapaSnippet'=> Post::query()->ofType(Post::TYPE_PROFIL)->where('slug', 'mengapa-investasi-di-surabaya')->published()->first(),
            'latestNews'    => Post::query()->ofType(Post::TYPE_NEWS)->published()->latest('published_at')->limit(3)->get(),
            'latestAnnounce'=> Post::query()->ofType(Post::TYPE_ANNOUNCEMENT)->published()->latest('published_at')->limit(3)->get(),
            'upcomingAgendas' => Agenda::query()->published()->orderBy('starts_at', 'desc')->limit(3)->get(),
            'latestRegs'    => Regulation::query()->where('is_published', true)->orderBy('doc_year', 'desc')->orderBy('doc_number', 'desc')->limit(4)->get(),
            'topFaqs'       => Faq::query()->where('is_published', true)->with('category')->orderBy('sort_order')->limit(4)->get(),
        ]);
    }
}
