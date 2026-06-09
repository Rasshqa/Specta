<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Rate limiter untuk seluruh rute autentikasi:
        // login lokal, register, dan tombol Google OAuth
        // Batas: 5 percobaan per menit per IP Address
        RateLimiter::for('auth_strict', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()
                        ->view('errors.429', [], 429)
                        ->withHeaders($headers);
                });
        });
    }
}
