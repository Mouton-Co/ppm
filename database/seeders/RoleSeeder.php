<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'Designer',
        ];

        foreach ($roles as $role) {
            Role::create([
                'role' => $role,
            ]);
        }
        
        Artisan::call('app:initialise-role-permissions');
    }
}
