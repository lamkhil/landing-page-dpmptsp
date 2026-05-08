<?php

namespace App\Domain\Statistic\Services;

use App\Domain\Statistic\Models\StatisticCounter;
use App\Domain\Statistic\Models\StatisticGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StatisticService
{
    /**
     * Returns visible counter cards as a Collection of stdClass-like arrays.
     * Caches primitive arrays (not Eloquent models) to avoid unserialize-class
     * issues across deploys / autoload changes.
     *
     * @return Collection<int, object{key:string,label:string,value:float,unit:?string,icon:?string}>
     */
    public function counters(): Collection
    {
        $rows = Cache::remember(
            'dpmptsp:statistic:counters',
            $this->ttl(),
            fn () => StatisticCounter::query()
                ->visible()
                ->orderBy('sort_order')
                ->get(['key', 'label', 'value', 'unit', 'icon', 'color'])
                ->map(fn (StatisticCounter $c) => [
                    'key'   => $c->key,
                    'label' => $c->label,
                    'value' => (float) $c->value,
                    'unit'  => $c->unit,
                    'icon'  => $c->icon,
                    'color' => $c->color,
                ])
                ->all(),
        );

        return collect($rows ?? [])->map(fn (array $r) => (object) $r);
    }

    /**
     * @return Collection<int, object{year:int,value:float,label:?string}>
     */
    public function yearlyTrend(string $key, int $years = 5): Collection
    {
        $rows = Cache::remember(
            "dpmptsp:statistic:trend:{$key}:{$years}",
            $this->ttl(),
            function () use ($key, $years) {
                $group = StatisticGroup::where('key', $key)->first();
                if (! $group) {
                    return [];
                }
                return $group->periods()
                    ->yearly()
                    ->where('year', '>=', now()->year - $years + 1)
                    ->orderBy('year')
                    ->get(['year', 'value', 'label'])
                    ->map(fn ($p) => [
                        'year'  => (int) $p->year,
                        'value' => (float) $p->value,
                        'label' => $p->label,
                    ])
                    ->all();
            },
        );

        return collect($rows ?? [])->map(fn (array $r) => (object) $r);
    }

    public function invalidate(): void
    {
        Cache::forget('dpmptsp:statistic:counters');
        foreach (array_keys((array) config('dpmptsp.statistic_groups', [])) as $key) {
            foreach ([3, 5, 10] as $years) {
                Cache::forget("dpmptsp:statistic:trend:{$key}:{$years}");
            }
        }
    }

    private function ttl(): int
    {
        return (int) config('dpmptsp.cache_ttl.statistic', 300);
    }
}
