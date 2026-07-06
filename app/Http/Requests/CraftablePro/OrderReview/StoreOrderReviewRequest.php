<?php

namespace App\Http\Requests\CraftablePro\OrderReview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-reviews.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:order_headers,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'replied_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'rating' => ['nullable', 'boolean'],
            'review' => ['nullable', 'string', 'max:255'],
            'review_time' => ['nullable'],
            'reply' => ['nullable', 'string', 'max:255'],
            'reply_time' => ['nullable'],
            'is_compensated' => ['required', 'boolean'],
            'compensation_value' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
