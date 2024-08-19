<?php

namespace App\Console\Commands;

use App\Models\Part;
use Illuminate\Console\Command;

class InitialiseStageField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialise-stage-field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initialising stage field...');

        // Get all the records from the database
        $records = Part::withTrashed()->get();

        // Loop through each record
        $bar = $this->output->createProgressBar(count($records));
        foreach ($records as $record) {
            // Set the stage field to 'new'
            $record->stage = 1;
            $record->save();
            $bar->advance();
        }
        $bar->finish();

        $this->info('');
        $this->info('Stage fields initialised.');
    }
}
