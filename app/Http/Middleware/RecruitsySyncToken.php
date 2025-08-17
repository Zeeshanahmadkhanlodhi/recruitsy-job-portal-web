<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class RecruitsySyncToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Recruitsy-Sync-Token');
        $expectedToken = Config::get('services.recruitsy.sync_token');

        if (!$expectedToken) {
            abort(500, 'Sync token not configured');
        }

        if (!$token || $token !== $expectedToken) {
            abort(401, 'Invalid sync token');
        }

        return $next($request);
    }
}
