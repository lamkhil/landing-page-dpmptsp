<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Agenda;
use App\Domain\Content\Models\Category;
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

    /**
     * Halaman Berita — layout portal berita (headline, kanal kategori,
     * grid berita, sidebar terpopuler). Headline hanya tampil saat tidak
     * memfilter/mencari agar urutan tetap relevan.
     */
    public function beritaIndex(Request $request): View
    {
        $term         = $request->query('q');
        $categorySlug = $request->query('kategori');

        $base = fn () => Post::query()->ofType(Post::TYPE_NEWS)->published()->with('category');

        // Kanal kategori — hanya kategori 'post' yang punya berita terbit.
        $usedCategoryIds = Post::query()->ofType(Post::TYPE_NEWS)->published()
            ->whereNotNull('category_id')->distinct()->pluck('category_id');
        $categories = Category::query()
            ->where('type', 'post')
            ->whereIn('id', $usedCategoryIds)
            ->orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name', 'slug', 'color']);
        $activeCategory = $categorySlug ? $categories->firstWhere('slug', $categorySlug) : null;

        $isFiltering = filled($term) || $activeCategory;

        // Headline + berita sekunder (hanya pada tampilan default).
        $headline   = null;
        $secondary  = collect();
        $excludeIds = [];
        if (! $isFiltering) {
            $headline = $base()->where('is_featured', true)->latest('published_at')->first()
                ?? $base()->latest('published_at')->first();
            if ($headline) {
                $secondary  = $base()->whereKeyNot($headline->id)->latest('published_at')->limit(4)->get();
                $excludeIds = $secondary->pluck('id')->push($headline->id)->all();
            }
        }

        $paginator = $base()
            ->when($term, fn ($q, $t) => $q->where('title', 'ilike', "%{$t}%"))
            ->when($activeCategory, fn ($q) => $q->where('category_id', $activeCategory->id))
            ->when($excludeIds, fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $popular = $base()->where('view_count', '>', 0)->orderByDesc('view_count')->limit(5)->get();
        if ($popular->isEmpty()) {
            $popular = $base()->latest('published_at')->limit(5)->get();
        }

        return view('pages.informasi.berita', [
            'pageTitle'      => 'Berita',
            'seo'            => $this->seo->for('informasi'),
            'paginator'      => $paginator,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'headline'       => $headline,
            'secondary'      => $secondary,
            'popular'        => $popular,
            'searchTerm'     => $term,
            'isFiltering'    => $isFiltering,
        ]);
    }

    public function beritaShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_NEWS, $slug, 'Berita', 'informasi.berita.index', 'informasi.berita.show');
    }

    /**
     * Halaman Pengumuman — layout papan pengumuman resmi: pengumuman
     * disematkan (is_featured) di atas, lalu daftar kronologis dengan blok
     * tanggal. Mendukung filter kategori (?kategori=) dan pencarian (?q=).
     */
    public function pengumumanIndex(Request $request): View
    {
        $term         = $request->query('q');
        $categorySlug = $request->query('kategori');

        $base = fn () => Post::query()->ofType(Post::TYPE_ANNOUNCEMENT)->published()->with('category');

        $usedCategoryIds = Post::query()->ofType(Post::TYPE_ANNOUNCEMENT)->published()
            ->whereNotNull('category_id')->distinct()->pluck('category_id');
        $categories = Category::query()
            ->where('type', 'post')
            ->whereIn('id', $usedCategoryIds)
            ->orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name', 'slug']);
        $activeCategory = $categorySlug ? $categories->firstWhere('slug', $categorySlug) : null;

        $isFiltering = filled($term) || $activeCategory;

        $pinned     = collect();
        $excludeIds = [];
        if (! $isFiltering) {
            $pinned     = $base()->where('is_featured', true)->latest('published_at')->limit(3)->get();
            $excludeIds = $pinned->pluck('id')->all();
        }

        $paginator = $base()
            ->when($term, fn ($q, $t) => $q->where('title', 'ilike', "%{$t}%"))
            ->when($activeCategory, fn ($q) => $q->where('category_id', $activeCategory->id))
            ->when($excludeIds, fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.informasi.pengumuman', [
            'pageTitle'      => 'Pengumuman',
            'seo'            => $this->seo->for('informasi'),
            'paginator'      => $paginator,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'pinned'         => $pinned,
            'searchTerm'     => $term,
            'isFiltering'    => $isFiltering,
        ]);
    }

    public function pengumumanShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_ANNOUNCEMENT, $slug, 'Pengumuman', 'informasi.pengumuman.index', 'informasi.pengumuman.show');
    }

    /**
     * Halaman Artikel — layout editorial/majalah (artikel pilihan, topik,
     * grid dengan penulis & estimasi baca, sidebar terpopuler). Sejajar
     * dengan halaman Berita namun fokus konten long-form.
     */
    public function artikelIndex(Request $request): View
    {
        $term         = $request->query('q');
        $categorySlug = $request->query('topik');

        $base = fn () => Post::query()->ofType(Post::TYPE_ARTICLE)->published()->with(['category', 'author']);

        $usedCategoryIds = Post::query()->ofType(Post::TYPE_ARTICLE)->published()
            ->whereNotNull('category_id')->distinct()->pluck('category_id');
        $categories = Category::query()
            ->where('type', 'post')
            ->whereIn('id', $usedCategoryIds)
            ->orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name', 'slug', 'color']);
        $activeCategory = $categorySlug ? $categories->firstWhere('slug', $categorySlug) : null;

        $isFiltering = filled($term) || $activeCategory;

        $headline   = null;
        $secondary  = collect();
        $excludeIds = [];
        if (! $isFiltering) {
            $headline = $base()->where('is_featured', true)->latest('published_at')->first()
                ?? $base()->latest('published_at')->first();
            if ($headline) {
                $secondary  = $base()->whereKeyNot($headline->id)->latest('published_at')->limit(4)->get();
                $excludeIds = $secondary->pluck('id')->push($headline->id)->all();
            }
        }

        $paginator = $base()
            ->when($term, fn ($q, $t) => $q->where('title', 'ilike', "%{$t}%"))
            ->when($activeCategory, fn ($q) => $q->where('category_id', $activeCategory->id))
            ->when($excludeIds, fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $popular = $base()->where('view_count', '>', 0)->orderByDesc('view_count')->limit(5)->get();
        if ($popular->isEmpty()) {
            $popular = $base()->latest('published_at')->limit(5)->get();
        }

        return view('pages.informasi.artikel', [
            'pageTitle'      => 'Artikel',
            'seo'            => $this->seo->for('informasi'),
            'paginator'      => $paginator,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'headline'       => $headline,
            'secondary'      => $secondary,
            'popular'        => $popular,
            'searchTerm'     => $term,
            'isFiltering'    => $isFiltering,
        ]);
    }

    public function artikelShow(string $slug): View
    {
        return $this->postShow(Post::TYPE_ARTICLE, $slug, 'Artikel', 'informasi.artikel.index', 'informasi.artikel.show');
    }

    /**
     * Halaman Infografis — galeri poster visual. Karena infografis bersifat
     * visual, kartu memakai header gradien + ikon per kategori (cover opsional).
     * Mendukung filter kategori (?kategori=) dan pencarian (?q=).
     */
    public function infografisIndex(Request $request): View
    {
        $term         = $request->query('q');
        $categorySlug = $request->query('kategori');

        $base = fn () => Post::query()->ofType(Post::TYPE_INFOGRAFIS)->published()->with('category');

        $usedCategoryIds = Post::query()->ofType(Post::TYPE_INFOGRAFIS)->published()
            ->whereNotNull('category_id')->distinct()->pluck('category_id');
        $categories = Category::query()
            ->where('type', 'post')
            ->whereIn('id', $usedCategoryIds)
            ->orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name', 'slug', 'color']);
        $activeCategory = $categorySlug ? $categories->firstWhere('slug', $categorySlug) : null;

        $isFiltering = filled($term) || $activeCategory;

        $featured   = collect();
        $excludeIds = [];
        if (! $isFiltering) {
            $featured   = $base()->where('is_featured', true)->latest('published_at')->limit(3)->get();
            $excludeIds = $featured->pluck('id')->all();
        }

        $paginator = $base()
            ->when($term, fn ($q, $t) => $q->where('title', 'ilike', "%{$t}%"))
            ->when($activeCategory, fn ($q) => $q->where('category_id', $activeCategory->id))
            ->when($excludeIds, fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('pages.informasi.infografis', [
            'pageTitle'      => 'Infografis',
            'seo'            => $this->seo->for('informasi'),
            'paginator'      => $paginator,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'featured'       => $featured,
            'searchTerm'     => $term,
            'isFiltering'    => $isFiltering,
        ]);
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

    /**
     * Hub "Dokumen Publik" — landing menu top-level yang mengarahkan ke
     * masing-masing arsip (Regulasi, LKjIP, Renstra, Laporan Tahunan,
     * Download Center). Hanya menampilkan kartu kategori + jumlah dokumen.
     */
    public function dokumenPublik(): View
    {
        $regulasiCount = Regulation::query()->where('is_published', true)->count();
        $dokumenCount  = Document::query()->where('is_published', true)->count();

        return view('pages.informasi.dokumen-publik', [
            'pageTitle' => 'Dokumen Publik',
            'seo'       => $this->seo->for('informasi'),
            'categories' => [
                ['label' => 'Regulasi',        'desc' => 'Perwali, Perda, SK, SOP & dasar hukum pelayanan.', 'route' => 'informasi.regulasi.index', 'icon' => 'scale',     'count' => $regulasiCount, 'unit' => 'dokumen'],
                ['label' => 'LKjIP',           'desc' => 'Laporan Kinerja Instansi Pemerintah tahunan.',      'route' => 'informasi.lkjip',         'icon' => 'chart',     'count' => null],
                ['label' => 'Renstra',         'desc' => 'Rencana Strategis DPMPTSP Kota Surabaya.',          'route' => 'informasi.renstra',       'icon' => 'target',    'count' => null],
                ['label' => 'Laporan Tahunan', 'desc' => 'Ringkasan capaian & realisasi program per tahun.',  'route' => 'informasi.laporan-tahunan','icon' => 'calendar',  'count' => null],
                ['label' => 'Download Center', 'desc' => 'Seluruh berkas publik yang dapat diunduh.',         'route' => 'informasi.download',      'icon' => 'download',  'count' => $dokumenCount, 'unit' => 'berkas'],
            ],
        ]);
    }

    public function infografisShow(string $slug): View
    {
        $post = Post::query()->ofType(Post::TYPE_INFOGRAFIS)->where('slug', $slug)->published()->with('category')->first();
        if (! $post) {
            throw new NotFoundHttpException('Infografis tidak ditemukan.');
        }

        Post::query()->whereKey($post->id)->update(['view_count' => $post->view_count + 1]);

        // Infografis terkait — utamakan kategori sama, lalu terbaru.
        $related = Post::query()->ofType(Post::TYPE_INFOGRAFIS)->published()
            ->whereKeyNot($post->id)
            ->with('category')
            ->when($post->category_id, fn ($q) => $q->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$post->category_id]))
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('pages.informasi.infografis-show', [
            'pageTitle' => $post->title,
            'seo'       => $this->seo->for('informasi'),
            'post'      => $post,
            'related'   => $related,
        ]);
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
            'showRoute' => $showRoute,
            'related'   => Post::query()->ofType($type)->published()->whereKeyNot($post->id)->latest('published_at')->limit(4)->get(),
        ]);
    }

    private function stub(string $title): View
    {
        return view('pages.placeholder', ['pageTitle' => $title, 'section' => 'Informasi Publik']);
    }
}
