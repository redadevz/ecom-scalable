<?php

namespace App\Http\Requests\Api\PaymentTime;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.payment-times.update");
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
