<?php

namespace App\Http\Requests\CraftablePro\OrderDiscount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-discounts.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discount_id' => ['required'],
            'order_id' => ['required'],
            'value' => ['required'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
