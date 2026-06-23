<?php

namespace App\Http\Requests\Api\DeliveryType;

use Illuminate\Foundation\Http\FormRequest;

class ShowDeliveryTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.delivery-types.show");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            
        ];
    }
}
