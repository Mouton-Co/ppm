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
            if (str_contains(str_replace(' ', '', $fileLocation), explode('.', str_replace(' ', '', $part->name))[0])) {
                $split = explode('.', $fileLocation);
                $fileType = $split[count($split) - 1];
                array_pop($split);
                $split = implode('.', $split);
                $fileName = explode('files/temp/'.$part->submission->submission_code.'/', $split)[1];

                $file = new File();

                /*
                * For dwg and dxf files
                * - If file does not have _R, append _R to it
                */
                if (
                    ($fileType === 'dwg' ||
                    $fileType === 'dxf') &&
                    ! str_contains($fileName, '_R')
                ) {
                    $fileName .= '_R';
                }

                $file->size = $this->formatSizeUnits(Storage::size($fileLocation));
                $fileLocation = str_replace(".{$fileType}", '', $fileLocation);

                if (
                    ($fileType === 'dwg' ||
                    $fileType === 'dxf')
                    && ! str_contains($fileLocation, '_R')
                ) {
                    $fileLocation .= "_R.{$fileType}";
                } else {
                    $fileLocation .= ".{$fileType}";
                }

                $file->name = $fileName;
                $file->file_type = $fileType;
                $file->location = env('APP_ENV') . '/' .  str_replace('/temp', '', $fileLocation);
                $file->part_id = $part->id;
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
            $bytes = number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes.' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes.' byte';
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

        if (empty($file)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return Storage::disk('s3')->download($file->location);
    }

    /**
     * Downloads the zip for a submission
     */
    public function downloadZip($id)
    {
        return Storage::disk('s3')->download(env('APP_ENV') . "/files/$id/$id.zip");
    }

    /**
     * Opens the file in the browser
     */
    public function open($id)
    {
        $file = File::find($id);
        
        if (empty($file)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return Storage::disk('s3')->response($file->location);
    }
}
