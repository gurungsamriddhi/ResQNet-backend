<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class RescueCompletedNotification extends Notification implements ShouldQueue
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
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'the rescue organization';

        return (new FcmMessage())
            ->setNotification(FcmNotification::create(
                "Great news! 🎉 Rescue Completed",
                "The rescue for '{$this->rescue->title}' has been completed. The animal is now safe and in care at {$orgName}."
            ))
            ->setData([
                'type' => 'rescue_completed',
                'rescue_id' => (string) $this->rescue->id,
                'case_code' => $this->rescue->case_code,
            ]);
    }

    public function getTitle()
    {
        return "Great news! 🎉 Rescue Completed";
    }

    public function getMessage()
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'the rescue organization';
        return "The rescue for '{$this->rescue->title}' has been completed. The animal is now safe and in care at {$orgName}.";
    }

    public function getType()
    {
        return "rescue_completed";
    }

    public function getLink()
    {
        return "/rescues/{$this->rescue->id}";
    }
}
