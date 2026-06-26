<?php

namespace App\Http\Requests\CraftablePro\SaleReturn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSaleReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.sale-returns.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'order_id' => ['required'],
            'entry_stock_time' => ['nullable'],
            'is_refund_required' => ['required', 'boolean'],
            'refund_amount' => ['required'],
            'is_refunded' => ['required', 'boolean'],
            'refund_time' => ['nullable'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
