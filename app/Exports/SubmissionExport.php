<?php

namespace App\Exports;

use App\Http\Controllers\Design\SubmissionController;
use App\Models\Submission;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubmissionExport implements FromQuery, WithHeadings, WithMapping
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
        return (new SubmissionController)->filter(Submission::class, Submission::query(), $this->request)->where('submitted', 1);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Submission Code',
            'Assembly Name',
            'Machine Number',
            'Submission Type',
            'Current Unit Number',
            'Notes',
            'Created by',
            'Project ID',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param  \App\Models\Submission  $datum
     */
    public function map($datum): array
    {
        return [
            $datum->id,
            $datum->submission_code,
            $datum->assembly_name,
            $datum->machine_number,
            $datum->submission_type,
            $datum->current_unit_number,
            $datum->notes,
            $datum->user?->name ?? '',
            $datum->project_id,
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
        ];
    }
}
