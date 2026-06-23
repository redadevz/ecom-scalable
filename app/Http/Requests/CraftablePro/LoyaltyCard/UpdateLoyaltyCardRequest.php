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
            'loyalty_card_type_id' => ['sometimes'],
            'customer_id' => ['nullable'],
            'code' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            
        ];
    }
}
