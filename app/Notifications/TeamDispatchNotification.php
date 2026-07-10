<?php

namespace App\Notifications;

use App\Models\RescueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class TeamDispatchNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use \App\Traits\DatabaseNotificationTrait;

    public function __construct(public RescueReport $rescue, public string $role = 'team member')
    {
        $this->rescue->loadMissing(['user', 'organization']);
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("🚨 Rescue Dispatch: {$this->rescue->case_code} - {$this->rescue->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been dispatched as a **{$this->role}** for a rescue operation.")
            ->line("**Case:** {$this->rescue->case_code}")
            ->line("**Title:** {$this->rescue->title}")
            ->line("**Urgency:** {$this->rescue->urgency}");

        if ($this->rescue->user) {
            $mail->line("**Reporter:** {$this->rescue->user->name} ({$this->rescue->user->phone})");
        }

        $mail->line("**Location:** {$this->rescue->address}");
        
        if ($this->rescue->latitude && $this->rescue->longitude) {
            $mapUrl = "https://www.google.com/maps/search/?api=1&query={$this->rescue->latitude},{$this->rescue->longitude}";
            $mail->line("**Map:** [View on Google Maps]({$mapUrl})");
        }

        $mail->line("**Description:** {$this->rescue->description}");

        if ($this->rescue->image_path) {
            // Generates an absolute URL using the configured APP_URL
            $imageUrl = rtrim(config('app.url'), '/') . '/storage/' . ltrim($this->rescue->image_path, '/');
            $mail->line('**Reported Image:**');
            $mail->line("[View Reported Image]({$imageUrl})");
        }

        $signedUrl = URL::signedRoute('rescue.access', ['report' => $this->rescue, 'teamMember' => $notifiable]);

        return $mail
            ->action('View Case Details', $signedUrl)
            ->line('Please respond promptly and proceed to the location.')
            ->line('Thank you for your service.');
    }

    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        $caseCode = $this->rescue->case_code ?? 'Unknown Case';
        $title = $this->rescue->title ?? 'Unknown Title';

        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => "🚨 Rescue Dispatch: {$caseCode}",
            'body' => "You've been dispatched as {$this->role} for: {$title}",
            'type' => 'rescue_dispatch',
            'rescue_id' => (string) $this->rescue->id,
            'case_code' => $caseCode,
        ]);
    }

    public function getTitle()
    {
        $caseCode = $this->rescue->case_code ?? 'Unknown Case';
        return "🚨 Rescue Dispatch: {$caseCode}";
    }

    public function getMessage()
    {
        $title = $this->rescue->title ?? 'Unknown Title';
        return "You've been dispatched as {$this->role} for: {$title}";
    }

    public function getType()
    {
        return "rescue_dispatch";
    }

    public function getLink()
    {
        return "/organization/rescues/{$this->rescue->id}";
    }
}
