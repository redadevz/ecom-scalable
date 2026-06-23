<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'created_at_store_id' => $this->created_at_store_id,
            'created_by' => $this->created_by,
            'code' => $this->code,
            'phone' => $this->phone,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'is_company' => $this->is_company,
            'company_name' => $this->company_name,
            'tax_number' => $this->tax_number,
            'is_tax_exempted' => $this->is_tax_exempted,
            'billing_address' => $this->billing_address,
            'postal_code' => $this->postal_code,
            'is_registered_online' => $this->is_registered_online,
            'email' => $this->email,
            'username' => $this->username,
            'credit' => $this->credit,
            'last_login_time' => $this->last_login_time,
            'is_active' => $this->is_active,
            'comments' => $this->comments,
        ];
    }
}
