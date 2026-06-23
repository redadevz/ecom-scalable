<?php

namespace App\Http\Requests\Api\LoyaltyCard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoyaltyCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.loyalty-cards.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'loyalty_card_type_id' => ['sometimes'],
            'customer_id' => ['nullable'],
            'code' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            
        ];
    }
}
