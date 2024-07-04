<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'po_number',
        'notes',
        'status',
        'total_parts',
        'supplier_id',
        'submission_id',
    ];

    /**
     * The possible statuses for an order
     *
     * @var array<string, string>
     */
    public const STATUSES = [
        'processing' => 'Processing',
        'emailed' => 'Email Sent',
        'ordered' => 'Ordered',
    ];

    /**
     * The structure of the order
     *
     * @var array<string, string>
     */
    public static $structure = [
        'po_number' => [
            'label' => 'PO Number',
            'type' => 'text',
            'filterable' => true,
        ],
        'notes' => [
            'label' => 'Notes',
            'type' => 'text',
            'filterable' => true,
        ],
        'status' => [
            'label' => 'Status',
            'type' => 'dropdown',
            'filterable' => true,
            'filterable_options' => [
                'processing' => 'Processing',
                'emailed' => 'Email Sent',
                'ordered' => 'Ordered',
            ],
        ],
        'supplier_name' => [
            'label' => 'Supplier',
            'type' => 'dropdown',
            'filterable' => true,
            'relationship' => 'supplier.name',
            'relationship_model' => Supplier::class,
        ],
        'submission_submission_code' => [
            'label' => 'Submission',
            'type' => 'text',
            'filterable' => true,
            'relationship' => 'submission.submission_code',
            'relationship_model' => Submission::class,
        ],
    ];

    /**
     * Get the supplier that the order belongs to
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the submission that the order belongs to
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the parts in the order
     */
    public function parts()
    {
        return Part::where('po_number', $this->po_number);
    }
}
