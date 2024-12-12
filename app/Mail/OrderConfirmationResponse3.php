<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationResponse3 extends Mailable
{
    use Queueable, SerializesModels;

    protected Order $order;

    protected string $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: "PO {$this->order->po_number} - not ready yet");
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation-response3',
            with: [
                'order' => $this->order,
                'reason' => $this->reason,
            ],
        );
    }
}
