<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $subject;

    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $subject, $body)
    {
        $this->order = $order;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address('hanna@proproject.co.za', 'Hannelene Caine')],
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.render-purchase-order',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->order->parts()->get() as $part) {
            foreach ($part->files as $file) {
                $attachments[] = Attachment::fromStorageDisk('s3', $file->location)
                    ->as($file->name.'.'.$file->file_type)
                    ->withMime('application/'.$file->file_type);
            }
        }

        return $attachments;
    }
}
