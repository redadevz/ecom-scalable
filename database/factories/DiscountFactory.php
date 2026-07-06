<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Discount;
use App\Models\DiscountType;
use App\Models\Item;
use App\Models\ItemCategory;

class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'discount_type_id' => DiscountType::factory(),
            'item_category_id' => ItemCategory::factory(),
            'item_id' => Item::factory(),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
