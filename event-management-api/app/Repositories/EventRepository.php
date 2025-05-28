<?php

namespace App\Repositories;

use App\Models\Event;
use App\Models\Organization;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Carbon\Carbon;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function getByOrganization(int $organizationId)
    {
        return $this->model->where('organization_id', $organizationId)->get();
    }

    public function getUpcoming(int $organizationId)
    {
        return $this->model->where('organization_id', $organizationId)
                           ->where('start_date', '>', Carbon::now())
                           ->get();
    }

    public function getPast(int $organizationId)
    {
        return $this->model->where('organization_id', $organizationId)
                          ->where('end_date', '<', Carbon::now())
                          ->get();
    }

    public function getWithAttendees(int $id)
    {
        return $this->model->with('attendees')->findOrFail($id);
    }

    public function hasAvailableCapacity(int $id)
    {
        $event = $this->model->findOrFail($id);
        $attendeeCount = $event->attendees()->count();
        return $attendeeCount < $event->max_attendees;
    }

    /**
     * Restore a soft-deleted event
     *
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $event = $this->model->withTrashed()->findOrFail($id);
        $event->restore();
        return $event;
    }

    /**
     * Get public events for an organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getPublic(int $organizationId)
    {
        return $this->model->where('organization_id', $organizationId)
                          ->where('is_public', true)
                          ->get();
    }

    public function getOrganization(string $org_slug)
    {
        return Organization::where('slug', $org_slug)->first();
    }

    /**
     * Get upcoming public events for an organization
     *
     * @param int $organizationId
     * @return mixed
     */
    public function getUpcomingPublic(int $organizationId)
    {
        return $this->model->where('organization_id', $organizationId)
                          ->where('is_public', true)
                          ->where('start_date', '>', Carbon::now())
                          ->get();
    }
}
