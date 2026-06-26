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
            'invoice_id' => ['sometimes'],
            'payment_method_id' => ['sometimes'],
            'payment_no' => ['nullable', 'string'],
            'amount' => ['sometimes'],
            'cash_paid' => ['nullable'],
            'cash_change' => ['nullable'],
            'payment_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
