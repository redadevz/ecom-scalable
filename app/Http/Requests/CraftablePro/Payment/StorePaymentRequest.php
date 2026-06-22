<?php

namespace App\Http\Requests\CraftablePro\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.payments.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'provider' => ['required', 'string'],
            'provider_reference' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'amount' => ['required'],
            'currency' => ['required', 'string'],
            
        ];
    }
}
