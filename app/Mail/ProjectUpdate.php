<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /*
    * The subject of the email.
    */
    public $subject;

    /**
     * The view of the body of the email.
     */
    public $view;

    /**
     * The eloquent datum to be passed to the view.
     */
    public $datum;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $view, $datum)
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->datum = $datum;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
            with: ['datum' => $this->datum],
        );
    }
}
