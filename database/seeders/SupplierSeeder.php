<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = config('seeding.suppliers');

        foreach ($rows as $row) {
            $supplier = \App\Models\Supplier::where('name', $row['name'])->first();
            if (empty($supplier)) {
                $supplier = \App\Models\Supplier::create([
                    'name' => $row['name'],
                ]);
            }

            \App\Models\Representative::create([
                'name' => $row['representative_name'],
                'email' => $row['representative_email'],
                'phone_1' => $row['representative_contact_1'],
                'phone_2' => $row['representative_contact_2'],
                'supplier_id' => $supplier->id,
            ]);
        }
    }
}
