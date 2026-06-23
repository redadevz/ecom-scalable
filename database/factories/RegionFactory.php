<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Region;

class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'country_id' => Country::factory(),
        ];
    }
}
