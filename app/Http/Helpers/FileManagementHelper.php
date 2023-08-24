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
            $newName = $fileName;
            // if DWG file
            if (str_contains($fileName, '.dwg')) {
                $path = explode('.dwg', $fileName)[0];
                if (in_array($path.'_R.dwg', $files)) {
                    // don't store filename.dwg
                    // if filename_R.dwg is present
                    continue;
                } else {
                    // add _R to filename
                    $newName = $path.'_R.dwg';
                }
            }
            if (str_contains($fileName, '.dxf')) {
                $path = explode('.dxf', $fileName)[0];
                if (in_array($path.'_R.dxf', $files)) {
                    // don't store filename.dxf
                    // if filename_R.dxf is present
                    continue;
                } else {
                    // add _R to filename
                    $newName = $path.'_R.dxf';
                }
            }
            Storage::disk('local')->move(
                $fileName,
                str_replace('/temp', '', $newName),
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
