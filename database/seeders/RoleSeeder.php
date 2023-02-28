<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'designer',
            'procurer',
            'warehouse',
        ];

        foreach($roles as $role) {
            Role::create([
                'role' => $role,
            ]);
        }
    }
}
