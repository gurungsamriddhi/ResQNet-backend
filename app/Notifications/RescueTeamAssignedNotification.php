<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class RescueTeamAssignedNotification extends Notification implements ShouldQueue
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
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'a rescue organization';

        return (new MailMessage)
            ->subject('Help is on the way! 🚨')
            ->greeting("Hello {$notifiable->name},")
            ->line("A rescue team from **{$orgName}** has just been assigned to the rescue case you reported.")
            ->line("Thank you for looking out for them. We will notify you once the rescue is completed.")
            ->action('View Case Details', url("/rescues/{$this->rescue->id}"));
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $orgName = $this->rescue->organization ? $this->rescue->organization->name : 'a rescue organization';
        $caseCode = $this->rescue->case_code ?? '';

        return new BroadcastMessage([
            'title' => "Help is on the way! 🚨",
            'body' => "A rescue team from {$orgName} has been assigned to your report.",
            'type' => 'rescue_assigned',
            'rescue_id' => (string) $this->rescue->id,
            'case_code' => $caseCode,
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
