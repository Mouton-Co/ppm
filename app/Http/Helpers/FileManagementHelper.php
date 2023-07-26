<?php

namespace App\Http\Helpers;

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

        $this->createZipFor($submissionCode);
    }

    /**
     * Creates a zip file of the given submission files in the same folder
     * @param $submissionCode
     */
    public function createZipFor($submissionCode)
    {
        $zipFile = new \PhpZip\ZipFile();
        $zipFile
            ->addDir(storage_path('app/files/'.$submissionCode))
            ->saveAsFile(storage_path("app/files/$submissionCode/$submissionCode.zip"))
            ->close(); // close archive
    }
}
