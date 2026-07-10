<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use App\Traits\DatabaseNotificationTrait;
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
    use DatabaseNotificationTrait;

    public function __construct(
        public readonly AdoptionRequest $adoption
    ) {}

    public function via($notifiable): array
    {
        $channels = ['database']; // Always send to database

        if ($notifiable->routeNotificationForFcm()) {
            $channels[] = FcmChannel::class;
        }

        // Only send email for specific statuses
        if (in_array($this->adoption->status, ['pending', 'approved', 'rejected'])) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $petName = $this->adoption->pet?->name ?? 'the pet';

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
                ->line("Our organization team will reach out to you within 1 to 2 working days.")
                ->action('View Next Steps', url("/requests/{$this->adoption->id}")),

            'rejected' => (new MailMessage)
                ->subject("Update regarding your application for {$petName}")
                ->greeting("Hello {$notifiable->name},")
                ->line("Thank you for your interest in adopting {$petName}.")
                ->line("Unfortunately, the organization decided to move forward with another applicant.")
                ->line($this->adoption->organization_response ? "Reason: {$this->adoption->organization_response}" : '')
                ->action('Browse Other Pets', url("/pets")),

            default => (new MailMessage)
                ->subject("Adoption Update")
                ->line("Your application status has been updated."),
        };
    }

    public function toFcm($notifiable): FcmMessage
    {
        $petName = $this->adoption->pet?->name ?? 'the pet';

        [$title, $body] = match ($this->adoption->status) {
            'pending' => ['Application Received! 📨', "Your application for {$petName} has been submitted successfully."],
            'under_review' => ['Application Under Review 🔍', "The organization is actively reviewing your request for {$petName}."],
            'approved' => ['Adoption Approved! 🎉', "You are approved to adopt {$petName}! Expect a reach out within 1-2 working days."],
            'rejected' => [
                'Application Update 📋',
                $this->adoption->organization_response
                    ? "Your application for {$petName} was declined: {$this->adoption->organization_response}"
                    : "A decision has been made on your application for {$petName}."
            ],
            'completed' => ['Welcome Home! 🏡❤️', "The adoption process for {$petName} is officially complete!"],
            default => ['Adoption Update', "Your application status has changed."],
        };

        return (new FcmMessage())
            ->setNotification(FcmNotification::create($title, $body))
            ->setData([
                'click_action' => 'NOTIFICATION_CLICK',
                'adoption_request_id' => (string) $this->adoption->id,
                'status' => $this->adoption->status,
                'type' => 'lifecycle_update',
            ]);
    }

    // Database Notification Trait Methods
    public function getTitle(): string
    {
        return "Adoption Update";
    }

    public function getMessage(): string
    {
        return "Your application for {$this->adoption->pet?->name} is now {$this->adoption->status}.";
    }

    public function getType(): string
    {
        return "adoption_status_updated";
    }

    public function getLink(): string
    {
        return "/requests/{$this->adoption->id}";
    }
}
