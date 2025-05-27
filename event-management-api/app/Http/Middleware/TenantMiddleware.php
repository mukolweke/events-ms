<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Extract the org slug from the first segment of the request path
        $segments = $request->segments();
        $orgSlug = $segments[0] === 'api'
            ? $segments[1] ?? null
            : $segments[0] ?? null;

        if (!$orgSlug) {
            throw new NotFoundHttpException('Organization slug not provided.');
        }

        // Resolve organization
        $organization = Organization::where('slug', $orgSlug)->first();

        if (!$organization) {
            Log::warning('Organization not found for slug: ' . $orgSlug);
            throw new NotFoundHttpException('Organization not found.');
        }

        // Check if user is authenticated and belongs to the organization
        if ($request->user() && !$organization->users()->where('users.id', $request->user()->id)->exists()) {
            Log::warning('User does not belong to organization', [
                'user_id' => $request->user()->id,
                'organization_id' => $organization->id
            ]);
            throw new AccessDeniedHttpException('Unauthorized access');
        }

        // Add organization context to request
        $request->attributes->set('organization', $organization);
        app()->instance('currentOrganization', $organization);

        return $next($request);
    }
}
