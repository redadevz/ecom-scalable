<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\OrderHeader;
use App\Models\Shipment;
use App\Models\City;
use App\Models\Store;

class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'order_id' => OrderHeader::factory(),
            'shipment_city_id' => City::factory(),
            'picked_up_by' => null,
            'shipment_address' => $this->faker->word(),
            'gps_location' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'shipment_notes' => $this->faker->word(),
            'picked_up_time' => $this->faker->dateTime(),
            'shipped_time' => $this->faker->dateTime(),
            'comments' => $this->faker->word(),
        ];
    }
}
