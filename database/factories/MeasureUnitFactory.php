<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\MeasureUnit;

class MeasureUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'symbol' => $this->faker->word(),
            'description' => $this->faker->text(),
        ];
    }
}
