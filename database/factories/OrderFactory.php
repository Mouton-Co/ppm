<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'po_number' => Str::random(10),
            'notes' => $this->faker->sentence,
            'status' => $this->faker->randomElement(array_keys(config('models.orders.status'))),
            'supplier_id' => $this->faker->randomElement(Supplier::pluck('id')),
            'submission_code' => Str::random(10),
        ];
    }
}
