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
        \Illuminate\Support\Facades\RateLimiter::for('auth_strict', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(30)->by($request->ip())->response(function (\Illuminate\Http\Request $request, array $headers) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terlalu banyak percobaan. Silakan coba lagi nanti.'
                ], 429, $headers);
            });
        });
    }
}
