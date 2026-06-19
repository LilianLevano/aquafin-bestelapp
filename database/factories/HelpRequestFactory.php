<?php

namespace Database\Factories;

use App\Models\HelpRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HelpRequest>
 */
class HelpRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'answer' => $this->faker->paragraph(),
            'is_completed' => $this->faker->boolean()
        ];
    }
}
