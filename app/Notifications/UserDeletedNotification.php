<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class UserDeletedNotification extends Notification
{
    use Queueable;

    protected $reason;

    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail', FcmChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Important: Account Deletion')
                    ->greeting("Hello {$notifiable->name},")
                    ->line('Your account has been permanently removed by the administrator.')
                    ->line('Reason for removal: ' . $this->reason)
                    ->line('Thank you for your time with ResQNet.');
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage())
            ->setNotification(FcmNotification::create('Account Removed', 'Your account has been removed. Check your email for details.'))
            ->setData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'account_deleted'
            ]);
    }
}
