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
            'invoice_id' => ['sometimes', 'integer', 'exists:invoices,id'],
            'payment_method_id' => ['sometimes', 'integer', 'exists:payment_methods,id'],
            'payment_no' => ['nullable', 'string', 'max:50'],
            'amount' => ['sometimes', 'numeric'],
            'cash_paid' => ['nullable', 'numeric'],
            'cash_change' => ['nullable', 'numeric'],
            'payment_time' => ['nullable'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
