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
                'roles' => ['admin'],
            ],
            [
                'email' => 'john@proproject.co.za',
                'name' => 'John Caine',
                'roles' => ['admin'],
            ],
            [
                'email' => 'ulrich@proproject.co.za',
                'name' => 'Ulrich Spies',
                'roles' => ['procurement'],
            ],
            [
                'email' => 'hannah@proproject.co.za',
                'name' => 'Hannah Caine',
                'roles' => ['procurement', 'chase'],
            ],
            [
                'email' => 'donovan@proproject.co.za',
                'name' => 'Donovan Thompson',
                'roles' => ['procurement', 'reporting'],
            ],
            [
                'email' => 'riaan@proproject.co.za',
                'name' => 'Riaan De Wet',
                'roles' => ['design'],
            ],
            [
                'email' => 'delport@proproject.co.za',
                'name' => 'Donovan Delport',
                'roles' => ['design'],
            ],
            [
                'email' => 'ralf@proproject.co.za',
                'name' => 'Ralf Brugers',
                'roles' => ['design'],
            ],
            [
                'email' => 'graham@proproject.co.za',
                'name' => 'Graham Hitzeroth',
                'roles' => ['warehouse'],
            ],
            [
                'email' => 'jone@proproject.co.za',
                'name' => 'Jone Russouw',
                'roles' => ['warehouse'],
            ],
            [
                'email' => 'danielle@proproject.co.za',
                'name' => 'Danielle Daniels',
                'roles' => ['warehouse'],
            ],
            [
                'email' => 'nelmari.b@proproject.co.za',
                'name' => 'Nelmari Barnard',
                'roles' => ['reporting', 'chase'],
            ],
            [
                'email' => 'qc@proproject.co.za',
                'name' => 'David Mpoyi',
                'roles' => ['design'],
            ],
            [
                'email' => 'celor.k@proproject.co.za',
                'name' => 'Celor Kalenda',
                'roles' => ['design'],
            ],
            [
                'email' => 'tanaka@proproject.co.za',
                'name' => 'Tanaka Zuze',
                'roles' => ['design'],
            ],
            [
                'email' => 'robin@proproject.co.za',
                'name' => 'Robin Burger',
                'roles' => ['reporting'],
            ],
            [
                'email' => 'fernando@proproject.co.za',
                'name' => 'Fernando Sneddon',
                'roles' => ['warehouse'],
            ],
        ];

        foreach($users as $user) {
            $roles = $user['roles'];
            $user = User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => $password,
            ]);
            foreach ($roles as $roleSlug) {
                $role = Role::where('role', $roleSlug)->first();
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                ]);
            }
        }
    }
}
