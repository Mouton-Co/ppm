<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Part;
use Illuminate\Support\Facades\Storage;

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
                $file->size      = $this->formatSizeUnits(Storage::size($fileLocation));
                $file->part_id   = $part->id;
                $file->save();
            }
        }
    }

    /**
     * Formats unit size in bytes to display size
     */
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * Downloads the file in the browser
     */
    public function download($id)
    {
        $file = File::find($id);
        return response()->download(storage_path().'/app/'.$file->location);
    }

    /**
     * Downloads the zip for a submission
     */
    public function downloadZip($id)
    {
        return response()->download(storage_path()."/app/files/$id/$id.zip");
    }
}
