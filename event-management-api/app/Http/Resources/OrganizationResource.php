<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'phone' => $this->phone,
            'address' => $this->address,
            'description' => $this->description,
            'slug' => $this->slug,
            'domain' => $this->domain,
            'status' => $this->is_active ? 'active' : 'inactive',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'events_count' => $this->whenCounted('events'),
            'members_count' => $this->whenCounted('members'),
            'events' => EventResource::collection($this->whenLoaded('events')),
            'members' => UserResource::collection($this->whenLoaded('members')),
        ];
    }
}
