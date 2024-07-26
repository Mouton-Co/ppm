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
use Illuminate\Support\Facades\Storage;

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
        $zipFile = new \PhpZip\ZipFile();

        /**
         * Add all files of this order to a zip
         */
        foreach ($this->order->parts()->get() as $part) {
            foreach ($part->files as $file) {
                /**
                 * temporarily store file on local from s3
                 */
                Storage::put(
                    "files/temp/{$file->name}.{$file->file_type}",
                    Storage::disk('s3')->get($file->location)
                );
                
                /**
                 * Add file to zip
                 */
                $zipFile->addFile(storage_path("app/files/temp/{$file->name}.{$file->file_type}"), "{$file->name}.{$file->file_type}");
            }
        }

        /**
         * Store zip locally as a temp file
         */
        $zipFile
            ->saveAsFile(storage_path("app/files/temp/{$this->order->po_number}.zip"))
            ->close();

        
        /**
         * Add the zip file as an attachment
         */
        $attachments[] = Attachment::fromPath(storage_path("app/files/temp/{$this->order->po_number}.zip"))
            ->as($this->order->po_number.'.zip')
            ->withMime('application/zip');

        return $attachments;
    }
}
