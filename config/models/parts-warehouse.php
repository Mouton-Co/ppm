<?php

$treatments = [
    'Anodize - Natural',
    'Anodize - Black',
    'Anodize - Red',
    'Anodize - Blue',
    'Blacken',
    'Powder Coat - Structured White',
    'Powder Coat - PPM Grey',
    'Powder Coat - Traffic Yellow',
    'Electropolish',
    'Electroplate',
    'Case Harden',
    'Vacuum Harden',
    'Surface Harden',
    'Teflon Coat',
    'Sharpen',
    'Machine',
    'Tap/Drill',
    'Bend',
    'Straighten',
    'Rubberize',
    'Skim',
    'Edge Radius',
    'Strip',
    'Other',
];

return [
    'columns' => [
        'part_lifecycle' => [
            'name'     => 'Part Lifecycle',
            'editable' => true,
            'type'     => 'life-cycle',
            'sortable' => false,
            'options'  => [
                'raw_part_received'         => 'Raw Part Received',
                'treatment_1_part_received' => 'Treatment 1 Part Received',
                'treatment_2_part_received' => 'Treatment 2 Part Received',
                'completed_part_received'   => 'Completed Part Received',
            ],
        ],
        'name' => [
            'name'     => 'Name',
            'sortable' => true,
        ],
        'treatment_1' => [
            'name'     => 'Treatment 1',
            'editable' => true,
            'type'     => 'select',
            'sortable' => true,
            'options'  => $treatments,
        ],
        'treatment_2' => [
            'name'     => 'Treatment 2',
            'editable' => true,
            'type'     => 'select',
            'sortable' => true,
            'options'  => $treatments,
        ],
        'po_number' => [
            'name'     => 'PO Number',
            'sortable' => true,
        ],
        'supplier->name' => [
            'name'     => 'Supplier',
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
        'status' => [
            'name'     => 'Status',
            'sortable' => true,
            'format'   => [
                'design'                 => 'Design',
                'waiting_on_raw_part'    => 'Waiting on Raw Part',
                'waiting_on_treatment_1' => 'Waiting on Treatment 1',
                'waiting_on_treatment_2' => 'Waiting on Treatment 2',
                'waiting_on_final_part'  => 'Waiting on Final Part',
                'part_received'          => 'Part Received',
            ],
        ],
        'part_lifecycle_stamps' => [
            'name'     => 'Part Lifecycle Stamps',
            'sortable' => false,
            'type'     => 'life-cycle-stamps',
            'options'  => [
                'raw_part_received_at'         => 'Raw Part Received At',
                'treatment_1_part_received_at' => 'Treatment 1 Received At',
                'treatment_2_part_received_at' => 'Treatment 2 Received At',
                'completed_part_received_at'   => 'Completed Part Received At',
            ],
        ],
        'qc_passed' => [
            'name'     => 'QC Passed',
            'editable' => true,
            'type'     => 'boolean',
            'sortable' => false,
        ],
        'qc_passed_at' => [
            'name'     => 'QC Passed At',
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
