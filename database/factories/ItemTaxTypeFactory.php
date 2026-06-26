<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemTaxType;
use App\Models\TaxType;

class ItemTaxTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'tax_type_id' => TaxType::factory(),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
        ];
    }
}
