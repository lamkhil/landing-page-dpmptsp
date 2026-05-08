<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Post;
use App\Domain\Faq\Models\Faq;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ProfilController extends Controller
{
    /**
     * Maps each profil sub-route to a CMS Post slug. Admin manages the content
     * via Filament (Post type=profil); the route + slug pair is hardcoded here.
     */
    private const SLUG_MAP = [
        'index'           => 'profil-dpmptsp-kota-surabaya',
        'visi-misi'       => 'visi-misi-dpmptsp-kota-surabaya',
        'struktur'        => 'struktur-organisasi',
        'tugas-fungsi'    => 'tugas-fungsi',
        'maklumat'        => 'maklumat-pelayanan',
        'sop'             => 'sop-pelayanan',
        'standar'         => 'standar-pelayanan',
        'reformasi'       => 'reformasi-birokrasi',
        'zona-integritas' => 'zona-integritas',
        'wbk-wbbm'        => 'wbk-wbbm',
        'mengapa-surabaya'=> 'mengapa-investasi-di-surabaya',
    ];

    public function __construct(private readonly SeoService $seo) {}

    public function index(): View         { return $this->render('Profil DPMPTSP', 'index'); }
    public function visiMisi(): View      { return $this->render('Visi & Misi', 'visi-misi'); }
    public function struktur(): View      { return $this->render('Struktur Organisasi', 'struktur'); }
    public function tugasFungsi(): View   { return $this->render('Tugas & Fungsi', 'tugas-fungsi'); }
    public function maklumat(): View      { return $this->render('Maklumat Pelayanan', 'maklumat'); }
    public function sop(): View           { return $this->render('SOP Pelayanan', 'sop'); }
    public function standar(): View       { return $this->render('Standar Pelayanan', 'standar'); }
    public function reformasi(): View     { return $this->render('Reformasi Birokrasi', 'reformasi'); }
    public function zonaIntegritas(): View{ return $this->render('Zona Integritas', 'zona-integritas'); }
    public function wbkWbbm(): View       { return $this->render('WBK / WBBM', 'wbk-wbbm'); }
    public function mengapaSurabaya(): View { return $this->render('Mengapa Investasi di Surabaya', 'mengapa-surabaya'); }

    public function faq(): View
    {
        return view('pages.profil.faq', [
            'pageTitle' => 'FAQ — DPMPTSP',
            'seo'       => $this->seo->for('profil'),
            'faqs'      => Faq::query()->where('is_published', true)->with('category')->orderBy('sort_order')->get(),
            'grouped'   => Faq::query()
                ->where('is_published', true)
                ->with('category')
                ->orderBy('sort_order')
                ->get()
                ->groupBy(fn ($f) => $f->category?->name ?? 'Umum'),
        ]);
    }

    private function render(string $title, string $key): View
    {
        $slug = self::SLUG_MAP[$key] ?? null;
        $post = $slug
            ? Post::query()->ofType(Post::TYPE_PROFIL)->where('slug', $slug)->published()->first()
            : null;

        return view('pages.profil.show', [
            'pageTitle' => $title,
            'seo'       => $this->seo->for('profil'),
            'post'      => $post,
            'fallbackTitle' => $title,
        ]);
    }
}
