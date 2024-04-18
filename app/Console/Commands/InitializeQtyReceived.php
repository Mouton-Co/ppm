<?php

namespace App\Console\Commands;

use App\Models\Part;
use Illuminate\Console\Command;

class InitializeQtyReceived extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-qty-received';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $parts = Part::all();

        foreach ($parts as $part) {
            $part->qty_received = $part->quantity_ordered;
            $part->save();
        }
    }
}
