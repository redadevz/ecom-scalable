<?php

namespace App\Http\Requests\CraftablePro\CartItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.cart-items.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cart_id' => ['sometimes'],
            'product_id' => ['sometimes'],
            'quantity' => ['sometimes'],
            'unit_price' => ['sometimes'],
            
        ];
    }
}
