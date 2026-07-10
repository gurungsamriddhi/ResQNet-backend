<?php

namespace App\Notifications;

use App\Models\Pet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PetSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use \App\Traits\DatabaseNotificationTrait;

    public function __construct(public Pet $pet)
    {
        $this->pet->loadMissing('organization');
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Important: Pet Profile Suspended')
            ->greeting('Hello ' . ($notifiable->name ?? 'Organization') . ',')
            ->line("The profile for the pet '{$this->pet->name}' has been suspended by an administrator.")
            ->line("Suspension Reason: " . $this->pet->suspension_reason)
            ->line('While suspended, this pet profile will not be visible to the public or available for adoption requests.')
            ->action('View Pet Profile', url("/organization/pets/{$this->pet->id}"))
            ->line('If you believe this was in error, please contact support.');
    }

    public function toBroadcast(object $notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        $petName = $this->pet->name ?? 'Unknown Pet';

        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => "Pet Profile Suspended",
            'body' => "The profile for '{$petName}' has been suspended.",
            'type' => 'pet_suspended',
            'pet_id' => (string) $this->pet->id,
        ]);
    }

    public function getTitle()
    {
        return "Pet Profile Suspended";
    }

    public function getMessage()
    {
        return "The profile for '{$this->pet->name}' has been suspended. Reason: {$this->pet->suspension_reason}";
    }

    public function getType()
    {
        return "pet_suspended";
    }

    public function getLink()
    {
        return "/organization/pets/{$this->pet->id}";
    }
}
