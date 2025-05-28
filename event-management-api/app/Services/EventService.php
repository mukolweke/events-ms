<?php

namespace App\Services;

use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventService
{
    protected $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Create a new event
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function createEvent(array $data)
    {
        try {
            $this->validateEventData($data);
            return $this->eventRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getOrganization(string $org_slug)
    {
        return $this->eventRepository->getOrganization($org_slug);
    }

    /**
     * Update an existing event
     *
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function updateEvent(int $id, array $data)
    {
        try {
            $this->validateEventData($data);
            return $this->eventRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an event
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteEvent(int $id)
    {
        try {
            return $this->eventRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get upcoming events
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getUpcomingEvents(int $organizationId)
    {
        return $this->eventRepository->getUpcoming($organizationId);
    }

    /**
     * Get events by organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getOrganizationEvents(int $organizationId)
    {
        return $this->eventRepository->getByOrganization($organizationId);
    }

    /**
     * Get event with attendees
     *
     * @param int $id
     * @return mixed
     */
    public function getEventWithAttendees(int $id)
    {
        return $this->eventRepository->getWithAttendees($id);
    }

    /**
     * Restore a soft-deleted event
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function restoreEvent(int $id)
    {
        try {
            return $this->eventRepository->restore($id);
        } catch (Exception $e) {
            Log::error('Error restoring event: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get past events
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getPastEvents(int $organizationId)
    {
        return $this->eventRepository->getPast($organizationId);
    }

    /**
     * Get public events for an organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getPublicEvents(int $organizationId)
    {
        return $this->eventRepository->getPublic($organizationId);
    }

    /**
     * Get a public event by ID
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function getPublicEvent(int $id)
    {
        try {
            $event = $this->eventRepository->getWithAttendees($id);
            if (!$event->is_public) {
                throw new Exception('Event is not public');
            }
            return $event;
        } catch (ModelNotFoundException $e) {
            Log::error('Public event not found', ['id' => $id]);
            throw new Exception('Event not found');
        } catch (Exception $e) {
            Log::error('Error getting public event: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get upcoming public events for an organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getUpcomingPublicEvents(int $organizationId)
    {
        return $this->eventRepository->getUpcomingPublic($organizationId);
    }

    /**
     * Validate event data
     *
     * @param array $data
     * @throws Exception
     */
    protected function validateEventData(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string|max:255',
            'max_attendees' => 'required|integer|min:1',
            'organization_id' => 'required|exists:organizations,id',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }
}
