<?php

namespace App\Exports;

use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectExport implements FromQuery, WithHeadings, WithMapping
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
        return (new ProjectController)->filter(Project::class, Project::query(), $this->request);
    }

    /**
     * The headings to be used in the exported file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Machine #',
            'Country',
            'CoC',
            'Notices Issue',
            'Proposed Solution',
            'Currently Responsible',
            'Status',
            'Resolved At',
            'Related POs',
            'Waybill #',
            'Customer Comment',
            'Commissioner Comment',
            'Logistics Comment',
            'Linked Submission',
            'Created By',
            'Created At',
            'Updated At',
            'Deleted At',
            'Costing',
            'Internal',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param  \App\Models\Project  $datum
     */
    public function map($datum): array
    {
        return [
            $datum->id,
            $datum->machine_nr,
            $datum->country,
            $datum->coc,
            $datum->notices_issue,
            $datum->proposed_solution,
            $datum->currently_responsible,
            $datum->status,
            $datum->resolved_at,
            $datum->related_pos,
            $datum->waybill_number,
            $datum->customer_comment,
            $datum->commissioner_comment,
            $datum->logistics_comment,
            $datum->submission?->submission_code ?? '',
            $datum->user?->name ?? '',
            $datum->created_at,
            $datum->updated_at,
            $datum->deleted_at,
            $datum->costing,
            $datum->internal ? 'Yes' : 'No',
        ];
    }
}
