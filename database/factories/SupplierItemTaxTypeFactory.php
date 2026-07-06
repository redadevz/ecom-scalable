<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Item;
use App\Models\SupplierItemTaxType;
use App\Models\SupplierTaxType;

class SupplierItemTaxTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'supplier_tax_type_id' => SupplierTaxType::factory(),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
        ];
    }
}
