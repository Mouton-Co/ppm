<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutofillSupplier extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'supplier_id'];

    /**
     * Get the supplier that owns the AutofillSupplier
     */
    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
