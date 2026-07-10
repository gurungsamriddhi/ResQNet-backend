<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDeletedNotification extends Notification implements ShouldQueue
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
                    ->subject('Important: Account Deletion')
                    ->greeting("Hello {$notifiable->name},")
                    ->line('Your account has been permanently removed by the administrator.')
                    ->line('Reason for removal: ' . $this->reason)
                    ->line('Thank you for your time with ResQNet.');
    }

    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => 'Account Removed',
            'body' => 'Your account has been removed. Check your email for details.',
            'type' => 'account_deleted',
        ]);
    }

    public function getTitle()
    {
        return 'Account Removed';
    }

    public function getMessage()
    {
        return 'Your account has been permanently removed. Reason: ' . $this->reason;
    }

    public function getType()
    {
        return 'account_deleted';
    }

    public function getLink()
    {
        return null;
    }
}
