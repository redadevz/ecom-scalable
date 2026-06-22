<?php

namespace App\Http\Requests\CraftablePro\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.payments.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['sometimes'],
            'provider' => ['sometimes', 'string'],
            'provider_reference' => ['nullable', 'string'],
            'status' => ['sometimes', 'string'],
            'amount' => ['sometimes'],
            'currency' => ['sometimes', 'string'],
            
        ];
    }
}
