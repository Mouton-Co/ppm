<?php

namespace App\Observers;

use App\Mail\PartReplaced;
use App\Models\Part;
use App\Models\RecipientGroup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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
        if (
            $part->isDirty('replaced_by_submission') &&
            env('APP_ENV') != 'local' &&
            // if it has these two, a whole BOM was replaced so don't send the emails
            ! request()->has('original_id') &&
            ! request()->has('new_id')
        ) {
            /**
             * see if there is a procurement recipient group to receive this
             */
            $group = RecipientGroup::where('field', 'Currently responsible')->where('value', 'Procurement')->first();

            if (! empty($group) && ! empty($group->recipient_emails)) {
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
                $emails = User::whereHas('role', function ($query) {
                    $query->where('role', 'procurement');
                })
                    ->pluck('email')
                    ->toArray();

                if (! empty($emails)) {
                    $mail = Mail::to($emails[0]);

                    if (count($emails) > 1) {
                        $mail->cc(array_slice($emails, 1));
                    }

                    $mail->send(new PartReplaced($part));
                }
            }
        }

        /**
         * when part is ordered
         */
        if (
            $part->isDirty('part_ordered_at') &&
            ! empty($part->part_ordered_at) &&
            ! empty($part?->supplier?->average_lead_time)
        ) {
            $part->due_date = now()->addDays($part->supplier->average_lead_time);
            $part->saveQuietly();
        }

        /**
         * when part is dispatched for treatment 1
         */
        if (
            $part->isDirty('treatment_1_part_dispatched_at') &&
            ! empty($part->treatment_1_part_dispatched_at) &&
            ! empty($days = Supplier::where('name', $part->treatment_1_supplier)->first()?->average_lead_time)
        ) {
            $part->due_date = now()->addDays($days);
            $part->saveQuietly();
        }

        /**
         * when part is dispatched for treatment 2
         */
        if (
            $part->isDirty('treatment_2_part_dispatched_at') &&
            ! empty($part->treatment_2_part_dispatched_at) &&
            ! empty($days = Supplier::where('name', $part->treatment_2_supplier)->first()?->average_lead_time)
        ) {
            $part->due_date = now()->addDays($days);
            $part->saveQuietly();
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
