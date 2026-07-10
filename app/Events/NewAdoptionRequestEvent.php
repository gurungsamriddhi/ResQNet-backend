<?php

namespace App\Events;

use App\Models\AdoptionRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAdoptionRequestEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AdoptionRequest $adoption) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("organization.{$this->adoption->pet->organization_id}"),
        ];
    }

    public function broadcastWith(): array
    {
        $req = $this->adoption;
        $pet = $req->pet;
        $user = $req->user;
        $profile = $user?->profile;

        $ageMonths = $pet->age_months;
        $petAge = $ageMonths === null ? '-' : (
            $ageMonths < 12 ? "{$ageMonths} months" : floor($ageMonths / 12) . ' years'
        );

        return [
            // Fields for the notification bell
            'adoption_id' => $req->id,
            'pet_name'    => $pet->name,
            'user_name'   => $user->name,
            'message'     => $req->message,
            'created_at'  => $req->created_at->toISOString(),

            // Full application payload for direct Alpine injection
            'application' => [
                'id'                  => $req->id,
                'name'                => $user->name ?? 'Guest',
                'email'               => $user->email ?? '',
                'phone'               => $user->phone ?? '',
                'address'             => $req->living_situation ?? '',
                'reason'              => $req->message ?? '',
                'pet'                 => $pet->name,
                'pet_id'              => $pet->id,
                'pet_age'             => $petAge,
                'pet_breed'           => $pet->breed ?? 'Unknown',
                'pet_image'           => $pet->getFirstMediaUrl('images'),
                'pet_traits'          => array_values(array_filter([$pet->species, $pet->size, $pet->gender])),
                'date'                => $req->created_at?->format('Y-m-d'),
                'status'              => 'Pending',
                'has_other_pets'      => $profile?->has_other_pets,
                'housing_type'        => $profile?->housing_type,
                'employment_status'   => $profile?->employment_status,
                'family_members_count' => $profile?->family_members_count,
                'experience_with_pets' => $profile?->experience_with_pets,
                'bio'                 => $profile?->bio,
                'preferred_pet_type'  => $profile?->preferred_pet_type,
                'has_yard'            => $profile?->has_yard,
                'is_allergic'         => $profile?->is_allergic,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewAdoptionRequestEvent';
    }
}

