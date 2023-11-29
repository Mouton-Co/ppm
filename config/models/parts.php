<?php

return [
    'columns' => [
        'po_number' => [
            'name' => 'PO Number',
            'editable' => true,
        ],
        'part_ordered' => [
            'name' => 'Part Ordered',
            'editable' => true,
            'type' => 'boolean',
        ],
        'raw_part_received' => [
            'name' => 'Raw Part Received',
            'editable' => true,
            'type' => 'boolean',
        ],
        'treated_part_received' => [
            'name' => 'Treated Part Received',
            'editable' => true,
            'type' => 'boolean',
        ],
        'supplier->name' => [
            'name' => 'Supplier',
            'editable' => true,
            'type' => 'select',
            'options' => [
                'model'    => 'App\Models\Supplier',
                'value'    => 'id',
                'label'    => 'name',
                'nullable' => true,
            ],
        ],
        'submission->submission_code' => [
            'name' => 'Submission',
        ],
        'name' => [
            'name' => 'Name',
        ],
        'status' => [
            'name'     => 'Status',
            'format'  => [
                'design'               => 'Design',
                'waiting_on_parts'     => 'Waiting on Parts',
                'waiting_on_treatment' => 'Waiting on Treatment',
                'part_received'        => 'Part Received',
            ],
        ],
        'quantity' => [
            'name' => 'Quantity',
        ],
        'material' => [
            'name' => 'Material',
        ],
        'material_thickness' => [
            'name' => 'Material Thickness',
        ],
        'finish' => [
            'name' => 'Finish',
        ],
        'used_in_weldment' => [
            'name' => 'Used In Weldment',
        ],
        'process_type' => [
            'name' => 'Process Type',
        ],
        'part_ordered_at' => [
            'name' => 'Part Ordered At',
        ],
        'raw_part_received_at' => [
            'name' => 'Raw Part Received At',
        ],
        'treated_part_received_at' => [
            'name' => 'Treated Part Received At',
        ],
    ]
];
