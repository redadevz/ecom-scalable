<?php

namespace App\Http\Requests\CraftablePro\PaymentTerm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePaymentTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.payment-terms.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_channel_id' => ['sometimes'],
            'delivery_type_id' => ['sometimes'],
            'payment_method_id' => ['sometimes'],
            'payment_time_id' => ['sometimes'],
            'is_allowed' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
