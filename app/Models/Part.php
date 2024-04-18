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
}
