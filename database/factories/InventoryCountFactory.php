<?php

namespace Database\Factories;

use App\Models\InventoryCount;
use App\Models\Store;

class InventoryCountFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'physical_count_time' => $this->faker->dateTime(),
            'change_stock_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
