<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class NewAdoptionRequestNotification extends Notification
{
    use Queueable;

    public function __construct(protected AdoptionRequest $adoption) {}

    public function via($notifiable)
    {
        return [FcmChannel::class, 'mail']; // or just FcmChannel if you prefer
    }

    public function toFcm($notifiable): FcmMessage
    {
        $petName = $this->adoption->pet->name;
        $userName = $this->adoption->user->name;

        return (new FcmMessage())
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create(
                "New Adoption Request",
                "{$userName} applied to adopt {$petName}"
            ))
            ->setData([
                'type' => 'new_adoption_request',
                'adoption_id' => $this->adoption->id,
                'pet_id' => $this->adoption->pet_id,
            ]);
    }

    // Optional: toMail() for email
}
