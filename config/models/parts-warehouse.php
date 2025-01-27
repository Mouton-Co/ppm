<?php

$treatments = [
    'Anodize - Black',
    'Anodize - Blue',
    'Anodize - Natural',
    'Anodize - Red',
    'Bend',
    'Blacken',
    'Case Harden',
    'Edge Radius',
    'Electropolish',
    'Electroplate',
    'Machine',
    'Matte black powder coated',
    'Powder Coat - PPM Grey',
    'Powder Coat - Red',
    'Powder Coat - Structured White',
    'Powder Coat - Traffic Yellow',
    'Rubberize',
    'Sharpen',
    'Skim',
    'Straighten',
    'Strip',
    'Surface Harden',
    'Teflon Coat',
    'Tap/Drill',
    'Vacuum Harden',
    'Other',
];

return [
    'columns' => [
        'part_lifecycle' => [
            'name' => 'Part Lifecycle',
            'editable' => true,
            'type' => 'life-cycle',
            'sortable' => false,
            'options' => [
                'raw_part_received' => 'Raw Part Received',
                'treatment_1_part_dispatched' => 'Treatment 1 Part - Dispatched',
                'treatment_1_part_received' => 'Treatment 1 Part - Received',
                'treatment_2_part_dispatched' => 'Treatment 2 Part - Dispatched',
                'treatment_2_part_received' => 'Treatment 2 Part - Received',
                'completed_part_received' => 'Completed Part Received',
                'qc_passed' => 'QC Passed',
            ],
        ],
        'name' => [
            'name' => 'Name',
            'sortable' => true,
        ],
        'treatment_1' => [
            'name' => 'Treatment 1',
            'editable' => true,
            'type' => 'select',
            'sortable' => true,
            'options' => $treatments,
        ],
        'treatment_1_supplier' => [
            'name' => 'Treatment 1 Supplier',
            'editable' => true,
            'type' => 'select',
            'sortable' => true,
            'options' => '\App\Models\Supplier',
        ],
        'treatment_2' => [
            'name' => 'Treatment 2',
            'editable' => true,
            'type' => 'select',
            'sortable' => true,
            'options' => $treatments,
        ],
        'treatment_2_supplier' => [
            'name' => 'Treatment 2 Supplier',
            'editable' => true,
            'type' => 'select',
            'sortable' => true,
            'options' => '\App\Models\Supplier',
        ],
        'po_number' => [
            'name' => 'PO Number',
            'sortable' => true,
        ],
        'supplier->name' => [
            'name' => 'Supplier',
            'sortable' => true,
        ],
        'quantity' => [
            'name' => 'Qty Needed',
            'sortable' => true,
        ],
        'quantity_ordered' => [
            'name' => 'Qty Ordered',
            'sortable' => true,
        ],
        'qty_received' => [
            'name' => 'Qty Received',
            'editable' => true,
            'sortable' => true,
            'type' => 'integer',
            'min' => 0,
        ],
        'material' => [
            'name' => 'Material',
            'sortable' => true,
        ],
        'material_thickness' => [
            'name' => 'Material Thickness',
            'sortable' => true,
        ],
        'finish' => [
            'name' => 'Finish',
            'sortable' => true,
        ],
        'used_in_weldment' => [
            'name' => 'Used In Weldment',
            'sortable' => true,
        ],
        'process_type' => [
            'name' => 'Process Type',
            'sortable' => true,
        ],
        'status' => [
            'name' => 'Status',
            'sortable' => true,
            'format' => \App\Models\Part::$statuses,
        ],
        'coc' => [
            'name' => 'COC',
            'sortable' => false,
        ],
        'part_lifecycle_stamps' => [
            'name' => 'Part Lifecycle Stamps',
            'sortable' => false,
            'type' => 'life-cycle-stamps',
            'options' => [
                'raw_part_received_at' => 'Raw Part Received At',
                'treatment_1_part_dispatched_at' => 'Treatment 1 Dispatched At',
                'treatment_1_part_received_at' => 'Treatment 1 Received At',
                'treatment_2_part_dispatched_at' => 'Treatment 2 Dispatched At',
                'treatment_2_part_received_at' => 'Treatment 2 Received At',
                'completed_part_received_at' => 'Completed Part Received At',
                'qc_passed_at' => 'QC Passed At',
            ],
        ],
        'qc_issue' => [
            'name' => 'QC Issue',
            'editable' => true,
            'type' => 'boolean',
            'sortable' => false,
        ],
        'qc_issue_at' => [
            'name' => 'QC Issue logged at',
            'sortable' => true,
        ],
        'qc_issue_reason' => [
            'name' => 'QC Issue Reason',
            'editable' => true,
            'sortable' => true,
        ],
        'comment_procurement' => [
            'name' => 'Procurement Comment',
            'editable' => true,
            'sortable' => true,
        ],
        'comment_warehouse' => [
            'name' => 'Warehouse Comment',
            'editable' => true,
            'sortable' => true,
        ],
        'comment_logistics' => [
            'name' => 'Logistics Comment',
            'editable' => true,
            'sortable' => true,
        ],
    ],
];
