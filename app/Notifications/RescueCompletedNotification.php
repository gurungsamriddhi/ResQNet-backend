<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class RescueCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use \App\Traits\DatabaseNotificationTrait;

    public function __construct(public RescueReport $rescue) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'the rescue organization';
        $title = $this->rescue->title ?? 'Unknown Rescue';

        return (new MailMessage)
            ->subject('Great news!Rescue Completed')
            ->greeting("Hello {$notifiable->name},")
            ->line("The rescue for **{$title}** has been successfully completed!")
            ->line("The animal is now safe and in care at {$orgName}.")
            ->line("Thank you again for reporting this to ResQNet and saving a life.");
            
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'the rescue organization';
        $title = $this->rescue->title ?? 'Unknown Rescue';
        $caseCode = $this->rescue->case_code ?? '';

        return new BroadcastMessage([
            'title' => "Great news! Rescue Completed",
            'body' => "The rescue for '{$title}' has been completed. The animal is now safe and in care at {$orgName}.",
            'type' => 'rescue_completed',
            'rescue_id' => (string) $this->rescue->id,
            'case_code' => $caseCode,
        ]);
    }

    public function getTitle()
    {
        return "Great news! Rescue Completed";
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
