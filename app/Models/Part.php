<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Part extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'quantity',
        'material',
        'material_thickness',
        'finish',
        'used_in_weldment',
        'process_type',
        'manufactured_or_purchased',
        'notes',
        'po_number',
        'status',
        'part_ordered',
        'part_ordered_at',
        'raw_part_received',
        'raw_part_received_at',
        'treatment_1_part_received',
        'treatment_1_part_received_at',
        'treatment_2_part_received',
        'treatment_2_part_received_at',
        'completed_part_received',
        'completed_part_received_at',
        'submission_id',
        'supplier_id',
        'qc_passed',
        'qc_passed_at',
        'qc_issue',
        'qc_issue_at',
        'qc_issue_reason',
        'treatment_1',
        'treatment_2',
        'comment_procurement',
        'comment_warehouse',
        'comment_logistics',
        'quantity_in_stock',
        'quantity_ordered',
        'treatment_1_supplier',
        'treatment_2_supplier',
        'qty_received',
    ];

    public static $statuses = [
        'design' => 'Design',
        'processing' => 'Processing',
        'email_sent' => 'Email Sent',
        'supplier' => 'Supplier',
        'treatment' => 'Treatment',
        'qc' => 'QC',
        'assembly' => 'Assembly',
    ];

    public static $markedAs = [
        'untick_all' => 'Untick All',
        'raw_part_received' => 'Raw Part Received',
        'treatment_1_part_received' => 'Treatment 1 Part Received',
        'treatment_2_part_received' => 'Treatment 2 Part Received',
        'completed_part_received' => 'Completed Part Received',
        'qc_passed' => 'QC Passed',
    ];

    /**
     * Procurment table structure.
     *
     * @var array
     */
    public static $procurementStructure = [
        'id' => [
            'label' => 'ID',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'po_number' => [
            'label' => 'PO #',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'part_ordered' => [
            'label' => 'Part Ordered',
            'type' => 'boolean',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.checkbox',
        ],
        'supplier_id' => [
            'label' => 'Supplier',
            'type' => 'relationship',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.select',
            'relationship_field' => 'name',
            'relationship_model' => Supplier::class,
        ],
        'name' => [
            'label' => 'Name',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'process_type' => [
            'label' => 'Process Type',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'quantity' => [
            'label' => 'Qty needed',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.number',
            'min' => 0,
        ],
        'quantity_in_stock' => [
            'label' => 'Qty in stock',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.number',
            'min' => 0,
        ],
        'quantity_ordered' => [
            'label' => 'Qty ordered',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.number',
            'min' => 0,
        ],
        'material' => [
            'label' => 'Material',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'material_thickness' => [
            'label' => 'Material Thickness',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'finish' => [
            'label' => 'Finish',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'used_in_weldment' => [
            'label' => 'Used in Weldment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'submission' => [
            'label' => 'Submission',
            'type' => 'relationship',
            'sortable' => true,
            'filterable' => true,
            'relationship_field' => 'submission_code',
            'relationship_model' => Submission::class,
            'component' => 'procurement.submission',
        ],
        'status' => [
            'label' => 'Status',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
                'design' => 'Design',
                'processing' => 'Processing',
                'email_sent' => 'Email Sent',
                'supplier' => 'Supplier',
                'treatment' => 'Treatment',
                'qc' => 'QC',
                'assembly' => 'Assembly',
            ],
            'casts' => [
                'design' => 'Design',
                'processing' => 'Processing',
                'email_sent' => 'Email Sent',
                'supplier' => 'Supplier',
                'treatment' => 'Treatment',
                'qc' => 'QC',
                'assembly' => 'Assembly',
            ],
        ],
        'coc' => [
            'label' => 'COC',
            'type' => 'text',
        ],
        'part_ordered_at' => [
            'label' => 'Part Ordered At',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'comment_procurement' => [
            'label' => 'Procurement Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'comment_warehouse' => [
            'label' => 'Warehouse Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'comment_logistics' => [
            'label' => 'Logistics Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
    ];

    /**
     * Warehouse table structure.
     *
     * @var array
     */
    public static $warehouseStructure = [
        'id' => [
            'label' => 'ID',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'lifecycle' => [
            'label' => 'Part Lifecycle',
            'type' => 'text',
            'component' => 'warehouse.lifecycle',
        ],
        'name' => [
            'label' => 'Name',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'treatment_1' => [
            'label' => 'Treatment 1',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
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
                'Matte black powder coated',
                'Other',
            ],
            'component' => 'editable.select',
        ],
        'treatment_1_supplier' => [
            'label' => 'Treatment 1 Supplier',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => 'custom',
            'component' => 'editable.select',
        ],
        'treatment_2' => [
            'label' => 'Treatment 2',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
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
                'Matte black powder coated',
                'Other',
            ],
            'component' => 'editable.select',
        ],
        'treatment_2_supplier' => [
            'label' => 'Treatment 2 Supplier',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => 'custom',
            'component' => 'editable.select',
        ],
        'po_number' => [
            'label' => 'PO #',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'supplier_id' => [
            'label' => 'Supplier',
            'type' => 'relationship',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.select',
            'relationship_field' => 'name',
            'relationship_model' => Supplier::class,
        ],
        'quantity' => [
            'label' => 'Qty needed',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'quantity_ordered' => [
            'label' => 'Qty ordered',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'qty_received' => [
            'label' => 'Qty received',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.number',
            'min' => 0,
        ],
        'material' => [
            'label' => 'Material',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'material_thickness' => [
            'label' => 'Material Thickness',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'finish' => [
            'label' => 'Finish',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'used_in_weldment' => [
            'label' => 'Used in Weldment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'process_type' => [
            'label' => 'Process Type',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'status' => [
            'label' => 'Status',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
                'design' => 'Design',
                'processing' => 'Processing',
                'email_sent' => 'Email Sent',
                'supplier' => 'Supplier',
                'treatment' => 'Treatment',
                'qc' => 'QC',
                'assembly' => 'Assembly',
            ],
            'casts' => [
                'design' => 'Design',
                'processing' => 'Processing',
                'email_sent' => 'Email Sent',
                'supplier' => 'Supplier',
                'treatment' => 'Treatment',
                'qc' => 'QC',
                'assembly' => 'Assembly',
            ],
        ],
        'coc' => [
            'label' => 'COC',
            'type' => 'text',
        ],
        'lifecycle_stamps' => [
            'label' => 'Part Lifecycle Stamps',
            'type' => 'text',
            'component' => 'warehouse.lifecycle-stamps',
        ],
        'qc_issue' => [
            'label' => 'QC Issue',
            'type' => 'boolean',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.checkbox',
        ],
        'qc_issue_at' => [
            'label' => 'QC Issue logged at',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'qc_issue_reason' => [
            'label' => 'QC Issue Reason',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'comment_procurement' => [
            'label' => 'Procurement Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'comment_warehouse' => [
            'label' => 'Warehouse Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'comment_logistics' => [
            'label' => 'Logistics Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get submission that the part belongs to
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get file for this part
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get supplier for this part
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the first pdf for the part
     */
    public function pdf()
    {
        return $this->files()->where('file_type', 'pdf')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */
    public function getCocAttribute()
    {
        return $this->submission->project->coc ?? 'N/A';
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function checkboxEnabled($key)
    {
        $enabled = false;

        switch ($key) {
            case 'raw_part_received':
                $enabled = $this->part_ordered && ! $this->treatment_1_part_received;
                break;
            case 'treatment_1_part_received':
                $enabled = $this->raw_part_received &&
                    ! $this->treatment_2_part_received &&
                    ! empty($this->treatment_1) &&
                    $this->treatment_1 != '-';
                break;
            case 'treatment_2_part_received':
                $enabled = $this->treatment_1_part_received &&
                    ! $this->completed_part_received &&
                    ! empty($this->treatment_2) &&
                    $this->treatment_2 != '-';
                break;
            case 'completed_part_received':
                $enabled = (
                    $this->treatment_2_part_received ||
                    $this->treatment_1_part_received ||
                    $this->raw_part_received
                ) && ! $this->qc_passed;
                break;
            case 'qc_passed':
                $enabled = $this->completed_part_received
                    && ! $this->qc_issue;
                break;
            case 'part_ordered':
            case 'qc_issue':
                $enabled = ! $this->qc_passed;
                break;
            default:
                $enabled = false;
        }

        return $enabled;
    }

    /**
     * Get the treatment_1_supplier attribute.
     *
     * @return array
     */
    public static function getCustomTreatment1SupplierAttribute(): array
    {
        return Supplier::orderBy('name')->pluck('name')->toArray();
    }

    /**
     * Get the treatment_2_supplier attribute.
     *
     * @return array
     */
    public static function getCustomTreatment2SupplierAttribute(): array
    {
        return Supplier::orderBy('name')->pluck('name')->toArray();
    }
}
