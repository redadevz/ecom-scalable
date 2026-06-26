<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDiscount;

class OrderDiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'discount_id' => Discount::factory(),
            'order_id' => Order::factory(),
            'value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'comments' => $this->faker->word(),
        ];
    }
}
