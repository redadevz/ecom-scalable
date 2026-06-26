<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\StockReturn;
use App\Models\StockReturnItem;

class StockReturnItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stock_return_id' => StockReturn::factory(),
            'item_id' => Item::factory(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'supplier_price_before_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'supplier_tax_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'supplier_price_after_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'supplier_discount_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'return_amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
