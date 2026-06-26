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
            'purchase_id' => ['required'],
            'item_id' => ['required'],
            'supplier_price_before_tax' => ['required'],
            'supplier_tax_value' => ['required'],
            'supplier_price_after_tax' => ['required'],
            'supplier_discount_value' => ['required'],
            'quantity' => ['required'],
            'return_amount' => ['required'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
