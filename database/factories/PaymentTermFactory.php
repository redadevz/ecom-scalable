<?php

namespace Database\Factories;

use App\Models\DeliveryType;
use App\Models\PaymentMethod;
use App\Models\PaymentTerm;
use App\Models\PaymentTime;
use App\Models\SaleChannel;

class PaymentTermFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sale_channel_id' => SaleChannel::factory(),
            'delivery_type_id' => DeliveryType::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'payment_time_id' => PaymentTime::factory(),
            'is_allowed' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
