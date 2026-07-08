<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class RescueTeamAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public RescueReport $rescue) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if (isset($notifiable->fcm_token) && $notifiable->fcm_token) {
            $channels[] = FcmChannel::class;
        }

        return $channels;
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'a rescue organization';

        return (new FcmMessage())
            ->setNotification(FcmNotification::create(
                "Help is on the way! 🚨",
                "A rescue team from {$orgName} has been assigned to your report."
            ))
            ->setData([
                'type' => 'rescue_assigned',
                'rescue_id' => (string) $this->rescue->id,
                'case_code' => $this->rescue->case_code,
            ]);
    }
}
