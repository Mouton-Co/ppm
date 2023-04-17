<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

class UploadFilesHelper
{
    public function containsExcel($submissionCode)
    {
        $files = Storage::files('files/temp/' . $submissionCode);
        foreach ($files as $fileName) {
            if (str_contains(strtolower($fileName), '.xlsx')) {
                return true;
            }
        }

        return false;
    }
}
