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
            'inventory_count_id' => ['sometimes'],
            'item_id' => ['sometimes'],
            'quantity_counted' => ['sometimes'],
            'quantity_expected' => ['sometimes'],
            'quantity_change' => ['sometimes'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
