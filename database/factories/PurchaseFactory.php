<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Store;
use App\Models\Supplier;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'supplier_id' => Supplier::factory(),
            'entry_stock_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'is_paid' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
