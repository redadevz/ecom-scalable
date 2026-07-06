<?php

namespace App\Http\Requests\CraftablePro\InventoryCountItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInventoryCountItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.inventory-count-items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'inventory_count_id' => ['required', 'integer', 'exists:inventory_counts,id'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'quantity_counted' => ['required', 'integer'],
            'quantity_expected' => ['required', 'integer'],
            'quantity_change' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
