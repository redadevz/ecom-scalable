<?php

namespace App\Http\Requests\CraftablePro\SaleReturnItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateSaleReturnItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.sale-return-items.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_return_id' => ['sometimes'],
            'order_line_id' => ['sometimes'],
            'item_id' => ['nullable'],
            'line_no' => ['nullable', 'string'],
            'quantity' => ['sometimes'],
            'return_quantity' => ['sometimes'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
