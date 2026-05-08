<?php

namespace App\Http\Controllers\Public;

use App\Domain\Application\Models\Application;
use App\Domain\Content\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('dpmptsp:sitemap.xml', 3600, function (): string {
            $urls = [];

            // Static section roots (8 navbar sections + key subroutes).
            $staticRoutes = [
                'home', 'profil.index', 'layanan.index', 'aplikasi.index',
                'statistik.index', 'informasi.index', 'pengaduan.index', 'kontak.index',
                'profil.visi-misi', 'profil.struktur', 'profil.tugas-fungsi',
                'profil.maklumat', 'profil.sop', 'profil.standar', 'profil.zi', 'profil.wbk',
                'layanan.perizinan', 'layanan.tracking', 'layanan.oss',
                'pengaduan.lapor', 'pengaduan.tracking',
            ];
            foreach ($staticRoutes as $name) {
                $urls[] = ['loc' => route($name), 'changefreq' => 'weekly', 'priority' => '0.7'];
            }

            // Published applications.
            Application::query()->active()->published()->get(['slug', 'updated_at'])->each(function (Application $a) use (&$urls) {
                $urls[] = [
                    'loc'     => route('aplikasi.show', $a->slug),
                    'lastmod' => $a->updated_at?->toAtomString(),
                    'priority'=> '0.6',
                ];
            });

            // Published posts (news + announcement + article).
            Post::query()->published()
                ->whereIn('type', [Post::TYPE_NEWS, Post::TYPE_ANNOUNCEMENT, Post::TYPE_ARTICLE])
                ->get(['type', 'slug', 'updated_at', 'published_at'])
                ->each(function (Post $p) use (&$urls) {
                    $route = match ($p->type) {
                        Post::TYPE_NEWS         => 'informasi.berita.show',
                        Post::TYPE_ANNOUNCEMENT => 'informasi.pengumuman.show',
                        default                 => 'informasi.artikel.show',
                    };
                    $urls[] = [
                        'loc'     => route($route, $p->slug),
                        'lastmod' => ($p->updated_at ?? $p->published_at)?->toAtomString(),
                        'priority'=> '0.5',
                    ];
                });

            $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($urls as $u) {
                $xml .= "  <url>\n    <loc>".htmlspecialchars($u['loc'], ENT_XML1)."</loc>\n";
                if (! empty($u['lastmod'])) $xml .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
                if (! empty($u['changefreq'])) $xml .= "    <changefreq>{$u['changefreq']}</changefreq>\n";
                if (! empty($u['priority'])) $xml .= "    <priority>{$u['priority']}</priority>\n";
                $xml .= "  </url>\n";
            }
            $xml .= '</urlset>';
            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
