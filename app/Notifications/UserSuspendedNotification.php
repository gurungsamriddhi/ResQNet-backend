<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class UserSuspendedNotification extends Notification
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
                    ->subject('Important: Account Suspension')
                    ->greeting("Hello {$notifiable->name},")
                    ->line('Your account has been suspended by the administrator.')
                    ->line('Reason for suspension: ' . $this->reason)
                    ->line('If you believe this is an error, please contact support.');
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage())
            ->setNotification(FcmNotification::create('Account Suspended', 'Your account has been suspended. Please check your email for details.'))
            ->setData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'account_suspended'
            ]);
    }
}
