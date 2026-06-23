<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'loyalty_card_type_id' => $this->loyalty_card_type_id,
            'customer_id' => $this->customer_id,
            'code' => $this->code,
            'is_active' => $this->is_active,
        ];
    }
}
