<?php

namespace App\Domain\Footer\Services;

use App\Domain\Footer\Models\FooterLink;
use App\Domain\Footer\Models\FooterSetting;
use Illuminate\Support\Facades\Cache;

class FooterService
{
    /**
     * Footer singleton settings as a primitive object (resilient cache).
     */
    public function settings(): object
    {
        $data = Cache::remember(
            'dpmptsp:footer:settings',
            $this->ttl(),
            fn () => $this->fetchSettings(),
        );
        return (object) $data;
    }

    /**
     * @return array<string, array<int, object>>  group → [{label,url,open_in_new_tab}]
     */
    public function groupedLinks(): array
    {
        $groups = Cache::remember(
            'dpmptsp:footer:links',
            $this->ttl(),
            fn () => $this->fetchLinks(),
        );

        // Re-hydrate inner items as stdClass so views can use ->property.
        $out = [];
        foreach ($groups as $group => $items) {
            $out[$group] = array_map(fn (array $i) => (object) $i, $items);
        }
        return $out;
    }

    public function invalidate(): void
    {
        Cache::forget('dpmptsp:footer:settings');
        Cache::forget('dpmptsp:footer:links');
    }

    private function fetchSettings(): array
    {
        $s = FooterSetting::singleton();
        return [
            'address'       => $s->address,
            'phone'         => $s->phone,
            'email'         => $s->email,
            'office_hours'  => $s->office_hours,
            'embed_map_url' => $s->embed_map_url,
            'social_links'  => $s->social_links ?? [],
            'about_text'    => $s->about_text,
        ];
    }

    private function fetchLinks(): array
    {
        return FooterLink::query()
            ->visible()
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get(['group', 'label', 'url', 'open_in_new_tab'])
            ->groupBy('group')
            ->map(fn ($items) => $items->map(fn ($i) => [
                'label'           => $i->label,
                'url'             => $i->url,
                'open_in_new_tab' => (bool) $i->open_in_new_tab,
            ])->all())
            ->all();
    }

    private function ttl(): int
    {
        return (int) config('dpmptsp.cache_ttl.footer', 1800);
    }
}
