<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Part;
use App\Models\Submission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearAllSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all submissions and files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::disk('local')->deleteDirectory('files');
        info('All files cleared');

        Submission::truncate();
        info('All submissions cleared');

        Part::truncate();
        info('All parts cleared');

        File::truncate();
        info('All files cleared');
    }
}
