<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all the temp files at 2am.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Storage::disk('local')->deleteDirectory('files/temp');
        info('Temp files cleared');
    }
}
