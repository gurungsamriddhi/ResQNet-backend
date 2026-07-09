<?php

namespace App\Events;

use App\Models\Organization;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrganizationStatusChangedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Organization $organization, public string $oldStatus) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('rescue-updates')];
    }

    public function broadcastWith(): array
    {
        return [
            'org_id'     => $this->organization->id,
            'org_name'   => $this->organization->name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->organization->status,
            'is_verified'=> (bool) $this->organization->is_verified,
        ];
    }

    public function broadcastAs(): string { return 'OrganizationStatusChangedEvent'; }
}
