<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InstallationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('app.installed') && !$request->is('install') && !$request->is('install/*')) {
            return redirect()->route('install.wizard');
        }

        return $next($request);
    }
}
