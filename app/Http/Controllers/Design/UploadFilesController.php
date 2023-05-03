<?php

namespace App\Http\Controllers\Design;

use App\Exports\BomExcel;
use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFilesHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $helper         = new UploadFilesHelper();

        if (!empty($submissionCode) && $request->hasFile('file')){
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();

            if ($fileType == 'xlsx' && $helper->containsExcel($submissionCode)) {
                return response()->json(['error' => 'Submission already has excel sheet.']);
            } else {
                $file->storeAs('files/temp/'.$submissionCode, $file->getClientOriginalName());
            }

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

    public function getFeedback(Request $request)
    {
        $helper = new UploadFilesHelper();
        $submissionCode = $request->submission_code;

        if (!empty($submissionCode)){
            
            $excel = $helper->getSubmissionExcel($submissionCode);

            // check if there's an excel sheet
            if (empty($excel)) {
                return response()->json([
                    "lines" => [
                        [
                            "type" => "error",
                            "text" => "No excel sheet found",
                        ],
                    ],
                ]);
            }
            
            $matrix   = Excel::toArray(new BomExcel, $excel)[0];
            $fileName = $helper->getFileName($excel, $submissionCode);

            // check headings
            list($correct, $response) = $helper->checkHeadings($matrix);
            if (!$correct) {
                return response()->json([
                    "lines" => [
                        [
                            "type" => "success",
                            "text" => "Excel sheet found - $fileName",
                            "tick" => "true",
                        ],
                        [
                            "type" => "error",
                            "text" => "Columns are missing:",
                            "list" => $response,
                        ],
                    ],
                ]);
            }
            
            // converts matrix to more readable array
            $matrix = $helper->cleanMatrix($matrix);
            
            // check data integrity
            list($correct, $message, $response) = $helper->checkDataIntegrity($matrix);
            if (!$correct) {
                return response()->json([
                    "lines" => [
                        [
                            "type" => "success",
                            "text" => "Excel sheet found - $fileName",
                            "tick" => "true",
                        ],
                        [
                            "type" => "success",
                            "text" => "Excel sheet columns checked",
                            "tick" => "true",
                        ],
                        [
                            "type" => "error",
                            "text" => $message,
                            "list" => $response,
                            "list_case" => "upper",
                        ],
                    ],
                ]);
            } else {
                $matrix = $message;
            }

            // check for duplicates
            list($correct, $message, $response) = $helper->checkDuplicates($matrix);
            if (!$correct) {
                return response()->json([
                    "lines" => [
                        [
                            "type" => "success",
                            "text" => "Excel sheet found - $fileName",
                            "tick" => "true",
                        ],
                        [
                            "type" => "success",
                            "text" => "Excel sheet columns checked",
                            "tick" => "true",
                        ],
                        [
                            "type" => "error",
                            "text" => $message,
                            "list" => $response,
                        ],
                    ],
                ]);
            }
            
            // get file matches
            list($correct, $requiredFiles) = $helper->getFileMatches($matrix, $submissionCode);
            if (!$correct) {
                return response()->json([
                    "lines" => [
                        [
                            "type" => "success",
                            "text" => "Excel sheet found - $fileName",
                            "tick" => "true",
                        ],
                        [
                            "type" => "success",
                            "text" => "Excel sheet columns checked",
                            "tick" => "true",
                        ],
                        [
                            "type" => "success",
                            "text" => "Excel sheet data checked",
                            "tick" => "true",
                        ],
                        [
                            "type"       => "error",
                            "text"       => "Files required: ",
                            "list"       => $requiredFiles,
                            "list_files" => "true",
                        ],
                    ],
                ]);
            }

            return response()->json([
                "lines" => [
                    [
                        "type" => "success",
                        "text" => "Excel sheet found - $fileName",
                        "tick" => "true",
                    ],
                    [
                        "type" => "success",
                        "text" => "Excel sheet columns checked",
                        "tick" => "true",
                    ],
                    [
                        "type" => "success",
                        "text" => "Excel sheet data checked",
                        "tick" => "true",
                    ],
                    [
                        "type" => "success",
                        "text" => "All required files found",
                        "tick" => "true",
                    ],
                ],
            ]);

        } else {
            return response()->json(['error' => 'Submission code not found']);
        }
    }
}
