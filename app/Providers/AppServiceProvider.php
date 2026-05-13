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
        // ... Observer kamu ...
        Request::observe(RequestObserver::class);
        Infrastructure::observe(InfrastructureObserver::class);

        // ✅ Trust semua proxy (ngrok)
        \Illuminate\Http\Request::setTrustedProxies(
            ['*'],
            \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
        );

        // ✅ FORCE HTTPS jika di Production ATAU jika sedang pakai Ngrok
        if (config('app.env') === 'production' || str_contains(request()->getHttpHost(), 'ngrok-free.dev')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // ✅ Pastikan Root URL mengikuti APP_URL di .env (Penting untuk asset & link)
        if (config('app.env') === 'local' && str_contains(config('app.url'), 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }
    }
}
