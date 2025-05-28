<?php

namespace App\Repositories\Interfaces;

interface EventRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get events by organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getByOrganization(int $organizationId);

    /**
     * Get upcoming events
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getUpcoming(int $organizationId);

    /**
     * Get upcoming events
     *
     * @return mixed
     */
    public function getAllUpcoming();

    /**
     * Get past events
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getPast(int $organizationId);

    /**
     * Get event with attendees
     *
     * @param int $id
     * @return mixed
     */
    public function getWithAttendees(int $id);

    /**
     * Check if event has available capacity
     *
     * @param int $id
     * @return bool
     */
    public function hasAvailableCapacity(int $id);

    /**
     * Restore a soft-deleted event
     *
     * @param int $id
     * @return mixed
     */
    public function restore(int $id);

    /**
     * Get public events for an organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getPublic(int $organizationId);

    /**
     * Get upcoming public events for an organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getUpcomingPublic(int $organizationId);



    public function getOrganization(string $org_slug);
}
