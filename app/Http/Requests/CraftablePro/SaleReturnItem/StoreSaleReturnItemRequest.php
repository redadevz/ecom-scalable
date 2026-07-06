<?php

namespace App\Http\Requests\CraftablePro\SaleReturnItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSaleReturnItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.sale-return-items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_return_id' => ['required', 'integer', 'exists:sale_returns,id'],
            'order_line_id' => ['required', 'integer', 'exists:order_lines,id'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'line_no' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer'],
            'return_quantity' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
