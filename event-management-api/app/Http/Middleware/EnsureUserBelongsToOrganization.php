<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Validates that the authenticated user belongs to the current organization,
 * preventing unauthorized access.
 */
class EnsureUserBelongsToOrganization
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $organization = app('currentOrganization');

        if (!$organization || $user->organization_id !== $organization->id) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return $next($request);
    }
}
