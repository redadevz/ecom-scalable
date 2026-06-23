<?php

namespace App\Http\Requests\Api\LoyaltyCard;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoyaltyCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.loyalty-cards.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'loyalty_card_type_id' => ['required'],
            'customer_id' => ['nullable'],
            'code' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            
        ];
    }
}
