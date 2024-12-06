<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FiveDaysLeft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:five-days-left';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through all the POs and send a reminder to the suppliers that the PO is due in 5 days.';

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

        foreach (Order::query()->dueInFiveDays()->get() as $order) {
            Mail::to('arouxmouton@gmail.com')
                ->send(new \App\Mail\FiveDaysLeft($order));
        }
    }
}
