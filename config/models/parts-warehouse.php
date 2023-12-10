<?php

return [
    'columns' => [
        'raw_part_received' => [
            'name'     => 'Raw Part Received',
            'editable' => true,
            'type'     => 'boolean',
            'sortable' => false,
        ],
        'treated_part_received' => [
            'name'     => 'Treated Part Received',
            'editable' => true,
            'type'     => 'boolean',
            'sortable' => false,
        ],
        'po_number' => [
            'name'     => 'PO Number',
            'sortable' => true,
        ],
        'supplier->name' => [
            'name'     => 'Supplier',
            'sortable' => true,
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
