<?php

namespace App\Mail\OrganizationRequestStatus;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Organization $organization;
    public ?string $reason;
    public string $status;

    public function __construct(Organization $organization, string $status, ?string $reason = null)
    {
        $this->organization = $organization;
        $this->status = $status;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->status) {
            'approved' => 'Your Organization Has Been Approved — ResQNet',
            'rejected' => 'Organization Registration Update — ResQNet',
            'suspended' => 'Organization Account Suspended — ResQNet',
            default => 'Organization Status Update — ResQNet',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: "emails.organization.{$this->status}",
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
