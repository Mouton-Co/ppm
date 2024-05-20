<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ProjectStatusSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(RecipientGroupSeeder::class);
    }
}
