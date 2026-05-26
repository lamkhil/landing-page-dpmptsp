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
        'pelayanan-non-perizinan' => 'pelayanan-non-perizinan',
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

    /** Perizinan Berusaha — enterprise page, CTA ke OSS RBA. */
    public function perizinan(): View
    {
        $post = $this->layananPost('perizinan');

        return view('pages.layanan.perizinan', [
            'pageTitle'     => 'Perizinan Berusaha',
            'fallbackTitle' => 'Perizinan Berusaha',
            'seo'           => $this->seo->for('layanan'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
        ]);
    }

    /** Perizinan Non-Berusaha — izin di luar kegiatan usaha, CTA ke SSW Alfa. */
    public function nonPerizinan(): View
    {
        $post = $this->layananPost('non-perizinan');

        // Kategori layanan nyata dari SSW Alfa (ServiceStandard akar) + jumlah
        // layanannya — dinamis, ikut data SSW.
        $kategori = \App\Domain\Profil\Models\ServiceStandard::query()
            ->where('is_published', true)
            ->whereNull('parent_id')
            ->withCount(['children' => fn ($q) => $q->where('is_published', true)])
            ->orderByDesc('children_count')
            ->orderBy('name')
            ->get();

        return view('pages.layanan.non-perizinan', [
            'pageTitle'     => 'Perizinan Non-Berusaha',
            'fallbackTitle' => 'Perizinan Non-Berusaha',
            'seo'           => $this->seo->for('layanan'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'kategori'      => $kategori,
        ]);
    }

    /** Pelayanan Non-Perizinan — surat/rekomendasi/administrasi, CTA ke SSW Alfa. */
    public function pelayananNonPerizinan(): View
    {
        $post = $this->layananPost('pelayanan-non-perizinan');

        // Filter hanya layanan NON-PERIZINAN (surat/rekomendasi/administrasi)
        // dari data SSW (ServiceStandard). Data tak punya tanda klasifikasi, jadi
        // disaring via nama: cocokkan penanda non-perizinan, buang penanda izin.
        $inc = '/(surat keterangan|surat rekomendasi|^rekomendasi|surat pengantar|surat pernyataan|^pelayanan surat|^rangkaian pelayanan|ahli waris|\bskaw\b|domisili|konfirmasi status|daftar riwayat|legalisir|surat kuasa)/i';
        $exc = '/(\bizin\b|\bijin\b|\bsip[a-z]?\b|sertifikat|persetujuan|tanda daftar|\bkkpr\b|\bpbg\b|\bslf\b|amdal|andalalin|andal lalin)/i';

        $nonPerizinan = \App\Domain\Profil\Models\ServiceStandard::query()
            ->where('is_published', true)
            ->whereNotNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'parent_id', 'name'])
            ->filter(fn ($r) => preg_match($inc, $r->name) && ! preg_match($exc, $r->name))
            ->unique('name')
            ->values();

        return view('pages.layanan.pelayanan-non-perizinan', [
            'pageTitle'     => 'Pelayanan Non-Perizinan',
            'fallbackTitle' => 'Pelayanan Non-Perizinan',
            'seo'           => $this->seo->for('layanan'),
            'post'          => $post,
            'intro'         => $post?->excerpt,
            'layanan'       => $nonPerizinan,
        ]);
    }
    public function oss(): View           { return $this->render('OSS', 'oss'); }
    public function konsultasi(): View    { return $this->render('Konsultasi Online', 'konsultasi'); }
    public function antrian(): View       { return $this->render('Antrian Online', 'antrian'); }
    public function persyaratan(): View   { return $this->render('Persyaratan Perizinan', 'persyaratan'); }
    public function formulir(): View      { return $this->render('Download Formulir', 'formulir'); }
    public function sla(): View           { return $this->render('SLA Pelayanan', 'sla'); }

    /** Fetch the published Post backing a layanan key (title/SEO/intro source). */
    private function layananPost(string $key): ?Post
    {
        $slug = self::SLUG_MAP[$key] ?? null;

        return $slug
            ? Post::query()->ofType(Post::TYPE_PROFIL)->where('slug', $slug)->published()->first()
            : null;
    }

    private function render(string $title, string $key): View
    {
        $post = $this->layananPost($key);

        return view('pages.layanan.show', [
            'pageTitle' => $title,
            'seo'       => $this->seo->for('layanan'),
            'post'      => $post,
            'fallbackTitle' => $title,
        ]);
    }
}
