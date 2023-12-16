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
        'total_parts',
        'supplier_id',
        'submission_id',
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

    /**
     * Get the submission that the order belongs to
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
