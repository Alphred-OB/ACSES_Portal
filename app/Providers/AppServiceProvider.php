<?php

namespace App\Providers;

use App\Models\PendingRegistration;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
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
        Blade::componentNamespace('App\View\Components', 'app');
        Blade::anonymousComponentNamespace('layouts', 'layouts');

        // Route model binding for PendingRegistration
        Route::model('registration', PendingRegistration::class);
    }
}
