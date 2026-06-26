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
            'sale_return_id' => ['required'],
            'order_line_id' => ['required'],
            'item_id' => ['nullable'],
            'line_no' => ['nullable', 'string'],
            'quantity' => ['required'],
            'return_quantity' => ['required'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
