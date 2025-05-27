<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Exception;

class PublicEventController extends Controller
{
    protected $eventService;
    protected $registrationService;

    public function __construct(
        EventService $eventService,
        RegistrationService $registrationService
    ) {
        $this->eventService = $eventService;
        $this->registrationService = $registrationService;
    }

    /**
     * Display a listing of public events for an organization.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');
        $events = $this->eventService->getPublicEvents($organization->id);
        return EventResource::collection($events);
    }

    /**
     * Display the specified event.
     */
    public function show(Request $request, $org_slug, $id): EventResource
    {
        try {
            $event = $this->eventService->getPublicEvent((int) $id);
            return new EventResource($event);
        } catch (Exception $e) {
            Log::error('Error showing public event: ' . $e->getMessage(), [
                'event_id' => $id,
                'org_slug' => $org_slug
            ]);
            abort(404, $e->getMessage());
        }
    }

    /**
     * Register for an event (public endpoint).
     *
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\EventResource
     */
    public function registerForEvent(Request $request, string $org_slug, $eventId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
            ]);

            $attendee = $this->registrationService->registerForPublicEvent((int) $eventId, $validated);
            return new EventResource($attendee->event);
        } catch (Exception $e) {
            Log::error('Error registering for public event: ' . $e->getMessage(), [
                'event_id' => $eventId,
                'org_slug' => $org_slug,
                'data' => $request->all()
            ]);
            if ($e->getMessage() === 'Event is at full capacity') {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            throw $e;
        }
    }

    /**
     * Get upcoming public events.
     */
    public function upcoming(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');
        $events = $this->eventService->getUpcomingPublicEvents($organization->id);
        return EventResource::collection($events);
    }
}
