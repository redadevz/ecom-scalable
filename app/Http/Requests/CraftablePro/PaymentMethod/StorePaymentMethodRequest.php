<?php

namespace App\Http\Requests\CraftablePro\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.payment-methods.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:payment_methods,name'],
            'code' => ['required', 'string', 'max:255', 'unique:payment_methods,code'],
            'sequence_no' => ['nullable', 'integer'],
            'is_active' => ['required', 'boolean'],
            'is_customer_required' => ['required', 'boolean'],
            'description' => ['nullable'],
            
        ];
    }
}
