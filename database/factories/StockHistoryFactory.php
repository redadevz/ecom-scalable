<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Document;
use App\Models\Item;
use App\Models\StockHistory;
use App\Models\Store;

class StockHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'item_id' => Item::factory(),
            'document_id' => Document::factory(),
            'initial_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'initial_item_cost' => $this->faker->randomFloat(0, 0, 9999999999.),
            'is_stock_entry' => $this->faker->boolean(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'current_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'current_item_cost' => $this->faker->randomFloat(0, 0, 9999999999.),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
