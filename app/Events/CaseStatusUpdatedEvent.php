<?php

namespace App\Events;

use App\Models\RescueReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseStatusUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RescueReport $rescue, public string $oldStatus) {}

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
            'old_status' => $this->oldStatus,
            'new_status' => $this->rescue->status,
            'organization_id' => $this->rescue->organization_id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'CaseStatusUpdatedEvent';
    }
}
