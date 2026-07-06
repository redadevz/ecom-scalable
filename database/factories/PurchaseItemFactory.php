<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class PurchaseItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),
            'item_id' => Item::factory(),
            'supplier_price_before_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'supplier_tax_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'supplier_price_after_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'supplier_discount_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'return_amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
