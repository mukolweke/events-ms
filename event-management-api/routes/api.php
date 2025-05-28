<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TenantMiddleware;
use App\Http\Middleware\EnsureUserBelongsToOrganization;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\PublicEventController;
use App\Http\Controllers\Api\V1\AttendeeController;
use App\Http\Controllers\Api\V1\OrganizationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Global API routes (no tenant required)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:60,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:60,1');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // Organization routes
    Route::apiResource('admin/organizations', OrganizationController::class)->names('admin.organizations');

    // Event routes
    Route::get('admin/events', [EventController::class, 'allEvents'])->name('admin.events.all');
    Route::post('admin/{org_slug}/events/{event}/register', [AttendeeController::class, 'register'])->name('attendees.register');
    Route::apiResource('admin/{org_slug}/events', EventController::class)->names('admin.events');
    // Attendee routes
    Route::get('admin/{org_slug}/events/{event}/attendees', [AttendeeController::class, 'index'])->name('attendees.index');
});

// All other routes require tenant middleware
Route::middleware([TenantMiddleware::class])->group(function () {
    // Test route for middleware testing
    Route::get('/test-global', function () {
        return response()->json(['message' => 'Global API working']);
    });

    // Path-based multi-tenancy: /{org_slug}/...
    Route::group(['prefix' => '{org_slug}'], function () {
        // Test route for middleware testing
        Route::get('/test-middleware', function () {
            return response()->json(['message' => 'Tenant middleware passed']);
        });
        // Public event routes (tenant-specific but no auth required)
        Route::get('/events/public', [PublicEventController::class, 'index']);
        Route::get('/events/public/{id}', [PublicEventController::class, 'show']);
        Route::post('/events/public/{id}/register', [PublicEventController::class, 'registerForEvent']);

        // Protected routes (require both tenant and auth)
        Route::middleware(['auth:sanctum', EnsureUserBelongsToOrganization::class])->group(function () {
            // Organization routes
            Route::apiResource('organizations', OrganizationController::class);

            // Event routes
            Route::get('/events/past', [EventController::class, 'pastEvents']);
            Route::post('/events/{event}/restore', [EventController::class, 'restore']);
            Route::apiResource('events', EventController::class);

            // Attendee routes
            Route::get('/attendees', [AttendeeController::class, 'listForOrganization'])->name('attendees.list');

            Route::delete('/events/{event}/cancel', [AttendeeController::class, 'cancel'])->name('attendees.cancel');
            Route::get('/events/{event}/attendees/export', [AttendeeController::class, 'export'])->name('attendees.export');
            Route::get('/events/{event}/attendees', [AttendeeController::class, 'index'])->name('attendees.index');
        });
    });
});
