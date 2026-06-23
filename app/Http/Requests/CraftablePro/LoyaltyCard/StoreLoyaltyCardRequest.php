<?php

namespace App\Http\Requests\CraftablePro\LoyaltyCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreLoyaltyCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.loyalty-cards.create");
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
