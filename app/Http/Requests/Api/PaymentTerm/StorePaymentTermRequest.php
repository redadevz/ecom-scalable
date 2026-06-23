<?php

namespace App\Http\Requests\Api\PaymentTerm;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.payment-terms.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_channel_id' => ['required'],
            'delivery_type_id' => ['required'],
            'payment_method_id' => ['required'],
            'payment_time_id' => ['required'],
            'is_allowed' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
