<?php

namespace App\Events;

use App\Models\Organization;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrganizationRegisteredEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Organization $organization) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('rescue-updates')];
    }

    public function broadcastWith(): array
    {
        return [
            'org_id'             => $this->organization->id,
            'org_name'           => $this->organization->name,
            'registration_number'=> $this->organization->registration_number,
            'address'            => $this->organization->address,
            'created_at'         => $this->organization->created_at->toISOString(),
        ];
    }

    public function broadcastAs(): string { return 'OrganizationRegisteredEvent'; }
}
