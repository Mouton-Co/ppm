<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'machine_nr' => $this->faker->word,
            'country' => $this->faker->word,
            'coc' => $this->faker->word,
            'noticed_issue' => $this->faker->text,
            'proposed_solution' => $this->faker->text,
            'currently_responsible' => $this->faker->word,
            'status' => $this->faker->word,
            'resolved_at' => $this->faker->dateTime(),
            'related_po' => $this->faker->word,
            'customer_comment' => $this->faker->text,
            'commisioner_comment' => $this->faker->text,
            'logistics_comment' => $this->faker->text,
            'submission_id' => null,
        ];
    }
}
