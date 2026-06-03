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
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'posted_on' => $this->faker->date(),
            'is_completed' => $this->faker->boolean(),
        ];
    }
}
