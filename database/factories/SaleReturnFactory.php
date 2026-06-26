<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\SaleReturn;
use App\Models\Store;

class SaleReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'order_id' => Order::factory(),
            'entry_stock_time' => $this->faker->dateTime(),
            'is_refund_required' => $this->faker->boolean(),
            'refund_amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'is_refunded' => $this->faker->boolean(),
            'refund_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
