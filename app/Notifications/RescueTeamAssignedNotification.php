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
    use \App\Traits\DatabaseNotificationTrait;

    public function __construct(public RescueReport $rescue) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

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

    public function getTitle()
    {
        return "Help is on the way! 🚨";
    }

    public function getMessage()
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'a rescue organization';
        return "A rescue team from {$orgName} has been assigned to your report.";
    }

    public function getType()
    {
        return "rescue_assigned";
    }

    public function getLink()
    {
        // Assuming mobile deep link or web fallback
        return "/rescues/{$this->rescue->id}";
    }
}
