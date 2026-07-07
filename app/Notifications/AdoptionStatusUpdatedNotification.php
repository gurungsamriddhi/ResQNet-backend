<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class AdoptionStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $adoption;

    public function __construct(AdoptionRequest $adoption)
    {
        $this->adoption = $adoption;
    }

    public function via($notifiable): array
    {
        // Only include FCM channel if the user has a device token registered
        $channels = [];

        if ($notifiable->routeNotificationForFcm()) {
            $channels[] = FcmChannel::class;
        }

        if (in_array($this->adoption->status, ['pending', 'approved', 'rejected'])) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Build the Email Message based on status changes.
     */
    public function toMail($notifiable): MailMessage
    {
        $petName = $this->adoption->pet->name;

        return match ($this->adoption->status) {
            'pending' => (new MailMessage)
                ->subject("Application Received for {$petName}! 🐾")
                ->greeting("Hi {$notifiable->name},")
                ->line("Your application to adopt {$petName} has been successfully submitted.")
                ->action('Track Application', url("/requests/{$this->adoption->id}")),

            'approved' => (new MailMessage)
                ->subject("Application Approved for {$petName}! 🎉")
                ->greeting("Congratulations {$notifiable->name}!")
                ->line("Great news! Your application to adopt **{$petName}** has been formally approved.")
                ->line("Our organization team will reach out to you within 1 to 2 working days to coordinate the next steps.")
                ->action('View Next Steps', url("/requests/{$this->adoption->id}")),

            'rejected' => (new MailMessage)
                ->subject("Update regarding your application for {$petName}")
                ->greeting("Hello {$notifiable->name},")
                ->line("Thank you for your interest in adopting {$petName}.")
                ->line("Unfortunately, the organization decided to move forward with another applicant at this time.")
                ->line($this->adoption->organization_response ? "Reason: " . $this->adoption->organization_response : '')
                ->action('Browse Other Pets', url("/pets")),

            default => (new MailMessage),
        };
    }

    /**
     * Build the Mobile Push Notification Payload based on status changes.
     */
    public function toFcm($notifiable): FcmMessage
    {
        $petName = $this->adoption->pet->name;

        // Dynamic Text Generation mapping directly to your ENUM lifecycle values
        [$title, $body] = match ($this->adoption->status) {
            'pending' => [
                'Application Received! 📨',
                "Your application for {$petName} has been submitted successfully."
            ],
            'under_review' => [
                'Application Under Review 🔍',
                "The organization is actively reviewing your request for {$petName}."
            ],
            'approved' => [
                'Adoption Approved! 🎉',
                "You are approved to adopt {$petName}! Expect a reach out within 1-2 working days."
            ],
            'rejected' => [
                'Application Update 📋',
                $this->adoption->organization_response
                    ? "Your application for {$petName} was declined: {$this->adoption->organization_response}"
                    : "A decision has been made on your application for {$petName}."
            ],
            'completed' => [
                'Welcome Home! 🏡❤️',
                "The adoption process for {$petName} is officially complete! View their health records now."
            ],
        };

        return (new FcmMessage())
            ->setNotification(FcmNotification::create($title, $body))
            ->setData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'adoption_request_id' => (string) $this->adoption->id,
                'status' => $this->adoption->status,
                'type' => 'lifecycle_update' // Mobile code uses this to refresh the exact tab view
            ]);
    }
}
