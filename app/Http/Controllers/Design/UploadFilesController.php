<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;

class UploadFilesController extends Controller
{
    /**
     * Called when file is dropped in the dropzone
     *
     * @param Request $request
     * @return Response
     */
    public function uploadFile(Request $request)
    {
        $submissionCode = apache_request_headers()['submission_code'] ?? null;

        if (empty($submissionCode) || $request->hasFile('file')){
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $file->storeAs('files/temp/'.$submissionCode, $file->getClientOriginalName());

            return response()->json(['success' => $fileName]);
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
    }

    /**
     * Called when file deleted from dropzone
     *
     * @param Request $request
     * @return Response
     */
    public function removeFile(Request $request)
    {
        
        $fileName       = json_decode($request->file)->upload->filename;
        $submissionCode = $request->submission_code;
        
        if (!empty($submissionCode) && !empty($fileName)){
            
            Storage::delete('files/temp/'.$submissionCode.'/'.$fileName);

            return response()->json(['success' => $fileName]);
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
    }
}
