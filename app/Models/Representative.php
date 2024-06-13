<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Representative extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_1',
        'phone_2',
        'supplier_id',
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
        'name' => [
            'label' => 'Name',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'email' => [
            'label' => 'Email',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'phone_1' => [
            'label' => 'Phone 1',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'phone_2' => [
            'label' => 'Phone 2',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'supplier' => [
            'label' => 'Supplier',
            'type' => 'relationship',
            'sortable' => true,
            'filterable' => true,
            'relationship_field' => 'name',
            'relationship_model' => Supplier::class,
        ],
        'created_at' => [
            'label' => 'Created At',
            'type' => 'datetime',
            'sortable' => true,
            'filterable' => true,
        ],
        'updated_at' => [
            'label' => 'Updated At',
            'type' => 'datetime',
            'sortable' => true,
            'filterable' => true,
        ],
    ];

    public static $actions = [
        'create' => 'Create new',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
