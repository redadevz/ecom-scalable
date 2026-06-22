<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'unit_price' => $this->faker->randomFloat(0, 0, 9999999999.),
        ];
    }
}
