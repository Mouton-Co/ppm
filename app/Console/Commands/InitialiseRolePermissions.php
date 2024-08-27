<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class InitialiseRolePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialise-role-permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissions = [
            'create_projects',
            'read_projects',
            'update_projects',
            'delete_projects',
            'restore_projects',
            'force_delete_projects',
            'create_design',
            'read_design',
            'delete_design',
            'restore_design',
            'force_delete_design',
            'read_procurement',
            'update_procurement',
            'read_warehouse',
            'update_warehouse',
            'create_purchase_orders',
            'read_purchase_orders',
            'update_purchase_orders',
            'delete_purchase_orders',
            'restore_purchase_orders',
            'force_delete_purchase_orders',
            'create_users',
            'read_users',
            'update_users',
            'delete_users',
            'restore_users',
            'force_delete_users',
            'create_roles',
            'read_roles',
            'update_roles',
            'delete_roles',
            'restore_roles',
            'force_delete_roles',
            'create_suppliers',
            'read_suppliers',
            'update_suppliers',
            'delete_suppliers',
            'restore_suppliers',
            'force_delete_suppliers',
            'create_representatives',
            'read_representatives',
            'update_representatives',
            'delete_representatives',
            'restore_representatives',
            'force_delete_representatives',
            'create_autofill_suppliers',
            'read_autofill_suppliers',
            'update_autofill_suppliers',
            'delete_autofill_suppliers',
            'restore_autofill_suppliers',
            'force_delete_autofill_suppliers',
            'create_process_types',
            'read_process_types',
            'update_process_types',
            'delete_process_types',
            'restore_process_types',
            'force_delete_process_types',
            'create_project_statuses',
            'read_project_statuses',
            'update_project_statuses',
            'delete_project_statuses',
            'restore_project_statuses',
            'force_delete_project_statuses',
            'create_departments',
            'read_departments',
            'update_departments',
            'delete_departments',
            'restore_departments',
            'force_delete_departments',
            'create_email_triggers',
            'read_email_triggers',
            'update_email_triggers',
            'delete_email_triggers',
            'restore_email_triggers',
            'force_delete_email_triggers',
        ];

        $roles = ['Admin'];

        foreach ($roles as $role) {
            $role = Role::where('role', $role)->first();
            if (!empty($role)) {
                $role->permissions = json_encode($permissions);
                $role->landing_page = 'Projects';
                $role->save();
            }
        }
    }
}
