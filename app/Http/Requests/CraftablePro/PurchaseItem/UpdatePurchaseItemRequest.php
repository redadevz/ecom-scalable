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
            'purchase_id' => ['sometimes'],
            'item_id' => ['sometimes'],
            'supplier_price_before_tax' => ['sometimes'],
            'supplier_tax_value' => ['sometimes'],
            'supplier_price_after_tax' => ['sometimes'],
            'supplier_discount_value' => ['sometimes'],
            'quantity' => ['sometimes'],
            'return_amount' => ['sometimes'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
