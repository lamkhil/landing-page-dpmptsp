<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Post;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class LayananController extends Controller
{
    private const SLUG_MAP = [
        'index'         => 'layanan',
        'perizinan'     => 'perizinan-berusaha',
        'non-perizinan' => 'non-perizinan',
        'oss'           => 'oss',
        'tracking'      => 'tracking-perizinan',
        'konsultasi'    => 'konsultasi-online',
        'antrian'       => 'antrian-online',
        'persyaratan'   => 'persyaratan-perizinan',
        'formulir'      => 'download-formulir',
        'sla'           => 'sla-pelayanan',
    ];

    public function __construct(private readonly SeoService $seo) {}

    public function index(): View         { return $this->render('Layanan DPMPTSP', 'index'); }
    public function perizinan(): View     { return $this->render('Perizinan Berusaha', 'perizinan'); }
    public function nonPerizinan(): View  { return $this->render('Non Perizinan', 'non-perizinan'); }
    public function oss(): View           { return $this->render('OSS', 'oss'); }
    public function tracking(): View      { return $this->render('Tracking Perizinan', 'tracking'); }
    public function konsultasi(): View    { return $this->render('Konsultasi Online', 'konsultasi'); }
    public function antrian(): View       { return $this->render('Antrian Online', 'antrian'); }
    public function persyaratan(): View   { return $this->render('Persyaratan Perizinan', 'persyaratan'); }
    public function formulir(): View      { return $this->render('Download Formulir', 'formulir'); }
    public function sla(): View           { return $this->render('SLA Pelayanan', 'sla'); }

    private function render(string $title, string $key): View
    {
        $slug = self::SLUG_MAP[$key] ?? null;
        $post = $slug
            ? Post::query()->ofType(Post::TYPE_PROFIL)->where('slug', $slug)->published()->first()
            : null;

        return view('pages.layanan.show', [
            'pageTitle' => $title,
            'seo'       => $this->seo->for('layanan'),
            'post'      => $post,
            'fallbackTitle' => $title,
        ]);
    }
}
