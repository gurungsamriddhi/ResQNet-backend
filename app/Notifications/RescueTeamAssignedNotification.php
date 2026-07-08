<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RescueTeamAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public RescueReport $rescue) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if (isset($notifiable->fcm_token) && $notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toFcm(object $notifiable): array
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'a rescue organization';

        return [
            'title' => "Help is on the way! 🚨",
            'body' => "A rescue team from {$orgName} has been assigned to your report.",
            'data' => [
                'type' => 'rescue_assigned',
                'rescue_id' => (string) $this->rescue->id,
                'case_code' => $this->rescue->case_code,
            ],
        ];
    }
}
