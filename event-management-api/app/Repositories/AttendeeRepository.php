<?php

namespace App\Repositories;

use App\Models\Attendee;
use App\Repositories\Interfaces\AttendeeRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AttendeeRepository extends BaseRepository implements AttendeeRepositoryInterface
{
    public function __construct(Attendee $model)
    {
        parent::__construct($model);
    }

    public function getByEvent(int $eventId)
    {
        return $this->model->forOrganization()->where('event_id', $eventId)->get();
    }

    public function isRegistered(int $eventId, int $userId)
    {
        return $this->model->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function getCountForEvent(int $eventId)
    {
        return $this->model->where('event_id', $eventId)->count();
    }

    public function getWithUser(int $id)
    {
        return $this->model->with(['user', 'event'])->findOrFail($id);
    }

    public function find(int $id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error in AttendeeRepository::find(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function getAllForOrganization()
    {
        $attendees = $this->model->forOrganization()
            ->with(['event', 'user'])
            ->orderBy('attendees.created_at', 'desc')
            ->paginate(request()->input('per_page', 15));

        return $attendees;
    }
}
