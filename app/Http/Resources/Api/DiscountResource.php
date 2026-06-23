<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discount_type_id' => $this->discount_type_id,
            'item_category_id' => $this->item_category_id,
            'item_id' => $this->item_id,
            'description' => $this->description,
            'comments' => $this->comments,
        ];
    }
}
