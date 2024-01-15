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
        'treatment_1_part_received',
        'treatment_1_part_received_at',
        'treatment_2_part_received',
        'treatment_2_part_received_at',
        'completed_part_received',
        'completed_part_received_at',
        'qc_passed',
        'qc_passed_at',
        'qc_failed',
        'qc_failed_at',
        'qc_failed_reason',
        'submission_id',
        'supplier_id',
        'treatment_1',
        'treatment_2',
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

    public function pdf()
    {
        return $this->files()->where('file_type', 'pdf')->first();
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
            case 'part_ordered':
                $enabled = $this->status == 'design' || $this->status == 'waiting_on_raw_part';
                break;
            case 'raw_part_received':
                $enabled = $this->status == 'waiting_on_raw_part' || $this->status == 'waiting_on_treatment_1';
                break;
            case 'treatment_1_part_received':
                $enabled = $this->status == 'waiting_on_treatment_1' || $this->status == 'waiting_on_treatment_2';
                break;
            case 'treatment_2_part_received':
                $enabled = $this->status == 'waiting_on_treatment_2' || $this->status == 'waiting_on_final_part';
                break;
            case 'completed_part_received':
                $enabled = $this->status == 'waiting_on_final_part' || $this->status == 'part_received';
                break;
            case 'qc_failed':
            case 'qc_passed':
                $enabled = true;
                break;
            default:
                $enabled = false;
        }

        return $enabled;
    }
}
