<?php

namespace App\Http\Requests\CraftablePro\InventoryCountItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateInventoryCountItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.inventory-count-items.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'inventory_count_id' => ['sometimes', 'integer', 'exists:inventory_counts,id'],
            'item_id' => ['sometimes', 'integer', 'exists:items,id'],
            'quantity_counted' => ['sometimes', 'integer'],
            'quantity_expected' => ['sometimes', 'integer'],
            'quantity_change' => ['sometimes', 'integer'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
