<?php

namespace App\Http\Requests\Api\LoyaltyCardType;

use Illuminate\Foundation\Http\FormRequest;

class ShowLoyaltyCardTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.loyalty-card-types.show");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            
        ];
    }
}
