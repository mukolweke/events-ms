<?php

namespace App\Repositories\Interfaces;

interface AttendeeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get attendees by event
     *
     * @param int $eventId
     * @return mixed
     */
    public function getByEvent(int $eventId);

    /**
     * Check if user is registered for event
     *
     * @param int $eventId
     * @param int $userId
     * @return bool
     */
    public function isRegistered(int $eventId, int $userId);

    /**
     * Get attendee count for event
     *
     * @param int $eventId
     * @return int
     */
    public function getCountForEvent(int $eventId);

    /**
     * Get attendee with user details
     *
     * @param int $id
     * @return mixed
     */
    public function getWithUser(int $id);

    /**
     * Get all attendees for the current organization
     *
     * @return mixed
     */
    public function getAllForOrganization();
}
