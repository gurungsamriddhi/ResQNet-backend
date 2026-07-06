<?php

namespace App\Events;

use App\Models\AdoptionRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAdoptionRequestEvent implements ShouldBroadcast
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
        return [
            'adoption_id' => $this->adoption->id,
            'pet_name' => $this->adoption->pet->name,
            'user_name' => $this->adoption->user->name,
            'message' => $this->adoption->message,
            'created_at' => $this->adoption->created_at->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewAdoptionRequestEvent';
    }
}
