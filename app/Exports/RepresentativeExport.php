<?php

namespace App\Exports;

use App\Http\Controllers\RepresentativeController;
use App\Models\Representative;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RepresentativeExport implements FromQuery, WithHeadings, WithMapping
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
        return (new RepresentativeController)->filter(Representative::class, Representative::query(), $this->request);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone 1',
            'Phone 2',
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
            $datum->name,
            $datum->email,
            $datum->phone_1,
            $datum->phone_2,
            $datum->supplier->name,
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
        ];
    }
}
