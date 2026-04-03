<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        if ($request->user() && $request->user()->hasRole('admin')) {
            return $next($request);
        }

        $enabled = app_setting($featureKey, true);

        if (!$enabled) {
            abort(403, 'This module is currently disabled by system settings.');
        }

        return $next($request);
    }
}