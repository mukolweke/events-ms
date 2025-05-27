<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class TenantServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind a singleton for current organization
        $this->app->singleton('currentOrganization', function ($app) {
            return null; // Will be set by middleware
        });
    }

    public function boot()
    {
        // No global helper declaration here
    }
}
