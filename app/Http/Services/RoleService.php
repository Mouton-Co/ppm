<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

class RoleService
{
    /**
     * @var array
     */
    protected array $permissions = [
        'projects',
        'design',
        'procurement',
        'warehouse',
        'purchase_orders',
        'users',
        'roles',
        'suppliers',
        'representatives',
        'autofill_suppliers',
        'process_types',
        'project_statuses',
        'departments',
        'email_triggers',
    ];

    /**
     * @var array
     */
    protected array $excludes = [
        'update_design',
        'create_procurement',
        'delete_procurement',
        'create_warehouse',
        'delete_warehouse',
    ];

    /**
     * @var array
     */
    protected array $crud = [
        'create',
        'read',
        'update',
        'delete',
    ];

    /**
     * Format permissions from request
     *
     * @param Request $request
     * @return array
     */
    public function formatPermissionsFromRequest(Request $request): array
    {
        $permissions = [];

        foreach ($this->permissions as $permission) {
            foreach ($this->crud as $action) {
                if (
                    $request->has("{$action}_{$permission}") &&
                    $request->get("{$action}_{$permission}") == 'on' &&
                    ! in_array("{$action}_{$permission}", $this->excludes)
                ) {
                    $permissions[] =  "{$action}_{$permission}";
                }
            }
        }

        return $permissions;
    }
}
