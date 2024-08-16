<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'landing_page',
        'permissions',
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
        'role' => [
            'label' => 'Role',
            'type' => 'text',
            'sortable' => true,
            'filterable' => true,
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
    
    /**
     * Table actions.
     *
     * @var array
     */
    public static $actions = [
        'create' => 'Create new',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'trash' => 'Trash',
    ];

    /**
     * Check if the role has a permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if (empty($this->permissions)) {
            return false;
        }

        return in_array($permission, json_decode($this->permissions));
    }
}
