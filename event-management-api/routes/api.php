<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TenantMiddleware;
use App\Http\Middleware\EnsureUserBelongsToOrganization;
use App\Http\Controllers\AuthController;
use App\Models\Organization;
use App\Models\Event;

// Path-based multi-tenancy: /{org_slug}/...
Route::group(['prefix' => '{org_slug}', 'middleware' => [TenantMiddleware::class]], function () {
    // Authentication routes with rate limiting
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:60,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', EnsureUserBelongsToOrganization::class]);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:sanctum', EnsureUserBelongsToOrganization::class]);
    Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth:sanctum', EnsureUserBelongsToOrganization::class]);

    // Example: List events for the organization
    Route::get('/events', function () {
        return Event::all(); // Automatically scoped by tenant
    });

    // Example: Get organization details
    Route::get('/org', function () {
        return request()->attributes->get('organization');
    });

    // Add more organization-aware routes here
});
