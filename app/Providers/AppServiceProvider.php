<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // CRITICAL: This line must be present

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
        // === THIS IS THE FIX ===
        // This code checks if the application is running in the 'production' environment (like on Railway).
        // If it is, it forces Laravel to always generate secure HTTPS URLs for all assets and links.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}