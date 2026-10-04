<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 🟢 ADD THIS LINE RIGHT HERE:
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
        // Force HTTPS when accessed via public tunnels or Railway
        if (!request()->isSecure() && (str_contains(request()->getHost(), 'pinggy') || str_contains(request()->getHost(), 'ngrok') || str_contains(request()->getHost(), 'localtunnel') || str_contains(request()->getHost(), 'serveo') || str_contains(request()->getHost(), 'trycloudflare') || str_contains(request()->getHost(), 'railway.app'))) {
            URL::forceScheme('https');
        }
    }
}
