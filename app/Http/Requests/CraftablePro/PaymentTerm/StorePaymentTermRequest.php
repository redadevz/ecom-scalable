<?php

namespace App\Http\Requests\CraftablePro\PaymentTerm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePaymentTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.payment-terms.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_channel_id' => ['required', 'integer', 'exists:sale_channels,id'],
            'delivery_type_id' => ['required', 'integer', 'exists:delivery_types,id'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'payment_time_id' => ['required', 'integer', 'exists:payment_times,id'],
            'is_allowed' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
