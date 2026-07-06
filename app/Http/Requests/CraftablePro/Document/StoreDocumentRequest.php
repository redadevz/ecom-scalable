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
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'document_type_id' => ['required', 'integer', 'exists:document_types,id'],
            'sale_order_id' => ['nullable', 'integer', 'exists:order_headers,id'],
            'purchase_id' => ['nullable', 'integer', 'exists:purchases,id'],
            'stock_return_id' => ['nullable', 'integer', 'exists:stock_returns,id'],
            'inventory_count_id' => ['nullable', 'integer', 'exists:inventory_counts,id'],
            'loss_and_damage_id' => ['nullable', 'integer', 'exists:loss_and_damages,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'number' => ['required', 'string', 'max:25'],
            'external_number' => ['nullable', 'string', 'max:25'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
