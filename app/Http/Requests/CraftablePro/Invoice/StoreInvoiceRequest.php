<?php

namespace App\Http\Requests\CraftablePro\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.invoices.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'invoice_no' => ['required', 'string'],
            'is_paid' => ['required', 'boolean'],
            'payment_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
