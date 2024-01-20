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
use App\Models\Supplier;
use Illuminate\Http\Request;

class PartsController extends Controller
{
    public $request;

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
            for ($i = 0; $i < count($matrix['Item Number']) - 1; $i++) {
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
        // all parts
        $this->request = $request;
        $parts = Part::with(['submission', 'supplier']);

        // order by
        if (!empty($request->get('order_by'))) {
            if ($request->get('order_by') == 'submission->submission_code') {
                $parts = $parts->orderBy(
                    Submission::select('submission_code')
                        ->whereColumn('submission_id', 'submissions.id')
                        ->orderBy('submission_code', $request->get('order') ?? 'asc')
                        ->limit(1),
                    $request->get('order') ?? 'asc'
                );
            } elseif ($request->get('order_by') == 'supplier->name') {
                $parts = $parts->orderBy(
                    Supplier::select('name')
                        ->whereColumn('supplier_id', 'suppliers.id')
                        ->orderBy('name', $request->get('order') ?? 'asc')
                        ->limit(1),
                    $request->get('order') ?? 'asc'
                );
            } else {
                $parts = $parts->orderBy($request->get('order_by'), $request->get('order') ?? 'asc');
            }
        }

        // status
        if (!empty($request->get('status')) && $request->get('status') != '-') {
            $parts = $parts->where('status', $request->get('status'));
        }

        // supplier
        if (!empty($request->get('supplier_id')) && $request->get('supplier_id') != '-') {
            $parts = $parts->where('supplier_id', $request->get('supplier_id'));
        }

        // submission
        if (!empty($request->get('submission'))) {
            $parts = $parts->where(
                Submission::select('submission_code')
                    ->whereColumn('submission_id', 'submissions.id')
                    ->where('submission_code', 'like', '%' . $request->get('submission') . '%')
                    ->limit(1),
                'like',
                '%' . $request->get('submission') . '%'
            );
        }

        // search
        if (!empty($request->get('search'))) {
            $parts = $parts->where(function ($query) {
                $query->where('parts.po_number', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.name', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.process_type', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.quantity', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.material', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.material_thickness', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.finish', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.used_in_weldment', 'like', '%' . $this->request->get('search') . '%');
            });
        }

        return view('parts.procurement-index')->with([
            'parts' => $parts->paginate(10)
        ]);
    }

    /**
     * Index table for warehouse table
     */
    public function warehouseIndex(IndexRequest $request)
    {
        // all parts
        $this->request = $request;
        $parts = Part::with(['submission', 'supplier']);

        // order by
        if (!empty($request->get('order_by'))) {
            if ($request->get('order_by') == 'submission->submission_code') {
                $parts = $parts->orderBy(
                    Submission::select('submission_code')
                        ->whereColumn('submission_id', 'submissions.id')
                        ->orderBy('submission_code', $request->get('order') ?? 'asc')
                        ->limit(1),
                    $request->get('order') ?? 'asc'
                );
            } elseif ($request->get('order_by') == 'supplier->name') {
                $parts = $parts->orderBy(
                    Supplier::select('name')
                        ->whereColumn('supplier_id', 'suppliers.id')
                        ->orderBy('name', $request->get('order') ?? 'asc')
                        ->limit(1),
                    $request->get('order') ?? 'asc'
                );
            } else {
                $parts = $parts->orderBy($request->get('order_by'), $request->get('order') ?? 'asc');
            }
        }

        // status
        if (!empty($request->get('status')) && $request->get('status') != '-') {
            $parts = $parts->where('status', $request->get('status'));
        } else {
            $parts = $parts->where('status', '!=', 'design');
        }

        // supplier
        if (!empty($request->get('supplier_id')) && $request->get('supplier_id') != '-') {
            $parts = $parts->where('supplier_id', $request->get('supplier_id'));
        }

        // submission
        if (!empty($request->get('submission'))) {
            $parts = $parts->where(
                Submission::select('submission_code')
                    ->whereColumn('submission_id', 'submissions.id')
                    ->where('submission_code', 'like', '%' . $request->get('submission') . '%')
                    ->limit(1),
                'like',
                '%' . $request->get('submission') . '%'
            );
        }

        // search
        if (!empty($request->get('search'))) {
            $parts = $parts->where(function ($query) {
                $query->where('parts.po_number', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.name', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.process_type', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.quantity', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.material', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.material_thickness', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.finish', 'like', '%' . $this->request->get('search') . '%')
                    ->orWhere('parts.used_in_weldment', 'like', '%' . $this->request->get('search') . '%');
            });
        }

        return view('parts.warehouse-index')->with([
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

        if (empty($part) || empty($request->get('field'))) {
            return response()->json([
                'success' => false,
                'message' => 'Part could not be updated'
            ]);
        }

        $field = $request->get('field');
        $part->$field = $request->get('value');
        $fieldAt = $field . '_at';
        $part->$fieldAt = $part->$field ? now() : null;

        switch ($field) {
            case 'part_ordered':
                $part->status = $part->$field ? 'supplier' : 'processing';
                break;
            case 'raw_part_received':
                $part->status = $part->$field ? 'treatment' : 'supplier';
                break;
            case 'completed_part_received':
                $part->status = $part->$field ? 'qc' : 'treatment';
                break;
            case 'qc_passed':
                $part->status = $part->$field ? 'assembly' : 'qc';
                break;
            default:
                break;
        }

        $part->save();
        
        return response()->json([
            'success'     => true,
            'part_id'     => $part->id,
            'status'      => Part::$statuses[$part->status],
            'field'       => $field,
            'stamp_field' => $fieldAt,
            'stamp_value' => !empty($part->$fieldAt) ? $part->$fieldAt->format('Y-m-d H:i:s') : '',
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

    /**
     * Autofill suppliers for all parts
     */
    public function autofillSuppliers(Request $request)
    {
        /**
         * all parts with no supplier and that's not ordered yet
         */
        $parts = Part::where('supplier_id', null)->where('part_ordered', false)->get();

        foreach ($parts as $part) {
            switch ($part->process_type) {
                case 'LC':
                case 'LCB':
                case 'LCM':
                case 'LCBM':
                case 'LCBW':
                    if (!empty($request->input('lc_supplier'))) {
                        $part->supplier_id = $request->input('lc_supplier');
                        $part->save();
                    }
                    break;
                case 'MCH':
                    if (!empty($request->input('part_supplier'))) {
                        $part->supplier_id = $request->input('part_supplier');
                        $part->save();
                    }
                    break;
                case 'TLC':
                case 'TLCM':
                    $part->supplier_id = Supplier::where('name', 'Schuurman Tube')->first()->id;
                    $part->save();
                    break;
                default:
                    break;
            }
        }

        return redirect()->back();
    }
}
