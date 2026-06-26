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
            'sale_return_id' => ['sometimes'],
            'payment_method_id' => ['sometimes'],
            'refund_no' => ['nullable', 'string'],
            'amount' => ['sometimes'],
            'cash_paid' => ['nullable'],
            'cash_change' => ['nullable'],
            'refund_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
