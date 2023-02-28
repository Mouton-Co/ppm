<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            [
                'email' => 'arouxmouton@gmail.com',
                'name' => 'Adriaan Mouton',
                'role' => 'admin',
            ],
            [
                'email' => 'john@ppm.co.za',
                'name' => 'John Caine',
                'role' => 'admin',
            ],
        ];

        foreach($users as $user) {
            $role = Role::where('role', $user['role'])->first();
            $user = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $password,
            ]);
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
    }
}
