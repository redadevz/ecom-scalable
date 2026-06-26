<?php

namespace App\Http\Requests\CraftablePro\Refund;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRefundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.refunds.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_return_id' => ['required'],
            'payment_method_id' => ['required'],
            'refund_no' => ['nullable', 'string'],
            'amount' => ['required'],
            'cash_paid' => ['nullable'],
            'cash_change' => ['nullable'],
            'refund_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
