<?php

namespace App\Providers;

use App\Domain\Role\Services\RoleService;
use Illuminate\Cache\Repository;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RoleService::class, function ($app) {
            return new RoleService($app->make(Repository::class));
        });
    }

    public function boot(): void
    {
    }
}
