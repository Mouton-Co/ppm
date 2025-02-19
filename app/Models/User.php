<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'configurations',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
        'role_role' => [
            'label' => 'Role',
            'type' => 'dropdown',
            'sortable' => true,
            'filterable' => true,
            'relationship' => 'role.role',
            'relationship_model' => Role::class,
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
        'restore' => 'Restore',
        'trash' => 'Trash',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Get user's role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Get user's table configurations
     */
    public function getTableConfigsAttribute(): ?array
    {
        try {
            return (array) json_decode($this->configurations, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Returns true if editable component is accessible to user
     */
    public function getCanAccessAttribute(): bool
    {
        $access = false;

        if (request()->segment(1) == 'projects' && auth()->user()->role->hasPermission('update_projects')) {
            $access = true;
        }

        if (
            (request()->segment(1) == 'submissions' ||
            request()->segment(1) == 'submission') &&
            auth()->user()->role->hasPermission('update_design')
        ) {
            $access = true;
        }

        if (request()->segment(2) == 'procurement' && auth()->user()->role->hasPermission('update_procurement')) {
            $access = true;
        }

        if (request()->segment(2) == 'warehouse' && auth()->user()->role->hasPermission('update_warehouse')) {
            $access = true;
        }

        if (request()->segment(1) == 'orders' && auth()->user()->role->hasPermission('update_purchase_orders')) {
            $access = true;
        }

        if (request()->segment(1) == 'users' && auth()->user()->role->hasPermission('update_users')) {
            $access = true;
        }

        if (request()->segment(1) == 'roles' && auth()->user()->role->hasPermission('update_roles')) {
            $access = true;
        }

        if (request()->segment(1) == 'suppliers' && auth()->user()->role->hasPermission('update_suppliers')) {
            $access = true;
        }

        if (request()->segment(1) == 'representatives' && auth()->user()->role->hasPermission('update_representatives')) {
            $access = true;
        }

        if (request()->segment(1) == 'autofill-suppliers' && auth()->user()->role->hasPermission('update_autofill_suppliers')) {
            $access = true;
        }

        if (request()->segment(1) == 'process-types' && auth()->user()->role->hasPermission('update_process_types')) {
            $access = true;
        }

        if (request()->segment(1) == 'project-statuses' && auth()->user()->role->hasPermission('update_project_statuses')) {
            $access = true;
        }

        if (request()->segment(1) == 'project-responsibles' && auth()->user()->role->hasPermission('update_project_responsibles')) {
            $access = true;
        }

        if (request()->segment(1) == 'recipient-groups' && auth()->user()->role->hasPermission('update_recipient_groups')) {
            $access = true;
        }

        return $access;
    }
}
