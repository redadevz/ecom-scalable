<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Language;

class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'short_name' => $this->faker->unique()->word(),
            'description' => $this->faker->text(),
        ];
    }
}
