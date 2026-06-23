<?php

namespace App\Http\Requests\Api\LoyaltyCardType;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoyaltyCardTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.loyalty-card-types.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            
        ];
    }
}
