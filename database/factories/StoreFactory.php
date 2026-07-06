<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\City;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Store;

class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'language_id' => Language::factory(),
            'currency_id' => Currency::factory(),
            'code' => $this->faker->unique()->bothify('C-########'),
            'name' => $this->faker->unique()->name(),
            'is_active' => $this->faker->boolean(),
            'legal_entity_name' => $this->faker->word(),
            'tax_code' => $this->faker->word(),
            'address' => $this->faker->word(),
            'registration_number' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'fax' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'logo' => $this->faker->word(),
            'bank_branch' => $this->faker->word(),
            'bank_code' => $this->faker->word(),
            'bank_account' => $this->faker->word(),
        ];
    }
}
