<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\OrderLine;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;

class SaleReturnItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sale_return_id' => SaleReturn::factory(),
            'order_line_id' => OrderLine::factory(),
            'item_id' => Item::factory(),
            'line_no' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'return_quantity' => $this->faker->numberBetween(-10000, 10000),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
