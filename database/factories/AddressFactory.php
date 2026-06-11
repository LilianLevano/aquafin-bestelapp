<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'street' => $this->faker->streetName(),
            'house_number' => $this->faker->numberBetween(1, 5000),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->numberBetween(1000, 9999),
            'country_iso' => $this->faker->randomLetter() . $this->faker->randomLetter(),
            'unit_number' => $this->faker->randomLetter() . $this->faker->randomLetter()
        ];
    }
}
