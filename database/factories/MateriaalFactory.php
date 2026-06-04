<?php

namespace Database\Factories;

use App\Models\Materiaal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Materiaal>
 */
class MateriaalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'category_id' => $this->faker->numberBetween(1, 6),
        ];
    }
}
