<?php

namespace App\Events;

use App\Models\RescueReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseClaimedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RescueReport $rescue) {}

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('rescue-updates'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'rescue_id' => $this->rescue->id,
            'case_code' => $this->rescue->case_code,
            'organization_id' => $this->rescue->organization_id,
            'status' => 'assigned',
            'claimed_by' => $this->rescue->organization?->name,
        ];
    }

    public function broadcastAs(): string
    {
        return 'CaseClaimedEvent';
    }
}
