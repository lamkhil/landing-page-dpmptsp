<?php

namespace App\Http\Controllers\Public;

use App\Domain\Application\Models\ApplicationCategory;
use App\Domain\Application\Services\ApplicationService;
use App\Domain\Seo\Services\SeoService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplicationController extends Controller
{
    public function __construct(
        private readonly ApplicationService $applications,
        private readonly SeoService $seo,
    ) {}

    public function index(Request $request): View
    {
        $categorySlug = $request->query('kategori');
        $perPage      = (int) $request->query('per_page', 12);
        $perPage      = max(6, min($perPage, 36));

        return view('pages.aplikasi.index', [
            'pageTitle'   => 'Aplikasi Publik',
            'seo'         => $this->seo->for('aplikasi'),
            'paginator'   => $this->applications->paginate($categorySlug, $perPage),
            'featured'    => $this->applications->featured(4),
            'categories'  => ApplicationCategory::query()->orderBy('sort_order')->get(['name', 'slug']),
            'activeCat'   => $categorySlug,
        ]);
    }

    public function show(string $slug): View
    {
        $app = $this->applications->findBySlug($slug);
        if (! $app) {
            throw new NotFoundHttpException('Aplikasi tidak ditemukan.');
        }

        return view('pages.aplikasi.show', [
            'pageTitle' => $app->name,
            'seo'       => $this->seo->for('aplikasi'),
            'app'       => $app,
            'related'   => $this->applications->featured(4),
        ]);
    }
}
