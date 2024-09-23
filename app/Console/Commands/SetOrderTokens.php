<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class SetOrderTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-order-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets all the order tokens to the order ID hashed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $order->update([
                'token' => hash('sha256', $order->id),
            ]);
        }

        $this->info('All order tokens have been set.');
    }
}
