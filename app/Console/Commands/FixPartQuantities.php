<?php

namespace App\Console\Commands;

use App\Models\Part;
use Illuminate\Console\Command;

class FixPartQuantities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-part-quantities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will set all the null values for part quantities to 0.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing part quantities...');

        $fields = [
            'quantity',
            'quantity_in_stock',
            'quantity_ordered',
        ];

        foreach ($fields as $field) {
            // Get all the parts that have a null quantity
            $parts = Part::whereNull($field)->get();

            // Set the quantity to 0
            foreach ($parts as $part) {
                $part->$field = 0;
                $part->save();
            }
        }

        $this->info('All part quantities have been fixed.');
    }
}
