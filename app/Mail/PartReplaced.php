<?php

namespace App\Mail;

use App\Models\Part;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartReplaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The part that was changed.
     */
    public Part $part;

    /**
     * Create a new message instance.
     */
    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Part Replaced',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.part-replaced',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
