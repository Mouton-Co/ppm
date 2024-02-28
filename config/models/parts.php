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
        'process_type' => [
            'name'     => 'Process Type',
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
        'submission->submission_code' => [
            'name'     => 'Submission',
            'sortable' => true,
        ],
        'status' => [
            'name'     => 'Status',
            'sortable' => true,
            'format'   => \App\Models\Part::$statuses,
        ],
        'part_ordered_at' => [
            'name'     => 'Part Ordered At',
            'sortable' => true,
        ],
        'comment_procurement' => [
            'name'     => 'Procurement Comment',
            'editable' => true,
            'sortable' => true,
        ],
        'comment_warehouse' => [
            'name'     => 'Warehouse Comment',
            'editable' => true,
            'sortable' => true,
        ],
        'comment_logistics' => [
            'name'     => 'Logistics Comment',
            'editable' => true,
            'sortable' => true,
        ],
    ]
];
