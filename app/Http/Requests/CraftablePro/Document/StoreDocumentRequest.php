<?php

namespace App\Http\Requests\CraftablePro\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.documents.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'document_type_id' => ['required'],
            'sale_order_id' => ['nullable'],
            'purchase_id' => ['nullable'],
            'stock_return_id' => ['nullable'],
            'inventory_count_id' => ['nullable'],
            'loss_and_damage_id' => ['nullable'],
            'created_by' => ['nullable'],
            'number' => ['required', 'string'],
            'external_number' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
