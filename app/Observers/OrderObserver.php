<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        /**
         * update due date of parts
         */
        if (! empty($order->due_date)) {
            foreach ($order->parts()->get() as $part) {
                $part->update([
                    'due_date' => $order->due_date,
                ]);
            }
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        info('order observer');
        /**
         * update due date of parts
         */
        if ($order->isDirty('due_date')) {
            foreach ($order->parts()->get() as $part) {
                $part->update([
                    'due_date' => $order->due_date,
                ]);
            }
        }
        info('order observer end');
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
