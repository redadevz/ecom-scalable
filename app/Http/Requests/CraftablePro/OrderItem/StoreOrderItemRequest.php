<?php

namespace App\Http\Requests\CraftablePro\OrderItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'product_id' => ['required'],
            'product_name' => ['required', 'string'],
            'quantity' => ['required'],
            'unit_price' => ['required'],
            
        ];
    }
}
