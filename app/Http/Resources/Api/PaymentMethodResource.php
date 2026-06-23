<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'sequence_no' => $this->sequence_no,
            'is_active' => $this->is_active,
            'is_customer_required' => $this->is_customer_required,
            'description' => $this->description,
        ];
    }
}
