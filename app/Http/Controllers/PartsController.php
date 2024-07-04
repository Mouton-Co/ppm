<?php

namespace App\Http\Controllers;

use App\Exports\BomExcel;
use App\Http\Helpers\UploadFilesHelper;
use App\Http\Requests\Parts\IndexRequest;
use App\Http\Requests\Parts\MarkAsRequest;
use App\Http\Requests\Parts\UpdateCheckboxRequest;
use App\Http\Requests\Parts\UpdateRequest;
use App\Http\Services\PartService;
use App\Models\AutofillSupplier;
use App\Models\Part;
use App\Models\Submission;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PartsController extends Controller
{
    public $request;

    protected $timestamp = 'Y-m-d H:i:s';

    /**
     * Store parts for the given submission
     */
    public function storeParts(Submission $submission)
    {
        $uploadFilesHelper = new UploadFilesHelper();
        $excel = $submission->excel_sheet;
        $matrix = Excel::toArray(new BomExcel, $excel)[0];
        $matrix = $uploadFilesHelper->cleanMatrix($matrix);

        if (! empty($matrix) && ! empty($matrix['Item Number'])) {
            for ($i = 0; $i < count($matrix['Item Number']); $i++) {
                $part = new Part();
                $part->name = $matrix['File Name'][$i];
                $part->quantity = $matrix['Quantity'][$i];
                $part->material = $matrix['Material'][$i];
                $part->material_thickness = $matrix['Material Thickness'][$i];
                $part->finish = $matrix['Finish'][$i];
                $part->used_in_weldment = $matrix['Used In Weldment'][$i];
                $part->process_type = $matrix['Process Type'][$i];
                $part->manufactured_or_purchased = $matrix['Manufactured or Purchased'][$i];
                $part->notes = $matrix['Notes'][$i];
                $part->submission_id = $submission->id;

                if (! is_numeric($part->quantity)) {
                    $part->quantity = 0;
                }
                $part->quantity_in_stock = 0;
                $part->quantity_ordered = $part->quantity;
                $part->qty_received = 0;

                $part->save();

                $fileController = new FileController();
                $fileController->storeFiles($part);
            }
        }
    }

    /**
     * View an index of all parts
     */
    public function index(Request $request)
    {
        $this->checkTableConfigurations('procurement', Part::class, Part::$procurementStructure);
        $parts = $this->filter(Part::class, Part::query(), $request, Part::$procurementStructure)->paginate(15);

        if ($parts->currentPage() > 1 && $parts->lastPage() < $parts->currentPage()) {
            return redirect()->route('parts.procurement.index', array_merge(['page' => $parts->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Procurement',
            'table' => 'procurement',
            'indexRoute' => 'parts.procurement.index',
            'data' => $parts,
            'model' => Part::class,
            'structure' => Part::$procurementStructure,
            'slot' => 'components.table.procurement.button-row',
        ]);
    }

    /**
     * Index table for warehouse table
     */
    public function warehouseIndex(Request $request)
    {
        $this->checkTableConfigurations('warehouse', Part::class, Part::$warehouseStructure);
        $parts = $this->filter(Part::class, Part::query(), $request, Part::$warehouseStructure)->paginate(15);

        if ($parts->currentPage() > 1 && $parts->lastPage() < $parts->currentPage()) {
            return redirect()->route('parts.warehouse.index', array_merge(['page' => $parts->lastPage()], $request->except(['page'])));
        }

        return view('generic.index')->with([
            'heading' => 'Warehouse',
            'table' => 'warehouse',
            'indexRoute' => 'parts.warehouse.index',
            'data' => $parts,
            'model' => Part::class,
            'structure' => Part::$warehouseStructure,
            'slot' => 'components.table.warehouse.button-row',
        ]);
    }

    /**
     * Update a part
     */
    public function update(UpdateRequest $request)
    {
        $part = Part::find($request->get('id'));

        if (! empty($request->get('field'))) {
            $partService = new PartService($part);
            $field = $request->get('field');

            if (str_contains($field, '->')) {
                $field = explode('->', $field)['0'].'_id';
            }

            $part->$field = $request->get('value');
            $qtyUpdated = $partService->updateQuantities($field, $request->get('value'));
            $part->save();

            return response()->json([
                'success' => true,
                'message' => 'Part updated successfully',
                'qty_updated' => $qtyUpdated,
                'quantity' => $part->quantity,
                'quantity_in_stock' => $part->quantity_in_stock,
                'quantity_ordered' => $part->quantity_ordered,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Part could not be updated',
        ]);
    }

    /**
     * Update checkbox ajax
     */
    public function updateCheckbox(UpdateCheckboxRequest $request)
    {
        $part = Part::find($request->get('id'));

        if (empty($part)) {
            return response()->json([
                'success' => false,
                'message' => 'Part not found',
            ]);
        }

        $field = $request->get('field');
        $part->$field = $request->get('value');
        $fieldAt = $field.'_at';
        $part->$fieldAt = $part->$field ? now() : null;

        switch ($field) {
            case 'part_ordered':
                $part->status = $part->$field ? 'supplier' : 'processing';
                break;
            case 'raw_part_received':
                $part->status = $part->$field ? 'treatment' : 'supplier';
                $part->qty_received = $part->quantity_ordered;
                break;
            case 'completed_part_received':
                $part->status = $part->$field ? 'qc' : 'treatment';
                if ($part->$field) {
                    $part->treatment_1_part_received = 1;
                    $part->treatment_2_part_received = 1;
                } else {
                    $part->treatment_1_part_received = $part->treatment_1_part_received_at ? 1 : 0;
                    $part->treatment_2_part_received = $part->treatment_2_part_received_at ? 1 : 0;
                }
                break;
            case 'qc_passed':
                $part->status = $part->$field ? 'assembly' : 'qc';
                break;
            default:
                break;
        }

        $part->save();

        return response()->json([
            'success' => true,
            'part_id' => $part->id,
            'qty_received' => $part->qty_received,
            'status' => $field == 'qc_issue' && $part->$field ?
                'QC Issue' :
                Part::$statuses[$part->status],
            'checkboxes' => [
                'raw_part_received' => [
                    'checked' => $part->raw_part_received,
                    'enabled' => $part->checkboxEnabled('raw_part_received'),
                    'at' => ! empty($part->raw_part_received_at) ?
                        Carbon::parse($part->raw_part_received_at)->format('Y-m-d H:i:s') :
                        '',
                ],
                'treatment_1_part_received' => [
                    'checked' => $part->treatment_1_part_received,
                    'enabled' => $part->checkboxEnabled('treatment_1_part_received'),
                    'at' => ! empty($part->treatment_1_part_received_at) ?
                        Carbon::parse($part->treatment_1_part_received_at)->format('Y-m-d H:i:s') :
                        '',
                ],
                'treatment_2_part_received' => [
                    'checked' => $part->treatment_2_part_received,
                    'enabled' => $part->checkboxEnabled('treatment_2_part_received'),
                    'at' => ! empty($part->treatment_2_part_received_at) ?
                        Carbon::parse($part->treatment_2_part_received_at)->format('Y-m-d H:i:s') :
                        '',
                ],
                'completed_part_received' => [
                    'checked' => $part->completed_part_received,
                    'enabled' => $part->checkboxEnabled('completed_part_received'),
                    'at' => ! empty($part->completed_part_received_at) ?
                        Carbon::parse($part->completed_part_received_at)->format('Y-m-d H:i:s') :
                        '',
                ],
                'qc_passed' => [
                    'checked' => $part->qc_passed,
                    'enabled' => $part->checkboxEnabled('qc_passed'),
                    'at' => ! empty($part->qc_passed_at) ?
                        Carbon::parse($part->qc_passed_at)->format('Y-m-d H:i:s') :
                        '',
                ],
                'qc_issue' => [
                    'checked' => $part->qc_issue,
                    'enabled' => true,
                    'at' => ! empty($part->qc_issue_at) ?
                        Carbon::parse($part->qc_issue_at)->format('Y-m-d H:i:s') :
                        '-',
                ],
            ],
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
            $poPrefix = str_pad($parts[0]->submission->machine_number, 2, '0', STR_PAD_LEFT).
                '-'.$parts[0]->submission->current_unit_number.'-';
            $latestPo = Part::where('po_number', 'like', $poPrefix.'%')->orderBy('po_number', 'desc')->first();
            $number = ! empty($latestPo) ? (int) explode('-', $latestPo->po_number)[2] + 1 : 1;
            $poNumber = $poPrefix.str_pad($number, 3, '0', STR_PAD_LEFT);

            foreach ($suppliers as $parts) {
                foreach ($parts as $part) {
                    $part->po_number = $poNumber;
                    $part->save();
                }

                // increment PO number
                $number++;
                $poNumber = $poPrefix.str_pad($number, 3, '0', STR_PAD_LEFT);
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
         * run through autofill suppliers table first and autofill them
         */
        foreach (AutofillSupplier::all() as $autofillSupplier) {
            $parts = Part::where('supplier_id', null)
                ->where('name', 'like', '%'.$autofillSupplier->text.'%')
                ->where('part_ordered', false)
                ->get();

            foreach ($parts as $part) {
                $part->supplier_id = $autofillSupplier->supplier_id;
                $part->save();
            }
        }

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
                    if (! empty($request->input('lc_supplier'))) {
                        $part->supplier_id = $request->input('lc_supplier');
                        $part->save();
                    }
                    break;
                case 'MCH':
                    if (! empty($request->input('part_supplier'))) {
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

    /**
     * Mark parts as
     */
    public function markAs(MarkAsRequest $request)
    {
        $parts = Part::where('po_number', $request->get('po_number'))->get();

        if ($parts->isEmpty()) {
            return redirect()->back()->withErrors('No parts found with PO Number '.$request->get('po_number'));
        }

        foreach ($parts as $part) {
            if ($request->get('mark_as') == 'untick_all') {
                $part->raw_part_received = false;
                $part->treatment_1_part_received = false;
                $part->treatment_2_part_received = false;
                $part->completed_part_received = false;
                $part->qc_passed = false;

                $part->raw_part_received_at = null;
                $part->treatment_1_part_received_at = null;
                $part->treatment_2_part_received_at = null;
                $part->completed_part_received_at = null;
                $part->qc_passed_at = null;

                $part->status = 'supplier';
            } elseif ($request->get('mark_as') == 'raw_part_received') {
                $part->raw_part_received = true;
                $part->raw_part_received_at = now();
                $part->status = 'treatment';

                $part->treatment_1_part_received = false;
                $part->treatment_2_part_received = false;
                $part->completed_part_received = false;
                $part->qc_passed = false;

                $part->treatment_1_part_received_at = null;
                $part->treatment_2_part_received_at = null;
                $part->completed_part_received_at = null;
                $part->qc_passed_at = null;
            } elseif ($request->get('mark_as') == 'treatment_1_part_received') {
                if (! $part->raw_part_received) {
                    $part->raw_part_received = true;
                    $part->raw_part_received_at = now();
                }

                $part->treatment_1_part_received = true;
                $part->treatment_1_part_received_at = now();
                $part->status = 'treatment';

                $part->treatment_2_part_received = false;
                $part->completed_part_received = false;
                $part->qc_passed = false;

                $part->treatment_2_part_received_at = null;
                $part->completed_part_received_at = null;
                $part->qc_passed_at = null;
            } elseif ($request->get('mark_as') == 'treatment_2_part_received') {
                if (! $part->raw_part_received) {
                    $part->raw_part_received = true;
                    $part->raw_part_received_at = now();
                }

                if (! $part->treatment_1_part_received) {
                    $part->treatment_1_part_received = true;
                    $part->treatment_1_part_received_at = now();
                }

                $part->treatment_2_part_received = true;
                $part->treatment_2_part_received_at = now();
                $part->status = 'treatment';

                $part->completed_part_received = false;
                $part->qc_passed = false;

                $part->completed_part_received_at = null;
                $part->qc_passed_at = null;
            } elseif ($request->get('mark_as') == 'completed_part_received') {
                if (! $part->raw_part_received) {
                    $part->raw_part_received = true;
                    $part->raw_part_received_at = now();
                }

                if (! $part->treatment_1_part_received) {
                    $part->treatment_1_part_received = true;
                }

                if (! $part->treatment_2_part_received) {
                    $part->treatment_2_part_received = true;
                }

                $part->completed_part_received = true;
                $part->completed_part_received_at = now();
                $part->status = 'qc';

                $part->qc_passed = false;
                $part->qc_passed_at = null;
            } elseif ($request->get('mark_as') == 'qc_passed') {
                if (! $part->raw_part_received) {
                    $part->raw_part_received = true;
                    $part->raw_part_received_at = now();
                }

                if (! $part->treatment_1_part_received) {
                    $part->treatment_1_part_received = true;
                }

                if (! $part->treatment_2_part_received) {
                    $part->treatment_2_part_received = true;
                }

                if (! $part->completed_part_received) {
                    $part->completed_part_received = true;
                    $part->completed_part_received_at = now();
                }

                $part->qc_passed = true;
                $part->qc_passed_at = now();
                $part->status = 'assembly';
            }
            $part->save();
        }

        return redirect()->back()->withSuccess('Parts marked as '.Part::$markedAs[$request->get('mark_as')]);
    }
}
