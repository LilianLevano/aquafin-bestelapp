<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                "Bevestigingsmateriaal",
                "Persoonlijke beschermingsmiddelen (PBM)",
                "Gereedschap (manueel & elektrisch)",
                "Technische onderhoudsmaterialen",
                "Specifieke Aquafin/riolering gerelateerde tools",
                "Diversen / Verbruiksgoederen"
            ]),
        ];
    }
}
