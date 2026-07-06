<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Currency;

class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'short_name' => $this->faker->unique()->word(),
            'symbol' => $this->faker->unique()->currencyCode(),
            'description' => $this->faker->text(),
        ];
    }
}
