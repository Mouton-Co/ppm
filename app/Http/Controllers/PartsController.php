<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BomExcel;
use App\Http\Helpers\UploadFilesHelper;
use App\Models\Part;

class PartsController extends Controller
{

    /**
     * Store parts for the given submission
     */
    public function storeParts(Submission $submission)
    {
        $uploadFilesHelper = new UploadFilesHelper();
        $excel             = $submission->excel_sheet;
        $matrix            = Excel::toArray(new BomExcel, $excel)[0];
        $matrix            = $uploadFilesHelper->cleanMatrix($matrix);

        if (!empty($matrix) && !empty($matrix['No.'])) {
            for ($i=0; $i<count($matrix['No.'])-1; $i++) {
                $part                            = new Part();
                $part->name                      = $matrix['File Name'][$i];
                $part->quantity                  = $matrix['Quantity'][$i];
                $part->material                  = $matrix['Material'][$i];
                $part->material_thickness        = $matrix['Material Thickness'][$i];
                $part->finish                    = $matrix['Finish'][$i];
                $part->used_in_weldment          = $matrix['Used In Weldment'][$i];
                $part->process_type              = $matrix['Process Type'][$i];
                $part->manufactured_or_purchased = $matrix['Manufactured or Purchased'][$i];
                $part->notes                     = $matrix['Notes'][$i];
                $part->submission_id             = $submission->id;
                $part->save();

                $fileController = new FileController();
                $fileController->storeFiles($part);
            }
        }
    }
    
}
