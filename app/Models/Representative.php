<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Representative extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_1',
        'phone_2',
        'supplier_id',
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
