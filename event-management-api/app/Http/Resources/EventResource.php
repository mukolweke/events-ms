<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'venue' => $this->venue,
            'event_date' => $this->event_date,
            'price' => $this->price,
            'max_attendees' => $this->max_attendees,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'organization_id' => $this->organization_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attendees_count' => $this->whenCounted('attendees'),
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'attendees' => AttendeeResource::collection($this->whenLoaded('attendees')),
            'is_full' => $this->when(isset($this->attendees_count), function () {
                return $this->attendees_count >= $this->capacity;
            }),
        ];
    }
}
