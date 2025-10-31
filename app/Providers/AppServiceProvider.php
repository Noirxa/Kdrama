<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; // Deze 'use' was de oorzaak van de error

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
        //
    }
}
