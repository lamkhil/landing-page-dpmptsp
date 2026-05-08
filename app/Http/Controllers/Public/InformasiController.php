<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Agenda;
use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\Post;
use App\Domain\Content\Models\Regulation;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InformasiController extends Controller
{
    public function __construct(private readonly SeoService $seo) {}

    public function index(): View
    {
        return view('pages.informasi.index', [
            'pageTitle'    => 'Informasi Publik',
            'seo'          => $this->seo->for('informasi'),
            'latestNews'   => Post::query()->ofType(Post::TYPE_NEWS)->published()->latest('published_at')->limit(6)->get(),
            'latestAnnounce' => Post::query()->ofType(Post::TYPE_ANNOUNCEMENT)->published()->latest('published_at')->limit(4)->get(),
            'upcomingAgenda' => Agenda::query()->published()->upcoming()->limit(4)->get(),
        ]);
    }

    public function beritaIndex(Request $request): View
    {
        return $this->postIndex(Post::TYPE_NEWS, 'Berita', 'berita', $request);
    }

    public function beritaShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_NEWS, $slug, 'Berita', 'informasi.berita.index', 'informasi.berita.show');
    }

    public function pengumumanIndex(Request $request): View
    {
        return $this->postIndex(Post::TYPE_ANNOUNCEMENT, 'Pengumuman', 'pengumuman', $request);
    }

    public function pengumumanShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_ANNOUNCEMENT, $slug, 'Pengumuman', 'informasi.pengumuman.index', 'informasi.pengumuman.show');
    }

    public function artikelIndex(Request $request): View
    {
        return $this->postIndex(Post::TYPE_ARTICLE, 'Artikel', 'artikel', $request);
    }

    public function artikelShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_ARTICLE, $slug, 'Artikel', 'informasi.artikel.index', 'informasi.artikel.show');
    }

    public function infografisIndex(Request $request): View
    {
        return $this->postIndex(Post::TYPE_INFOGRAFIS, 'Infografis', 'infografis', $request);
    }

    public function agendaIndex(): View
    {
        return view('pages.informasi.agenda', [
            'pageTitle' => 'Agenda',
            'seo'       => $this->seo->for('informasi'),
            'agendas'   => Agenda::query()->published()->orderBy('starts_at', 'desc')->paginate(12),
        ]);
    }

    public function regulasiIndex(Request $request): View
    {
        $year = $request->query('tahun');
        $type = $request->query('jenis');

        $paginator = Regulation::query()
            ->where('is_published', true)
            ->when($year, fn ($q) => $q->where('doc_year', (int) $year))
            ->when($type, fn ($q) => $q->where('doc_type', $type))
            ->orderBy('doc_year', 'desc')
            ->orderBy('doc_number', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pages.informasi.regulasi', [
            'pageTitle' => 'Regulasi',
            'seo'       => $this->seo->for('informasi'),
            'paginator' => $paginator,
            'years'     => Regulation::query()->where('is_published', true)->distinct()->orderBy('doc_year', 'desc')->pluck('doc_year'),
            'types'     => Regulation::query()->where('is_published', true)->distinct()->pluck('doc_type'),
            'activeYear'=> $year,
            'activeType'=> $type,
        ]);
    }

    public function dokumenIndex(): View
    {
        return view('pages.informasi.dokumen', [
            'pageTitle' => 'Dokumen Publik',
            'seo'       => $this->seo->for('informasi'),
            'paginator' => Document::query()->where('is_published', true)->latest()->paginate(15),
        ]);
    }

    public function infografisShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_INFOGRAFIS, $slug, 'Infografis', 'informasi.infografis.index', 'informasi.infografis.show');
    }

    public function lkjip(): View              { return $this->stub('LKjIP'); }
    public function renstra(): View            { return $this->stub('Renstra'); }
    public function laporanTahunan(): View     { return $this->stub('Laporan Tahunan'); }
    public function downloadCenter(): View     { return view('pages.informasi.dokumen', [
        'pageTitle' => 'Download Center',
        'seo'       => $this->seo->for('informasi'),
        'paginator' => Document::query()->where('is_published', true)->latest()->paginate(15),
    ]); }

    private function postIndex(string $type, string $title, string $section, Request $request): View
    {
        $paginator = Post::query()
            ->ofType($type)
            ->published()
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'ilike', "%{$term}%"))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('pages.informasi.list', [
            'pageTitle' => $title,
            'seo'       => $this->seo->for('informasi'),
            'paginator' => $paginator,
            'section'   => $section,
            'detailRouteName' => "informasi.{$section}.show",
            'searchTerm' => $request->query('q'),
        ]);
    }

    private function postShow(string $type, string $slug, string $section, string $listRoute, string $showRoute): View
    {
        $post = Post::query()->ofType($type)->where('slug', $slug)->published()->first();
        if (! $post) {
            throw new NotFoundHttpException(ucfirst($section) . ' tidak ditemukan.');
        }

        // Increment view count without firing model events (avoids cache invalidation churn).
        Post::query()->whereKey($post->id)->update(['view_count' => $post->view_count + 1]);

        return view('pages.informasi.show', [
            'pageTitle' => $post->title,
            'seo'       => $this->seo->for('informasi'),
            'post'      => $post,
            'section'   => $section,
            'listRoute' => $listRoute,
            'related'   => Post::query()->ofType($type)->published()->whereKeyNot($post->id)->latest('published_at')->limit(4)->get(),
        ]);
    }

    private function stub(string $title): View
    {
        return view('pages.placeholder', ['pageTitle' => $title, 'section' => 'Informasi Publik']);
    }
}
