<?php

namespace App\Http\Services;

use App\Mail\BomReplaced;
use App\Mail\ClientConfirmed;
use App\Models\Order;
use App\Models\Submission;
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

        if (!empty($recipientGroup)) {
            $emails = $recipientGroup->recipient_emails;

            if (!empty($emails)) {
                $mailBuilder = Mail::to($emails[0]);

                if (count($emails) > 1) {
                    $mailBuilder->cc(array_slice($emails, 1));
                }

                $mailBuilder->send(new ClientConfirmed($order));
            }
        }
    }

    /**
     * Send an email to the procurement team when a BOM is replaced
     *
     * @param array $replacements
     * @param array $replacementOptions
     * @param string $originalId
     * @param string $replacementId
     * @return void
     */
    public static function sendBomReplacedEmail(array $replacements, array $replacementOptions, string $originalId, string $replacementId): void
    {
        /**
         * see if there is a procurement recipient group to receive this
         */
        $group = RecipientGroup::where('field', 'Currently responsible')->where('value', 'Procurement')->first();
        $original = Submission::find($originalId);
        $replacement = Submission::find($replacementId);

        if (!empty($group) && !empty($group->recipient_emails)) {
            /**
             * send to group
             */
            $emails = $group->recipient_emails;
            $mail = Mail::to($emails[0]);

            if (count($emails) > 1) {
                $mail->cc(array_slice($emails, 1));
            }

            $mail->send(new BomReplaced($replacements, $replacementOptions, $original, $replacement));
        } else {
            /**
             * send to users with procurement role instead
             */
            $emails = User::whereHas('role', function ($query) {
                $query->where('role', 'procurement');
            })
                ->pluck('email')
                ->toArray();

            if (!empty($emails)) {
                $mail = Mail::to($emails[0]);

                if (count($emails) > 1) {
                    $mail->cc(array_slice($emails, 1));
                }

                $mail->send(new BomReplaced($replacements, $replacementOptions, $original, $replacement));
            }
        }
    }
}
