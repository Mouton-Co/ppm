<?php

namespace App\Exports;

use App\Http\Controllers\ProcessTypeController;
use App\Models\ProcessType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProcessTypeExport implements FromQuery, WithHeadings, WithMapping
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
        return (new ProcessTypeController)->filter(ProcessType::class, ProcessType::query(), $this->request);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Process Type',
            'Required Files',
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
            $datum->process_type,
            $datum->required_files,
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
        ];
    }
}
