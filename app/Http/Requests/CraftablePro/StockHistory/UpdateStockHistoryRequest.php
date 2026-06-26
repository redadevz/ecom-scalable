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
            'store_id' => ['sometimes'],
            'item_id' => ['sometimes'],
            'document_id' => ['nullable'],
            'initial_stock_quantity' => ['sometimes'],
            'initial_item_cost' => ['sometimes'],
            'is_stock_entry' => ['sometimes', 'boolean'],
            'quantity' => ['sometimes'],
            'current_stock_quantity' => ['sometimes'],
            'current_item_cost' => ['sometimes'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
