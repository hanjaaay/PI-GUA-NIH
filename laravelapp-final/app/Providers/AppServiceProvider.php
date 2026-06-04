<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; // Wajib diimpor
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
        
    if (
    app()->environment('production')
    && !app()->runningInConsole()
    && request()->server('HTTP_HOST') !== '127.0.0.1:8000'
    && request()->server('HTTP_HOST') !== 'localhost:8000'
) {
    URL::forceScheme('https');
}

        
    }
}
