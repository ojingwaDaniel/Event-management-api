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
            "Event Name" => $this->name,
            "description" => $this->description,
            "start_at" => $this->start_time,
            "end_at" => $this->end_time,
            "Event Owner" => new UserResource($this->whenLoaded("user")),
            "Event Attendees" => AttendeeResource::collection($this->whenLoaded("attendees"))
        ];
    }
}
