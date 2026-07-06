<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\OrderHeader;
use App\Models\SaleReturn;
use App\Models\Store;

class SaleReturnFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'order_id' => OrderHeader::factory(),
            'entry_stock_time' => $this->faker->dateTime(),
            'is_refund_required' => $this->faker->boolean(),
            'refund_amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'is_refunded' => $this->faker->boolean(),
            'refund_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
        ];
    }
}
