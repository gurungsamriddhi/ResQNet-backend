<?php

namespace App\Events;

use App\Models\RescueReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RescueCompletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RescueReport $rescue) {}

    public function broadcastOn(): array
    {
        // Only broadcast to the user who submitted the report
        if (!$this->rescue->user_id) {
            return [];
        }

        return [
            new PrivateChannel('user.' . $this->rescue->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        $orgName = $this->rescue->organization?->name ?? 'the rescue organization';

        return [
            'rescue_id' => $this->rescue->id,
            'case_code' => $this->rescue->case_code,
            'title' => $this->rescue->title,
            'status' => 'resolved',
            'message' => "The rescue for '{$this->rescue->title}' has been completed. The animal is now safe and in care at {$orgName}.",
        ];
    }

    public function broadcastAs(): string
    {
        return 'RescueCompletedEvent';
    }
}
