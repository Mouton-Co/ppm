<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BomReplaced extends Mailable
{
    use Queueable, SerializesModels;

    protected array $replacements;

    protected array $replacementOptions;

    protected Submission $original;

    protected Submission $replacement;

    /**
     * Create a new message instance.
     */
    public function __construct(array $replacements, array $replacementOptions, Submission $original, Submission $replacement)
    {
        $this->replacements = $replacements;
        $this->replacementOptions = $replacementOptions;
        $this->original = $original;
        $this->replacement = $replacement;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'BOM Replacement submitted',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bom-replaced',
            with: [
                'replacements' => $this->replacements,
                'replacementOptions' => $this->replacementOptions,
                'original' => $this->original,
                'replacement' => $this->replacement,
            ],
        );
    }
}
