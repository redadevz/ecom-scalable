<?php

namespace App\Http\Requests\CraftablePro\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.payment-methods.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'code' => ['sometimes', 'string'],
            'sequence_no' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'is_customer_required' => ['sometimes', 'boolean'],
            'description' => ['nullable'],
            
        ];
    }
}
