<?php

namespace App\Domain\Hero\Services;

use App\Domain\Hero\Models\HeroSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HeroService
{
    /**
     * Returns all active heroes (sorted) as primitive objects so the
     * homepage carousel can iterate them. Cache key isolated from any
     * legacy single-hero state.
     *
     * @return Collection<int, object>
     */
    public function slides(): Collection
    {
        $rows = Cache::remember(
            'dpmptsp:hero:slides',
            (int) config('dpmptsp.cache_ttl.hero', 600),
            fn () => $this->fetchSlides(),
        );

        return collect($rows ?? [])->map(function (array $r) {
            $r['highlights'] = array_map(fn (array $h) => (object) $h, $r['highlights'] ?? []);
            return (object) $r;
        });
    }

    /** Backwards-compatible: returns the first active hero (or null). */
    public function active(): ?object
    {
        return $this->slides()->first();
    }

    public function invalidate(): void
    {
        Cache::forget('dpmptsp:hero:slides');
        Cache::forget('dpmptsp:hero:active');
    }

    private function fetchSlides(): array
    {
        return HeroSection::query()
            ->active()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('highlights')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (HeroSection $h) => [
                'title'               => $h->title,
                'subtitle'            => $h->subtitle,
                'description'         => $h->description,
                'cta_label'           => $h->cta_label,
                'cta_url'             => $h->cta_url,
                'secondary_cta_label' => $h->secondary_cta_label,
                'secondary_cta_url'   => $h->secondary_cta_url,
                'running_text'        => $h->running_text,
                'background_path'     => $h->background_path,
                'video_path'          => $h->video_path,
                'highlights'          => $h->highlights->map(fn ($x) => [
                    'title'       => $x->title,
                    'description' => $x->description,
                    'icon'        => $x->icon,
                    'url'         => $x->url,
                ])->all(),
            ])
            ->all();
    }
}
