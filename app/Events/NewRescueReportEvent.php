<?php

namespace App\Events;

use App\Models\RescueReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRescueReportEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RescueReport $rescue) {}

    public function broadcastOn(): array
    {
        $channels = [
            new PresenceChannel('rescue-updates'),
        ];

        if ($this->rescue->organization_id) {
            $channels[] = new PrivateChannel("organization.{$this->rescue->organization_id}");
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'rescue_id' => $this->rescue->id,
            'case_code' => $this->rescue->case_code,
            'title' => $this->rescue->title,
            'urgency' => $this->rescue->urgency,
            'address' => $this->rescue->address,
            'latitude' => $this->rescue->latitude,
            'longitude' => $this->rescue->longitude,
            'status' => $this->rescue->status,
            'created_at' => $this->rescue->created_at->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewRescueReportEvent';
    }
}
