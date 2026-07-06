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
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'order_id' => ['required', 'integer', 'exists:order_headers,id'],
            'entry_stock_time' => ['nullable'],
            'is_refund_required' => ['required', 'boolean'],
            'refund_amount' => ['required', 'numeric'],
            'is_refunded' => ['required', 'boolean'],
            'refund_time' => ['nullable'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
