<?php

namespace App\Http\Requests\CraftablePro\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.purchases.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'supplier_id' => ['sometimes'],
            'entry_stock_time' => ['nullable'],
            'description' => ['nullable', 'string'],
            'is_paid' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
