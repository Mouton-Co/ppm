<?php

namespace Database\Seeders;

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
            ],
            [
                'email' => 'john@ppm.co.za',
                'name' => 'John Caine',
            ],
        ];

        foreach($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $password,
            ]);
        }
    }
}
