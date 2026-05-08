<?php

namespace App\Domain\Application\Repositories;

use App\Domain\Application\Models\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentApplicationRepository implements ApplicationRepository
{
    public function find(int $id): ?Application
    {
        return Application::query()->find($id);
    }

    public function findBySlug(string $slug): ?Application
    {
        return Application::query()->where('slug', $slug)->first();
    }

    public function publishedAndActive(): Collection
    {
        return Application::query()
            ->active()
            ->published()
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function featured(int $limit = 8): Collection
    {
        return Application::query()
            ->active()
            ->published()
            ->featured()
            ->with('category')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function paginatePublished(?string $categorySlug, int $perPage = 12): LengthAwarePaginator
    {
        return Application::query()
            ->active()
            ->published()
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->with('category')
            ->orderBy('is_featured', 'desc')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($perPage);
    }
}
