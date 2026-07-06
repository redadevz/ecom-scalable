<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\City;
use App\Models\Store;
use App\Models\Supplier;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'city_id' => City::factory(),
            'created_by' => null,
            'code' => $this->faker->unique()->bothify('C-########'),
            'phone' => $this->faker->phoneNumber(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'is_company' => $this->faker->boolean(),
            'company_name' => $this->faker->word(),
            'tax_number' => $this->faker->word(),
            'is_tax_exempted' => $this->faker->boolean(),
            'billing_address' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
