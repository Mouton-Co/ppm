<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
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
                'role' => 'Admin',
            ],
            [
                'email' => 'john@proproject.co.za',
                'name' => 'John Caine',
                'role' => 'Admin',
            ],
            [
                'email' => 'hanna@proproject.co.za',
                'name' => 'Hannah Caine',
                'role' => 'Admin',
            ],
            [
                'email' => 'riaan@proproject.co.za',
                'name' => 'Riaan De Wet',
                'role' => 'Designer',
            ],
            [
                'email' => 'delport@proproject.co.za',
                'name' => 'Donovan Delport',
                'role' => 'Designer',
            ],
            [
                'email' => 'ralf@proproject.co.za',
                'name' => 'Ralf Brugers',
                'role' => 'Designer',
            ],
            [
                'email' => 'qc@proproject.co.za',
                'name' => 'David Mpoyi',
                'role' => 'Designer',
            ],
            [
                'email' => 'celor.k@proproject.co.za',
                'name' => 'Celor Kalenda',
                'role' => 'Designer',
            ],
            [
                'email' => 'tanaka@proproject.co.za',
                'name' => 'Tanaka Zuze',
                'role' => 'Designer',
            ],
        ];

        foreach ($users as $user) {
            $role = Role::where('role', $user['role'])->first();
            $user = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $password,
                'role_id' => $role->id,
            ]);
        }
    }
}
