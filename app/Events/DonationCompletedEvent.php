<?php

namespace App\Events;

use App\Models\Donation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationCompletedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Donation $donation) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel('rescue-updates')];
    }

    public function broadcastWith(): array
    {
        $donor = $this->donation->is_anonymous || empty($this->donation->donor_name)
            ? 'Anonymous'
            : $this->donation->donor_name;

        return [
            'donation_id' => $this->donation->id,
            'amount' => number_format((float) $this->donation->amount, 2),
            'donor_name' => $donor,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'DonationCompletedEvent';
    }
}
