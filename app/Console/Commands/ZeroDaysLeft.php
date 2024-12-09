<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ZeroDaysLeft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:zero-days-left';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through all the POs and send a reminder to the suppliers that the PO is due today.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * if env is local don't send emails
         */
        if (app()->environment('local')) {
            return 0;
        }

        foreach (Order::query()->dueToday()->get() as $order) {
            Mail::to($order->supplier?->representatives()?->first()?->email ?? '')->send(new \App\Mail\ZeroDaysLeft($order));
        }
    }
}
