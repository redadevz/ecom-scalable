<?php

namespace App\Http\Requests\Api\PaymentTime;

use Illuminate\Foundation\Http\FormRequest;

class DestroyPaymentTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.payment-times.destroy");
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
