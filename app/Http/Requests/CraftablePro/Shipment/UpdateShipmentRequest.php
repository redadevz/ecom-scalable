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
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'order_id' => ['sometimes', 'integer', 'exists:order_headers,id'],
            'shipment_city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'picked_up_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'shipment_address' => ['sometimes', 'string', 'max:255'],
            'gps_location' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'shipment_notes' => ['nullable', 'string', 'max:255'],
            'picked_up_time' => ['nullable'],
            'shipped_time' => ['nullable'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
