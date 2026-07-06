<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamDispatchNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public RescueReport $rescue, public string $role = 'team member') {}

    public function via(object $notifiable): array
    {
        $channels = ['mail'];

        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🚨 Rescue Dispatch: {$this->rescue->case_code} - {$this->rescue->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been dispatched as a **{$this->role}** for a rescue operation.")
            ->line("**Case:** {$this->rescue->case_code}")
            ->line("**Title:** {$this->rescue->title}")
            ->line("**Urgency:** {$this->rescue->urgency}")
            ->line("**Location:** {$this->rescue->address}")
            ->line("**Description:** {$this->rescue->description}")
            ->action('View Case Details', url('/organization/rescues'))
            ->line('Please respond promptly and proceed to the location.')
            ->line('Thank you for your service.');
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => "🚨 Rescue Dispatch: {$this->rescue->case_code}",
            'body' => "You've been dispatched as {$this->role} for: {$this->rescue->title}",
            'data' => [
                'type' => 'rescue_dispatch',
                'rescue_id' => (string) $this->rescue->id,
                'case_code' => $this->rescue->case_code,
            ],
        ];
    }
}
