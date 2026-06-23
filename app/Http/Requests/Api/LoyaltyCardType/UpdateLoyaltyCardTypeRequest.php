<?php

namespace App\Http\Requests\Api\LoyaltyCardType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoyaltyCardTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.loyalty-card-types.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            
        ];
    }
}
