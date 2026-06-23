<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'language_id' => $this->language_id,
            'currency_id' => $this->currency_id,
            'code' => $this->code,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'legal_entity_name' => $this->legal_entity_name,
            'tax_code' => $this->tax_code,
            'address' => $this->address,
            'registration_number' => $this->registration_number,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'logo' => $this->logo,
            'bank_branch' => $this->bank_branch,
            'bank_code' => $this->bank_code,
            'bank_account' => $this->bank_account,
        ];
    }
}
