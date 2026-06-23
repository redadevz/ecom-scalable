<?php

namespace Database\Factories;

use App\Models\CreatedBy;
use App\Models\Item;
use App\Models\Price;
use App\Models\Store;

class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'store_id' => Store::factory(),
            'created_by' => CreatedBy::factory(),
            'description' => $this->faker->text(),
            'current_item_cost' => $this->faker->randomFloat(0, 0, 9999999999.),
            'markup_percentage' => $this->faker->numberBetween(-10000, 10000),
            'price_before_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'tax_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_after_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'sale_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_change_allowed' => $this->faker->boolean(),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
