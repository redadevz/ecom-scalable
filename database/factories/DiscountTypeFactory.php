<?php

namespace Database\Factories;

use App\Models\DiscountType;
use App\Models\LoyaltyCardType;
use App\Models\Store;

class DiscountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'loyalty_card_type_id' => LoyaltyCardType::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'is_percentage' => $this->faker->boolean(),
            'value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'coupon_code' => $this->faker->word(),
            'min_order_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'min_item_quantity' => $this->faker->numberBetween(-10000, 10000),
            'apply_to_all' => $this->faker->boolean(),
            'apply_to_next' => $this->faker->boolean(),
            'max_discount_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
