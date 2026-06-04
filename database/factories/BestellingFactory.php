<?php

namespace Database\Factories;

use App\Models\Bestelling;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bestelling>
 */
class BestellingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1,5),
            'delivery_date' => $this->faker->date(),
            'site_id' => $this->faker->numberBetween(1,5),
        ];
    }
}
