<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\MeasureUnit;
use App\Models\Store;
use App\Models\Supplier;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'item_category_id' => ItemCategory::factory(),
            'supplier_id' => Supplier::factory(),
            'measure_unit_id' => MeasureUnit::factory(),
            'sku_code' => $this->faker->unique()->bothify('SKU-########'),
            'name' => $this->faker->unique()->name(),
            'description' => $this->faker->text(),
            'is_service' => $this->faker->boolean(),
            'in_stock' => $this->faker->boolean(),
            'using_default_quantity' => $this->faker->boolean(),
            'default_quantity' => $this->faker->numberBetween(-10000, 10000),
            'current_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'preferred_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'min_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'low_stock_warning' => $this->faker->boolean(),
            'low_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
