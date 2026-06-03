<?php

namespace Database\Factories;

use App\Models\Aanvraag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aanvraag>
 */
class AanvraagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'posted_by' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'posted_on' => $this->faker->date(),
            'is_completed' => $this->faker->boolean(),
        ];
    }
}
