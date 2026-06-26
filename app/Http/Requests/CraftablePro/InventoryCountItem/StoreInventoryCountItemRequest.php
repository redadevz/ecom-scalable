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
            'inventory_count_id' => ['required'],
            'item_id' => ['required'],
            'quantity_counted' => ['required'],
            'quantity_expected' => ['required'],
            'quantity_change' => ['required'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
