<?php

namespace App\Exports;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * The constructor.
     *
     * @var \Illuminate\Http\Request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * The query to be exported.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return (new OrderController)->filter(Order::class, Order::query(), $this->request);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'PO number',
            'Notes',
            'Status',
            'Total parts',
            'Supplier',
            'Submission',
            'Due date',
            'Created at',
            'Updated at',
            'Deleted at',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param  \App\Models\Order  $datum
     */
    public function map($datum): array
    {
        return [
            $datum->id,
            $datum->po_number,
            $datum->notes,
            $datum->status,
            $datum->total_parts,
            $datum->supplier?->name ?? '',
            $datum->submission?->submission_code ?? '',
            $datum->due_date,
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
        ];
    }
}
