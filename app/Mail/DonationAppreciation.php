<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAppreciation extends Mailable
{
    use Queueable, SerializesModels;

    public string $donorName;
    public string $date;
    public string $amount;

    public function __construct(string $donorName, string $date, string $amount)
    {
        $this->donorName = $donorName;
        $this->date = $date;
        $this->amount = $amount;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Generous Donation — ResQNet',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donation.appreciation',
        );
    }
}
