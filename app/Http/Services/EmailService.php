<?php

namespace App\Http\Services;

use App\Mail\ClientConfirmed;
use App\Models\Order;
use App\Models\RecipientGroup;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send an email to the client when the order is confirmed by the supplier
     *
     * @param Order $order
     * @return void
     */
    public static function sendClientConfirmedEmail(Order $order): void
    {
        $recipientGroup = RecipientGroup::where('field', 'Order confirmed by supplier')->first();
        
        if (! empty($recipientGroup)) {
            $emails = $recipientGroup->recipient_emails;
        
            if (! empty($emails)) {
                $mailBuilder = Mail::to($emails[0]);
    
                if (count($emails) > 1) {
                    $mailBuilder->cc(array_slice($emails, 1));
                }
                
                $mailBuilder->send(new ClientConfirmed($order));
            }
        }
    }
}
