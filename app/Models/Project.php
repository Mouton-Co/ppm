<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'machine_nr',
        'country',
        'coc',
        'noticed_issue',
        'proposed_solution',
        'currently_responsible',
        'status',
        'resolved_at',
        'related_pos',
        'waybill_nr',
        'customer_comment',
        'commisioner_comment',
        'logistics_comment',
        'submission_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'resolved_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Table structure.
     *
     * @var array
     */
    public static $structure = [
        'id' => [
            'label' => 'ID',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'machine_nr' => [
            'label' => 'Machine Nr',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'country' => [
            'label' => 'Country/Company',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'coc' => [
            'label' => 'Ticket Nr',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'noticed_issue' => [
            'label' => 'Noticed Issue',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
            'tooltip' => true,
        ],
        'proposed_solution' => [
            'label' => 'Proposed Solution',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
            'tooltip' => true,
        ],
        'currently_responsible' => [
            'label' => 'Currently Responsible',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => 'custom',
            'component' => 'editable.select',
        ],
        'status' => [
            'label' => 'Status',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => 'custom',
            'component' => 'editable.select',
        ],
        'resolved_at' => [
            'label' => 'Resolved At',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
        ],
        'related_pos' => [
            'label' => 'Related POs',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'waybill_nr' => [
            'label' => 'Waybill Nr',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
        ],
        'customer_comment' => [
            'label' => 'Customer Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
            'tooltip' => true,
        ],
        'commisioner_comment' => [
            'label' => 'Commisioner Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
            'tooltip' => true,
        ],
        'logistics_comment' => [
            'label' => 'Logistics Comment',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'component' => 'editable.text',
            'tooltip' => true,
        ],
        'submission_submission_code' => [
            'label' => 'Submission',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
            'relationship' => 'submission.submission_code',
            'relationship_model' => Submission::class,
            'component' => 'project.submission',
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
        'created_by' => [
            'label' => 'Created by',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'relationship' => 'user.name',
            'relationship_model' => User::class,
        ],
    ];

    /**
     * Actions.
     *
     * @var array<string, string>
     */
    public static $actions = [
        'create' => 'Create new',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'trash' => 'Trash',
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

    /**
     * Get the user that owns the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the notes attribute.
     *
     * @return string
     */
    public function getNotesAttribute(): string
    {
        return 'Noticed issue&#10;' . $this->noticed_issue .
            '&#10;&#10;Proposed solution&#10;' . $this->proposed_solution;
    }

    /**
     * Get the issued at attribute.
     *
     * @return string
     */
    public function getIssuedAtAttribute(): string
    {
        return $this->created_at;
    }

    /**
     * Get the resolved at attribute.
     *
     * @return string|null
     */
    public function getResolvedAtFormattedAttribute(): ?string
    {
        return $this->resolved_at ? Carbon::parse($this->resolved_at)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Get the currently responsible attribute.
     *
     * @return array
     */
    public static function getCustomCurrentlyResponsibleAttribute(): array
    {
        $responsibles = array_merge(
            ProjectResponsible::pluck('name')->toArray(),
            User::pluck('name')->toArray()
        );
        sort($responsibles);
        return $responsibles;
    }

    /**
     * Get the status attribute.
     *
     * @return array
     */
    public static function getCustomStatusAttribute(): array
    {
        return ProjectStatus::orderBy('name')->pluck('name')->toArray();
    }
}
