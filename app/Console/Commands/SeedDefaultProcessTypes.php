<?php

namespace App\Console\Commands;

use App\Models\ProcessType;
use Illuminate\Console\Command;

class SeedDefaultProcessTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:default-process-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds the default process types.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ProcessType::truncate();

        foreach (config('seeding.process-types') as $requiredFiles => $processTypes) {
            foreach ($processTypes as $processType) {
                ProcessType::create([
                    'process_type' => $processType,
                    'required_files' => $requiredFiles,
                ]);
            }
        }
    }
}
