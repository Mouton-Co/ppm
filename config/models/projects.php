<?php

return [
    'columns' => [
        'submission_id' => [
            'name' => 'Submission',
            'sortable' => true,
        ],
        'machine_nr' => [
            'name' => 'Machine Nr',
            'sortable' => true,
        ],
        'country' => [
            'name' => 'Country/Company',
            'sortable' => true,
        ],
        'coc' => [
            'name' => 'COC',
            'sortable' => true,
        ],
        'noticed_issue' => [
            'name' => 'Noticed Issue',
            'sortable' => true,
            'editable' => true,
        ],
        'proposed_solution' => [
            'name' => 'Proposed Solution',
            'sortable' => true,
            'editable' => true,
        ],
        'currently_responsible' => [
            'name' => 'Currently Responsible',
            'sortable' => true,
            'type' => 'responsibles',
            'editable' => true,
        ],
        'status' => [
            'name' => 'Status',
            'type' => 'select',
            'sortable' => true,
            'editable' => true,
            'options' => [
                'model' => 'App\Models\ProjectStatus',
                'value' => 'name',
                'label' => 'name',
            ],
        ],
        'issued_at' => [
            'name' => 'Issued At',
            'sortable' => true,
        ],
        'resolved_at' => [
            'name' => 'Resolved At',
            'sortable' => true,
        ],
        'related_pos' => [
            'name' => 'Related POs',
            'sortable' => true,
            'editable' => true,
        ],
        'waybill_nr' => [
            'name' => 'Waybill #',
            'sortable' => true,
            'editable' => true,
        ],
        'customer_comment' => [
            'name' => 'Customer Comment',
            'sortable' => true,
            'editable' => true,
        ],
        'commisioner_comment' => [
            'name' => 'Commisioner Comment',
            'sortable' => true,
            'editable' => true,
        ],
        'logistics_comment' => [
            'name' => 'Logistics Comment',
            'sortable' => true,
            'editable' => true,
        ],
    ],
];
