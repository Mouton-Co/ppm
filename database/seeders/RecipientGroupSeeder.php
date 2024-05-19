<?php

namespace Database\Seeders;

use App\Models\RecipientGroup;
use Illuminate\Database\Seeder;

class RecipientGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RecipientGroup::truncate();

        $groups = [
            [
                'field' => 'Currently responsible',
                'value' => 'Design',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'delport@proproject.co.za',
                    'riaan@proproject.co.za',
                ],
            ],
            [
                'field' => 'Currently responsible',
                'value' => 'Procurement',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'hanna@proproject.co.za',
                    'jone@proproject.co.za',
                ],
            ],
            [
                'field' => 'Currently responsible',
                'value' => 'Logistics',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'hanna@proproject.co.za',
                ],
            ],
            [
                'field' => 'Currently responsible',
                'value' => 'Programming',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'johan@proproject.co.za',
                    'john@proproject.co.za',
                ],
            ],
            [
                'field' => 'Currently responsible',
                'value' => 'Electrical',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'ulrich@proproject.co.za',
                ],
            ],
            [
                'field' => 'Currently responsible',
                'value' => 'Project Manager',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                ],
            ],
            [
                'field' => 'Currently responsible',
                'value' => 'Commissioning',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'john@proproject.co.za',
                ],
            ],
            [
                'field' => 'Status',
                'value' => 'Closed',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'donovan@proproject.co.za',
                    'john@proproject.co.za',
                ],
            ],
            [
                'field' => 'Status',
                'value' => 'Waiting for customer',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'john@proproject.co.za',
                ],
            ],
            [
                'field' => 'Item created',
                'value' => 'CoC',
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'donovan@proproject.co.za',
                    'john@proproject.co.za',
                ],
            ],
            [
                'field' => "Item created",
                'value' => "Submission",
                'recipients' => [
                    'nelmari.b@proproject.co.za',
                    'jone@proproject.co.za',
                    'hanna@proproject.co.za',
                ],
            ],
        ];

        foreach ($groups as $group) {
            RecipientGroup::create([
                'field' => $group['field'],
                'value' => $group['value'],
                'recipients' => implode("\n", $group['recipients']),
            ]);
        }
    }
}
