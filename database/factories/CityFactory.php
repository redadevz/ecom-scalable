<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Region;
use App\Models\Timezone;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'region_id' => Region::factory(),
            'timezone_id' => Timezone::factory(),
            'zipcode' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
