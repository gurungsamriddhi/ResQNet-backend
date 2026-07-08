<?php

namespace App\Events;

use App\Models\RescueReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseTeamAssignedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RescueReport $rescue, public array $teamMembers) {}

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
            'status' => $this->rescue->status,
            'team_members' => $this->teamMembers,
        ];
    }

    public function broadcastAs(): string
    {
        return 'CaseTeamAssignedEvent';
    }
}
