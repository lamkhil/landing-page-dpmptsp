<?php

namespace App\Domain\Application\Services;

use App\Domain\Application\Models\Application;
use App\Domain\Application\Repositories\ApplicationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ApplicationService
{
    public const CACHE_TAG = 'applications';

    public function __construct(private readonly ApplicationRepository $repository) {}

    /**
     * @return Collection<int, object>  primitive shape: {id,name,slug,url,link_type,icon_path,category_name}
     */
    public function published(): Collection
    {
        return $this->primitiveCollection(
            'published',
            fn () => $this->repository->publishedAndActive()
        );
    }

    public function featured(int $limit = 8): Collection
    {
        return $this->primitiveCollection(
            "featured:{$limit}",
            fn () => $this->repository->featured($limit)
        );
    }

    /**
     * Pagination is request-scoped (depends on ?page=N) — skip persistent cache.
     * Returns the raw paginator so views can call ->links() / ->onFirstPage() etc.
     */
    public function paginate(?string $categorySlug, int $perPage = 12): LengthAwarePaginator
    {
        return $this->repository->paginatePublished($categorySlug, $perPage);
    }

    public function findBySlug(string $slug): ?Application
    {
        // Detail page reads — short cache, raw model (single row, low risk).
        return $this->repository->findBySlug($slug);
    }

    public function invalidate(): void
    {
        $cache = Cache::store();
        if ($cache->getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $cache->tags(self::CACHE_TAG)->flush();
            return;
        }
        // Non-tagged store: forget keys we know we wrote.
        foreach (['published', 'featured:4', 'featured:6', 'featured:8', 'featured:12'] as $suffix) {
            $cache->forget($this->key($suffix));
        }
    }

    private function primitiveCollection(string $cacheKey, \Closure $loader): Collection
    {
        $rows = $this->remember(
            $cacheKey,
            fn () => $loader()->map(fn (Application $a) => [
                'id'            => $a->id,
                'name'          => $a->name,
                'slug'          => $a->slug,
                'description'   => $a->description,
                'url'           => $a->url,
                'link_type'     => $a->link_type,
                'icon_path'     => $a->icon_path,
                'thumbnail_path'=> $a->thumbnail_path,
                'is_featured'   => (bool) $a->is_featured,
                'category_name' => $a->category?->name,
                'category_slug' => $a->category?->slug,
            ])->all()
        );

        return collect($rows ?? [])->map(fn (array $r) => (object) $r);
    }

    private function remember(string $key, \Closure $callback): mixed
    {
        return $this->store()->remember($this->key($key), $this->ttl(), $callback);
    }

    private function store(): \Illuminate\Contracts\Cache\Repository
    {
        $cache = Cache::store();
        if ($cache->getStore() instanceof \Illuminate\Cache\TaggableStore) {
            return $cache->tags(self::CACHE_TAG);
        }
        return $cache;
    }

    private function key(string $suffix): string
    {
        return 'dpmptsp:applications:' . $suffix;
    }

    private function ttl(): int
    {
        return (int) config('dpmptsp.cache_ttl.application', 600);
    }
}
