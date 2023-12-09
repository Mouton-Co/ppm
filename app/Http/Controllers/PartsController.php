<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BomExcel;
use App\Http\Helpers\UploadFilesHelper;
use App\Http\Requests\Parts\IndexRequest;
use App\Http\Requests\Parts\UpdateCheckboxRequest;
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
                ->orWhere('parts.part_ordered_at', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.raw_part_received_at', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.treated_part_received_at', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.status', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.qc_failed_at', 'like', '%' . $request->get('search') . '%')
                ->orWhere('parts.qc_failed_reason', 'like', '%' . $request->get('search') . '%')
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
        
        if (!empty($request->get('field'))) {
            $field = $request->get('field');

            if (str_contains($field, '->')) {
                $field = explode('->', $field)['0'] . '_id';
            }

            $part->$field = $request->get('value') == '0' ? null : $request->get('value');
            $part->save();

            return response()->json([
                'success' => true,
                'message' => 'Part updated successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Part could not be updated'
        ]);
    }

    /**
     * Update checkbox ajax
     */
    public function updateCheckbox(UpdateCheckboxRequest $request)
    {
        $part = Part::find($request->get('id'));

        if (!empty($request->get('field'))) {
            $field = $request->get('field');
            $part->$field = $request->get('value');
            $fieldAt = $field . '_at';
            $part->$fieldAt = $part->$field ? now() : null;
            switch ($field) {
                case 'part_ordered':
                    $part->status = $part->$field ? 'waiting_on_parts' : 'design';
                    break;
                case 'raw_part_received':
                    $part->status = $part->$field ? 'waiting_on_treatment' : 'waiting_on_parts';
                    break;
                case 'treated_part_received':
                    $part->status = $part->$field ? 'part_received' : 'waiting_on_treatment';
                    break;
                default:
                    $part->status = 'design';
                    break;
            }
            $part->save();

            return response()->json([
                'success'     => true,
                'part_id'     => $part->id,
                'status'      => config('models.parts.columns.status.format')[$part->status],
                'stamp_field' => $fieldAt,
                'stamp_value' => !empty($part->$fieldAt) ? $part->$fieldAt->format('Y-m-d H:i:s') : '-',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Part could not be updated'
        ]);
    }

    /**
     * Generate PO numbers for all parts
     */
    public function generatePoNumbers()
    {
        // all parts grouped by submission with no PO number
        $submissions = Part::where('po_number', null)->where('supplier_id', '!=', null)
            ->get()->groupBy('submission_id');

        foreach ($submissions as $parts) {
            $suppliers = Part::where('po_number', null)->where('submission_id', $parts[0]->submission_id)
                ->where('supplier_id', '!=', null)->get()->groupBy('supplier_id');

            // get latest PO number and increment by 1
            $poPrefix = str_pad($parts[0]->submission->machine_number, 2, '0', STR_PAD_LEFT) .
                '-' . $parts[0]->submission->current_unit_number . '-';
            $latestPo = Part::where('po_number', 'like', $poPrefix . '%')->orderBy('po_number', 'desc')->first();
            $number   = !empty($latestPo) ? (int)explode('-', $latestPo->po_number)[2] + 1 : 1;
            $poNumber = $poPrefix . str_pad($number, 4, '0', STR_PAD_LEFT);

            foreach ($suppliers as $parts) {
                foreach ($parts as $part) {
                    $part->po_number = $poNumber;
                    $part->save();
                }

                // increment PO number
                $number++;
                $poNumber = $poPrefix . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        }

        return redirect()->back();
    }
}
