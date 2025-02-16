<?php

namespace App\Exports;

use App\Http\Controllers\PartsController;
use App\Models\Part;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PartExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * The structure of the export.
     */
    protected array $structure;

    /**
     * The constructor.
     *
     * @var \Illuminate\Http\Request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->structure = $request->get('slug') == 'procurement' ? Part::$procurementStructure : Part::$warehouseStructure;
    }

    /**
     * The query to be exported.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return (new PartsController)->filter(Part::class, Part::query(), $this->request, $this->structure);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Quantity',
            'Due Date',
            'Material',
            'Material Thickness',
            'Finish',
            'Used in Weldment',
            'Manufactured/Processed',
            'Notes',
            'PO Number',
            'Status',
            'Part Ordered',
            'Part Ordered At',
            'Raw part received',
            'Raw part received At',
            'Treatment 1 Part Received',
            'Treatment 1 Part Received At',
            'Treatment 2 Part Received',
            'Treatment 2 Part Received At',
            'Completed Part Received',
            'Completed Part Received At',
            'Submission',
            'Supplier',
            'QC Passed',
            'QC Passed At',
            'QC Issue',
            'QC Issue At',
            'QC Issue Reason',
            'Treatment 1',
            'Treatment 1 Supplier',
            'Treatment 2',
            'Treatment 2 Supplier',
            'Comment procurement',
            'Comment warehouse',
            'Comment logistics',
            'Quantity in stock',
            'Quantity ordered',
            'Quanity received',
            'Created at',
            'Updated at',
            'Deleted at',
            'Stage',
            'Job Card',
            'Replace by',
            'Treatement 1 Part Dispatched',
            'Treatement 1 Part Dispatched At',
            'Treatement 2 Part Dispatched',
            'Treatement 2 Part Dispatched At',
            'QC by',
            'Redundant',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param  \App\Models\Part  $datum
     */
    public function map($datum): array
    {
        return [
            $datum->id,
            $datum->name,
            $datum->quantity,
            $datum->due_date,
            $datum->material,
            $datum->material_thickness,
            $datum->finish,
            $datum->used_in_weldment,
            $datum->manufactured_processed,
            $datum->notes,
            $datum->po_number,
            $datum->status,
            $datum->part_ordered ? 'Yes' : 'No',
            $datum->part_ordered_at,
            $datum->raw_part_received ? 'Yes' : 'No',
            $datum->raw_part_received_at,
            $datum->treatment_1_part_received ? 'Yes' : 'No',
            $datum->treatment_1_part_received_at,
            $datum->treatment_2_part_received ? 'Yes' : 'No',
            $datum->treatment_2_part_received_at,
            $datum->completed_part_received ? 'Yes' : 'No',
            $datum->completed_part_received_at,
            $datum->submission?->submission_code ?? '',
            $datum->supplier?->name ?? '',
            $datum->qc_passed ? 'Yes' : 'No',
            $datum->qc_passed_at,
            $datum->qc_issue ? 'Yes' : 'No',
            $datum->qc_issue_at,
            $datum->qc_issue_reason,
            $datum->treatment_1,
            $datum->treatment_1_supplier,
            $datum->treatment_2,
            $datum->treatment_2_supplier,
            $datum->comment_procurement,
            $datum->comment_warehouse,
            $datum->comment_logistics,
            $datum->quantity_in_stock,
            $datum->quantity_ordered,
            $datum->qty_received,
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
            $datum->stage,
            $datum->job_card,
            $datum->replaced_by_submission,
            $datum->treatment_1_part_dispatched ? 'Yes' : 'No',
            $datum->treatment_1_part_dispatched_at,
            $datum->treatment_2_part_dispatched ? 'Yes' : 'No',
            $datum->treatment_2_part_dispatched_at,
            $datum->qc_by,
            $datum->redundant ? 'Yes' : 'No',
        ];
    }
}
