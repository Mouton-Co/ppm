<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'notes',
        'status',
        'submission_code',
        'supplier_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get the supplier that the order belongs to
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
