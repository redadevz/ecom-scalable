<?php

namespace App\Http\Requests\CraftablePro\CartItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.cart-items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cart_id' => ['required'],
            'product_id' => ['required'],
            'quantity' => ['required'],
            'unit_price' => ['required'],
            
        ];
    }
}
