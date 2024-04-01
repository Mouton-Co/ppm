<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectResponsibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responsibles = [
            'Robin',
            'Customer',
            'Procurement',
            'Documentation',
            'Nicholas',
            'Programming',
            'Logistics',
            'Mechanical',
            'Commissioning',
            'Johan',
            'Design',
            'John',
            'Riaan',
            'Delport',
            'Ralf',
            'Donovan',
            'Hanna',
            'Nelmari',
            'Manuals',
            'Commission',
            'Electrical',
            'Jaco',
            'Tanaka',
            'Mike',
            'QC',
            'Warehouse',
            'Procurement',
            'Ulrich',
            'Project Manager',
        ];

        \App\Models\ProjectResponsible::truncate();
        foreach ($responsibles as $responsible) {
            \App\Models\ProjectResponsible::create([
                'name' => $responsible,
            ]);
        }
    }
}
