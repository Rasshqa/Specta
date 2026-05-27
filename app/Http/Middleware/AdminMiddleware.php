<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Only allow authenticated users with the 'admin' role to pass.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya admin yang diizinkan.');
        }

        return $next($request);
    }
}
