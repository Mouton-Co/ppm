<?php

return [
    'columns' => [
        'po_number' => [
            'name'     => 'PO Number',
            'editable' => true,
            'sortable' => true,
        ],
        'part_ordered' => [
            'name'     => 'Part Ordered',
            'editable' => true,
            'type'     => 'boolean',
            'sortable' => false,
        ],
        'supplier->name' => [
            'name'     => 'Supplier',
            'editable' => true,
            'type'     => 'select',
            'sortable' => true,
            'options'  => [
                'model'    => 'App\Models\Supplier',
                'value'    => 'id',
                'label'    => 'name',
                'nullable' => true,
            ],
        ],
        'name' => [
            'name'     => 'Name',
            'sortable' => true,
        ],
        'quantity' => [
            'name'     => 'Quantity',
            'sortable' => true,
        ],
        'material' => [
            'name'     => 'Material',
            'sortable' => true,
        ],
        'material_thickness' => [
            'name'     => 'Material Thickness',
            'sortable' => true,
        ],
        'finish' => [
            'name'     => 'Finish',
            'sortable' => true,
        ],
        'used_in_weldment' => [
            'name'     => 'Used In Weldment',
            'sortable' => true,
        ],
        'process_type' => [
            'name'     => 'Process Type',
            'sortable' => true,
        ],
        'submission->submission_code' => [
            'name'     => 'Submission',
            'sortable' => true,
        ],
        'status' => [
            'name'     => 'Status',
            'sortable' => true,
            'format'   => [
                'design'               => 'Design',
                'waiting_on_parts'     => 'Waiting on Parts',
                'waiting_on_treatment' => 'Waiting on Treatment',
                'part_received'        => 'Part Received',
            ],
        ],
        'part_ordered_at' => [
            'name'     => 'Part Ordered At',
            'sortable' => true,
        ],
        'raw_part_received_at' => [
            'name'     => 'Raw Part Received At',
            'sortable' => true,
        ],
        'treated_part_received_at' => [
            'name'     => 'Treated Part Received At',
            'sortable' => true,
        ],
        'qc_failed' => [
            'name'     => 'QC Failed',
            'editable' => true,
            'type'     => 'boolean',
            'sortable' => false,
        ],
        'qc_failed_at' => [
            'name'     => 'QC Failed At',
            'sortable' => true,
        ],
        'qc_failed_reason' => [
            'name'     => 'QC Failed Reason',
            'editable' => true,
            'sortable' => true,
        ],
    ]
];
