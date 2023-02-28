<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
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
            UserRole::create([
                'role' => $role,
            ]);
        }
    }
}
