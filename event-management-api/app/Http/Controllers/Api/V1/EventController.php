<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');
        $events = $this->eventService->getUpcomingEvents($organization->id);
        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request): EventResource
    {
        $organization = $request->attributes->get('organization');
        $data = array_merge($request->validated(), ['organization_id' => $organization->id]);
        $event = $this->eventService->createEvent($data);
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $org_slug, Event $event): EventResource
    {
        $organization = $request->attributes->get('organization');

        if ($event->organization_id !== $organization->id) {
            abort(404, 'Event not found');
        }

        $event = $this->eventService->getEventWithAttendees($event->id);
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, $org_slug, Event $event): EventResource
    {
        $organization = $request->attributes->get('organization');
        $data = array_merge($request->validated(), ['organization_id' => $organization->id]);
        $event = $this->eventService->updateEvent($event->id, $data);
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($org_slug, Event $event): Response
    {
        $this->eventService->deleteEvent($event->id);
        return response()->noContent();
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore($org_slug, $eventId): EventResource
    {
        $event = $this->eventService->restoreEvent($eventId);
        return new EventResource($event);
    }

    /**
     * Get past events
     */
    public function pastEvents(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');
        $events = $this->eventService->getPastEvents($organization->id);
        return EventResource::collection($events);
    }
}
