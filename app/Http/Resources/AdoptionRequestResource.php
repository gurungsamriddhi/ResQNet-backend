<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdoptionRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pet_id' => $this->pet_id,
            'message' => $this->message,
            'living_situation' => $this->living_situation,
            'has_other_pets' => $this->user?->profile?->has_other_pets,
            'housing_type' => $this->user?->profile?->housing_type,
            'employment_status' => $this->user?->profile?->employment_status,
            'family_members_count' => $this->user?->profile?->family_members_count,
            'experience_with_pets' => $this->user?->profile?->experience_with_pets,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'pet' => new PetResource($this->whenLoaded('pet')),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
