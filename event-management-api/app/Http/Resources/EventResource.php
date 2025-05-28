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
            'price' => $this->price,
            'max_attendees' => $this->max_attendees,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'start_date' => $this->start_date->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date->format('Y-m-d H:i:s'),
            'organization_id' => $this->organization_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'attendees_count' => $this->whenCounted('attendees'),
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'attendees' => AttendeeResource::collection($this->whenLoaded('attendees')),
            'is_full' => $this->when(isset($this->attendees_count), function () {
                return $this->attendees_count >= $this->max_attendees;
            }),
        ];
    }
}
