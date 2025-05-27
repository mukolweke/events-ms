<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'organizations_count' => $this->whenCounted('organizations'),
            'registered_events_count' => $this->whenCounted('registeredEvents'),
            'organizations' => OrganizationResource::collection($this->whenLoaded('organizations')),
            'registered_events' => EventResource::collection($this->whenLoaded('registeredEvents')),
        ];
    }
}
