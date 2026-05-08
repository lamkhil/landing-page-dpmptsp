<?php

namespace App\Providers;

use App\Domain\Application\Models\Application;
use App\Domain\Application\Repositories\ApplicationRepository;
use App\Domain\Application\Repositories\EloquentApplicationRepository;
use App\Domain\Application\Services\ApplicationService;
use Illuminate\Support\ServiceProvider;

/**
 * Wires Domain repository contracts to their Eloquent implementations.
 * Add new bindings here as additional modules grow their layer.
 */
class DomainServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        ApplicationRepository::class => EloquentApplicationRepository::class,
    ];

    /** @var array<class-string> */
    public array $singletons = [
        ApplicationService::class => ApplicationService::class,
    ];

    public function boot(): void
    {
        // Cache invalidation: model → listener mapping.
        Application::saved(fn (Application $m) => app(\App\Domain\Application\Listeners\InvalidateApplicationCache::class)->saved($m));
        Application::deleted(fn (Application $m) => app(\App\Domain\Application\Listeners\InvalidateApplicationCache::class)->deleted($m));
    }
}
