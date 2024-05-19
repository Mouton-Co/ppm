<?php

namespace Database\Seeders;

use App\Models\ProjectResponsible;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'CNC',
            'Commissioning',
            'Customer',
            'Design',
            'Documentation',
            'Electrical',
            'Fabrication',
            'Logistics',
            'Management',
            'Manuals',
            'Mechanical',
            'Operations',
            'Procurement',
            'Programming',
            'Project Manager',
            'QC',
            'R&D',
            'VB',
            'Warehouse',
        ];

        ProjectResponsible::truncate();
        foreach ($departments as $department) {
            ProjectResponsible::create([
                'name' => $department,
            ]);
        }
    }
}
