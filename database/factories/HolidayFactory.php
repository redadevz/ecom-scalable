<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\Store;

class HolidayFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'name' => $this->faker->name(),
            'reason' => $this->faker->word(),
            'starts_at' => $this->faker->dateTime(),
            'ends_at' => $this->faker->dateTime(),
            'comments' => $this->faker->word(),
        ];
    }
}
