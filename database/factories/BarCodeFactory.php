<?php

namespace Database\Factories;

use App\Models\BarCode;
use App\Models\Item;

class BarCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'bar_code' => $this->faker->word(),
            'is_active' => $this->faker->boolean(),
            'description' => $this->faker->text(),
        ];
    }
}
