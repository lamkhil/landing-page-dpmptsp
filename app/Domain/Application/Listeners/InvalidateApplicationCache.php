<?php

namespace App\Domain\Application\Listeners;

use App\Domain\Application\Models\Application;
use App\Domain\Application\Services\ApplicationService;

class InvalidateApplicationCache
{
    public function __construct(private readonly ApplicationService $service) {}

    public function saved(Application $application): void
    {
        $this->service->invalidate();
    }

    public function deleted(Application $application): void
    {
        $this->service->invalidate();
    }
}
