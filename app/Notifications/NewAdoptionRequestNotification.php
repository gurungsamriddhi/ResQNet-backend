<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewAdoptionRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public AdoptionRequest $adoption) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New Adoption Request')
            ->line("{$this->adoption->user->name} applied to adopt {$this->adoption->pet->name}.");
    }
}
