<?php

namespace App\Services;

use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\AttendeeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class RegistrationService
{
    protected $eventRepository;
    protected $attendeeRepository;
    protected $eventService;

    public function __construct(
        EventRepositoryInterface $eventRepository,
        AttendeeRepositoryInterface $attendeeRepository,
        EventService $eventService
    ) {
        $this->eventRepository = $eventRepository;
        $this->attendeeRepository = $attendeeRepository;
        $this->eventService = $eventService;
    }

    /**
     * Register a user for an event
     *
     * @param int $eventId
     * @param int|null $userId
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function registerForEvent(int $eventId, ?int $userId = null, array $data = [])
    {
        try {
            // Check if event exists and has capacity
            if (!$this->eventRepository->hasAvailableCapacity($eventId)) {
                throw new Exception('Event is at full capacity');
            }

            // For authenticated users, check if already registered
            if ($userId && $this->attendeeRepository->isRegistered($eventId, $userId)) {
                throw new Exception('User is already registered for this event');
            }

            $registrationData = array_merge($data, [
                'event_id' => $eventId,
                'user_id' => $userId,
                'registration_date' => now(),
            ]);

            $attendee = $this->attendeeRepository->create($registrationData);
            return $this->attendeeRepository->getWithUser($attendee->id);
        } catch (Exception $e) {
            Log::error('Error registering for event: ' . $e->getMessage(), [
                'event_id' => $eventId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Cancel event registration
     *
     * @param int $eventId
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function cancelRegistration(int $eventId, int $userId)
    {
        try {
            $attendee = $this->attendeeRepository->findBy('user_id', $userId);

            if (!$attendee) {
                throw new Exception('Registration not found');
            }

            return $this->attendeeRepository->delete($attendee->id);
        } catch (Exception $e) {
            Log::error('Error canceling registration: ' . $e->getMessage(), [
                'event_id' => $eventId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Get event attendees
     *
     * @param int $eventId
     * @return mixed
     */
    public function getEventAttendees(int $eventId)
    {
        return $this->attendeeRepository->getByEvent($eventId);
    }

    /**
     * Get all attendees for the current organization
     *
     * @return mixed
     */
    public function getOrganizationAttendees()
    {
        return $this->attendeeRepository->getAllForOrganization();
    }

    /**
     * Get attendee count for event
     *
     * @param int $eventId
     * @return int
     */
    public function getAttendeeCount(int $eventId)
    {
        return $this->attendeeRepository->getCountForEvent($eventId);
    }

    /**
     * Register for a public event
     *
     * @param int $eventId
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function registerForPublicEvent(int $eventId, array $data)
    {
        try {
            $event = $this->eventService->getPublicEvent($eventId);
            return $this->registerForEvent($eventId, null, $data);
        } catch (Exception $e) {
            Log::error('Error registering for public event: ' . $e->getMessage(), [
                'event_id' => $eventId,
                'data' => $data
            ]);
            throw $e;
        }
    }
}
