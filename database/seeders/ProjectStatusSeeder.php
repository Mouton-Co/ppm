<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Prepare',
            'Order placed',
            'On Site',
            'Work in Progress',
            'Shipped',
            'Waiting for customer',
            'Closed',
        ];

        \App\Models\ProjectStatus::truncate();
        foreach ($statuses as $status) {
            \App\Models\ProjectStatus::create([
                'name' => $status,
            ]);
        }
    }
}
