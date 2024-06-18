<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutofillSupplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['text', 'supplier_id'];

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
        'text' => [
            'label' => 'Text',
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
    ];

    /**
     * Get the supplier that owns the AutofillSupplier
     */
    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
