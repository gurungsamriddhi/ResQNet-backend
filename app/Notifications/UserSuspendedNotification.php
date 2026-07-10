<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use \App\Traits\DatabaseNotificationTrait;

    protected $reason;

    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
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

    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => 'Account Suspended',
            'body' => 'Your account has been suspended. Please check your email for details.',
            'type' => 'account_suspended',
        ]);
    }

    public function getTitle()
    {
        return 'Account Suspended';
    }

    public function getMessage()
    {
        return 'Your account has been suspended. Reason: ' . $this->reason;
    }

    public function getType()
    {
        return 'account_suspended';
    }

    public function getLink()
    {
        return null;
    }
}
