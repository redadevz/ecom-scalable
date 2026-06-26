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
            'invoice_id' => ['required'],
            'payment_method_id' => ['required'],
            'payment_no' => ['nullable', 'string'],
            'amount' => ['required'],
            'cash_paid' => ['nullable'],
            'cash_change' => ['nullable'],
            'payment_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
