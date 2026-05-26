<?php

namespace App\Domain\Menu\Services;

use App\Domain\Menu\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Returns visible menu items for a given navbar group, ordered by sort_order.
     * Falls back to an empty collection if the table is empty / DB unreachable —
     * navbar component then renders the hardcoded route defaults.
     */
    public function byGroup(string $group): Collection
    {
        return Cache::remember(
            $this->key($group),
            $this->ttl(),
            fn () => $this->fetch($group),
        );
    }

    public function invalidate(?string $group = null): void
    {
        if ($group) {
            Cache::forget($this->key($group));
            return;
        }
        foreach (['beranda', 'profil', 'layanan', 'aplikasi', 'statistik', 'informasi', 'dokumen', 'pengaduan', 'kontak', 'footer'] as $g) {
            Cache::forget($this->key($g));
        }
    }

    private function fetch(string $group): Collection
    {
        try {
            return Menu::query()
                ->visible()
                ->inGroup($group)
                ->whereNull('parent_id')
                ->with(['children' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get(['id', 'group', 'label', 'route_name', 'external_url', 'icon', 'open_in_new_tab']);
        } catch (\Throwable $e) {
            // DB unreachable / table missing → graceful fallback so the page still renders.
            report($e);
            return collect();
        }
    }

    private function key(string $group): string
    {
        return "dpmptsp:menu:group:{$group}";
    }

    private function ttl(): int
    {
        return (int) config('dpmptsp.cache_ttl.menu', 1800);
    }
}
