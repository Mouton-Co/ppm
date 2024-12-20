<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'process_type',
        'required_files',
    ];

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
        'process_type' => [
            'label' => 'Process Type',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'required_files' => [
            'label' => 'Required Files',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
                'pdf' => 'PDF',
                'dwg' => 'DWG',
                'step' => 'STEP',
            ],
            'component' => 'process-type.required-files',
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

    public static $actions = [
        'create' => 'Create new',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'trash' => 'Trash',
    ];

    /**
     * Checks if the field is disabled in the edit/create form.
     */
    public function isDisabled(string $field): bool
    {
        $disabled = false;

        if (str_contains($this->required_files, '/')) {
            foreach (explode(',', $this->required_files) as $requiredFile) {
                if (
                    str_contains($requiredFile, '/') &&
                    str_replace('OR', '/', strtoupper($field)) != strtoupper($requiredFile)
                ) {
                    $disabled = str_contains(strtoupper($field), explode('/', $requiredFile)[0]) ||
                        str_contains(strtoupper($field), explode('/', $requiredFile)[1]);
                }
            }
        }

        return $disabled;
    }
}
