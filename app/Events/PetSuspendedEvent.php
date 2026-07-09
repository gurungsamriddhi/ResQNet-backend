<?php

namespace App\Events;

use App\Models\Pet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PetSuspendedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pet $pet)
    {
        $this->pet->loadMissing('organization');
    }

    public function broadcastOn(): array
    {
        if (!$this->pet->organization_id) {
            return [];
        }

        return [
            new PrivateChannel('organization.' . $this->pet->organization_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'pet_id' => $this->pet->id,
            'pet_name' => $this->pet->name,
            'suspension_reason' => $this->pet->suspension_reason,
            'message' => "The profile for '{$this->pet->name}' has been suspended.",
        ];
    }

    public function broadcastAs(): string
    {
        return 'PetSuspendedEvent';
    }
}
