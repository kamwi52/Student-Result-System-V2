<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        try {
            // Load all settings from the database and share them with every view
            $settings = \App\Models\Setting::pluck('value', 'key')->all();
            view()->share('settings', $settings);
        } catch (\Exception $e) {
            // Do nothing if the table doesn't exist yet
        }
    }
}