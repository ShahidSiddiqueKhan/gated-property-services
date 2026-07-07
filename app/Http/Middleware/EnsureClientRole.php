<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientRole
{
    /**
     * Restrict the Client Portal to authenticated client accounts.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['client', 'admin'], true)) {
            abort(403, 'You are not authorized to access the Client Portal.');
        }

        return $next($request);
    }
}
