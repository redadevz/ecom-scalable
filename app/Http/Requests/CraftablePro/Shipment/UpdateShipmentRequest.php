<?php

namespace App\Http\Requests\CraftablePro\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.shipments.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'order_id' => ['sometimes'],
            'shipment_city_id' => ['nullable'],
            'picked_up_by' => ['nullable'],
            'shipment_address' => ['sometimes', 'string'],
            'gps_location' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'shipment_notes' => ['nullable', 'string'],
            'picked_up_time' => ['nullable'],
            'shipped_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
