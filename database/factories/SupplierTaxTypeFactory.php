<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Supplier;
use App\Models\SupplierTaxType;

class SupplierTaxTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'name' => $this->faker->unique()->name(),
            'code' => $this->faker->unique()->bothify('C-########'),
            'description' => $this->faker->text(),
            'is_percentage' => $this->faker->boolean(),
            'value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
