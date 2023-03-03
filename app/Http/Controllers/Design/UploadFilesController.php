<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class UploadFilesController extends Controller
{
    /**
     * Upload file
     *
     * @param Request $request
     * @return Response
     */
    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')){
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('files', $file->getClientOriginalName());
            return response()->json(['success' => $fileName]);
        } else {
            return response()->json(['error' => 'No file uploaded']);
        }
    }
}
