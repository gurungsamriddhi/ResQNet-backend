<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtpNotification extends Notification
{
    use Queueable;

    protected $otp;
    protected $type;

    public function __construct($otp, $type = 'email')
    {
        $this->otp = $otp;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];        // You can add 'sms' later
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->type === 'reset_password' 
            ? 'Your Password Reset OTP - ResQNet' 
            : 'Your Verification OTP - ResQNet';
            
        $actionText = $this->type === 'reset_password'
            ? 'password reset'
            : 'verification';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name)
            ->line("Your {$actionText} OTP is:")
            ->line('**' . $this->otp . '**')
            ->line('This OTP will expire in 10 minutes.')
            ->line('If you did not request this, please ignore this email.');
    }
}
