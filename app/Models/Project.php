<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_nr',
        'country',
        'coc',
        'noticed_issue',
        'proposed_solution',
        'currently_responsible',
        'status',
        'resolved_at',
        'related_po',
        'customer_comment',
        'commisioner_comment',
        'logistics_comment',
        'submission_id'
    ];

    /**
     * Get the submission that owns the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
