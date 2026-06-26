<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\PickedUpBy;
use App\Models\Shipment;
use App\Models\ShipmentCity;
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
            'order_id' => Order::factory(),
            'shipment_city_id' => ShipmentCity::factory(),
            'picked_up_by' => PickedUpBy::factory(),
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
