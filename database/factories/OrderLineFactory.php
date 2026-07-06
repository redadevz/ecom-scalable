<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\Store;

class OrderLineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'order_id' => OrderHeader::factory(),
            'item_id' => Item::factory(),
            'line_no' => $this->faker->word(),
            'description' => $this->faker->text(),
            'customer_notes' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'current_item_cost' => $this->faker->randomFloat(0, 0, 9999999999.),
            'markup_percentage' => $this->faker->numberBetween(-10000, 10000),
            'price_before_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'tax_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_after_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_before_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'discount_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_after_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_adjustment' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_adjustment_reason' => $this->faker->word(),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'is_canceled' => $this->faker->boolean(),
            'canceled_time' => $this->faker->dateTime(),
            'cancel_reason' => $this->faker->word(),
            'return_required' => $this->faker->boolean(),
            'return_quantity' => $this->faker->numberBetween(-10000, 10000),
            'return_time' => $this->faker->dateTime(),
            'customer_review' => $this->faker->word(),
            'customer_like' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
