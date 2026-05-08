<?php

namespace App\Domain\Application\Repositories;

use App\Domain\Application\Models\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ApplicationRepository
{
    public function find(int $id): ?Application;

    public function findBySlug(string $slug): ?Application;

    public function publishedAndActive(): Collection;

    public function featured(int $limit = 8): Collection;

    public function paginatePublished(?string $categorySlug, int $perPage = 12): LengthAwarePaginator;
}
