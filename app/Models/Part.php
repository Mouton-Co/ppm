<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'treated_part_received',
        'treated_part_received_at',
        'submission_id',
        'supplier_id',
        'qc_failed',
        'qc_failed_at',
        'qc_failed_reason',
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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function checkboxEnabled($key)
    {
        $enabled = false;

        if ($this->status == 'design' && $key == 'part_ordered') {
            $enabled = true;
        }

        if (
            $this->status == 'waiting_on_parts' &&
            ($key == 'part_ordered' || $key == 'raw_part_received')
        ) {
            $enabled = true;
        }
        
        if (
            $this->status == 'waiting_on_treatment' &&
            ($key == 'raw_part_received' || $key == 'treated_part_received')
        ) {
            $enabled = true;
        }

        if ($this->status == 'part_received' && $key == 'treated_part_received') {
            $enabled = true;
        }

        if ($key == 'qc_failed') {
            $enabled = true;
        }

        return $enabled;
    }
}
