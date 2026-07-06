<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Discount;
use App\Models\OrderLine;
use App\Models\OrderLineDiscount;

class OrderLineDiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'discount_id' => Discount::factory(),
            'order_line_id' => OrderLine::factory(),
            'value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'comments' => $this->faker->word(),
        ];
    }
}
