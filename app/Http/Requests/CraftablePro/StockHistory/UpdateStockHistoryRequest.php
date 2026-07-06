<?php

namespace App\Http\Requests\CraftablePro\StockHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateStockHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.stock-histories.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'item_id' => ['sometimes', 'integer', 'exists:items,id'],
            'document_id' => ['nullable', 'integer', 'exists:documents,id'],
            'initial_stock_quantity' => ['sometimes', 'integer'],
            'initial_item_cost' => ['sometimes', 'numeric'],
            'is_stock_entry' => ['sometimes', 'boolean'],
            'quantity' => ['sometimes', 'integer'],
            'current_stock_quantity' => ['sometimes', 'integer'],
            'current_item_cost' => ['sometimes', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
