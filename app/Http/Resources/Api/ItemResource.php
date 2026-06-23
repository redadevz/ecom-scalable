<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'item_category_id' => $this->item_category_id,
            'supplier_id' => $this->supplier_id,
            'measure_unit_id' => $this->measure_unit_id,
            'sku_code' => $this->sku_code,
            'name' => $this->name,
            'description' => $this->description,
            'is_service' => $this->is_service,
            'in_stock' => $this->in_stock,
            'using_default_quantity' => $this->using_default_quantity,
            'default_quantity' => $this->default_quantity,
            'current_stock_quantity' => $this->current_stock_quantity,
            'preferred_stock_quantity' => $this->preferred_stock_quantity,
            'min_stock_quantity' => $this->min_stock_quantity,
            'low_stock_warning' => $this->low_stock_warning,
            'low_stock_quantity' => $this->low_stock_quantity,
            'is_active' => $this->is_active,
            'comments' => $this->comments,
        ];
    }
}
