<?php

namespace Database\Factories;

use App\Models\Currency;

class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'short_name' => $this->faker->word(),
            'symbol' => $this->faker->word(),
            'description' => $this->faker->text(),
        ];
    }
}
