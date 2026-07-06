<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\City;
use App\Models\Region;
use App\Models\TimeZone;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'region_id' => Region::factory(),
            'timezone_id' => TimeZone::factory(),
            'zipcode' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
