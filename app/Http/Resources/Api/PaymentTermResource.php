<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sale_channel_id' => $this->sale_channel_id,
            'delivery_type_id' => $this->delivery_type_id,
            'payment_method_id' => $this->payment_method_id,
            'payment_time_id' => $this->payment_time_id,
            'is_allowed' => $this->is_allowed,
            'is_active' => $this->is_active,
            'comments' => $this->comments,
        ];
    }
}
