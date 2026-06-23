<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\CreatedAtStore;
use App\Models\CreatedBy;
use App\Models\Customer;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'created_at_store_id' => CreatedAtStore::factory(),
            'created_by' => CreatedBy::factory(),
            'code' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'is_company' => $this->faker->boolean(),
            'company_name' => $this->faker->word(),
            'tax_number' => $this->faker->word(),
            'is_tax_exempted' => $this->faker->boolean(),
            'billing_address' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'is_registered_online' => $this->faker->boolean(),
            'email' => $this->faker->safeEmail(),
            'username' => $this->faker->userName(),
            'password' => $this->faker->password(),
            'credit' => $this->faker->randomFloat(0, 0, 9999999999.),
            'last_login_time' => $this->faker->dateTime(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
