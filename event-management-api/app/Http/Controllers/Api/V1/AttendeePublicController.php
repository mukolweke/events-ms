<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

class AttendeePublicController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Register a user for an event.
     */
    public function register($org_slug, Event $event): AttendeeResource|\Illuminate\Http\JsonResponse
    {
        try {
            $userId = request()->get('userId');
            $attendee = $this->registrationService->registerForEvent($event->id, $userId, request()->all());

            return new AttendeeResource($attendee);
        } catch (\Exception $e) {
            Log::error('Error in register method: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'event_id' => $event->id
            ]);

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
