<?php

namespace App\Http\Requests\CraftablePro\StockReturnItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreStockReturnItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.stock-return-items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'stock_return_id' => ['required'],
            'item_id' => ['required'],
            'quantity' => ['required'],
            'supplier_price_before_tax' => ['required'],
            'supplier_tax_value' => ['required'],
            'supplier_price_after_tax' => ['required'],
            'supplier_discount_value' => ['required'],
            'return_amount' => ['required'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
