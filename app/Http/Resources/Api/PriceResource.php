<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'store_id' => $this->store_id,
            'created_by' => $this->created_by,
            'description' => $this->description,
            'current_item_cost' => $this->current_item_cost,
            'markup_percentage' => $this->markup_percentage,
            'price_before_tax' => $this->price_before_tax,
            'tax_value' => $this->tax_value,
            'price_after_tax' => $this->price_after_tax,
            'sale_price' => $this->sale_price,
            'price_change_allowed' => $this->price_change_allowed,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_active' => $this->is_active,
            'comments' => $this->comments,
        ];
    }
}
