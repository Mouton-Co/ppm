<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

class FileManagementHelper
{
    /**
     * Makes temp files permanent for a specific submission
     */
    public function makeFilesPermanent($submissionCode)
    {
        $files = Storage::disk('local')->files('files/temp/'.$submissionCode);

        foreach ($files as $fileName) {
            $newName = $fileName;

            /*
             * For dwg and dxf files
             * - If there is a file with _R and one without _R, keep the one with _R
             * - If there is no file with _R, append _R to it
             */

            if (
                str_contains($fileName, '.dwg') ||
                str_contains($fileName, '.dxf')) {
                $path = explode('.', $fileName)[0];
                $suffix = explode('.', $fileName)[1];
                if (! str_contains($path, '_R')) {
                    if (in_array($path.'_R.'.$suffix, $files)) {
                        continue;
                    } else {
                        $newName = $path.'_R.'.$suffix;
                    }
                }
            }

            // store the file on s3
            Storage::disk('s3')->put(env('APP_ENV') . '/' . str_replace('/temp', '', $newName), Storage::disk('local')->get($fileName));
        }

        $this->createZipFor($submissionCode);
    }

    /**
     * Creates a zip file of the given submission files in the same folder
     */
    public function createZipFor($submissionCode)
    {
        $zipFile = new \PhpZip\ZipFile();
        $zipFile
            ->addDir(storage_path('app/files/temp/'.$submissionCode))
            ->saveAsFile(storage_path("app/files/temp/$submissionCode/$submissionCode.zip"))
            ->close(); // close archive
        
        // store the zip file on s3
        Storage::disk('s3')->put(env('APP_ENV').'/files/'.$submissionCode.'/'.$submissionCode.'.zip', Storage::disk('local')->get("files/temp/$submissionCode/$submissionCode.zip"));
    }
}
