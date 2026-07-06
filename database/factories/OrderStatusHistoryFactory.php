<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\OrderHeader;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;

class OrderStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => OrderHeader::factory(),
            'order_status_id' => OrderStatus::factory(),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
        ];
    }
}
