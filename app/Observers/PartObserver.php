<?php

namespace App\Observers;

use App\Mail\PartReplaced;
use App\Models\Part;
use App\Models\RecipientGroup;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PartObserver
{
    /**
     * Handle the Part "created" event.
     */
    public function created(Part $part): void
    {
        //
    }

    /**
     * Handle the Part "updated" event.
     */
    public function updated(Part $part): void
    {
        /**
         * check if replaced_by_submission was updated
         * don't send email if in local environment
         */
        if ($part->isDirty('replaced_by_submission') && env('APP_ENV') != 'local') {
            /**
             * see if there is a procurement recipient group to receive this
             */
            $group = RecipientGroup::where('field', 'Currently responsible')->where('value', 'Procurement')->first();

            if (!empty($group) && !empty($group->recipient_emails)) {
                /**
                 * send to group
                 */
                $emails = $group->recipient_emails;
                $mail = Mail::to($emails[0]);

                if (count($emails) > 1) {
                    $mail->cc(array_slice($emails, 1));
                }

                $mail->send(new PartReplaced($part));
            } else {
                /**
                 * send to users with procurement role instead
                 */
                $emails = User::whereHas('roles', function ($query) {
                    $query->where('name', 'procurement');
                })
                    ->pluck('email')
                    ->toArray();

                $mail = Mail::to($emails[0]);

                if (count($emails) > 1) {
                    $mail->cc(array_slice($emails, 1));
                }

                $mail->send(new PartReplaced($part));
            }
        }
    }

    /**
     * Handle the Part "deleted" event.
     */
    public function deleted(Part $part): void
    {
        //
    }

    /**
     * Handle the Part "restored" event.
     */
    public function restored(Part $part): void
    {
        //
    }

    /**
     * Handle the Part "force deleted" event.
     */
    public function forceDeleted(Part $part): void
    {
        //
    }
}
