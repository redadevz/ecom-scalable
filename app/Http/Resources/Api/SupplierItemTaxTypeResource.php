<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierItemTaxTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'supplier_tax_type_id' => $this->supplier_tax_type_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'description' => $this->description,
        ];
    }
}
