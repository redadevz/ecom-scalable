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
            'stock_return_id' => ['required', 'integer', 'exists:stock_returns,id'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'quantity' => ['required', 'integer'],
            'supplier_price_before_tax' => ['required', 'numeric'],
            'supplier_tax_value' => ['required', 'numeric'],
            'supplier_price_after_tax' => ['required', 'numeric'],
            'supplier_discount_value' => ['required', 'numeric'],
            'return_amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
