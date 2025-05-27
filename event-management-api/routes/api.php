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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Test route for middleware testing
Route::get('/test-tenant', function () {
    return response()->json(['message' => 'Middleware passed']);
})->middleware(TenantMiddleware::class);

// Path-based multi-tenancy: /{org_slug}/...
Route::group(['prefix' => '{org_slug}', 'middleware' => [TenantMiddleware::class]], function () {
    // Test route for middleware testing
    Route::get('/test-middleware', function () {
        return response()->json(['message' => 'Middleware passed']);
    });

    // Public routes
    // Auth routes with rate limiting
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:60,1');
    //Route::post('/register', [AuthController::class, 'register']);

    // Public event routes
    Route::get('/events/public', [PublicEventController::class, 'index']);
    Route::get('/events/public/{id}', [PublicEventController::class, 'show']);
    Route::post('/events/public/{id}/register', [PublicEventController::class, 'registerForEvent']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User profile and auth
        Route::get('/profile', [AuthController::class, 'profile'])->middleware(EnsureUserBelongsToOrganization::class);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware(EnsureUserBelongsToOrganization::class);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(EnsureUserBelongsToOrganization::class);

        // Organization routes
        Route::apiResource('organizations', OrganizationController::class);

        // Event routes
        Route::get('/events/past', [EventController::class, 'pastEvents']);
        Route::post('/events/{event}/restore', [EventController::class, 'restore']);
        Route::apiResource('events', EventController::class);

        // Attendee routes
        Route::get('/attendees', [AttendeeController::class, 'listForOrganization'])->name('attendees.list');
        Route::post('/events/{event}/register', [AttendeeController::class, 'register'])->name('attendees.register');
        Route::delete('/events/{event}/cancel', [AttendeeController::class, 'cancel'])->name('attendees.cancel');
        Route::get('/events/{event}/attendees/export', [AttendeeController::class, 'export'])->name('attendees.export');
        Route::get('/events/{event}/attendees', [AttendeeController::class, 'index'])->name('attendees.index');
    });
});
