<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Setting;
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
        $send = Setting::where('key', 'supplier_emails')?->first()?->value ?? 'false';

        /**
         * if env is local or send setting is turned off don't send emails
         */
        if (app()->environment('local') || $send == 'false') {
            return 0;
        }

        foreach (Order::query()->dueToday()->get() as $order) {
            Mail::to($order->supplier?->representatives()?->first()?->email ?? '')->send(new \App\Mail\ZeroDaysLeft($order));
        }
    }
}
