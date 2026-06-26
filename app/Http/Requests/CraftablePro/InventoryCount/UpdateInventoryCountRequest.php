<?php

namespace App\Http\Requests\CraftablePro\InventoryCount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateInventoryCountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.inventory-counts.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'physical_count_time' => ['nullable'],
            'change_stock_time' => ['nullable'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
