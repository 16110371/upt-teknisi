<?php

namespace App\Providers;

use App\Models\Infrastructure;
use Illuminate\Support\ServiceProvider;
use App\Models\Request;
use App\Observers\InfrastructureObserver;
use App\Observers\RequestObserver;
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
        Request::observe(RequestObserver::class);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Infrastructure::observe(InfrastructureObserver::class);
    }
}
