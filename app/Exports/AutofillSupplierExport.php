<?php

namespace App\Exports;

use App\Http\Controllers\AutofillSupplierController;
use App\Models\AutofillSupplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AutofillSupplierExport implements FromQuery, WithHeadings, WithMapping
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
        return (new AutofillSupplierController)->filter(AutofillSupplier::class, AutofillSupplier::query(), $this->request);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Text',
            'Supplier',
            'Created at',
            'Updated at',
            'Deleted at',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param  \App\Models\User  $datum
     */
    public function map($datum): array
    {
        return [
            $datum->id,
            $datum->text,
            $datum->supplier?->name,
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
        ];
    }
}
