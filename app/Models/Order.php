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
        'token',
        'due_date',
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
            'filterable_options' => 'custom',
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
        'created_at' => [
            'label' => 'Created at',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'updated_at' => [
            'label' => 'Updated at',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
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

    /**
     * Scope for all the orders that are due in five days
     */
    public function scopeDueInFiveDays($query)
    {
        return $query->whereDate('due_date', now()->addDays(5)->toDateString());
    }

    /**
     * Scope for all the orders that are due today
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', now()->toDateString());
    }

    /**
     * Get the status of the order
     */
    public static function getCustomStatusAttribute()
    {
        $statuses = self::STATUSES;
        array_unshift($statuses, 'All except ordered');

        $options = [];
        foreach ($statuses as $status) {
            $options[$status] = $status;
        }

        return $options;
    }

    /**
     * Get the due days attribute.
     */
    public function getDueDaysAttribute()
    {
        $days = 'N/A';

        if (! empty($this->due_date)) {
            $dueDate = new \DateTime($this->due_date);
            $today = new \DateTime();
            $interval = $today->diff($dueDate);

            if ($interval->invert) {
                $days = $interval->days * -1;
            } else {
                $days = $interval->days + 1;
            }
        }

        return $days;
    }

    /**
     * Create an array of combined parts for supplier emails.
     */
    public function getCombinedPartsAttribute(): array
    {
        $parts = [];

        foreach ($this->parts()->orderBy('stage')->orderBy('name')->get() as $part) {
            if (array_key_exists($part->name, $parts)) {
                $parts[$part->name]['quantity_ordered'] += $part->quantity_ordered;
            } else {
                $parts[$part->name] = [
                    'quantity_ordered' => $part->quantity_ordered,
                    'material' => $part->material,
                    'material_thickness' => $part->material_thickness,
                    'stage' => $part->stage,
                ];
            }
        }

        return $parts;
    }
}
