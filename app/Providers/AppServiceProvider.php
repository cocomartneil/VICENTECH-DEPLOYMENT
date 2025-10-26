<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // If the app is running in production (or FORCE_HTTPS is set), force generated
        // URLs (including asset() and @vite tags) to use the https scheme. This
        // helps avoid Mixed Content errors when the external URL is HTTPS but the
        // internal request scheme is HTTP (common on some PaaS like Render).
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
