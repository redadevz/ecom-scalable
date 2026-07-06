<?php

namespace App\Http\Requests\CraftablePro\LoyaltyCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateLoyaltyCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.loyalty-cards.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'loyalty_card_type_id' => ['sometimes', 'integer', 'exists:loyalty_card_types,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'code' => ['sometimes', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
            
        ];
    }
}
