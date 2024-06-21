<?php

namespace Database\Seeders;

use App\Models\ProcessType;
use Illuminate\Database\Seeder;

class ProcessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProcessType::truncate();

        foreach (config('seeding.process-types') as $requiredFiles => $processTypes) {
            foreach ($processTypes as $processType) {
                ProcessType::create([
                    'process_type' => $processType,
                    'required_files' => $requiredFiles,
                ]);
            }
        }
    }
}
