<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

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
        'project_id',
    ];

    /**
     * The submission types
     */
    const ADDITIONAL_PROJECT = 0;

    const ADDITION = 1;

    const NEW_BOM = 2;

    const REPLACEMENT = 3;

    const MODIFICATION = 4;

    const WORKSHOP = 5;

    /*
    |--------------------------------------------------------------------------
    | Index table properties
    |--------------------------------------------------------------------------
    */
    public static $structure = [
        'id' => [
            'label' => 'ID',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'submission_code' => [
            'label' => 'Submission Code',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'assembly_name' => [
            'label' => 'Assembly Name',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'machine_number' => [
            'label' => 'Machine Number',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'submission_type' => [
            'label' => 'Submission Type',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
                'Additional Project',
                'Addition',
                'New BOM',
                'Replacement',
                'Modification',
                'Workshop',
            ],
            'casts' => [
                0 => 'Additional Project',
                1 => 'Addition',
                2 => 'New BOM',
                3 => 'Replacement',
                4 => 'Modification',
                5 => 'Workshop',
            ],
        ],
        'current_unit_number' => [
            'label' => 'Unit Number',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
                '00 - Common Parts',
                '01 - Main Frame',
                '02 - Unwind',
                '03 - Tapping Unit',
                '04 - Gland Unit',
                '05 - Side Seal Unit',
                '06 - Punch Unit',
                '07 - Cross Seal 1',
                '08 - Cross Seal 2',
                '09 - Cross Cool',
                '10 - Delta Seal',
                '11 - Deliver Table',
                '12 - Gland Unit Hopper/Bowl',
                '13 - Tapping Unit Hopper/Bowl',
                '14 - Ancillary Units',
                '15 - Perforator',
                '16 - Gusset Seal',
                '17 - Custom Unit 2',
                '18 - Gusset Unwind',
                '19 - 3 Point Seal',
                '20 - Handle Punch',
            ],
        ],
        'notes' => [
            'label' => 'Notes',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'user_name' => [
            'label' => 'Submitted by',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'relationship' => 'user.name',
            'relationship_model' => User::class,
        ],
        'project_coc' => [
            'label' => 'Project COC',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'relationship' => 'project.coc',
            'relationship_model' => Project::class,
        ],
        'created_at' => [
            'label' => 'Created at',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
    ];

    public static $actions = [
        'create' => 'Create new',
        'show' => 'View',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'trash' => 'Trash',
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

    /**
     * Get the project for this submission
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the previous submissions for the same machine and unit number
     */
    public function previousSubmissions(): \Illuminate\Database\Eloquent\Builder
    {
        return Submission::where('created_at', '<', $this->created_at)
            ->where('machine_number', $this->machine_number)
            ->where('current_unit_number', $this->current_unit_number)
            ->where('submitted', 1)
            ->orderBy('created_at', 'desc');
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
     * Gets the permanent excel sheet for the submission.
     *
     * @return $excelSheet The excel sheet for the submission
     */
    public function getExcelSheetAttribute()
    {
        $files = Storage::disk('local')->files('files/temp/'.$this->submission_code);

        foreach ($files as $fileName) {
            if (str_contains(strtolower($fileName), '.xlsx')) {
                return $fileName;
            }
        }

        return '';
    }

    /**
     * Gets a list of permanent files for the given submission
     *
     * @param  Submission  $submission
     * @return array
     */
    public function getFilesAttribute()
    {
        return Storage::disk('local')->files('files/temp/'.$this->submission_code);
    }
}
