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
            'store_id' => ['required'],
            'item_id' => ['required'],
            'document_id' => ['nullable'],
            'initial_stock_quantity' => ['required'],
            'initial_item_cost' => ['required'],
            'is_stock_entry' => ['required', 'boolean'],
            'quantity' => ['required'],
            'current_stock_quantity' => ['required'],
            'current_item_cost' => ['required'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
