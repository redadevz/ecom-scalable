<?php

namespace App\Http\Requests\Api\OrderStatusHistory;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderStatusHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.order-status-histories.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'order_status_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            
        ];
    }
}
