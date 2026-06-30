<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Same role check as AdminMiddleware, but designed for the
     * token-authenticated (Sanctum) JSON API instead of the session-based
     * web admin panel. Returns a JSON 403 instead of an HTML abort page.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasAnyRole(['Super Admin', 'Admin', 'Manager', 'Staff'])) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
