<?php

namespace App\Http\Requests\CraftablePro\Refund;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRefundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.refunds.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_return_id' => ['sometimes', 'integer', 'exists:sale_returns,id'],
            'payment_method_id' => ['sometimes', 'integer', 'exists:payment_methods,id'],
            'refund_no' => ['nullable', 'string', 'max:50'],
            'amount' => ['sometimes', 'numeric'],
            'cash_paid' => ['nullable', 'numeric'],
            'cash_change' => ['nullable', 'numeric'],
            'refund_time' => ['nullable'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
