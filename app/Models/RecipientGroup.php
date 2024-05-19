<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\StringHelper;
use App\Mail\ProjectUpdate;
use Illuminate\Support\Facades\Mail;

class RecipientGroup extends Model
{
    use HasFactory;

    protected $fillable = ['field', 'value', 'recipients'];

    /**
     * Get the recipient emails for the recipient group.
     */
    public function getRecipientEmailsAttribute(): array
    {
        $emails = [];
        if (!empty(explode('<br />', nl2br($this->recipients)))) {
            foreach (explode('<br />', nl2br($this->recipients)) as $email) {
                $emails[] = StringHelper::stripWhitespaceAndLinebreaks($email);
            }
        }
        return $emails;
    }

    /**
     * Send an email to the recipient group.
     * @param string $subject
     * @param string $view
     * @param mixed $datum
     */
    public function mail(string $subject, string $view, $datum): void
    {
        $emails = $this->recipient_emails;
        
        if (! empty($emails)) {
            $mailBuilder = Mail::to($emails[0]);

            if (count($emails) > 1) {
                $mailBuilder->cc(array_slice($emails, 1));
            }
            
            $mailBuilder->send(new ProjectUpdate($subject, $view, $datum));
        }
    }
}
