<?php

namespace App\Http\Requests\CraftablePro\StockHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreStockHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.stock-histories.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'document_id' => ['nullable', 'integer', 'exists:documents,id'],
            'initial_stock_quantity' => ['required', 'integer'],
            'initial_item_cost' => ['required', 'numeric'],
            'is_stock_entry' => ['required', 'boolean'],
            'quantity' => ['required', 'integer'],
            'current_stock_quantity' => ['required', 'integer'],
            'current_item_cost' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
