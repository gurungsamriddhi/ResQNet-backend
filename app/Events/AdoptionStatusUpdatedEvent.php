<?php

namespace App\Events;

use App\Models\AdoptionRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast adoption status changes to the applicant's mobile app in real-time.
 *
 * The mobile client listens on `private-user.{userId}` and refreshes
 * the adoption request list whenever this event fires.
 */
class AdoptionStatusUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AdoptionRequest $adoption) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->adoption->user_id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'adoption_id' => $this->adoption->id,
            'pet_name' => $this->adoption->pet->name,
            'status' => $this->adoption->status,
            'organization_response' => $this->adoption->organization_response,
            'reviewed_at' => $this->adoption->reviewed_at?->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'AdoptionStatusUpdatedEvent';
    }
}
