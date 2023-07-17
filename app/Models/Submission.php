<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get all the parts for this submission
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
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

    /**
     * Gets the permanent excel sheet for the submission
     *
     * @return $excelSheet The excel sheet for the submission
     */
    public function getExcelSheetAttribute()
    {
        $files = Storage::disk('local')->files('files/' . $this->submission_code);
        
        foreach ($files as $fileName) {
            if (str_contains(strtolower($fileName), '.xlsx')) {
                return $fileName;
            }
        }

        return '';
    }

    /**
     * Gets a list of permanent files for the given submission
     * @param Submission $submission
     * @return array
     */
    public function getFilesAttribute()
    {
        return Storage::disk('local')->files('files/' . $this->submission_code);
    }
}
