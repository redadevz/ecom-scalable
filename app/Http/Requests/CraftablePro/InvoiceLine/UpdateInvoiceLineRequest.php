<?php

namespace App\Http\Requests\CraftablePro\InvoiceLine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateInvoiceLineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.invoice-lines.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['sometimes'],
            'order_line_id' => ['sometimes'],
            'line_no' => ['sometimes', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
