<?php

namespace App\Http\Requests\CraftablePro\LossAndDamageItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateLossAndDamageItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.loss-and-damage-items.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'loss_and_damage_id' => ['sometimes'],
            'item_id' => ['sometimes'],
            'quantity' => ['sometimes'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
