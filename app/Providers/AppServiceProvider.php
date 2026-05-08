<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // super-admin role bypasses all gates.
        Gate::before(function ($user, $ability) {
            if (! $user || ! method_exists($user, 'hasRole')) {
                return null;
            }
            return $user->hasRole('super_admin') ? true : null;
        });

        // Production safety: enforce HTTPS + strict model state.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Model::shouldBeStrict(! $this->app->isProduction());
        Model::unguard(false);
    }
}
