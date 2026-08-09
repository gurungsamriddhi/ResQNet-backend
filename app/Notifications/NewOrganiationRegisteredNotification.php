<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrganiationRegisteredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Organization $organization)
    {
        $this->organization=$organization;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database','broadcast'];
    }

    // Construct the Email
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Organization Registration Request')
            ->greeting('Hello Admin,')
            ->line('A new organization named "' . $this->organization->name . '" has just registered and is waiting for approval.')
            ->action('Review Registration', url('/admin/dashboard'))
            ->line('Please log in to the dashboard to approve or reject their application.');
    }
    // Construct the data saved to the Database for the Bell dropdown
    public function toArray(object $notifiable): array
    {
        return [
            'org_id' => $this->organization->id,
            'message' => 'New organization registration: ' . $this->organization->name,
            'type' => 'organization_registration',
        ];
    }
    // Construct the data sent over Reverb WebSockets for the live Bell update
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'org_id' => $this->organization->id,
            'message' => 'New organization registration: ' . $this->organization->name,
        ]);
    }
}