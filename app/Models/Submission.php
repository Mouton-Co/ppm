<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submission_code',
        'assembly_name',
        'machine_number',
        'submission_type',
        'current_unit_number',
        'notes',
        'submitted',
        'user_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the creator of the submission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Get attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Get value of the submission type key from config
     */
    public function getFormattedSubmissionTypeAttribute()
    {
        return config("dropdowns.submission_types.$this->submission_type");
    }

    /**
     * Get value of the unit number key from config
     */
    public function getFormattedUnitNumberAttribute()
    {
        return $this->current_unit_number.' - '.config("dropdowns.unit_numbers.$this->current_unit_number");
    }
}
