<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'average_lead_time',
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
            'filterable_type' => 'text',
        ],
        'name' => [
            'label' => 'Name',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'text',
        ],
        'average_lead_time' => [
            'label' => 'Average Lead Time',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                '1' => '1 day',
                '2' => '2 days',
                '3' => '3 days',
                '4' => '4 days',
                '5' => '5 days',
                '6' => '6 days',
                '7' => '7 days',
                '8' => '8 days',
                '9' => '9 days',
                '10' => '10 days',
            ],
        ],
        'created_at' => [
            'label' => 'Created at',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'text',
        ],
        'updated_at' => [
            'label' => 'Updated at',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'text',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function representatives(): HasMany
    {
        return $this->hasMany(Representative::class);
    }
}
