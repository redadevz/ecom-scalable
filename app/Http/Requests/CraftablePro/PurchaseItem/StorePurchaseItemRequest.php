<?php

namespace App\Http\Requests\CraftablePro\PurchaseItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePurchaseItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.purchase-items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'purchase_id' => ['required', 'integer', 'exists:purchases,id'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'supplier_price_before_tax' => ['required', 'numeric'],
            'supplier_tax_value' => ['required', 'numeric'],
            'supplier_price_after_tax' => ['required', 'numeric'],
            'supplier_discount_value' => ['required', 'numeric'],
            'quantity' => ['required', 'integer'],
            'return_amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
