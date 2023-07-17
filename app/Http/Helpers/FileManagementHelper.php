<?php

namespace App\Http\Helpers;

use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class FileManagementHelper
{
    /**
     * Makes temp files permanent for a specific submission
     * @param $submissionCode
     */
    public function makeFilesPermanent($submissionCode)
    {
        $files = Storage::disk('local')->files('files/temp/' . $submissionCode);

        foreach ($files as $fileName) {
            Storage::disk('local')->move(
                $fileName,
                str_replace('/temp', '', $fileName),
            );
        }
    }
}
