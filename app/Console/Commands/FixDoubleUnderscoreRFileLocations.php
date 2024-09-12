<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixDoubleUnderscoreRFileLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-double-underscore-r-file-locations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = \App\Models\File::all();

        foreach ($files as $file) {
            $fileLocation = $file->location;
            $fileType = $file->file_type;

            if (
                ($fileType === 'dwg' ||
                $fileType === 'dxf') &&
                str_contains($fileLocation, '_R_R')
            ) {
                $fileLocation = str_replace('_R_R', '_R', $fileLocation);
                $file->location = $fileLocation;
                $file->save();
            }
        }
    }
}
