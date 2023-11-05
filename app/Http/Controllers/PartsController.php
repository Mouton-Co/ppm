<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BomExcel;
use App\Http\Helpers\UploadFilesHelper;
use App\Http\Requests\Parts\IndexRequest;
use App\Http\Requests\Parts\UpdateRequest;
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

        if (!empty($matrix) && !empty($matrix['Item Number'])) {
            for ($i=0; $i<count($matrix['Item Number'])-1; $i++) {
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

    /**
     * View an index of all parts
     */
    public function index(IndexRequest $request)
    {
        $parts = Part::select(['parts.*', 'submissions.submission_code'])
            ->join('submissions', 'submissions.id', '=', 'parts.submission_id');

        if (!empty($request->get('order_by')) && $request->get('order_by') == 'submission->submission_code') {
            $parts = $parts->orderBy(
                'submissions.submission_code',
                $request->get('order_direction') ?? 'asc'
            );
        } else {
            $parts = $parts->orderBy(
                $request->get('order_by') ?? 'parts.created_at',
                $request->get('order_direction') ?? 'asc'
            );
        }

        if (!empty($request->get('search'))) {
            $parts = $parts->where('parts.name', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.quantity', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.material', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.material_thickness', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.finish', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.used_in_weldment', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.process_type', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.manufactured_or_purchased', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.po_number', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.date_stamp', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.status', 'like', '%' . $request->get('search') . '%')
                ->orWhere('submissions.submission_code', 'like', '%' . $request->get('search') . '%');
        }

        return view('parts.index')->with([
            'parts' => $parts->paginate(10)
        ]);
    }
    
    /**
     * Update a part
     */
    public function update(UpdateRequest $request)
    {
        $part = Part::find($request->get('id'));
        
        $part->po_number  = $request->get('po_number') ?? $part->po_number;
        $part->date_stamp = $request->get('date_stamp') ?? $part->date_stamp;
        $part->status     = $request->get('status') ?? $part->status;
        $part->save();

        return redirect()->back()->with([
            'message' => 'Part updated successfully'
        ]);
    }
}
