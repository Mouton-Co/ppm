<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectResponsible extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

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
        'name' => [
            'label' => 'Name',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'created_at' => [
            'label' => 'Created At',
            'type' => 'date',
            'sortable' => true,
            'filterable' => true,
        ],
        'updated_at' => [
            'label' => 'Updated At',
            'type' => 'date',
            'sortable' => true,
            'filterable' => true,
        ],
    ];

    public static $actions = [
        'create' => 'Create new',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ];
}
