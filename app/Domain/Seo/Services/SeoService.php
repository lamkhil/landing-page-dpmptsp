<?php

namespace App\Domain\Seo\Services;

use App\Domain\Seo\Models\SeoSetting;
use Illuminate\Support\Facades\Cache;

class SeoService
{
    public function for(string $pageKey): ?SeoSetting
    {
        return Cache::remember("dpmptsp:seo:{$pageKey}", config('dpmptsp.cache_ttl.seo', 1800), fn () =>
            SeoSetting::query()->where('page_key', $pageKey)->first()
        );
    }

    public function invalidate(?string $pageKey = null): void
    {
        if ($pageKey) {
            Cache::forget("dpmptsp:seo:{$pageKey}");
            return;
        }
        // page_keys are limited; iterate the known set.
        foreach (['home', 'profil', 'layanan', 'aplikasi', 'statistik', 'informasi', 'pengaduan', 'kontak'] as $key) {
            Cache::forget("dpmptsp:seo:{$key}");
        }
    }
}
