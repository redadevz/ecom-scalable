<?php

namespace App\Http\Requests\CraftablePro\OrderStatusHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderStatusHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-status-histories.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:order_headers,id'],
            'order_status_id' => ['required', 'integer', 'exists:order_statuses,id'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            
        ];
    }
}
