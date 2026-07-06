<?php

namespace App\Http\Requests\CraftablePro\PurchaseItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePurchaseItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.purchase-items.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'purchase_id' => ['sometimes', 'integer', 'exists:purchases,id'],
            'item_id' => ['sometimes', 'integer', 'exists:items,id'],
            'supplier_price_before_tax' => ['sometimes', 'numeric'],
            'supplier_tax_value' => ['sometimes', 'numeric'],
            'supplier_price_after_tax' => ['sometimes', 'numeric'],
            'supplier_discount_value' => ['sometimes', 'numeric'],
            'quantity' => ['sometimes', 'integer'],
            'return_amount' => ['sometimes', 'numeric'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
