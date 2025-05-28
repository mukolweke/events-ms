<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventPublicController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    private function getOrganization(Request $request, $org_slug)
    {
        return $request->attributes->get('organization') ?? $this->eventService->getOrganization($org_slug);
    }

    /**
     * Get all events.
     */
    public function allEvents(Request $request): AnonymousResourceCollection
    {
        $events = $this->eventService->getAllUpcomingEvents();
        return EventResource::collection($events);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $org_slug, Event $event): EventResource
    {
        $organization = $this->getOrganization($request, $org_slug);

        if ($event->organization_id !== $organization->id) {
            abort(404, 'Event not found');
        }

        $event = $this->eventService->getEventWithAttendees($event->id);
        return new EventResource($event);
    }
}
