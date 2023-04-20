<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

class UploadFilesHelper
{
    /**
     * Checks if the given temporary submission contains an excel file.
     * 
     * @param string $submissionCode Submission directory to check
     * @return bool 
     */
    public function containsExcel($submissionCode)
    {
        $files = Storage::disk('local')->files('files/temp/' . $submissionCode);
        
        foreach ($files as $fileName) {
            if (str_contains(strtolower($fileName), '.xlsx')) {
                return true;
            }
        }

        return false;
    }

    
}
