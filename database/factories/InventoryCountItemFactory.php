<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Models\Item;

class InventoryCountItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'inventory_count_id' => InventoryCount::factory(),
            'item_id' => Item::factory(),
            'quantity_counted' => $this->faker->numberBetween(-10000, 10000),
            'quantity_expected' => $this->faker->numberBetween(-10000, 10000),
            'quantity_change' => $this->faker->numberBetween(-10000, 10000),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
