<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Part;

class FileController extends Controller
{

    /**
     * Store files for the given part
     */
    public function storeFiles(Part $part)
    {
        $files = $part->submission->files;
        foreach ($files as $fileLocation) {
            if (str_contains($fileLocation, explode('.par', $part->name)[0])) {
                $split    = explode('.', $fileLocation);
                $fileType = $split[count($split)-1];
                array_pop($split);
                $split    = implode('.', $split);
                $fileName = explode('files/' . $part->submission->submission_code . '/', $split)[1];

                $file            = new File();
                $file->name      = $fileName;
                $file->file_type = $fileType;
                $file->location  = $fileLocation;
                $file->part_id   = $part->id;
                $file->save();
            }
        }
    }
}
