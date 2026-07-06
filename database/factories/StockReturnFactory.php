<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Purchase;
use App\Models\StockReturn;
use App\Models\Store;

class StockReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'purchase_id' => Purchase::factory(),
            'exit_stock_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'is_paid' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
