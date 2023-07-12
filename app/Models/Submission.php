<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
    ];
}
